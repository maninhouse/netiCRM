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

require_once 'CRM/Core/Form.php';
require_once 'CRM/Member/Import/Parser/Membership.php';

/**
 * This class previews the uploaded file and returns summary
 * statistics
 */
class CRM_Member_Import_Form_Preview extends CRM_Core_Form {

  /**
   * Function to set variables up before form is built
   *
   * @return void
   * @access public
   */
  public function preProcess() {
    $skipColumnHeader = $this->controller->exportValue('UploadFile', 'skipColumnHeader');

    //get the data from the session
    $dataValues = $this->get('dataValues');
    $mapper = $this->get('mapper');
    $invalidRowCount = $this->get('invalidRowCount');
    $conflictRowCount = $this->get('conflictRowCount');
    $mismatchCount = $this->get('unMatchCount');

    //get the mapping name displayed if the mappingId is set
    $mappingId = $this->get('loadMappingId');
    if ($mappingId) {
      $mapDAO = new CRM_Core_DAO_Mapping();
      $mapDAO->id = $mappingId;
      $mapDAO->find(TRUE);
      $this->assign('loadedMapping', $mappingId);
      $this->assign('savedName', $mapDAO->name);
    }


    if ($skipColumnHeader) {
      $this->assign('skipColumnHeader', $skipColumnHeader);
      $this->assign('rowDisplayCount', 3);
    }
    else {
      $this->assign('rowDisplayCount', 2);
    }

    if ($invalidRowCount) {
      $urlParams = 'type=' . CRM_Member_Import_Parser::ERROR . '&parser=CRM_Member_Import_Parser';
      $this->set('downloadErrorRecordsUrl', CRM_Utils_System::url('civicrm/export', $urlParams));
    }

    if ($conflictRowCount) {
      $urlParams = 'type=' . CRM_Member_Import_Parser::CONFLICT . '&parser=CRM_Member_Import_Parser';
      $this->set('downloadConflictRecordsUrl', CRM_Utils_System::url('civicrm/export', $urlParams));
    }

    if ($mismatchCount) {
      $urlParams = 'type=' . CRM_Member_Import_Parser::NO_MATCH . '&parser=CRM_Member_Import_Parser';
      $this->set('downloadMismatchRecordsUrl', CRM_Utils_System::url('civicrm/export', $urlParams));
    }


    $properties = array('mapper',
      'dataValues', 'columnCount',
      'totalRowCount', 'validRowCount',
      'invalidRowCount', 'conflictRowCount',
      'downloadErrorRecordsUrl',
      'downloadConflictRecordsUrl',
      'downloadMismatchRecordsUrl',
    );

    foreach ($properties as $property) {
      $this->assign($property, $this->get($property));
    }
  }

  /**
   * Function to actually build the form
   *
   * @return None
   * @access public
   */
  public function buildQuickForm() {
    $attr = array();
    $locked = CRM_Core_Lock::isUsed($this->controller->_key);
    if ($locked) {
      $attr['disabled'] = 'disabled';
      $this->assign('locked_import', TRUE);
    }
    $this->addButtons(array(
        array('type' => 'back',
          'name' => ts('<< Previous'),
        ),
        array('type' => 'next',
          'name' => ts('Import Now >>'),
          'spacing' => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
          'isDefault' => TRUE,
          'js' => $attr,
        ),
        array('type' => 'cancel',
          'name' => ts('Cancel'),
        ),
      )
    );
  }

  /**
   * Return a descriptive name for the page, used in wizard header
   *
   * @return string
   * @access public
   */
  public function getTitle() {
    return ts('Preview');
  }

  /**
   * Process the mapped fields and map it into the uploaded file
   * preview the file and extract some summary statistics
   *
   * @return void
   * @access public
   */
  public function postProcess() {
    $fileName = $this->controller->exportValue('UploadFile', 'uploadFile');
    $skipColumnHeader = $this->controller->exportValue('UploadFile', 'skipColumnHeader');
    $invalidRowCount = $this->get('invalidRowCount');
    $conflictRowCount = $this->get('conflictRowCount');
    $onDuplicate = $this->get('onDuplicate');
    $dataReferenceField = $this->get('dataReferenceField');

    $config = CRM_Core_Config::singleton();
    $seperator = $config->fieldSeparator;

    $mapper = $this->get('mapperKeys');
    $mapperKeys = array();
    foreach ($mapper as $key => $value) {
      $mapperKeys[$key] = $mapper[$key][0];
    }
    $properties = array(
      'ims' => 'mapperImProvider',
      'phones' => 'mapperPhoneType',
      'websites' => 'mapperWebsiteType',
      'locationTypes' => 'mapperLocType',
      'locations' => 'locations',
    );
    foreach ($properties as $propertyName => $propertyVal) {
      $$propertyVal = $this->get($propertyName);
    }
    $parser = new CRM_Member_Import_Parser_Membership($mapperKeys, $mapperLocType, $mapperPhoneType, $mapperWebsiteType,$mapperImProvider);

    $mapFields = $this->get('fields');
    $parser->_dateFormats = $this->get('dateFormats');

    foreach ($mapper as $key => $value) {
      $header = array();
      if (isset($mapFields[$mapper[$key][0]])) {
        $header[] = $mapFields[$mapper[$key][0]];
      }
      $mapperFields[] = implode(' - ', $header);
    }
    $lock = new CRM_Core_Lock($this->controller->_key);
    if (!$lock->isAcquired()) {
      CRM_Core_Error::statusBounce(ts("The selected import job is already running. To prevent duplicate records being imported, please wait the job complete."));
      CRM_Core_Error::debug_log_message("Trying acquire lock {$this->controller->_key} failed at line ".__LINE__);
    }
    $parser->run($fileName, $seperator,
      $mapperFields,
      $skipColumnHeader,
      CRM_Member_Import_Parser::MODE_IMPORT,
      $this->get('contactType'),
      $onDuplicate,
      $this->get('createContactOption'),
      $this->get('dedupeRuleGroup'),
      $dataReferenceField
    );

    // add all the necessary variables to the form
    $parser->set($this, CRM_Member_Import_Parser::MODE_IMPORT);

    // check if there is any error occured

    $errorStack = &CRM_Core_Error::singleton();
    $errors = $errorStack->getErrors();
    $errorMessage = array();

    if (is_array($errors)) {
      foreach ($errors as $key => $value) {
        $errorMessage[] = $value['message'];
      }

      $errorFile = $fileName['name'] . '.error.log';

      if ($fd = fopen($errorFile, 'w')) {
        fwrite($fd, implode('\n', $errorMessage));
      }
      fclose($fd);

      $this->set('errorFile', $errorFile);
      $urlParams = 'type=' . CRM_Member_Import_Parser::ERROR . '&parser=CRM_Member_Import_Parser';
      $this->set('downloadErrorRecordsUrl', CRM_Utils_System::url('civicrm/export', $urlParams));
      $urlParams = 'type=' . CRM_Member_Import_Parser::CONFLICT . '&parser=CRM_Member_Import_Parser';
      $this->set('downloadConflictRecordsUrl', CRM_Utils_System::url('civicrm/export', $urlParams));
      $urlParams = 'type=' . CRM_Member_Import_Parser::NO_MATCH . '&parser=CRM_Member_Import_Parser';
      $this->set('downloadMismatchRecordsUrl', CRM_Utils_System::url('civicrm/export', $urlParams));
    }
  }
}

