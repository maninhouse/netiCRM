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

require_once 'CRM/Contact/Form/Search/Interface.php';
class CRM_Contact_Form_Search_Custom_Base {

  protected $_formValues;

  protected $_columns;
  
  function __construct(&$formValues) {
    $this->_formValues = &$formValues;
  }

  function count() {
    return CRM_Core_DAO::singleValueQuery($this->sql('count(distinct contact_a.id) as total'),
      CRM_Core_DAO::$_nullArray
    );
  }

  function summary() {
    return NULL;
  }

  function contactIDs($offset = 0, $rowcount = 0, $sort = NULL) {
    $sql = $this->sql('contact_a.id as contact_id',
      $offset, $rowcount, $sort
    );
    $this->validateUserSQL($sql);

    return CRM_Core_DAO::composeQuery($sql,
      CRM_Core_DAO::$_nullArray,
      TRUE
    );
  }

  function sql($selectClause,
    $offset = 0, $rowcount = 0, $sort = NULL,
    $includeContactIDs = FALSE,
    $groupBy = NULL
  ) {

    $sql = "SELECT $selectClause " . $this->from() . " WHERE " . $this->where();

    if ($includeContactIDs) {
      $this->includeContactIDs($sql,
        $this->_formValues
      );
    }

    if ($groupBy) {
      $sql .= " $groupBy ";
    }

    $this->addSortOffset($sql, $offset, $rowcount, $sort);
    return $sql;
  }

  function templateFile() {
    return NULL;
  }

  function &columns() {
    return $this->_columns;
  }

  static function includeContactIDs(&$sql, &$formValues) {
    $contactIDs = array();
    foreach ($formValues as $id => $value) {
      list($contactID, $additionalID) = CRM_Core_Form::cbExtract($id);
      if ($value && !empty($contactID)) {
        $contactIDs[] = $contactID;
      }
    }

    if (!empty($contactIDs)) {
      $contactIDs = implode(', ', $contactIDs);
      $sql .= " AND contact_a.id IN ( $contactIDs )";
    }
  }

  function addSortOffset(&$sql,
    $offset, $rowcount, $sort
  ) {
    if (!empty($sort)) {
      if (is_string($sort)) {
        $sql .= " ORDER BY $sort ";
      }
      else {
        $sql .= " ORDER BY " . trim($sort->orderBy());
      }
    }

    if ($rowcount > 0 && $offset >= 0) {
      $sql .= " LIMIT $offset, $rowcount ";
    }
  }

  function validateUserSQL(&$sql, $onlyWhere = FALSE) {
    $includeStrings = array('contact_a');
    $excludeStrings = array('insert', 'delete', 'update');

    if (!$onlyWhere) {
      $includeStrings += array('select', 'from', 'where', 'civicrm_contact');
    }

    foreach ($includeStrings as $string) {
      if (stripos($sql, $string) === FALSE) {
        return CRM_Core_Error::statusBounce(ts('Could not find \'%1\' string in SQL clause.',
            array(1 => $string)
          ));
      }
    }

    foreach ($excludeStrings as $string) {
      if (preg_match('/(\s' . $string . ')|(' . $string . '\s)/i', $sql)) {
        return CRM_Core_Error::statusBounce(ts('Found illegal \'%1\' string in SQL clause.',
            array(1 => $string)
          ));
      }
    }
  }

  function whereClause(&$where, &$params) {
    return CRM_Core_DAO::composeQuery($where, $params, TRUE);
  }
}

