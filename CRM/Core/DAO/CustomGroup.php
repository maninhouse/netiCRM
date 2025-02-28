<?php
/*
+--------------------------------------------------------------------+
| CiviCRM version 3.3                                                |
+--------------------------------------------------------------------+
| Copyright CiviCRM LLC (c) 2004-2010                                |
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
 * @copyright CiviCRM LLC (c) 2004-2010
 * $Id$
 *
 */
require_once 'CRM/Core/DAO.php';
require_once 'CRM/Utils/Type.php';
class CRM_Core_DAO_CustomGroup extends CRM_Core_DAO
{
  /**
   * static instance to hold the table name
   *
   * @var string
   * @static
   */
  static $_tableName = 'civicrm_custom_group';
  /**
   * static instance to hold the field values
   *
   * @var array
   * @static
   */
  static $_fields = null;
  /**
   * static instance to hold the FK relationships
   *
   * @var string
   * @static
   */
  static $_links = null;
  /**
   * static instance to hold the values that can
   * be imported / apu
   *
   * @var array
   * @static
   */
  static $_import = null;
  /**
   * static instance to hold the values that can
   * be exported / apu
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
   * Unique Custom Group ID
   *
   * @var int unsigned
   */
  public $id;
  /**
   * Variable name/programmatic handle for this group.
   *
   * @var string
   */
  public $name;
  /**
   * Friendly Name.
   *
   * @var string
   */
  public $title;
  /**
   * Type of object this group extends (can add other options later e.g. contact_address, etc.).
   *
   * @var enum('Contact', 'Individual', 'Household', 'Organization', 'Location', 'Address', 'Contribution', 'ContributionPage', 'ContributionRecur', 'Activity', 'Relationship', 'Group', 'Membership', 'Participant', 'Event', 'Grant', 'Pledge', 'Case', 'PriceField')
   */
  public $extends;
  /**
   * FK to civicrm_option_value.id (for option group custom_data_type.)
   *
   * @var int unsigned
   */
  public $extends_entity_column_id;
  /**
   * linking custom group for dynamic object
   *
   * @var string
   */
  public $extends_entity_column_value;
  /**
   * Visual relationship between this form and its parent.
   *
   * @var enum('Tab', 'Inline')
   */
  public $style;
  /**
   * Will this group be in collapsed or expanded mode on initial display ?
   *
   * @var int unsigned
   */
  public $collapse_display;
  /**
   * Description and/or help text to display before fields in form.
   *
   * @var text
   */
  public $help_pre;
  /**
   * Description and/or help text to display after fields in form.
   *
   * @var text
   */
  public $help_post;
  /**
   * Controls display order when multiple extended property groups are setup for the same class.
   *
   * @var int
   */
  public $weight;
  /**
   * Is this property active?
   *
   * @var boolean
   */
  public $is_active;
  /**
   * Name of the table that holds the values for this group.
   *
   * @var string
   */
  public $table_name;
  /**
   * Does this group hold multiple values?
   *
   * @var boolean
   */
  public $is_multiple;
  /**
   * minimum number of multiple records (typically 0?)
   *
   * @var int unsigned
   */
  public $min_multiple;
  /**
   * maximum number of multiple records, if 0 - no max
   *
   * @var int unsigned
   */
  public $max_multiple;
  /**
   * Will this group be in collapsed or expanded mode on advanced search display ?
   *
   * @var int unsigned
   */
  public $collapse_adv_display;
  /**
   * FK to civicrm_contact, who created this custom group
   *
   * @var int unsigned
   */
  public $created_id;
  /**
   * Date and time this custom group was created.
   *
   * @var datetime
   */
  public $created_date;
  /**
   * class constructor
   *
   * @access public
   * @return civicrm_custom_group
   */
  function __construct()
  {
    parent::__construct();
  }
  /**
   * return foreign links
   *
   * @access public
   * @return array
   */
  function &links()
  {
    if (!(self::$_links)) {
      self::$_links = array(
        'created_id' => 'civicrm_contact:id',
      );
    }
    return self::$_links;
  }
  /**
   * Returns foreign keys and entity references.
   *
   * @return array
   *   [CRM_Core_Reference_Interface]
   */
  public static function getReferenceColumns()
  {
    if (!isset(Civi::$statics[__CLASS__]['links'])) {
      Civi::$statics[__CLASS__]['links'] = static ::createReferenceColumns(__CLASS__);
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName() , 'created_id', 'civicrm_contact', 'id');
    }
    return Civi::$statics[__CLASS__]['links'];
  }
  /**
   * returns all the column names of this table
   *
   * @access public
   * @return array
   */
  function &fields()
  {
    if (!(self::$_fields)) {
      self::$_fields = array(
        'id' => array(
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'required' => true,
        ) ,
        'name' => array(
          'name' => 'name',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Name') ,
          'maxlength' => 64,
          'size' => CRM_Utils_Type::BIG,
        ) ,
        'title' => array(
          'name' => 'title',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Title') ,
          'maxlength' => 64,
          'size' => CRM_Utils_Type::BIG,
        ) ,
        'extends' => array(
          'name' => 'extends',
          'type' => CRM_Utils_Type::T_ENUM,
          'title' => ts('Extends') ,
          'default' => 'Contact',
          'enumValues' => 'Contact, Individual, Household, Organization, Location, Address, Contribution, ContributionPage, ContributionRecur, Activity, Relationship, Group, Membership, Participant, Event, Grant, Pledge, Case, PriceField',
        ) ,
        'extends_entity_column_id' => array(
          'name' => 'extends_entity_column_id',
          'type' => CRM_Utils_Type::T_INT,
          'default' => 'UL',
        ) ,
        'extends_entity_column_value' => array(
          'name' => 'extends_entity_column_value',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Extends Entity Column Value') ,
          'maxlength' => 64,
          'size' => CRM_Utils_Type::BIG,
        ) ,
        'style' => array(
          'name' => 'style',
          'type' => CRM_Utils_Type::T_ENUM,
          'title' => ts('Style') ,
          'enumValues' => 'Tab, Inline',
        ) ,
        'collapse_display' => array(
          'name' => 'collapse_display',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Collapse Display') ,
        ) ,
        'help_pre' => array(
          'name' => 'help_pre',
          'type' => CRM_Utils_Type::T_TEXT,
          'title' => ts('Help Pre') ,
          'rows' => 4,
          'cols' => 80,
        ) ,
        'help_post' => array(
          'name' => 'help_post',
          'type' => CRM_Utils_Type::T_TEXT,
          'title' => ts('Help Post') ,
          'rows' => 4,
          'cols' => 80,
        ) ,
        'weight' => array(
          'name' => 'weight',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Weight') ,
          'required' => true,
          'default' => '',
        ) ,
        'is_active' => array(
          'name' => 'is_active',
          'type' => CRM_Utils_Type::T_BOOLEAN,
        ) ,
        'table_name' => array(
          'name' => 'table_name',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Table Name') ,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
        ) ,
        'is_multiple' => array(
          'name' => 'is_multiple',
          'type' => CRM_Utils_Type::T_BOOLEAN,
        ) ,
        'min_multiple' => array(
          'name' => 'min_multiple',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Min Multiple') ,
        ) ,
        'max_multiple' => array(
          'name' => 'max_multiple',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Max Multiple') ,
        ) ,
        'collapse_adv_display' => array(
          'name' => 'collapse_adv_display',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Collapse Adv Display') ,
        ) ,
        'created_id' => array(
          'name' => 'created_id',
          'type' => CRM_Utils_Type::T_INT,
          'FKClassName' => 'CRM_Contact_DAO_Contact',
        ) ,
        'created_date' => array(
          'name' => 'created_date',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => ts('Custom Group Created Date') ,
        ) ,
      );
    }
    return self::$_fields;
  }
  /**
   * returns the names of this table
   *
   * @access public
   * @return string
   */
  function getTableName()
  {
    global $dbLocale;
    return self::$_tableName . $dbLocale;
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
   */
  function &import($prefix = false)
  {
    if (!(self::$_import)) {
      self::$_import = array();
      $fields = & self::fields();
      foreach($fields as $name => $field) {
        if (CRM_Utils_Array::value('import', $field)) {
          if ($prefix) {
            self::$_import['custom_group'] = & $fields[$name];
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
   */
  function &export($prefix = false)
  {
    if (!(self::$_export)) {
      self::$_export = array();
      $fields = & self::fields();
      foreach($fields as $name => $field) {
        if (CRM_Utils_Array::value('export', $field)) {
          if ($prefix) {
            self::$_export['custom_group'] = & $fields[$name];
          } else {
            self::$_export[$name] = & $fields[$name];
          }
        }
      }
    }
    return self::$_export;
  }
  /**
   * returns an array containing the enum fields of the civicrm_custom_group table
   *
   * @return array (reference)  the array of enum fields
   */
  static function &getEnums()
  {
    static $enums = array(
      'extends',
      'style',
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
        'extends' => array(
          'Contact' => ts('Contact') ,
          'Individual' => ts('Individual') ,
          'Household' => ts('Household') ,
          'Organization' => ts('Organization') ,
          'Location' => ts('Location') ,
          'Address' => ts('Address') ,
          'Contribution' => ts('Contribution') ,
          'ContributionPage' => ts('ContributionPage') ,
          'ContributionRecur' => ts('ContributionRecur') ,
          'Activity' => ts('Activity') ,
          'Relationship' => ts('Relationship') ,
          'Group' => ts('Group') ,
          'Membership' => ts('Membership') ,
          'Participant' => ts('Participant') ,
          'Event' => ts('Event') ,
          'Grant' => ts('Grant') ,
          'Pledge' => ts('Pledge') ,
          'Case' => ts('Case') ,
          'PriceField' => ts('PriceField') ,
        ) ,
        'style' => array(
          'Tab' => ts('Tab') ,
          'Inline' => ts('Inline') ,
        ) ,
      );
    }
    return $translations[$field][$value];
  }
  /**
   * adds $value['foo_display'] for each $value['foo'] enum from civicrm_custom_group
   *
   * @param array $values (reference)  the array up for enhancing
   * @return void
   */
  static function addDisplayEnums(&$values)
  {
    $enumFields = & CRM_Core_DAO_CustomGroup::getEnums();
    foreach($enumFields as $enum) {
      if (isset($values[$enum])) {
        $values[$enum . '_display'] = CRM_Core_DAO_CustomGroup::tsEnum($enum, $values[$enum]);
      }
    }
  }
}
