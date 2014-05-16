<?php

require_once 'hrrecruitment.civix.php';

/**
 * Implementation of hook_civicrm_config
 */
function hrrecruitment_civicrm_config(&$config) {
  _hrrecruitment_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 */
function hrrecruitment_civicrm_xmlMenu(&$files) {
  _hrrecruitment_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_install
 */
function hrrecruitment_civicrm_install() {
  $activityTypesResult = civicrm_api3('activity_type', 'get', array());
  $weight = count($activityTypesResult["values"]);
  foreach (array('Evaluation', 'Comment') as $activityType) {
    if (!in_array($activityType, $activityTypesResult["values"])) {
      civicrm_api3('activity_type', 'create', array(
          'weight' => $weight++,
          'name' => $activityType,
          'label' => $activityType,
          'is_active' => 1,
        )
      );
    }
  }

  $result = civicrm_api3('OptionGroup', 'create', array(
    'name' => 'vacancy_status',
    'title' => ts('Vacancy Status'),
    'is_reserved' => 1,
    'is_active' => 1,
    )
  );

  $vacancyStatus = array(
    'Draft' => ts('Draft'),
    'Open' => ts('Open'),
    'Closed' => ts('Closed'),
    'Cancelled' => ts('Cancelled'),
    'Rejected' => ts('Rejected')
  );
  $weight = 1;
  foreach ($vacancyStatus as $name => $label) {
    $statusParam = array(
      'option_group_id' => $result['id'],
      'label' => $label,
      'name' => $name,
      'value' => $weight++,
      'is_active' => 1,
    );
    if ($name == 'Draft') {
      $statusParam['is_default'] = 1;
    }
    elseif ($name == 'Open') {
      $statusParam['is_reserved'] = 1;
    }
    civicrm_api3('OptionValue', 'create', $statusParam);
  }

  $stages = array(
    'Apply' => ts('Apply'),
    'Ongoing_Vacancy' => ts('Ongoing'),
    'Phone_Interview' => ts('Phone Interview'),
    'Manager_Interview' => ts('Manager Interview'),
    'Board_Interview' => ts('Board Interview'),
    'Group_Interview' => ts('Group Interview'),
    'Psych_Exam' => ts('Psych Exam'),
    'Offer' => ts('Offer'),
    'Hired' => ts('Hired'),
  );
  $count = count(CRM_Core_OptionGroup::values('case_status'));
  foreach ($stages as $name => $label) {
    $count++;

    $caseStatusParam = array(
      'option_group_id' => 'case_status',
      'label' => $label,
      'name' => $name,
      'value' => $count,
      'grouping' => 'Vacancy',
      'filter' => 1,
    );
    civicrm_api3('OptionValue', 'create', $caseStatusParam);
  }

  $reportWeight = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_Navigation', 'Reports', 'weight', 'name');
  $vacancyNavigation = new CRM_Core_DAO_Navigation();
  $params = array (
    'domain_id' => CRM_Core_Config::domainID(),
    'label' => ts('Vacancies'),
    'name' => 'Vacancies',
    'url' => null,
    'operator' => null,
    'weight' => $reportWeight-1,
    'is_active' => 1
  );
  $vacancyNavigation->copyValues($params);
  $vacancyNavigation->save();

  $vacancyMenuTree = array(
    array(
      'label' => ts('Dashboard'),
      'name' => 'dashboard',
      'url' => 'civicrm/vacancy/dashboard?reset=1',
      'permission' => 'view Applicants, manage Applicants, evaluate Applicants, administer Vacancy, administer CiviCRM',
      'permission_operator' => 'OR',
    ),
    array(
      'label' => ts('Public Vacancy List'),
      'name' => 'public_list',
      'url' => 'civicrm/vacancy?reset=1',
      'permission' => NULL,
      'has_separator' => 1,
    ),
    array(
      'label' => ts('New Vacancy'),
      'name' => 'new_vacancy',
      'url' => 'civicrm/vacancy/add?reset=1',
      'permission' => 'administer Vacancy, administer CiviCRM',
      'permission_operator' => 'OR',
    ),
    array(
      'label' => ts('New Template'),
      'name' => 'new_template',
      'url' => 'civicrm/vacancy/add?reset=1&template=1',
      'permission' => 'administer Vacancy, administer CiviCRM',
      'permission_operator' => 'OR',
    ),
    array(
      'label' => ts('New Applicant'),
      'name' => 'new_applicant',
      'has_separator' => 1,
    ),
    array(
      'label' => ts('Find Vacancies'),
      'name' => 'find_vacancies',
      'url' => 'civicrm/vacancy/find?reset=1',
      'permission' => 'view Applicants, manage Applicants, evaluate Applicants, administer Vacancy, administer CiviCRM',
      'permission_operator' => 'OR',
    ),
    array(
      'label' => ts('Find Templates'),
      'name' => 'find_templates',
      'url' => 'civicrm/vacancy/find?reset=1&template=1',
      'permission' => 'view Applicants, manage Applicants, evaluate Applicants, administer Vacancy, administer CiviCRM',
      'permission_operator' => 'OR',
    ),
  );

  foreach ($vacancyMenuTree as $key => $menuItems) {
    $menuItems['is_active'] = 1;
    $menuItems['parent_id'] = $vacancyNavigation->id;
    $menuItems['weight'] = $key;
    CRM_Core_BAO_Navigation::add($menuItems);
  }
  CRM_Core_BAO_Navigation::resetNavigation();

  return _hrrecruitment_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_postInstall
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 */
function hrrecruitment_civicrm_postInstall() {
  $caseTypes = CRM_Case_PseudoConstant::caseType('name');
  $value = array_search('Application', $caseTypes);
  $value = CRM_Core_DAO::VALUE_SEPARATOR . $value . CRM_Core_DAO::VALUE_SEPARATOR;
  $sql = "UPDATE civicrm_custom_group SET extends_entity_column_value = '{$value}' WHERE extends_entity_column_value = 'Application'";
  CRM_Core_DAO::executeQuery($sql);

  //change the profile Type of Application
  if ($ufID = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_UFGroup', 'application_profile', 'id', 'name')) {
    $fieldsType = CRM_Core_BAO_UFGroup::calculateGroupType($ufID, TRUE);
    CRM_Core_BAO_UFGroup::updateGroupTypes($ufID, $fieldsType);
  }
}

/**
 * Implementation of hook_civicrm_uninstall
 */
function hrrecruitment_civicrm_uninstall() {
  $vacanciesId = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_Navigation', 'Vacancies', 'id', 'name');
  CRM_Core_BAO_Navigation::processDelete($vacanciesId);
  CRM_Core_BAO_Navigation::resetNavigation();

  if ($statusId = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_OptionGroup', 'vacancy_status', 'id', 'name')) {
    civicrm_api3('OptionGroup', 'delete', array('id' => $statusId));
  }

  foreach (array('Evaluation', 'Comment') as $activityType) {
    if ($id = civicrm_api3('OptionValue', 'getvalue', array('name' => $activityType, 'return' => 'id'))) {
      civicrm_api3('OptionValue', 'delete', array('id' => $id));
    }
  }

  $caseTypes = CRM_Case_PseudoConstant::caseType('name');
  $value = array_search('Application', $caseTypes);
  //Delete cases and related contact of type Application on uninstall
  if ($value)
  {
    $caseDAO = new CRM_Case_DAO_Case();
    $caseDAO->case_type_id = $value;
    $caseDAO->find();
    while ($caseDAO->fetch()) {
      CRM_Case_BAO_Case::deleteCase($caseDAO->id);
    }
  }

  $CaseStatuses = CRM_Core_OptionGroup::values('case_status', FALSE, FALSE, FALSE, " AND grouping = 'Vacancy'", 'id');
  foreach ($CaseStatuses as $id => $dontCare) {
    civicrm_api3('OptionValue', 'delete', array('id' => $id));
  }

  foreach (array('application_profile', 'evaluation_profile') as $name) {
    if ($ufID = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_UFGroup', $name, 'id', 'name')) {
      CRM_Core_BAO_UFGroup::del($ufID);
    }
  }

  foreach (array('Application', 'application_case') as $cgName) {
    $customGroup = new CRM_Core_DAO_CustomGroup();
    $customGroup->name = $cgName;
    $customGroup->find(TRUE);
    $customField = new CRM_Core_DAO_CustomField();
    $customField->custom_group_id = $customGroup->id;
    $customField->find();
    while ($customField->fetch()) {
      CRM_Core_BAO_CustomField::deleteField($customField);
    }
    CRM_Core_BAO_CustomGroup::deleteGroup($customGroup);
  }

  return _hrrecruitment_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 */
function hrrecruitment_civicrm_enable() {
  CRM_Core_BAO_Navigation::processUpdate(array('name' => 'Vacancies'), array('is_active' => 1));
  CRM_Core_BAO_Navigation::resetNavigation();

  if ($vacancyStatusID = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_OptionGroup', 'vacancy_status', 'id', 'name')) {
    civicrm_api3('OptionGroup', 'create', array('id' => $vacancyStatusID, 'is_active' => 1));

    $statusIDs = CRM_Core_OptionGroup::valuesByID($vacancyStatusID, FALSE, FALSE, FALSE, 'id', FALSE);
    foreach ($statusIDs as $statusID) {
      civicrm_api3('OptionValue', 'create', array('id' => $statusID, 'is_active' => 1));
    }
  }

  $CaseStatuses = CRM_Core_OptionGroup::values('case_status', FALSE, FALSE, FALSE, " AND grouping = 'Vacancy'", 'id', FALSE);
  foreach ($CaseStatuses as $value => $id) {
    civicrm_api3('OptionValue', 'create', array('id' => $id, 'is_active' => 1));
  }

  foreach (array('Evaluation', 'Comment') as $activityType) {
    if ($id = civicrm_api3('OptionValue', 'getvalue', array('name' => $activityType, 'return' => 'id'))) {
      civicrm_api3('OptionValue', 'create', array('id' => $id, 'is_active' => 1));
    }
  }

  foreach (array('application_profile', 'evaluation_profile') as $name) {
    if ($ufID = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_UFGroup', $name, 'id', 'name')) {
      CRM_Core_BAO_UFGroup::setIsActive($ufID, 1);
    }
  }

  foreach (array('Application', 'application_case') as $cgName) {
    $customGroup = new CRM_Core_DAO_CustomGroup();
    $customGroup->name = $cgName;
    $customGroup->find(TRUE);
    CRM_Core_BAO_CustomGroup::setIsActive($customGroup->id, 1);
  }

  return _hrrecruitment_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 */
function hrrecruitment_civicrm_disable() {
  CRM_Core_BAO_Navigation::processUpdate(array('name' => 'Vacancies'), array('is_active' => 0));
  CRM_Core_BAO_Navigation::resetNavigation();

  if ($vacancyStatusID = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_OptionGroup', 'vacancy_status', 'id', 'name')) {
    $statusIDs = CRM_Core_OptionGroup::valuesByID($vacancyStatusID, FALSE, FALSE, FALSE, 'id');
    foreach ($statusIDs as $statusID) {
      civicrm_api3('OptionValue', 'create', array('id' => $statusID, 'is_active' => 0));
    }
    civicrm_api3('OptionGroup', 'create', array('id' => $vacancyStatusID, 'is_active' => 0));
  }

  foreach (array('Evaluation', 'Comment') as $activityType) {
    if ($id = civicrm_api3('OptionValue', 'getvalue', array('name' => $activityType, 'return' => 'id'))) {
      civicrm_api3('OptionValue', 'create', array('id' => $id, 'is_active' => 0));
    }
  }

  $CaseStatuses = CRM_Core_OptionGroup::values('case_status', FALSE, FALSE, FALSE, " AND grouping = 'Vacancy'", 'id');
  foreach ($CaseStatuses as $value => $id) {
    civicrm_api3('OptionValue', 'create', array('id' => $id, 'is_active' => 0));
  }

  foreach (array('application_profile', 'evaluation_profile') as $name) {
    if ($ufID = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_UFGroup', $name, 'id', 'name')) {
      CRM_Core_BAO_UFGroup::setIsActive($ufID, 0);
    }
  }

  foreach (array('Application', 'application_case') as $cgName) {
    $customGroup = new CRM_Core_DAO_CustomGroup();
    $customGroup->name = $cgName;
    $customGroup->find(TRUE);
    CRM_Core_BAO_CustomGroup::setIsActive($customGroup->id, 0);
  }

  return _hrrecruitment_civix_civicrm_disable();
}

function hrrecruitment_civicrm_customFieldOptions($fieldID, &$options, $detailedFormat = FALSE, $selectAttributes = array()) {
  $cfVacancyID = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_CustomField', 'vacancy_id', 'id', 'name');
  if ($fieldID == $cfVacancyID) {
    $sql = "SELECT id, position FROM civicrm_hrvacancy WHERE is_template = 0";
    $dao = CRM_Core_DAO::executeQuery($sql);
    $options = array();
    while ($dao->fetch()) {
      $vacancies[$dao->id] = $dao->position;
    }
  }

  if (!empty($vacancies) && !$detailedFormat ) {
    foreach ($vacancies AS $id => $position) {
      $options[$id] = $position;
    }
  }
}

/**
 * Implementation of hook_civicrm_upgrade
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed  based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 */
function hrrecruitment_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _hrrecruitment_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_permission
 *
 * @param array $permissions
 * @return void
 */
function hrrecruitment_civicrm_permission(&$permissions) {
  $prefix = ts('CiviHRRecruitment') . ': '; // name of extension or module
  $permissions += array(
    'access Vacancy' => $prefix . ts('Access Vacancy'),
    'view Applicants' => $prefix . ts('View Applicants'),
    'evaluate Applicants' => $prefix . ts('Evaluate Applicants'),
    'manage Applicants' => $prefix . ts('Manage Applicants'),
    'administer Vacancy' => $prefix . ts('Administer Vacancy'),
  );
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 */
function hrrecruitment_civicrm_managed(&$entities) {
  return _hrrecruitment_civix_civicrm_managed($entities);
}

/**
 * Implementation of hook_civicrm_caseTypes
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 */
function hrrecruitment_civicrm_caseTypes(&$caseTypes) {
  _hrrecruitment_civix_civicrm_caseTypes($caseTypes);
}

function hrrecruitment_civicrm_entityTypes(&$entityTypes) {
  $entityTypes[] = array(
    'name' => 'HRVacancy',
    'class' => 'CRM_HRRecruitment_DAO_HRVacancy',
    'table' => 'civicrm_hrvacancy',
  );
  $entityTypes[] = array(
    'name' => 'HRVacancyStage',
    'class' => 'CRM_HRRecruitment_DAO_HRVacancyStage',
    'table' => 'civicrm_hrvacancy_stage',
  );
}

function hrrecruitment_civicrm_navigationMenu( &$params ) {
  $vacancyMenuItems = array();
  $vacancyStatus = CRM_Core_OptionGroup::values('vacancy_status');
  $vacancyID = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_Navigation', 'Vacancies', 'id', 'name');
  $parentID =  CRM_Core_DAO::getFieldValue('CRM_Core_DAO_Navigation', 'find_vacancies', 'id', 'name');
  $count = 0;
  foreach ($vacancyStatus as $value => $status) {
    $vacancyMenuItems[$count] = array(
      'attributes' => array(
        'label' => "{$status}",
        'name' => "{$status}",
        'url' => "civicrm/vacancy/find?force=1&status={$value}&reset=1",
        'permission' => NULL,
        'operator' => 'OR',
        'separator' => NULL,
        'parentID' => $parentID,
        'navID' => 1,
        'active' => 1
      )
    );
    $count++;
  }
  if (!empty($vacancyMenuItems)) {
    $params[$vacancyID]['child'][$parentID]['child'] = $vacancyMenuItems;
  }

  $parentID =  CRM_Core_DAO::getFieldValue('CRM_Core_DAO_Navigation', 'new_applicant', 'id', 'name');
  $positions = CRM_HRRecruitment_BAO_HRVacancy::getJobPosition();
  $count = 0;
  foreach ($positions as $vid => $position) {
    $menuItems[$count] = array(
      'attributes' => array(
        'label' => 'New '. "{$position}",
        'name' => 'New '. "{$position}",
        'url' => "civicrm/vacancy/apply?reset=1&id={$vid}&cid=0",
        'permission' => NULL,
        'operator' => 'OR',
        'separator' => NULL,
        'parentID' => $parentID,
        'navID' => 1,
        'active' => 1
      )
    );
    $count++;
  }

  if (!empty($menuItems)) {
    $params[$vacancyID]['child'][$parentID]['child'] = $menuItems;
  }
}

/**
 * Implementation of hook_civicrm_buildForm
 *
 * @params string $formName - the name of the form
 *         object $form - reference to the form object
 * @return void
 */
function hrrecruitment_civicrm_buildForm($formName, &$form) {
  if ($formName == 'CRM_Case_Form_Activity' || $formName == 'CRM_Contact_Form_Task_Email') {
    $caseId = CRM_Utils_Request::retrieve('caseid', 'String', $form);
    $vacancyID = CRM_HRRecruitment_BAO_HRVacancy::getVacancyIDByCase($caseId);
    $caseId = explode(',', $caseId);
    $aType = CRM_Utils_Request::retrieve('atype', 'Positive') ? CRM_Utils_Request::retrieve('atype', 'Positive') : $form->_defaultValues['activity_type_id'];
    $evalID = CRM_Core_PseudoConstant::getKey('CRM_Activity_BAO_Activity', 'activity_type_id', 'Evaluation');
    /* Check for proper permissions */
    if ($vacancyID && $aType) {
      $administerper = CRM_HRRecruitment_BAO_HRVacancyPermission::checkVacancyPermission($vacancyID,array("administer Vacancy","administer CiviCRM","manage Applicants"));
      $evaluateper = CRM_HRRecruitment_BAO_HRVacancyPermission::checkVacancyPermission($vacancyID,array("administer Vacancy","administer CiviCRM","evaluate Applicants"));

      if ((($aType != $evalID) && !($administerper)) || (($aType == $evalID) && !($evaluateper))) {
        CRM_Core_Session::singleton()->pushUserContext(CRM_Utils_System::url('civicrm'));
        CRM_Core_Error::statusBounce(ts('You do not have the necessary permission to perform this action.'));
        return;
      }
    }

    $caseTypes = CRM_Case_PseudoConstant::caseType('name');
    $appValue = array_search('Application', $caseTypes);
    /* TO set vacancy stages as case status for 'Change Case Status' activity */
    if (($aType == CRM_Core_PseudoConstant::getKey('CRM_Activity_BAO_Activity', 'activity_type_id', 'Change Case Status'))) {
      foreach($caseId as $key => $val) {
        $caseType = CRM_Case_BAO_Case::getCaseType($val, 'id');
        if ($caseType != $appValue) {
          CRM_Core_Error::fatal('Case Id is not of type application');
        }
      }
      $form->removeElement('case_status_id');
      $form->_caseStatus = CRM_Case_PseudoConstant::caseStatus('label', TRUE, 'AND filter = 1', TRUE);
      $form->add('select', 'case_status_id', ts('Case Status'),
        $form->_caseStatus, TRUE
      );
      if ($caseStatusId = CRM_Utils_Request::retrieve('case_status_id', 'Positive', $form)) {
        $form->freeze('case_status_id');
        $form->setDefaults(array('case_status_id'=>$caseStatusId));
      }
    }
    /* build the evaluation profile on the evaluation activity */
    //check for evaluation activity type
    if ($aType == CRM_Core_PseudoConstant::getKey('CRM_Activity_BAO_Activity', 'activity_type_id', 'Evaluation')) {
      //retriev Case ID, Activity ID, Contact ID
      $caseID = CRM_Utils_Request::retrieve('caseid', 'Positive', $form);
      $activityID = CRM_Utils_Request::retrieve('id', 'Positive', $form);
      $contactID = CRM_Utils_Request::retrieve('cid', 'Positive', $form);

      //get Evaluation profile ID
      $dao = new CRM_Core_DAO_UFJoin;
      $dao->module = 'Vacancy';
      $dao->entity_id = CRM_HRRecruitment_BAO_HRVacancy::getVacancyIDByCase($caseID);
      $dao->module_data = 'evaluation_profile';
      $dao->find(TRUE);
      $profileID = $dao->uf_group_id;
      $profileFields = CRM_Core_BAO_UFGroup::getFields($profileID);
      $form->assign('fields', $profileFields);
      CRM_Core_BAO_UFGroup::setProfileDefaults($contactID, $profileFields, $def);
      $form->setDefaults($def);

      //build evaluaiton profile fields
      foreach ($profileFields as $profileFieldKey => $profileFieldVal) {
        CRM_Core_BAO_UFGroup::buildProfile($form, $profileFieldVal, CRM_Profile_Form::MODE_EDIT, $contactID, TRUE, FALSE, NULL);
        $form->_fields[$profileFieldKey] = $profileFields[$profileFieldKey];
        $params[$profileFieldKey] = $profileFieldVal;
      }

      if (!empty($activityID)) {
        $params['entityID'] = $activityID;
        $form->addElement('hidden', 'evaluationProfile', $profileID);
        $defVal = CRM_Core_BAO_CustomValueTable::getValues($params);
        $form->setDefaults($defVal);
      }

      CRM_Core_Region::instance('case-activity-form')->add(array(
        'template' => 'CRM/UF/Form/Block.tpl',
      ));
    }

    //HR-373 -- set Completed status for Comment activity by default
    if ($aType == CRM_Core_PseudoConstant::getKey('CRM_Activity_BAO_Activity', 'activity_type_id', 'Comment')) {
      $defaults['status_id'] = CRM_Core_OptionGroup::getValue('activity_status','Completed');
      $form->setDefaults($defaults);
    }
  }
}

/**
 * Implementation of hook_civicrm_postProcess
 *
 * @params string $formName - the name of the form
 *         object $form - reference to the form object
 * @return void
 */
function hrrecruitment_civicrm_postProcess( $formName, &$form ) {
  if ($formName == 'CRM_Case_Form_Activity') {
    if (!empty($form->_submitValues['evaluationProfile']) && isset($_POST['new_activity_id'])) {
      //Save evaluation profile fields
      $pID = $form->_submitValues['evaluationProfile'];
      $profileContactType = CRM_Core_BAO_UFGroup::getContactType($pID);
      $dedupeParams = CRM_Dedupe_Finder::formatParams($params, $profileContactType);
      $dedupeParams['check_permission'] = FALSE;
      $ids = CRM_Dedupe_Finder::dupesByParams($dedupeParams, $profileContactType);
      $applicantID = $form->_currentlyViewedContactId;
      if(count($ids)) {
        $applicantID = CRM_Utils_Array::value(0, $ids);
      }
      $applicantID = CRM_Contact_BAO_Contact::createProfileContact(
        $form->_submitValues, CRM_Core_DAO::$_nullArray,
        $applicantID, NULL,
        $pID
      );

      //set custom fields values
      $profileFields = CRM_Core_BAO_UFGroup::getFields($form->_submitValues['evaluationProfile']);
      foreach ($profileFields as $profileFieldKey => $profileFieldVal) {
        $params = array(
          "entityID" => $_POST['new_activity_id'], //ID of updated activity type passed from post hook
          $profileFieldKey => $form->_submitValues[$profileFieldKey],
        );
        CRM_Core_BAO_CustomValueTable::setValues($params);
      }
    }
  }
}

/**
 * Implementation of hook_civicrm_post
 *
 * @return void
 */
function hrrecruitment_civicrm_post( $op, $objectName, $objectId, &$objectRef ) {
  if ($objectName == 'Activity' && ($op == 'create' || $op == 'edit') &&
    ($objectRef->activity_type_id == CRM_Core_PseudoConstant::getKey('CRM_Activity_BAO_Activity', 'activity_type_id', 'Evaluation'))) {
    // Save newly created activity ID in post variable to use in postprocess
    // to be used as entity ID for creating / editing evaluation profile custom fields
    $_POST['new_activity_id'] = $objectId;
  }
}

/**
 * Implementation of hook_civicrm_alterContent
 *
 * @return void
 */
function hrrecruitment_civicrm_alterContent( &$content, $context, $tplName, &$object ) {
  /* HR-372, HR-373 -- To hide unwanted fields from view/edit screen for Evaluation and Comment activity */
  $requiredAct = FALSE;
  $activityTypes = array(CRM_Core_PseudoConstant::getKey('CRM_Activity_BAO_Activity', 'activity_type_id', 'Evaluation'),
    CRM_Core_PseudoConstant::getKey('CRM_Activity_BAO_Activity', 'activity_type_id', 'Comment'));
  $smarty = CRM_Core_Smarty::singleton();

  //Get activity Type
  if ($tplName == "CRM/Case/Form/ActivityView.tpl") {
    $activityID = $smarty->_tpl_vars['activityID'];
    $aType = CRM_Core_DAO::getFieldValue('CRM_Activity_DAO_Activity', $activityID, 'activity_type_id');
    $requiredAct = TRUE;
  } elseif ($tplName == "CRM/Case/Form/Activity.tpl") {
    $aType = $object->_activityTypeId;
    $requiredAct = TRUE;
  }

  if ($requiredAct && $context == "form" && in_array($aType, $activityTypes)) {
    $completeStatus = CRM_Core_OptionGroup::getValue('activity_status','Completed');
    $formname = $smarty->_tpl_vars['form']['formName'];

    $hideFields = '';
    //Array for the unwanted fields
    $hide = array(
      'client_name' => 'Client',
      'activityTypeName' => 'Activity.Type',
      'source_contact_id' => 'Created.By',
      'duration' => 'Location',
      'priority_id' => 'Priority',
      'medium_id' => 'Medium');
    if ($aType == CRM_Core_PseudoConstant::getKey('CRM_Activity_BAO_Activity', 'activity_type_id', 'Comment')) {
      $hide['assignee_contact_id'] = 'Assigned.To';
      $hide['status_id'] = 'Status';
      $hide['activity_date_time'] = 'Date.and.Time';
    }

    foreach ($hide as $hideForm => $hideView) {
      $hideFields .= "$('.crm-case-activity-form-block-{$hideForm}', context).hide();";
      $hideFields .= "$('.crm-case-activity-view-{$hideView}', context).hide();";
    }

    //Add script to the content to hide the above fields and show Evaluation profile for Completed status
    $content .="<script type=\"text/javascript\">
      CRM.$(function($) {
        var context = $('form#{$formname}');
        toggleEvaluation($('#status_id', context).val());
        $('#customData', context).hide();
        $('#sendcopy', context).hide();
        $('#follow-up', context).hide();
        $('.crm-accordion-wrapper', context).hide();
        {$hideFields}
        $('#status_id', context).bind('change', function(){
          toggleEvaluation(this.value);
        });
        function toggleEvaluation(val) {
          if(val == {$completeStatus}) {
            $('.crm-profile-name-evaluation_profile', context).show();
          }
          else {
            $('.crm-profile-name-evaluation_profile', context).hide();
          }
        }
      });
    </script>";
  }
}

