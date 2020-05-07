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

require_once 'CRM/Contribute/DAO/TaiwanACH.php';
class CRM_Contribute_BAO_TaiwanACH extends CRM_Contribute_DAO_TaiwanACH {

  static $_txtFormat = array();

  CONST BANK = 'ACH Bank';
  CONST POST = 'ACH Post';

  CONST VERIFICATION = 'verification';
  CONST TRANSACTION = 'transaction';

  public static function getTxtFormatArray() {
    if (empty(self::$_txtFormat)) {
      self::$_txtFormat = array(
        self::BANK => array(
          self::VERIFICATION => array(
            'header' => array(
              '1' => 'X(03)',
              '4' => 'X(06)',
              '10' => '9(08)',
              '18' => '9(07)',
              '25' => 'X(3)',
              '28' => 'X(193)',
            ),
            'body' => array(
              '1' => '9(06)',
              '7' => 'X(03)',
              '10' => 'X(10)',
              '20' => '9(07)',
              '27' => 'X(16)',
              '43' => 'X(10)',
              '53' => 'X(20)',
              '73' => 'X(01)',
              '74' => '9(08)',
              '82' => '9(07)',
              '89' => 'X(40)',
              '129' => 'X(01)',
              '130' => 'X(01)',
              '131' => 'X(08)',
              '139' => 'X(01)',
              '140' => 'X(08)',
              '148' => 'X(20)',
              '168' => 'X(53)',
            ),
            'footer' => array(
              '1' => 'X(03)',
              '4' => '9(08)',
              '12' => 'X(209)',
            ),
          ),
          self::TRANSACTION => array(
            'header' => array(
              '1' => 'X(03)',
              '4' => 'X(06)',
              '10' => '9(08)',
              '18' => '9(06)',
              '24' => '9(07)',
              '31' => '9(07)',
              '38' => 'X(3)',
              '41' => 'X(210)',
            ),
            'body' => array(
              '1' => 'X(01)',
              '2' => 'X(02)',
              '4' => 'X(03)',
              '7' => '9(08)',
              '15' => '9(07)',
              '22' => 'X(16)',
              '38' => '9(07)',
              '45' => 'X(16)',
              '61' => '9(10)',
              '71' => 'X(02)',
              '73' => 'X(01)',
              '74' => 'X(10)',
              '84' => 'X(10)',
              '94' => 'X(06)',
              '100' => '9(08)',
              '108' => '9(08)',
              '116' => 'X(01)',
              '117' => 'X(20)',
              '137' => 'X(40)',
              '177' => 'X(10)',
              '187' => '9(05)',
              '192' => 'X(20)',
              '212' => 'X(39)',
            ),
            'footer' => array(
              '1' => 'X(03)',
              '4' => 'X(06)',
              '10' => '9(08)',
              '18' => '9(07)',
              '25' => '9(07)',
              '32' => '9(08)',
              '40' => '9(16)',
              '56' => '9(08)',
              '64' => 'X(187)',
            ),
          ),
        ),
        self::POST => array(
          self::VERIFICATION => array(
            'body' => array(
              '1' => 'X(1)',
              '2' => 'X(3)',
              '5' => 'X(4)',
              '9' => 'X(8)',
              '17' => '9(3)',
              '20' => '9(6)',
              '26' => 'X(1)',
              '27' => 'X(1)',
              '28' => '9(14)',
              '42' => 'X(20)',
              '62' => 'X(10)',
              '72' => 'X(2)',
              '74' => 'X(1)',
              '75' => 'X(26)',
            ),
            'footer' => array(
              '1' => 'X(1)',
              '2' => 'X(3)',
              '5' => 'X(4)',
              '9' => 'X(8)',
              '17' => '9(3)',
              '20' => 'X(1)',
              '21' => '9(6)',
              '27' => 'X(8)',
              '35' => '9(6)',
              '41' => '9(6)',
              '47' => 'X(54)',
            ),
          ),
          self::TRANSACTION => array(
            'body' => array(
              '1' => '9(1)',
              '2' => 'X(1)',
              '3' => 'X(3)',
              '6' => 'X(4)',
              '10' => '9(7)',
              '17' => 'X(1)',
              '18' => 'X(2)',
              '20' => '9(14)',
              '34' => 'X(10)',
              '44' => '9(11,2)',
              '55' => 'X(20)',
              '75' => 'X(1)',
              '76' => 'X(1)',
              '77' => 'X(1)',
              '78' => 'X(1)',
              '79' => 'X(2)',
              '81' => '9(5)',
              '86' => 'X(5)',
              '91' => 'X(20)',
              '111' => 'X(10)',
            ),
            'footer' => array(
              '1' => '9(1)',
              '2' => 'X(1)',
              '3' => 'X(3)',
              '6' => 'X(4)',
              '10' => '9(7)',
              '17' => 'X(1)',
              '18' => 'X(2)',
              '20' => '9(7)',
              '27' => '9(13,2)',
              '40' => 'X(16)',
              '56' => '9(7)',
              '63' => '9(13,2)',
              '76' => 'X(45)',
            ),
          ),
        ),
      );
    }
    return self::$_txtFormat;
  }

  /**
   * takes an associative array and creates a contribution object
   *
   * the function extract all the params it needs to initialize the create a
   * contribution object. the params array could contain additional unused name/value
   * pairs
   *
   * @param array  $params (reference ) an assoc array of name/value pairs
   * @param array $ids    the array that holds all the db ids
   *
   * @return object CRM_Contribute_BAO_Contribution object
   * @access public
   * @static
   */
  static function add(&$params) {

    // pre-processing hooks
    require_once 'CRM/Utils/Hook.php';
    if (CRM_Utils_Array::value('id', $params)) {
      CRM_Utils_Hook::pre('edit', 'TaiwanACH', $params['id'], $params);
    }
    else {
      CRM_Utils_Hook::pre('create', 'TaiwanACH', NULL, $params);
    }

    if (!empty($params['id'])) {
      $taiwanACH = new CRM_Contribute_DAO_TaiwanACH();
      $taiwanACH->id = $params['id'];
      $taiwanACH->find(TRUE);
    }
    else if (!empty($params['contribution_recur_id'])) {
      $taiwanACH = new CRM_Contribute_DAO_TaiwanACH();
      $taiwanACH->contribution_recur_id = $params['contribution_recur_id'];
      $taiwanACH->find(TRUE);
    }
    else {
      $taiwanACH = new CRM_Contribute_DAO_TaiwanACH();
    }
    $originData = unserialize($taiwanACH->data);
    if (empty($originData)) {
      $originData = array();
    }
    $paramsData = $params['data'];
    $mergedData = array_merge($originData, $paramsData);
    $taiwanACH->copyValues($params);

    $recurring = new CRM_Contribute_DAO_ContributionRecur();
    $recurParams = array();
    $recurringFields = $recurring->fields();
    foreach ($recurringFields as $field) {
      $fieldName = $field['name'];
      if (isset($params[$fieldName])) {
        $recurParams[$fieldName] = $params[$fieldName];
      }
    }
    if (empty($recurParams['create_date'])) {
      $recurParams['create_date'] = date('YmdHis');
    }
    $ids = array();
    if (!empty($taiwanACH->contribution_recur_id)) {
      $recurParams['id'] = $taiwanACH->contribution_recur_id;
    }
    $recurring = CRM_Contribute_BAO_ContributionRecur::add($recurParams, $ids);
    if (empty($taiwanACH->contribution_recur_id)) {
      $taiwanACH->contribution_recur_id = $recurring->id;
    }

    // set currency for CRM-1496
    if (!isset($taiwanACH->currency)) {
      $config = CRM_Core_Config::singleton();
      $taiwanACH->currency = $config->defaultCurrency;
    }

    if (isset($mergedData) && is_array($mergedData)) {
      $taiwanACH->data = serialize($mergedData);
    }

    $result = $taiwanACH->save();

    // create post-processing hooks
    if (CRM_Utils_Array::value('id', $params)) {
      CRM_Utils_Hook::post('edit', 'TaiwanACH', $taiwanACH->id, $taiwanACH);
    }
    else {
      CRM_Utils_Hook::post('create', 'TaiwanACH', $taiwanACH->id, $taiwanACH);
    }

    return $result;
  }

  static function addNote($taiwanACHId, $title, $body = NULL) {
    $session = CRM_Core_Session::singleton();
    $userId = $session->get('userID');
    if (empty($userId)) {
      $userId = "NULL";
    }
    $noteParams = array(
      'entity_table'  => 'civicrm_contribution_recur',
      'subject'       => $title,
      'note'          => $body,
      'entity_id'     => $taiwanACHId,
      'contact_id'    => $userId,
      'modified_date' => date('YmdHis'),
    );
    $note = CRM_Core_BAO_Note::add( $noteParams, NULL );
  }

  static function getValue($recurringId) {
    $output = array();

    $taiwanACH = new CRM_Contribute_DAO_TaiwanACH();
    $taiwanACH->contribution_recur_id = $recurringId;
    $taiwanACH->find(TRUE);
    $taiwanACH->data = unserialize($taiwanACH->data);
    $taiwanACHFields = $taiwanACH->fields();
    foreach ($taiwanACHFields as $field) {
      $fieldName = $field['name'];
      $output[$fieldName] = $taiwanACH->$fieldName;
    }

    $recurring = new CRM_Contribute_DAO_ContributionRecur();
    $recurring->id = $recurringId;
    $recurring->find(TRUE);
    $recurringFields = $recurring->fields();
    foreach ($recurringFields as $field) {
      $fieldName = $field['name'];
      if ($fieldName != 'id') {
        $output[$fieldName] = $recurring->$fieldName;
      }
    }

    return $output;
  }

  static function getTaiwanACHDatas($recurringIds = array()) {
    $achDatas = array();
    foreach ($recurringIds as $recurringId) {
      $achDatas[$recurringId] = self::getValue($recurringId);
    }
    return $achDatas;
  }

  static function doExportVerification($recurringIds = array(), &$params = array(), $officeType = self::BANK, $type = 'txt') {
    // Assign params
    $fileName = $params['file_name'];
    // $table = $bodyTable = array();
    $table = array();
    $achDatas = self::getTaiwanACHDatas($recurringIds);


    $firstAch = reset($achDatas);
    $paymentProcessor = CRM_Core_BAO_PaymentProcessor::getPayment($firstAch['processor_id'], '');
    $params['paymentProcessor'] = $paymentProcessor;

    if ($type == 'txt') {
      // account = ['user_name']
      // sic_code = ['password']
      // bank code = ['signature']
      // post_account = ['subject']
      if (strstr($officeType, 'Bank')) {
        $table = self::getBankVerifyTable($achDatas, $params);
      }
      else if (strstr($officeType, 'Post')) {
        $table = self::getPostVerifyTable($achDatas, $params);
      }

      $checkResults = $table['check_results'];
      $isError = FALSE;
      foreach($checkResults as $lineType => $check) {
        if (!empty($check['is_error'])) {
          $isError = TRUE;
          foreach ($check['messages'] as $message) {
            $messages[] = ts('Error on line %1:', array(1 => $lineType)).$message;
          }
        }
      }
      if ($isError) {
        CRM_Core_Error::statusBounce(implode('<br/>', $messages));
      }
      unset($table['check_results']);

      // Add civicrm_log file
      $log = new CRM_Core_DAO_Log();
      $log->entity_table = 'civicrm_contribution_taiwanach_verification';
      $log->entity_id = $params['date'];
      $log->data = implode(',', $recurringIds);
      $log->modified_date = $params['date'].'120000';
      $session = CRM_Core_Session::singleton();
      $log->modified_id = $session->get('userID');
      $log->save();

      // Export File
      self::doExportTXTFile($fileName, $table);
    }
    else {
      $table = $achDatas;
      self::doExportXSLFile($fileName, $table);
    }
  }

  static function doExportTransaction($recurringIds, &$params = array(), $officeType = self::BANK, $type = 'txt') {
    // Assign params
    $fileName = $params['file_name'];
    // $table = $bodyTable = array();
    $table = array();
    $achDatas = self::getTaiwanACHDatas($recurringIds);


    $firstAch = reset($achDatas);
    $paymentProcessor = CRM_Core_BAO_PaymentProcessor::getPayment($firstAch['processor_id'], '');
    $params['paymentProcessor'] = $paymentProcessor;

    // Add civicrm_log file
    $lastTransactLogTime = CRM_Core_DAO::singleValueQuery("SELECT MAX(entity_id) FROM civicrm_log WHERE entity_table = 'civicrm_contribution_taiwanach_transaction'");
    if (empty($lastTransactLogTime)){
      $lastTransactLogTime = '000000';
    }
    else{
      $lastTransactLogTime = str_pad($lastTransactLogTime , 6, '0', STR_PAD_LEFT);
    }
    $timezone = date_default_timezone_get();
    date_default_timezone_set('GMT');
    $lastTransactLogTimeId = strtotime('19700101T'.$lastTransactLogTime);
    $transactLogTimeId = $lastTransactLogTimeId + 1;
    $params['transact_id'] = (int) date('Gis', $transactLogTimeId);
    date_default_timezone_set($timezone);

    if ($type == 'txt') {
      // account = ['user_name']
      // sic_code = ['password']
      // bank code = ['signature']
      // post_account = ['subject']
      if (strstr($officeType, 'Bank')) {
        $table = self::getBankTransactTable($achDatas, $params);
      }
      else if (strstr($officeType, 'Post')) {
        $table = self::getPostTransactTable($achDatas, $params);
      }

      $checkResults = $table['check_results'];
      $isError = FALSE;
      foreach($checkResults as $lineType => $check) {
        if (!empty($check['is_error'])) {
          $isError = TRUE;
          foreach ($check['messages'] as $message) {
            $messages[] = ts('Error on line %1:', array(1 => $lineType)).$message;
          }
        }
      }
      if ($isError) {
        CRM_Core_Error::statusBounce(implode('<br/>', $messages));
      }
      unset($table['check_results']);

      $log = new CRM_Core_DAO_Log();
      $log->entity_table = 'civicrm_contribution_taiwanach_transaction';
      $log->entity_id = $params['transact_id'];
      $log->data = implode(',', $params['contribution_ids']);
      $log->modified_date = date('Ymd', strtotime($params['transact_date'])).str_pad($params['transact_id'], 6, '0', STR_PAD_LEFT);
      $session = CRM_Core_Session::singleton();
      $log->modified_id = $session->get('userID');
      $log->save();

      // Export File
      self::doExportTXTFile($fileName, $table);
    }
    else {
      $table = $achDatas;
      self::doExportXSLFile($fileName, $table);
    }
  }

  static private function doExportTXTFile($fileName, $table) {
    // arrange txt
    $txt = '';
    foreach ($table as $row) {
      $lines[] = implode('',$row);
    }
    $txt = implode("\n", $lines);

    // export file
    $config = CRM_Core_Config::singleton();
    $tmpDir = empty($config->uploadDir) ? CIVICRM_TEMPLATE_COMPILEDIR : $config->uploadDir;
    $fileName .= '.txt';
    $fileName = CRM_Utils_File::makeFileName($fileName);
    $fileFullPath = $tmpDir.'/'.$fileName;
    file_put_contents($fileFullPath, $txt, FILE_APPEND);
    header('Content-type: text/plain');
    header('Content-Disposition: attachment; filename=' . $fileName);
    header('Pragma: no-cache');
    echo file_get_contents($fileFullPath);
    CRM_Utils_System::civiExit();
  }

  static private function doExportXSLFile($fileName, $table) {
    $fileName .= '.xlsx';

    $header = array_shift($table);
    CRM_Core_Report_Excel::writeExcelFile(
      $fileName,
      $header,
      $table
    );
    CRM_Utils_System::civiExit();
  }

  static private function getBankVerifyTable($achDatas, &$params) {
    $paymentProcessor = $params['paymentProcessor'];
    $orgBankCode = str_pad($paymentProcessor['signature'], 7, '0', STR_PAD_LEFT);

    // Generate Header
    $header = array(
      'BOF',
      'ACHP02',
      $params['date'],
      $orgBankCode,
      'V10',
      str_repeat(' ', 193),
    );
    $checkResults['header'] = self::doCheckParseRow($header, self::BANK, self::VERIFICATION, 'header');
    $table[] = $header;

    // Generate Body Table
    $i = 1;
    foreach ($achDatas as $achData) {
      $achData['invoice_id'] = $params['date'].'_'.$i;
      CRM_Contribute_BAO_TaiwanACH::add($achData);
      $row = array(
        str_pad($i, 6, '0', STR_PAD_LEFT),
        '530',
        str_pad($paymentProcessor['password'], 10, ' ', STR_PAD_RIGHT),
        $achData['bank_code'],
        str_pad($achData['bank_account'], 16, '0', STR_PAD_LEFT),
        str_pad($achData['identifier_number'], 10, ' ', STR_PAD_RIGHT),
        str_pad($achData['identifier_number'], 20, ' ', STR_PAD_RIGHT),
        'A',
        $params['date'],
        $orgBankCode,
        str_pad($achData['contribution_recur_id'], 40, ' ', STR_PAD_RIGHT),
        'N',
        ' ',
        str_repeat(' ', 8),
        ' ',
        str_repeat(' ', 8),
        str_repeat(' ', 20),
        str_repeat(' ', 53),
      );
      $checkResults[$i] = self::doCheckParseRow($row, self::BANK, self::VERIFICATION, 'body');
      $table[] = $row;
      $i++;
    }

    // Generate Footer
    $total = str_pad(count($achData), 8, '0', STR_PAD_LEFT);
    $footer = array(
      'EOF',
      $total,
      str_repeat(' ', 209),
    );
    $checkResults['footer'] = self::doCheckParseRow($footer, self::BANK, self::VERIFICATION, 'footer');
    $table['check_results'] = $checkResults;
    $table[] = $footer;

    return $table;
  }

  static private function getPostVerifyTable($achDatas, &$params) {
    $paymentProcessor = $params['paymentProcessor'];

    // Generate Body Table
    $i = 1;
    foreach ($achDatas as $achData) {
      $bankAccount = str_pad($achData['bank_account'], 14, '0', STR_PAD_RIGHT);
      $identifier_number = str_pad($achData['identifier_number'], 10, ' ', STR_PAD_LEFT);
      $row = array(
        1,
        $paymentProcessor['subject'],
        str_repeat(' ', 3),
        $params['date'],
        '001',
        str_pad($i, 6, '0', STR_PAD_LEFT),
        '1',
        ($achData['postoffice_acc_type'] == 1)? 'P' : 'G',
        $bankAccount,
        str_pad($achData['contribution_recur_id'], 20, ' ', STR_PAD_LEFT),
        $identifier_number,
        str_repeat(' ', 2),
        ' ',
        str_repeat(' ', 26),
      );
      $checkResults[$i] = self::doCheckParseRow($row, self::POST, self::VERIFICATION, 'body');
      $table[] = $row;
      $i++;
    }

    // Generate Footer
    $total = str_pad(count($achData), 6, '0', STR_PAD_LEFT);
    $footer = array(
      2,
      $paymentProcessor['subject'],
      str_repeat(' ', 4),
      $params['date'],
      '001',
      'B',
      $total,
      str_repeat(' ', 8),
      str_repeat('0', 6),
      str_repeat('0', 6),
      str_repeat(' ', 54),
    );
    $checkResults['footer'] = self::doCheckParseRow($footer, self::POST, self::VERIFICATION, 'footer');
    $table['check_results'] = $checkResults;
    $table[] = $footer;

    return $table;
  }

  static private function getBankTransactTable($achDatas, &$params) {
    $paymentProcessor = $params['paymentProcessor'];

    // Generate Header
    $date = $params['transact_date'];
    $header = array(
      'BOF',
      'ACHP01',
      $date,
      str_pad($params['transact_id'], 6, '0', STR_PAD_LEFT),
      str_pad($paymentProcessor['signature'], 7, '0', STR_PAD_LEFT),
      '9990250',
      'V10',
      str_repeat(' ', 210),
    );
    $checkResults['header'] = self::doCheckParseRow($header, self::BANK, self::TRANSACTION, 'header');
    $table[] = $header;

    // Generate Body Table
    $i = 1;
    $totalAmount = 0;
    foreach ($achDatas as $achData) {
      $contribution = self::createContributionByACHData($achData);
      $invoice_id = "{$params['transact_date']}_{$params['transact_id']}_$i";
      CRM_Core_DAO::setFieldValue('CRM_Contribute_DAO_Contribution', $contribution->id, 'invoice_id', $invoice_id);
      $params['contribution_ids'][] = $contribution->id;
      $identifier_number = str_pad($achData['identifier_number'], 10, ' ', STR_PAD_RIGHT);
      $row = array(
        'N',
        'SD',
        '530',
        str_pad($i, 8, '0', STR_PAD_LEFT),
        str_pad($paymentProcessor['signature'], 7, '0', STR_PAD_LEFT),
        str_pad($paymentProcessor['user_name'], 16, '0', STR_PAD_LEFT),
        $achData['bank_code'],
        str_pad($achData['bank_account'], 16, '0', STR_PAD_LEFT),
        str_pad((int) $achData['amount'], 10, '0', STR_PAD_LEFT),
        str_repeat(' ', 2),
        'B',
        str_pad($paymentProcessor['password'], 10, ' ', STR_PAD_RIGHT),
        str_pad($identifier_number, 10, ' ', STR_PAD_RIGHT),
        str_repeat(' ', 6),
        str_repeat(' ', 8),
        str_repeat(' ', 8),
        ' ',
        str_pad($identifier_number, 20, ' ', STR_PAD_RIGHT),
        str_pad($contribution->id, 40, ' ', STR_PAD_RIGHT),
        str_repeat(' ', 10),
        str_repeat('0', 5),
        str_repeat(' ', 20),
        str_repeat(' ', 39),
      );
      $checkResults[$i] = self::doCheckParseRow($row, self::BANK, self::TRANSACTION, 'body');
      $table[] = $row;
      $i++;
      $totalAmount += $achDatas['amount'];
    }

    // Generate Footer
    $total = str_pad(count($achData), 8, '0', STR_PAD_LEFT);
    $totalAmount = str_pad($totalAmount, 16, '0', STR_PAD_LEFT);
    $footer = array(
      'EOF',
      'ACHP01',
      $params['transact_date'],
      str_pad($paymentProcessor['signature'], 7, '0', STR_PAD_LEFT),
      '9990250',
      $total,
      $totalAmount,
      str_repeat(' ', 8),
      str_repeat(' ', 187),
    );
    $checkResults['footer'] = self::doCheckParseRow($footer, self::BANK, self::TRANSACTION, 'footer');
    $table['check_results'] = $checkResults;
    $table[] = $footer;

    return $table;
  }

  static private function getPostTransactTable($achDatas, &$params) {
    $paymentProcessor = $params['paymentProcessor'];

    // Generate Body Table
    $i = 1;
    $totalAmount = 0;
    foreach ($achDatas as $achData) {
      $contributionParams = array(
        'contact_id' => $achData['contact_id'],
        'total_amount' => $achData['amount'],
        'create_date' => date('Y-m-d H:i:s'),
        'contribution_recur_id' => $achData['contribution_recur_id'],
      );
      $ids = array();
      $contribution = CRM_Contribute_BAO_Contribution::add($contributionParams, $ids);
      $bankAccount = str_pad($achData['bank_account'], 14, '0', STR_PAD_RIGHT);
      $identifier_number = str_pad($achData['identifier_number'], 10, ' ', STR_PAD_LEFT);
      $totalAmount += $achData['amount'];
      $row = array(
        1,
        ($achData['postoffice_acc_type'] == 1)? 'P' : 'G',
        $paymentProcessor['subject'],
        str_repeat(' ', 4),
        $params['date'],
        'S',
        str_repeat(' ', 2),
        $bankAccount,
        str_repeat(' ', 10),
        str_pad($achData['amount'], 9, '0', STR_PAD_LEFT).'00',
        str_pad($contribution->id, 20, ' ', STR_PAD_LEFT),
        1,
        ' ',
        ' ',
        ' ',
        str_repeat(' ', 2),
        $params['date'],
        str_repeat(' ', 5),
        str_repeat(' ', 20),
        str_repeat(' ', 10),
      );
      $checkResults[$i] = self::doCheckParseRow($row, self::POST, self::TRANSACTION, 'body');
      $table[] = $row;
      $i++;
    }

    // Generate Footer
    $total = str_pad(count($achData), 7, '0', STR_PAD_LEFT);
    $totalAmount = str_pad($totalAmount, 11, '0', STR_PAD_LEFT).'00';
    $footer = array(
      2,
      ' ',
      $paymentProcessor['subject'],
      str_repeat(' ', 4),
      $params['date'],
      'S',
      str_repeat(' ', 2),
      $total,
      $totalAmount,
      str_repeat(' ', 16),
      str_repeat('0', 7),
      str_repeat('0', 13),
      str_repeat(' ', 45),
    );
    $checkResults['footer'] = self::doCheckParseRow($footer, self::POST, self::TRANSACTION, 'footer');
    $table['check_results'] = $checkResults;
    $table[] = $footer;
    return $table;
  }

  static private function createContributionByACHData($achData) {
    $page = new CRM_Contribute_DAO_ContributionPage();
    $page->id = $achData['contribution_page_id'];
    $page->find(TRUE);
    $instrumentIds = CRM_Core_OptionGroup::values('payment_instrument', FALSE, FALSE, FALSE, "AND v.name = 'ACH Bank'", 'value');
    $instrumentId = reset($instrumentIds);
    foreach ($achData['data'] as $key => $value) {
      if (strstr($key, 'custom_')) {
        $customFieldID = CRM_Core_BAO_CustomField::getKeyID($key);
        if ($customFieldID) {
          CRM_Core_BAO_CustomField::formatCustomField($customFieldID, $customData, $value, 'contribution');
        }
      }
    }
    $contributionParams = array(
      'contact_id' => $achData['contact_id'],
      'total_amount' => $achData['amount'],
      'create_date' => date('Y-m-d H:i:s'),
      'contribution_recur_id' => $achData['contribution_recur_id'],
      'contribution_type_id' => $page->contribution_type_id,
      'contribution_status_id' => 2,
      'contribution_page_id' => $achData['contribution_page_id'],
      'payment_processor_id' => $achData['processor_id'],
      'is_test' => $achData['is_test'],
      'currency' => $achData['currency'],
      'payment_instrument_id' => $instrumentId,
      'custom' => $customData,
    );
    $ids = array();
    $contribution = CRM_Contribute_BAO_Contribution::create($contributionParams, $ids);
    if (!empty($contribution->id)) {
      $contribution->trxn_id = $contribution->id;
      $contribution->save();
    }

    return $contribution;

  }

  static function parseUpload($content) {
    $rows = explode("\n", $content);
    $lines = count($rows);
    $instrumentType = '';
    $processType = '';
    $isError = FALSE;
    $resolvedData = array();
    // Consider instrument type and process type, Transfer row text to array.
    for ($i = 0; $i < $lines; $i++) {
      $bodyLine = NULL;
      $row = $rows[$i];
      if ($i == 0) {
        $wordCount = strlen($row);
        switch ($wordCount) {
          case 220:
            $instrumentType = self::BANK;
            $processType = self::VERIFICATION;
            break;
          case 250:
            $instrumentType = self::BANK;
            $processType = self::TRANSACTION;
            break;
          case 100:
            $instrumentType = self::POST;
            $processType = self::VERIFICATION;
            break;
          case 120:
            $instrumentType = self::POST;
            $processType = self::TRANSACTION;
            break;
        }
        if (empty($instrumentType) || empty($processType)) {
          CRM_Core_Error::statusBounce(ts("Word count of txt is wrong: %1", array(1 => $wordCount)));
        }
        if ($instrumentType == self::BANK) {
          $header = self::doCheckParseRow($row, $instrumentType, $processType, 'header');
          if (!empty($header['is_error'])) {
            $isError = TRUE;
            foreach ($header['messages'] as $message) {
              $messages[] = ts('Header have wrong value :').$message;
            }
          }
        }
        else if($instrumentType == self::POST) {
          $bodyLine = self::doCheckParseRow($row, $instrumentType, $processType, 'body');
        }
      }
      else if ($i == ($lines -1)) {
        $footer = self::doCheckParseRow($row, $instrumentType, $processType, 'footer');
        if (!empty($footer['is_error'])) {
          $isError = TRUE;
          foreach ($footer['messages'] as $message) {
            $messages[] = ts('Footer have wrong value :').$message;
          }
        }
      }
      else {
        $bodyLine = self::doCheckParseRow($row, $instrumentType, $processType, 'body');
      }
      if (!empty($bodyLine)) {
        $resolvedData[] = $bodyLine;
        if (!empty($bodyLine['is_error'])) {
          $isError = TRUE;
          foreach ($bodyLine['messages'] as $message) {
            $lineOrder = ($instrumentType == self::POST) ? $i+1 : $i;
            $messages[] = ts('The data of order %1 have wrong value :', $lineOrder).$message;
          }
        }
      }
    }

    // Rearrange data, Add id to key so that it's easy to find the row.
    $rearrangeData = array();
    foreach ($resolvedData as $data) {
      if ($processType == self::VERIFICATION) {
        if ($instrumentType == self::BANK) {
          $rearrangeData[$data[10]] = $data;
        }
        else if ($instrumentType == self::POST) {
          $rearrangeData[$data[9]] = $data;
        }
      }
      else if ($processType = self::TRANSACTION) {
        $rearrangeData[$data[18]] = $data;
      }
    }


    // Get id and data which need to process from civicrm_log
    if ($instrumentType == self::BANK) {
      if ($processType == self::VERIFICATION) {
        $entityId = $header[2];
      }
      if ($processType == self::TRANSACTION) {
        $entityId = $header[3];
      }
    }

    $sql = "SELECT data FROM civicrm_log WHERE entity_table = 'civicrm_contribution_taiwanach_$processType' AND entity_id = %1 ORDER BY id DESC LIMIT 1";
    $params = array( 1 => array($entityId, 'String'));
    $data = CRM_Core_DAO::singleValueQuery($sql, $params);
    if (!empty($data)) {
      $processIds = explode(',', $data);
      $processedData = array();
      foreach ($processIds as $id) {
        // if $processType = 'verification' => 'contribution_recur_id'
        // if $processType = 'transaction' => 'contribution_id'
        $parsedData = $rearrangeData[$id];
        if (empty($rearrangeData['is_error'])) {
          if ($instrumentType == self::BANK) {
            if ($processType == self::VERIFICATION) {
              $data = self::doProcessVerification($id, $parsedData);
            }
            if ($processType == self::TRANSACTION) {
              if (empty($parsedData)) {
                $rearrangeData[$id] = $parsedData = array(
                  'is_success' => TRUE,
                  'receive_date' => $header[2].'120000',
                );
              }
              $data = self::doProcessTransaction($id, $parsedData);
            }
            if (!empty($data['is_error'])) {
              $isError = TRUE;
              foreach ($data['messages'] as $message) {
                $messages[] = ts('There is process error on id: %1', $id).$message;
              }
            }
            $processedData[$data['id']] = $data;
          }
        }
      }
      if ($isError) {
        CRM_Core_Error::statusBounce(implode('<br/>', $messages));
      }

      $result = array(
        'import_type' => $processType,
        'payment_type' => $instrumentType,
        'parsed_data' => $rearrangeData,
      );
      $result['process_id'] = $entityId;
      $result['processed_data'] = $processedData;

    }
    return $result;
  }

  static function doCheckParseRow($row = '', $instrumentType = self::BANK, $processType = self::VERIFICATION, $headerOrFooter = 'body') {
    $allTxtFormat = self::getTxtFormatArray();
    $format = $allTxtFormat[$instrumentType][$processType][$headerOrFooter];
    $formatKeys = array_keys($format);
    $isError = FALSE;
    if (is_array($row)) {
      // Check when input is Array
      if(count($row) != count($format)) {
        $isError = TRUE;
        $msg[] = "Row count is not correct";
      }
      else {
        foreach ($formatKeys as $i => $wordCount) {
          $regexp = self::getRegexpFromFormatString($format[$wordCount]);
          if (!preg_match($regexp, $row[$i])) {
            $isError = TRUE;
            $msg[] = ts("In %1, row %2 is not correct, input value is %3, format should be %4", array(
              1 => $headerOrFooter,
              2 => $i+1,
              3 => str_replace(' ', "_", $row[$i]),
              4 => $format[$wordCount],
            ));
          }
        }
      }
      $returnArray = $row;
    }
    else {
      $returnArray = array();
      // Check when input is String
      for ($i = 0; $i < count($formatKeys); $i++) {
        $wordCount = $formatKeys[$i];
        if ($i == (count($formatKeys) - 1)) {
          $str = substr($row, $wordCount - 1);
        }
        else {
          $len = $formatKeys[$i+1] - $wordCount;
          $str = substr($row, $wordCount - 1, $len);
        }

        $regexp = self::getRegexpFromFormatString($format[$wordCount]);
        if (!preg_match($regexp, $str)) {
          $isError = TRUE;
          $msg[] = ts("In %1, word since %2 is not correct, input value is %3, format should be %4", array(
            1 => $headerOrFooter,
            2 => $formatKeys[$i],
            3 => str_replace(' ', "_", $str),
            4 => $format[$wordCount],
          ));
        }
        $returnArray[] = trim($str);
      }
    }

    if ($isError) {
      $returnArray['is_error'] = TRUE;
      $returnArray['messages'] = $msg;
    }

    return $returnArray;

  }

  static function getRegexpFromFormatString($formatString) {
    preg_match('/([X9])\((\d{1,3})\)/', $formatString, $match);
    if (!empty($match)) {
      $wordFormat = $match[1];
      $wordLength = (int)$match[2];
      if ($wordFormat == 'X') {
        $regexp = "/^[\w ]{{$wordLength}}$/";
      }
      elseif ($wordFormat == '9') {
        $regexp = "/^[\d ]{{$wordLength}}$/";
      }
    }
    else {
      CRM_Core_Error::statusBounce(ts("Format is not correct. Input format is '%1'", array(1 => $formatString)));
    }
    return $regexp;
  }

  static function doProcessVerification($recurId, $parsedData, $isPreview = TRUE) {
    // Consider type is Bank or Post
    $arrayLen = count($parsedData);
    if ($arrayLen == 18 ) {
      $processType = self::BANK;
      $startDate = $parsedData[8];
    }
    if ($arrayLen == 14 ) {
      $processType = self::POST;
      $startDate = $parsedData[3];
    }
    $taiwanACHData = self::getValue($recurId);
    $contributionTypeId = CRM_Core_DAO::getFieldValue('CRM_Contribute_DAO_ContributionPage', $taiwanACHData['contribution_page_id'], 'contribution_type_id');

    $result = $taiwanACHData;
    $result['id'] = $taiwanACHData['contribution_recur_id'];
    $result['total_amount'] = $result['amount'];
    $result['contribution_type_id'] = $contributionTypeId;
    $result['start_date'] = $startDate;

    // check invoice_id
    // check Amount
    // check Recurring Status
    // check Stamp Verification

    $isError = FALSE;
    $messages = array();
    if ($taiwanACHData['contribution_status_id'] == 2 && $taiwanACHData['stamp_verification'] == 0) {
      if ($processType == self::BANK) {
        if ($parsedData[11] == 'R') {
          if ($parsedData[12] == '0') {
            $result['contribution_status_id'] = 5;
          }
          else {
            $result['contribution_status_id'] = 4;
            $failed_reason = $parsedData[12];
          }
        }
        else {
          $isError = TRUE;
          $messages[] = ts("Type of upload file is not a responding file.");
        }
      }
    }
    else {
      $isError = TRUE;
      $messages[] = ts("Status of recurring: %1 should be 'pending'.", array(1 => $recurId));
    }
    if ($isError) {
      CRM_Core_Error::statusBounce(implode('<br/>', $messages));
    }
    if ($isPreview) {
      return $result;
    }

    // if $isPreview is FALSE, Execute modify CRM data.
    $taiwanACHData['contribution_status_id'] = $result['contribution_status_id'];
    if ($taiwanACHData['contribution_status_id'] == 5) {
      $taiwanACHData['start_date'] = $result['start_date'];
      $taiwanACHData['stamp_verification'] = 1;
    }
    else if ($taiwanACHData['contribution_status_id'] == 4){
      $taiwanACHData['data']['verification_failed_reason'] = $failed_reason;
      $taiwanACHData['data']['verification_failed_date'] = $startDate.'120000';
      $taiwanACHData['stamp_verification'] = 2;
    }
    self::add($taiwanACHData);
    return $result;
  }

  static function doProcessTransaction($contributionId, $parsedData, $isPreview = TRUE) {
    // Consider type is Bank or Post
    $arrayLen = count($parsedData);
    if ($arrayLen == 23 ) {
      $processType = self::BANK;
      $errorCode = $parsedData[9];
      $cancelDate = $parsedData[14];
    }
    if ($arrayLen == 20 ) {
      $processType = self::POST;
      $errorCode = $parsedData[15];
      $cancelDate = $parsedData[4] + 19110000;
    }
    // if $parsedData = array('is_success' => TRUE, 'receive_date' => '2020XXXX');
    // The contribution is successed.

    // check invoice_id
    // check trxn_id
    // check Amount
    // check Recurring Status


    $pass = TRUE;
    $contribution = new CRM_Contribute_DAO_Contribution();
    $contribution->id = $contributionId;
    $contribution->find(TRUE);
    $result = (array) $contribution;
    if ($parsedData['is_success']) {
      $result['contribution_status_id'] = 1;
      $result['receive_date'] = $parsedData['receive_date'];
    }
    else {
      $result['contribution_status_id'] = 4;
      $result['cancel_date'] = $cancelDate.'120000';
      $result['cancel_reason'] = $errorCode;
      $pass = FALSE;
    }

    // when $isPreview = TRUE, interrupt and return preview data.
    if ($isPreview) {
      return $result;
    }

    // Execute ipn.
    $ids = CRM_Contribute_BAO_Contribution::buildIds($contributionId, FALSE);

    // prepare input
    $input = $result;
    if(!empty($ids['event'])){
      $input['component'] = 'event';
    }
    else{
      $input['component'] = 'contribute';
    }

    // ipn transact
    $ipn = new CRM_Core_Payment_BaseIPN();

    // First use ipn validate
    $validate_result = $ipn->validateData($input, $ids, $objects, FALSE);
    if(!$validate_result){
      return FALSE;
    }
    else {
      $contribution = $objects['contribution'];
      $transaction = new CRM_Core_Transaction();

      if($pass){
        $input['payment_instrument_id'] = $objects['contribution']->payment_instrument_id;
        $input['amount'] = $objects['contribution']->amount;
        $receiveTime = empty($result->transaction_time_millis) ? time() : ($result->transaction_time_millis / 1000);
        $objects['contribution']->receive_date = date('YmdHis', $receiveTime);
        $transaction_result = $ipn->completeTransaction($input, $ids, $objects, $transaction, NULL, $sendMail);
        if (!empty($ids['contributionRecur'])) {
          $sql = "SELECT count(*) FROM civicrm_contribution WHERE contribution_recur_id = %1";
          $params = array( 1 => array($ids['contributionRecur'], 'Positive'));
          $recurTimes = CRM_Core_DAO::singleValueQuery($sql, $params);
          if ($recurTimes == 1) {
            $recur_params = array(
              'id' => $ids['contributionRecur'],
              'contribution_status_id' => 5,
            );
            $null = array();
            CRM_Contribute_BAO_ContributionRecur::add($recur_params, $null);
          }
        }
      }
      else{
        // Failed
        $ipn->failed($objects, $transaction, $note);
      }
      self::addNote($note, $objects['contribution']);
    }
  }
}

