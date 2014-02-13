<?php
/*
+--------------------------------------------------------------------+
| CiviHR version 1.2                                                 |
+--------------------------------------------------------------------+
| Copyright CiviCRM LLC (c) 2004-2013                                |
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
 * @copyright CiviCRM LLC (c) 2004-2013
 *
 * Generated from xml/schema/CRM/HRJob/HRJobHour.xml
 * DO NOT EDIT.  Generated by GenCode.php
 */
require_once 'CRM/Core/DAO.php';
require_once 'CRM/Utils/Type.php';
class CRM_HRJob_DAO_HRJobHour extends CRM_Core_DAO
{
  /**
   * static instance to hold the table name
   *
   * @var string
   * @static
   */
  static $_tableName = 'civicrm_hrjob_hour';
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
   * Unique HRJobHour ID
   *
   * @var int unsigned
   */
  public $id;
  /**
   * FK to Job
   *
   * @var int unsigned
   */
  public $job_id;
  /**
   * Full-Time, Part-Time, Casual
   *
   * @var string
   */
  public $hours_type;
  /**
   * Amount of time allocated for work (in given period)
   *
   * @var float
   */
  public $hours_amount;
  /**
   * Period during which hours are allocated (eg 5 hours per day; 5 hours per week)
   *
   * @var enum('Day', 'Week', 'Month', 'Year')
   */
  public $hours_unit;
  /**
   * Typically, employment at 40 hr/wk is 1 FTE
   *
   * @var float
   */
  public $hours_fte;
  /**
   * class constructor
   *
   * @access public
   * @return civicrm_hrjob_hour
   */
  function __construct()
  {
    $this->__table = 'civicrm_hrjob_hour';
    parent::__construct();
  }
  /**
   * return foreign keys and entity references
   *
   * @static
   * @access public
   * @return array of CRM_Core_EntityReference
   */
  static function getReferenceColumns()
  {
    if (!self::$_links) {
      self::$_links = array(
        new CRM_Core_EntityReference(self::getTableName() , 'job_id', 'civicrm_hrjob', 'id') ,
      );
    }
    return self::$_links;
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
        'job_id' => array(
          'name' => 'job_id',
          'type' => CRM_Utils_Type::T_INT,
          'required' => true,
          'FKClassName' => 'CRM_HRJob_DAO_HRJob',
        ) ,
        'hrjob_hours_type' => array(
          'name' => 'hours_type',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Job Hours Type') ,
          'maxlength' => 63,
          'size' => CRM_Utils_Type::BIG,
          'export' => true,
          'import' => true,
          'where' => 'civicrm_hrjob_hour.hours_type',
          'headerPattern' => '',
          'dataPattern' => '',
          'pseudoconstant' => array(
            'optionGroupName' => 'hrjob_hours_type',
          )
        ) ,
        'hrjob_hours_amount' => array(
          'name' => 'hours_amount',
          'type' => CRM_Utils_Type::T_FLOAT,
          'title' => ts('Job Hours Amount') ,
          'export' => true,
          'import' => true,
          'where' => 'civicrm_hrjob_hour.hours_amount',
          'headerPattern' => '',
          'dataPattern' => '',
        ) ,
        'hrjob_hours_unit' => array(
          'name' => 'hours_unit',
          'type' => CRM_Utils_Type::T_ENUM,
          'title' => ts('Job Hours Unit') ,
          'export' => true,
          'import' => true,
          'where' => 'civicrm_hrjob_hour.hours_unit',
          'headerPattern' => '',
          'dataPattern' => '',
          'enumValues' => 'Day, Week, Month, Year',
        ) ,
        'hrjob_hours_fte' => array(
          'name' => 'hours_fte',
          'type' => CRM_Utils_Type::T_FLOAT,
          'title' => ts('Job Full-Time Equivalence') ,
          'export' => true,
          'import' => true,
          'where' => 'civicrm_hrjob_hour.hours_fte',
          'headerPattern' => '',
          'dataPattern' => '',
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
        'job_id' => 'job_id',
        'hours_type' => 'hrjob_hours_type',
        'hours_amount' => 'hrjob_hours_amount',
        'hours_unit' => 'hrjob_hours_unit',
        'hours_fte' => 'hrjob_hours_fte',
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
        if (!empty($field['import'])) {
          if ($prefix) {
            self::$_import['hrjob_hour'] = & $fields[$name];
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
        if (!empty($field['export'])) {
          if ($prefix) {
            self::$_export['hrjob_hour'] = & $fields[$name];
          } else {
            self::$_export[$name] = & $fields[$name];
          }
        }
      }
    }
    return self::$_export;
  }
  /**
   * returns an array containing the enum fields of the civicrm_hrjob_hour table
   *
   * @return array (reference)  the array of enum fields
   */
  static function &getEnums()
  {
    static $enums = array(
      'hours_unit',
    );
    return $enums;
  }
  /**
   * returns a ts()-translated enum value for display purposes
   *
   * @param string $field  the enum field in question
   * @param string $value  the enum value up for translation
   *
   * @return string  the display value of the enum
   */
  static function tsEnum($field, $value)
  {
    static $translations = null;
    if (!$translations) {
      $translations = array(
        'hours_unit' => array(
          'Day' => ts('Day') ,
          'Week' => ts('Week') ,
          'Month' => ts('Month') ,
          'Year' => ts('Year') ,
        ) ,
      );
    }
    return $translations[$field][$value];
  }
  /**
   * adds $value['foo_display'] for each $value['foo'] enum from civicrm_hrjob_hour
   *
   * @param array $values (reference)  the array up for enhancing
   * @return void
   */
  static function addDisplayEnums(&$values)
  {
    $enumFields = & CRM_HRJob_DAO_HRJobHour::getEnums();
    foreach($enumFields as $enum) {
      if (isset($values[$enum])) {
        $values[$enum . '_display'] = CRM_HRJob_DAO_HRJobHour::tsEnum($enum, $values[$enum]);
      }
    }
  }
}
