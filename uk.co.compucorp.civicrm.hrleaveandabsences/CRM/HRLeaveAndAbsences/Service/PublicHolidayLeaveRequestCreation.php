<?php

use CRM_HRLeaveAndAbsences_BAO_AbsenceType as AbsenceType;
use CRM_HRLeaveAndAbsences_BAO_LeaveRequest as LeaveRequest;
use CRM_HRLeaveAndAbsences_BAO_LeaveRequestDate as LeaveRequestDate;
use CRM_HRLeaveAndAbsences_BAO_LeaveBalanceChange as LeaveBalanceChange;
use CRM_HRLeaveAndAbsences_BAO_PublicHoliday as PublicHoliday;
use CRM_HRLeaveAndAbsences_Service_JobContract as JobContractService;

class CRM_HRLeaveAndAbsences_Service_PublicHolidayLeaveRequestCreation {

  /**
   * @var \CRM_HRLeaveAndAbsence_Service_JobContract
   */
  private $jobContractService;

  public function __construct(JobContractService $jobContractService) {
    $this->jobContractService = $jobContractService;
  }

  /**
   * Creates Public Holiday Leave Requests for all the contacts with contracts
   * overlapping the date of the given Public Holiday
   *
   * @param \CRM_HRLeaveAndAbsences_BAO_PublicHoliday $publicHoliday
   */
  public function createForAllContacts(PublicHoliday $publicHoliday) {
    $absenceType = AbsenceType::getOneWithMustTakePublicHolidayAsLeaveRequest();

    $contracts = $this->jobContractService->getContractsForPeriod(
      new DateTime($publicHoliday->date),
      new DateTime($publicHoliday->date)
    );

    foreach($contracts as $contract) {
      $this->create($contract['contact_id'], $absenceType, $publicHoliday);
    }
  }

  /**
   * Creates Public Holiday Leave Requests for all the existing Public Holidays
   * int the future
   *
   * For each contract overlapping one Public Holiday, a Leave Request will be
   * created for the contract's contact and the public holiday date.
   */
  public function createForAllInTheFuture() {
    $absenceType = AbsenceType::getOneWithMustTakePublicHolidayAsLeaveRequest();

    if(!$absenceType) {
      return;
    }

    $futurePublicHolidays = PublicHoliday::getAllInFuture();
    $lastPublicHoliday = end($futurePublicHolidays);

    $contracts = $this->jobContractService->getContractsForPeriod(
      new DateTime(),
      new DateTime($lastPublicHoliday->date)
    );

    foreach($contracts as $contract) {
      foreach($futurePublicHolidays as $publicHoliday) {
        if($this->publicHolidayOverlapsContract($contract, $publicHoliday)) {
          $this->create($contract['contact_id'], $absenceType, $publicHoliday);
        }
      }
    }
  }

  /**
   * Creates Public Holiday Leave Requests for all Public Holidays in the
   * Future overlapping the start and end dates of the given contract
   *
   * @param int $contractID
   */
  public function createAllForContract($contractID) {
    $contract = $this->jobContractService->getContractByID($contractID);

    if (!$contract) {
      return;
    }

    $publicHolidays = PublicHoliday::getAllForPeriod(
      $contract['period_start_date'],
      $contract['period_end_date']
    );

    foreach($publicHolidays as $publicHoliday) {
      if(strtotime($publicHoliday->date) >= strtotime('today')) {
        $this->createForContact($contract['contact_id'], $publicHoliday);
      }
    }
  }

  /**
   * Creates a Public Holiday Leave Request for the contact with the
   * given $contactId
   *
   * @param int $contactID
   * @param \CRM_HRLeaveAndAbsences_BAO_PublicHoliday $publicHoliday
   */
  public function createForContact($contactID, PublicHoliday $publicHoliday) {
    $absenceType = AbsenceType::getOneWithMustTakePublicHolidayAsLeaveRequest();
    $this->create($contactID, $absenceType, $publicHoliday);
  }

  /**
   * Creates a Public Holiday Leave Request for the given $contactID, Absence
   * Type and Public Holiday.
   *
   * The Leave Request will only be created if there's no existing Public Holiday
   * Leave Request for the given $contactID and $publicHoliday.
   *
   * @param int $contactID
   * @param \CRM_HRLeaveAndAbsences_BAO_AbsenceType $absenceType
   * @param \CRM_HRLeaveAndAbsences_BAO_PublicHoliday $publicHoliday
   */
  private function create($contactID, AbsenceType $absenceType, PublicHoliday $publicHoliday) {
    $existingLeaveRequest = LeaveRequest::findPublicHolidayLeaveRequest($contactID, $publicHoliday);
    if($existingLeaveRequest) {
      return;
    }

    $leaveRequest = $this->createLeaveRequest($contactID, $absenceType, $publicHoliday);
    $this->createLeaveBalanceChangeRecord($leaveRequest);
  }

  /**
   * Creates a Leave Request for the given $contactID and $absenceType with the
   * date of the given Public Holiday
   *
   * @param int $contactID
   * @param \CRM_HRLeaveAndAbsences_BAO_AbsenceType $absenceType
   * @param \CRM_HRLeaveAndAbsences_BAO_PublicHoliday $publicHoliday
   *
   * @return \CRM_HRLeaveAndAbsences_BAO_LeaveRequest|NULL
   */
  private function createLeaveRequest($contactID, AbsenceType $absenceType, PublicHoliday $publicHoliday) {
    $leaveRequestStatuses = array_flip(LeaveRequest::buildOptions('status_id'));
    $leaveRequestDayTypes = array_flip(LeaveRequest::buildOptions('from_date_type'));

    return LeaveRequest::create([
      'contact_id'     => $contactID,
      'type_id'        => $absenceType->id,
      'status_id'      => $leaveRequestStatuses['Admin Approved'],
      'from_date'      => CRM_Utils_Date::processDate($publicHoliday->date),
      'from_date_type' => $leaveRequestDayTypes['All Day'],
      'to_date' => CRM_Utils_Date::processDate($publicHoliday->date),
      'to_date_type' => $leaveRequestDayTypes['All Day']
    ], false);
  }

  /**
   * Creates LeaveBalanceChange records for the dates of the given $leaveRequest.
   *
   * For PublicHolidays, the deducted amount will always be -1.
   *
   * If there is already a leave request to this on the same date, the deduction
   * amount for that specific date will be updated to be 0, in order to not
   * deduct the same day twice.
   *
   * @param \CRM_HRLeaveAndAbsences_BAO_LeaveRequest $leaveRequest
   */
  private function createLeaveBalanceChangeRecord(LeaveRequest $leaveRequest) {
    $leaveBalanceChangeTypes = array_flip(LeaveBalanceChange::buildOptions('type_id'));

    $dates = $leaveRequest->getDates();
    foreach($dates as $date) {
      $this->zeroDeductionForOverlappingLeaveRequestDate($leaveRequest, $date);

      LeaveBalanceChange::create([
        'source_id'   => $date->id,
        'source_type' => LeaveBalanceChange::SOURCE_LEAVE_REQUEST_DAY,
        'type_id'     => $leaveBalanceChangeTypes['Public Holiday'],
        'amount'      => -1
      ]);
    }
  }

  /**
   * First, searches for an existing balance change for the same contact and absence
   * type of the given $leaveRequest and linked to a LeaveRequestDate with the
   * same date as $leaveRequestDate. Next, if such balance change exists, update
   * it's amount to 0.
   *
   * @param \CRM_HRLeaveAndAbsences_BAO_LeaveRequest $leaveRequest
   * @param \CRM_HRLeaveAndAbsences_BAO_LeaveRequestDate $leaveRequestDate
   */
  private function zeroDeductionForOverlappingLeaveRequestDate(LeaveRequest $leaveRequest, LeaveRequestDate $leaveRequestDate) {
    $date = new DateTime($leaveRequestDate->date);

    $leaveBalanceChange = LeaveBalanceChange::getExistingBalanceChangeForALeaveRequestDate($leaveRequest, $date);

    if($leaveBalanceChange) {
      LeaveBalanceChange::create([
        'id' => $leaveBalanceChange->id,
        'amount' => 0
      ]);
    }
  }

  /**
   * Checks if the date of the given PublicHoliday overlaps the start and end
   * dates of the given $contract
   *
   * @param array $contract
   *   An contract as returned by the HRJobContract.getcontractswithdetailsinperiod API
   * @param \CRM_HRLeaveAndAbsences_BAO_PublicHoliday $publicHoliday
   *
   * @return bool
   */
  private function publicHolidayOverlapsContract($contract, PublicHoliday $publicHoliday) {
    $startDate = new DateTime($contract['period_start_date']);
    $endDate = empty($contract['period_end_date']) ? null : new DateTime($contract['period_end_date']);
    $publicHolidayDate = new DateTime($publicHoliday->date);

    return $startDate <= $publicHolidayDate && (!$endDate || $endDate >= $publicHolidayDate);
  }

}
