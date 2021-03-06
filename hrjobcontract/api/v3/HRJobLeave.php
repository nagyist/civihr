<?php
/*
+--------------------------------------------------------------------+
| CiviHR version 1.4                                                 |
+--------------------------------------------------------------------+
| Copyright CiviCRM LLC (c) 2004-2014                                |
+--------------------------------------------------------------------+
| This file is a part of CiviCRM.                                    |
|                                                                    |
| CiviCRM is free software; you can copy, modify, and distribute it  |
| under the terms of the GNU Affero General Public License           |
| Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
|                                                                    |
| CiviCRM is distributed in the hope that it will be useful, but     |
| WITHOUT ANY WARRANTY; without even the implied warranty of         |
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
| See the GNU Affero General Public License for more details.        |
|                                                                    |
| You should have received a copy of the GNU Affero General Public   |
| License and the CiviCRM Licensing Exception along                  |
| with this program; if not, contact CiviCRM LLC                     |
| at info[AT]civicrm[DOT]org. If you have questions about the        |
| GNU Affero General Public License or the licensing of CiviCRM,     |
| see the CiviCRM license FAQ at http://civicrm.org/licensing        |
+--------------------------------------------------------------------+
*/

/**
 * HRJobLeave.create API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRM/API+Architecture+Standards
 */
function _civicrm_api3_h_r_job_leave_create_spec(&$spec) {
}

/**
 * HRJobLeave.create API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function civicrm_api3_h_r_job_leave_create($params) {
  return _civicrm_api3_basic_create(_civicrm_api3_get_BAO(__FUNCTION__), $params);
  //return _civicrm_api3_basic_get(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

/**
 * HRJobLeave.delete API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function civicrm_api3_h_r_job_leave_delete($params) {
  return _civicrm_api3_basic_delete(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

function _civicrm_api3_h_r_job_leave_get_spec(&$spec) {
}

/**
 * HRJobLeave.get API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function civicrm_api3_h_r_job_leave_get($params) {
  _civicrm_hrjobcontract_api3_set_current_revision($params, _civicrm_get_table_name(_civicrm_api3_get_BAO(__FUNCTION__)));
  return _civicrm_api3_basic_get(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

/**
 * HRJobLeave.get API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function civicrm_api3_h_r_job_leave_replace($params) {
    $validRevisionId = null;
    if (!empty($params['values'])) {
        foreach ($params['values'] as $leave) {
            if (!empty($leave['id']) && !empty($leave['jobcontract_revision_id'])) {
                $validRevisionId = $leave['jobcontract_revision_id'];
                break;
            }
        }
    }
    $result =  _civicrm_hrjobcontract_api3_replace(_civicrm_get_entity_name(_civicrm_api3_get_BAO(__FUNCTION__)), $params, $validRevisionId);

    if (!empty($params['values'])) {
      $firstLeaveEntry = CRM_Utils_Array::first($params['values']);

      $jobContractId = isset($firstLeaveEntry['jobcontract_id']) ? $firstLeaveEntry['jobcontract_id'] : null;
      if (!$jobContractId && isset($firstLeaveEntry['jobcontract_revision_id'])) {
        $revision = civicrm_api3('HRJobContractRevision', 'get', array(
          'sequential' => 1,
          'id' => $firstLeaveEntry['jobcontract_revision_id'],
        ));
        $revisionData = CRM_Utils_Array::first($revision['values']);
        $jobContractId = $revisionData['jobcontract_id'];
      }

      if ($jobContractId) {
        CRM_HRAbsence_BAO_HRAbsenceEntitlement::recalculateAbsenceEntitlement($jobContractId);
      }
    }

    return $result;
}
