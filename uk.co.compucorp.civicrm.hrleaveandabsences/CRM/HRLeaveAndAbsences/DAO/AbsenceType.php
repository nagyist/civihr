<?php
/*
+--------------------------------------------------------------------+
| CiviCRM version 4.5                                                |
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
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2014
 *
 * Generated from xml/schema/CRM/HRLeaveAndAbsences/AbsenceType.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 */
require_once 'CRM/Core/DAO.php';
require_once 'CRM/Utils/Type.php';
class CRM_HRLeaveAndAbsences_DAO_AbsenceType extends CRM_Core_DAO
{
  /**
   * static instance to hold the table name
   *
   * @var string
   * @static
   */
  static $_tableName = 'civicrm_hrleaveandabsences_absence_type';
  /**
   * static instance to hold the field values
   *
   * @var array
   * @static
   */
  static $_fields = null;
  /**
   * static instance to hold the keys used in $_fields for each field.
   *
   * @var array
   * @static
   */
  static $_fieldKeys = null;
  /**
   * static instance to hold the FK relationships
   *
   * @var string
   * @static
   */
  static $_links = null;
  /**
   * static instance to hold the values that can
   * be imported
   *
   * @var array
   * @static
   */
  static $_import = null;
  /**
   * static instance to hold the values that can
   * be exported
   *
   * @var array
   * @static
   */
  static $_export = null;
  /**
   * static value to see if we should log any modifications to
   * this table in the civicrm_log table
   *
   * @var boolean
   * @static
   */
  static $_log = true;
  /**
   * Unique AbsenceType ID
   *
   * @var int unsigned
   */
  public $id;
  /**
   * The AbsenceType title. There can't be more than one entity with the same title
   *
   * @var string
   */
  public $title;
  /**
   * The weight value is used to order the types on a list
   *
   * @var int unsigned
   */
  public $weight;
  /**
   * The color hex value (including the #) used to display this type on a calendar
   *
   * @var string
   */
  public $color;
  /**
   * There can only be one default entity at any given time
   *
   * @var boolean
   */
  public $is_default;
  /**
   * Reserved entities are used by internal calculations and cannot be deleted.
   *
   * @var boolean
   */
  public $is_reserved;
  /**
   *
   * @var int unsigned
   */
  public $max_consecutive_leave_days;
  /**
   * Can only be one of the values defined in AbsenceType::REQUEST_CANCELATION_OPTIONS
   *
   * @var int unsigned
   */
  public $allow_request_cancelation;
  /**
   *
   * @var boolean
   */
  public $allow_overuse;
  /**
   *
   * @var boolean
   */
  public $must_take_public_holiday_as_leave;
  /**
   * The number of days entitled for this type
   *
   * @var int unsigned
   */
  public $default_entitlement;
  /**
   *
   * @var boolean
   */
  public $add_public_holiday_to_entitlement;
  /**
   * Only enabled types can be requested
   *
   * @var boolean
   */
  public $is_active;
  /**
   *
   * @var boolean
   */
  public $allow_accruals_request;
  /**
   * Value is the number of days that can be accrued. Null means unlimited
   *
   * @var int unsigned
   */
  public $max_leave_accrual;
  /**
   *
   * @var boolean
   */
  public $allow_accrue_in_the_past;
  /**
   * An amount of accrual_expiration_unit
   *
   * @var int unsigned
   */
  public $accrual_expiration_duration;
  /**
   * The unit (year, month, etc) of accrual_expiration_duration of this type default expiry
   *
   * @var int unsigned
   */
  public $accrual_expiration_unit;
  /**
   *
   * @var boolean
   */
  public $allow_carry_forward;
  /**
   *
   * @var int unsigned
   */
  public $max_number_of_days_to_carry_forward;
  /**
   * An amount of carry_forward_expiration_unit
   *
   * @var int unsigned
   */
  public $carry_forward_expiration_duration;
  /**
   * The unit (year, month, etc) of carry_forward_expiration_duration of this type default expiry
   *
   * @var int unsigned
   */
  public $carry_forward_expiration_unit;
  /**
   * If expiration_unit + expiration_duration is not informed, this should be. Its a date in the format dd-mm
   *
   * @var string
   */
  public $carry_forward_expiration_date;
  /**
   * class constructor
   *
   * @access public
   * @return civicrm_hrleaveandabsences_absence_type
   */
  function __construct()
  {
    $this->__table = 'civicrm_hrleaveandabsences_absence_type';
    parent::__construct();
  }
  /**
   * returns all the column names of this table
   *
   * @access public
   * @return array
   */
  static function &fields()
  {
    if (!(self::$_fields)) {
      self::$_fields = array(
        'id' => array(
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'required' => true,
        ) ,
        'title' => array(
          'name' => 'title',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Title') ,
          'required' => true,
          'maxlength' => 127,
          'size' => CRM_Utils_Type::HUGE,
        ) ,
        'weight' => array(
          'name' => 'weight',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Weight') ,
          'required' => true,
        ) ,
        'color' => array(
          'name' => 'color',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Colour') ,
          'required' => true,
          'maxlength' => 7,
          'size' => CRM_Utils_Type::EIGHT,
        ) ,
        'is_default' => array(
          'name' => 'is_default',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => ts('Is default?') ,
        ) ,
        'is_reserved' => array(
          'name' => 'is_reserved',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => ts('Is reserved?') ,
        ) ,
        'max_consecutive_leave_days' => array(
          'name' => 'max_consecutive_leave_days',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Duration of consecutive leave permitted to be taken at once') ,
        ) ,
        'allow_request_cancelation' => array(
          'name' => 'allow_request_cancelation',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Can staff cancel requests for this leave type after they have been made?') ,
          'required' => true,
        ) ,
        'allow_overuse' => array(
          'name' => 'allow_overuse',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => ts('Can employee apply for this leave type even if they have used up their entitlement for the year?') ,
        ) ,
        'must_take_public_holiday_as_leave' => array(
          'name' => 'must_take_public_holiday_as_leave',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => ts('Must staff take public holiday as leave') ,
        ) ,
        'default_entitlement' => array(
          'name' => 'default_entitlement',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Default entitlement') ,
          'required' => true,
        ) ,
        'add_public_holiday_to_entitlement' => array(
          'name' => 'add_public_holiday_to_entitlement',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => ts('By default should public holiday be added to the default entitlement?') ,
        ) ,
        'is_active' => array(
          'name' => 'is_active',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => ts('Is enabled?') ,
          'default' => '1',
        ) ,
        'allow_accruals_request' => array(
          'name' => 'allow_accruals_request',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => ts('Allow staff to request to accrue additional days leave of this type during the period') ,
        ) ,
        'max_leave_accrual' => array(
          'name' => 'max_leave_accrual',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Maximum amount of leave that can be accrued of this absence type during a period') ,
        ) ,
        'allow_accrue_in_the_past' => array(
          'name' => 'allow_accrue_in_the_past',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => ts('Can staff request to accrue leave for dates in the past?') ,
        ) ,
        'accrual_expiration_duration' => array(
          'name' => 'accrual_expiration_duration',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Default expiry of accrued amounts') ,
        ) ,
        'accrual_expiration_unit' => array(
          'name' => 'accrual_expiration_unit',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Accrual Expiration Unit') ,
        ) ,
        'allow_carry_forward' => array(
          'name' => 'allow_carry_forward',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => ts('Allow leave of this type to be carried forward from one period to another?') ,
        ) ,
        'max_number_of_days_to_carry_forward' => array(
          'name' => 'max_number_of_days_to_carry_forward',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Maximum number of days that can be carried forward to a new period?') ,
        ) ,
        'carry_forward_expiration_duration' => array(
          'name' => 'carry_forward_expiration_duration',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Carried forward leave expiry') ,
        ) ,
        'carry_forward_expiration_unit' => array(
          'name' => 'carry_forward_expiration_unit',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Carry Forward Expiration Unit') ,
        ) ,
        'carry_forward_expiration_date' => array(
          'name' => 'carry_forward_expiration_date',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Carry Forward Expiration Date') ,
          'maxlength' => 5,
          'size' => CRM_Utils_Type::SIX,
        ) ,
      );
    }
    return self::$_fields;
  }
  /**
   * Returns an array containing, for each field, the arary key used for that
   * field in self::$_fields.
   *
   * @access public
   * @return array
   */
  static function &fieldKeys()
  {
    if (!(self::$_fieldKeys)) {
      self::$_fieldKeys = array(
        'id' => 'id',
        'title' => 'title',
        'weight' => 'weight',
        'color' => 'color',
        'is_default' => 'is_default',
        'is_reserved' => 'is_reserved',
        'max_consecutive_leave_days' => 'max_consecutive_leave_days',
        'allow_request_cancelation' => 'allow_request_cancelation',
        'allow_overuse' => 'allow_overuse',
        'must_take_public_holiday_as_leave' => 'must_take_public_holiday_as_leave',
        'default_entitlement' => 'default_entitlement',
        'add_public_holiday_to_entitlement' => 'add_public_holiday_to_entitlement',
        'is_active' => 'is_active',
        'allow_accruals_request' => 'allow_accruals_request',
        'max_leave_accrual' => 'max_leave_accrual',
        'allow_accrue_in_the_past' => 'allow_accrue_in_the_past',
        'accrual_expiration_duration' => 'accrual_expiration_duration',
        'accrual_expiration_unit' => 'accrual_expiration_unit',
        'allow_carry_forward' => 'allow_carry_forward',
        'max_number_of_days_to_carry_forward' => 'max_number_of_days_to_carry_forward',
        'carry_forward_expiration_duration' => 'carry_forward_expiration_duration',
        'carry_forward_expiration_unit' => 'carry_forward_expiration_unit',
        'carry_forward_expiration_date' => 'carry_forward_expiration_date',
      );
    }
    return self::$_fieldKeys;
  }
  /**
   * returns the names of this table
   *
   * @access public
   * @static
   * @return string
   */
  static function getTableName()
  {
    return self::$_tableName;
  }
  /**
   * returns if this table needs to be logged
   *
   * @access public
   * @return boolean
   */
  function getLog()
  {
    return self::$_log;
  }
  /**
   * returns the list of fields that can be imported
   *
   * @access public
   * return array
   * @static
   */
  static function &import($prefix = false)
  {
    if (!(self::$_import)) {
      self::$_import = array();
      $fields = self::fields();
      foreach($fields as $name => $field) {
        if (CRM_Utils_Array::value('import', $field)) {
          if ($prefix) {
            self::$_import['hrleaveandabsences_absence_type'] = & $fields[$name];
          } else {
            self::$_import[$name] = & $fields[$name];
          }
        }
      }
    }
    return self::$_import;
  }
  /**
   * returns the list of fields that can be exported
   *
   * @access public
   * return array
   * @static
   */
  static function &export($prefix = false)
  {
    if (!(self::$_export)) {
      self::$_export = array();
      $fields = self::fields();
      foreach($fields as $name => $field) {
        if (CRM_Utils_Array::value('export', $field)) {
          if ($prefix) {
            self::$_export['hrleaveandabsences_absence_type'] = & $fields[$name];
          } else {
            self::$_export[$name] = & $fields[$name];
          }
        }
      }
    }
    return self::$_export;
  }
}
