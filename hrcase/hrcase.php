<?php

require_once 'hrcase.civix.php';
require_once 'CRM/HRCase/Upgrader.php';

/**
 * Implementation of hook_civicrm_config
 */
function hrcase_civicrm_config(&$config) {
  _hrcase_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param array $files
 */
function hrcase_civicrm_xmlMenu(&$files) {
  _hrcase_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_install
 */
function hrcase_civicrm_install() {
  // PCHR-1263 : hrcase should not be installed without Task & Assignments extension
  if (!CRM_HRCase_Upgrader::isExtensionEnabled('uk.co.compucorp.civicrm.tasksassignments'))  {
    _hrcase_TaskAndAssignmentsPageRedirect();
  }

  _hrcase_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 */
function hrcase_civicrm_uninstall() {
  CRM_HRCase_Upgrader::activityTypesWordReplacement(true);
  CRM_HRCase_Upgrader::removeRelationshipTypes();

  _hrcase_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 */
function hrcase_civicrm_enable() {
  // PCHR-1263 : hrcase should not be installed/enabled without Task & Assignments extension
  if (!CRM_HRCase_Upgrader::isExtensionEnabled('uk.co.compucorp.civicrm.tasksassignments'))  {
    _hrcase_TaskAndAssignmentsPageRedirect();
  }

  CRM_HRCase_Upgrader::toggleRelationshipTypes(1);

  _hrcase_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 */
function hrcase_civicrm_disable() {
  CRM_HRCase_Upgrader::toggleRelationshipTypes(0);

  _hrcase_civix_civicrm_disable();
}

/**
 * Implementation of hook_civicrm_upgrade
 *
 * @param string $op
 *   The type of operation being performed; 'check' or 'enqueue'
 * @param CRM_Queue_Queue $queue
 *   (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 */
function hrcase_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _hrcase_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 */
function hrcase_civicrm_managed(&$entities) {
  _hrcase_civix_civicrm_managed($entities);
}

/**
 * Implementation of hook_civicrm_caseTypes
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 */
function hrcase_civicrm_caseTypes(&$caseTypes) {
  _hrcase_civix_civicrm_caseTypes($caseTypes);
}

/**
 * redirect to extension list page and show error notification if T&A isn't installed/enabled
 */
function _hrcase_TaskAndAssignmentsPageRedirect()  {
  $message = ts("You should Install/Enable Task & Assignments extension first");
  CRM_Core_Session::setStatus($message, ts('Cannot install/enable extension'), 'error');
  $url = CRM_Utils_System::url(
    'civicrm/admin/extensions',
    http_build_query([
      'reset' => 1
    ])
  );
  CRM_Utils_System::redirect($url);
}
