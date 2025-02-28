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
class CRM_Core_DAO_PaymentProcessorType extends CRM_Core_DAO
{
  /**
   * static instance to hold the table name
   *
   * @var string
   * @static
   */
  static $_tableName = 'civicrm_payment_processor_type';
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
  static $_log = false;
  /**
   * Payment Processor Type ID
   *
   * @var int unsigned
   */
  public $id;
  /**
   * Payment Processor Name.
   *
   * @var string
   */
  public $name;
  /**
   * Payment Processor Name.
   *
   * @var string
   */
  public $title;
  /**
   * Payment Processor Description.
   *
   * @var string
   */
  public $description;
  /**
   * Is this processor active?
   *
   * @var boolean
   */
  public $is_active;
  /**
   * Is this processor the default?
   *
   * @var boolean
   */
  public $is_default;
  /**
   *
   * @var string
   */
  public $user_name_label;
  /**
   *
   * @var string
   */
  public $password_label;
  /**
   *
   * @var string
   */
  public $signature_label;
  /**
   *
   * @var string
   */
  public $subject_label;
  /**
   *
   * @var string
   */
  public $class_name;
  /**
   *
   * @var string
   */
  public $url_site_default;
  /**
   *
   * @var string
   */
  public $url_api_default;
  /**
   *
   * @var string
   */
  public $url_recur_default;
  /**
   *
   * @var string
   */
  public $url_button_default;
  /**
   *
   * @var string
   */
  public $url_site_test_default;
  /**
   *
   * @var string
   */
  public $url_api_test_default;
  /**
   *
   * @var string
   */
  public $url_recur_test_default;
  /**
   *
   * @var string
   */
  public $url_button_test_default;
  /**
   * Billing Mode, check CRM_Core_Payment for details
   *
   * @var int unsigned
   */
  public $billing_mode;
  /**
   * Can process recurring contributions
   *
   * @var boolean
   */
  public $is_recur;
  /**
   * Payment Type: Credit or Debit
   *
   * @var int unsigned
   */
  public $payment_type;
  /**
   * class constructor
   *
   * @access public
   * @return civicrm_payment_processor_type
   */
  function __construct()
  {
    parent::__construct();
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
          'title' => ts('Payment Processor variable name to be used in code') ,
          'maxlength' => 64,
          'size' => CRM_Utils_Type::BIG,
        ) ,
        'title' => array(
          'name' => 'title',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Payment Processor Title') ,
          'maxlength' => 64,
          'size' => CRM_Utils_Type::BIG,
        ) ,
        'description' => array(
          'name' => 'description',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Description') ,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
        ) ,
        'is_active' => array(
          'name' => 'is_active',
          'type' => CRM_Utils_Type::T_BOOLEAN,
        ) ,
        'is_default' => array(
          'name' => 'is_default',
          'type' => CRM_Utils_Type::T_BOOLEAN,
        ) ,
        'user_name_label' => array(
          'name' => 'user_name_label',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Label for User Name if used') ,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
        ) ,
        'password_label' => array(
          'name' => 'password_label',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Label for password') ,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
        ) ,
        'signature_label' => array(
          'name' => 'signature_label',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Label for Signature') ,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
        ) ,
        'subject_label' => array(
          'name' => 'subject_label',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Label for Subject') ,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
        ) ,
        'class_name' => array(
          'name' => 'class_name',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Suffix for PHP clas name implementation') ,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
        ) ,
        'url_site_default' => array(
          'name' => 'url_site_default',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Default Live Site URL') ,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
        ) ,
        'url_api_default' => array(
          'name' => 'url_api_default',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Default API Site URL') ,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
        ) ,
        'url_recur_default' => array(
          'name' => 'url_recur_default',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Default Live Recurring Payments URL') ,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
        ) ,
        'url_button_default' => array(
          'name' => 'url_button_default',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Default Live Button URL') ,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
        ) ,
        'url_site_test_default' => array(
          'name' => 'url_site_test_default',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Default Test Site URL') ,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
        ) ,
        'url_api_test_default' => array(
          'name' => 'url_api_test_default',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Default Test API URL') ,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
        ) ,
        'url_recur_test_default' => array(
          'name' => 'url_recur_test_default',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Default Test Recurring Payment URL') ,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
        ) ,
        'url_button_test_default' => array(
          'name' => 'url_button_test_default',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => ts('Default Test Button URL') ,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
        ) ,
        'billing_mode' => array(
          'name' => 'billing_mode',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Billing Mode') ,
          'required' => true,
        ) ,
        'is_recur' => array(
          'name' => 'is_recur',
          'type' => CRM_Utils_Type::T_BOOLEAN,
        ) ,
        'payment_type' => array(
          'name' => 'payment_type',
          'type' => CRM_Utils_Type::T_INT,
          'title' => ts('Payment Type') ,
          'default' => '',
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
            self::$_import['payment_processor_type'] = & $fields[$name];
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
            self::$_export['payment_processor_type'] = & $fields[$name];
          } else {
            self::$_export[$name] = & $fields[$name];
          }
        }
      }
    }
    return self::$_export;
  }
}
