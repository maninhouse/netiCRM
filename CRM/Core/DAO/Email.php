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
class CRM_Core_DAO_Email extends CRM_Core_DAO
{
  /**
   * static instance to hold the table name
   *
   * @var string
   * @static
   */
  static $_tableName = 'civicrm_email';
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
   * Unique Email ID
   *
   * @var int unsigned
   */
  public $id;
  /**
   * FK to Contact ID
   *
   * @var int unsigned
   */
  public $contact_id;
  /**
   * Which Location does this email belong to.
   *
   * @var int unsigned
   */
  public $location_type_id;
  /**
   * Email address
   *
   * @var string
   */
  public $email;
  /**
   * Is this the primary email for this contact and location.
   *
   * @var boolean
   */
  public $is_primary;
  /**
   * Is this the billing?
   *
   * @var boolean
   */
  public $is_billing;
  /**
   * Is this address on bounce hold?
   *
   * @var boolean
   */
  public $on_hold;
  /**
   * Is this address for bulk mail ?
   *
   * @var boolean
   */
  public $is_bulkmail;
  /**
   * When the address went on bounce hold
   *
   * @var datetime
   */
  public $hold_date;
  /**
   * When the address bounce status was last reset
   *
   * @var datetime
   */
  public $reset_date;
  /**
   * Text formatted signature for the email.
   *
   * @var text
   */
  public $signature_text;
  /**
   * HTML formatted signature for the email.
   *
   * @var text
   */
  public $signature_html;
  /**
   * class constructor
   *
   * @access public
   * @return civicrm_email
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
        'contact_id' => 'civicrm_contact:id',
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
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName() , 'contact_id', 'civicrm_contact', 'id');
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
        'contact_id' => array(
          'name' => 'contact_id',
          'type' => CRM_Utils_Type::T_INT,
          'FKClassName' => 'CRM_Contact_DAO_Contact',
        ) ,
        'location_type_id' => array(
          'name' => 'location_type_id',
          'type' => CRM_Utils_Type::T_INT,
        ) ,
        'email' => array(
          'name' => 'email',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Email') ,
          'maxlength' => 64,
          'size' => CRM_Utils_Type::BIG,
          'import' => true,
          'where' => 'civicrm_email.email',
          'headerPattern' => '/e.?mail/i',
          'dataPattern' => '/^[a-zA-Z][\w\.-]*[a-zA-Z0-9]@[a-zA-Z0-9][\w\.-]*[a-zA-Z0-9]\.[a-zA-Z][a-zA-Z\.]*[a-zA-Z]$/',
          'export' => true,
          'rule' => 'email',
        ) ,
        'is_primary' => array(
          'name' => 'is_primary',
          'type' => CRM_Utils_Type::T_BOOLEAN,
        ) ,
        'is_billing' => array(
          'name' => 'is_billing',
          'type' => CRM_Utils_Type::T_BOOLEAN,
        ) ,
        'on_hold' => array(
          'name' => 'on_hold',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => ts('On Hold') ,
          'required' => true,
          'export' => true,
          'where' => 'civicrm_email.on_hold',
          'headerPattern' => '',
          'dataPattern' => '',
        ) ,
        'is_bulkmail' => array(
          'name' => 'is_bulkmail',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => ts('Use for Bulk Mail') ,
          'required' => true,
          'export' => true,
          'where' => 'civicrm_email.is_bulkmail',
          'headerPattern' => '',
          'dataPattern' => '',
        ) ,
        'hold_date' => array(
          'name' => 'hold_date',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => ts('Hold Date') ,
        ) ,
        'reset_date' => array(
          'name' => 'reset_date',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => ts('Reset Date') ,
        ) ,
        'signature_text' => array(
          'name' => 'signature_text',
          'type' => CRM_Utils_Type::T_TEXT,
          'title' => ts('Signature Text') ,
          'import' => true,
          'where' => 'civicrm_email.signature_text',
          'headerPattern' => '',
          'dataPattern' => '',
          'export' => true,
          'default' => 'UL',
        ) ,
        'signature_html' => array(
          'name' => 'signature_html',
          'type' => CRM_Utils_Type::T_TEXT,
          'title' => ts('Signature Html') ,
          'import' => true,
          'where' => 'civicrm_email.signature_html',
          'headerPattern' => '',
          'dataPattern' => '',
          'export' => true,
          'default' => 'UL',
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
   */
  function &import($prefix = false)
  {
    if (!(self::$_import)) {
      self::$_import = array();
      $fields = & self::fields();
      foreach($fields as $name => $field) {
        if (CRM_Utils_Array::value('import', $field)) {
          if ($prefix) {
            self::$_import['email'] = & $fields[$name];
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
            self::$_export['email'] = & $fields[$name];
          } else {
            self::$_export[$name] = & $fields[$name];
          }
        }
      }
    }
    return self::$_export;
  }
}
