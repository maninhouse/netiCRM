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

require_once 'CRM/Event/Form/ManageEvent.php';
require_once 'CRM/Event/BAO/Event.php';
require_once 'CRM/Core/OptionGroup.php';

/**
 * This class generates form components for Event Fees
 *
 */
class CRM_Event_Form_ManageEvent_Fee extends CRM_Event_Form_ManageEvent {

  /**
   * Constants for number of options for data types of multiple option.
   */
  CONST NUM_OPTION = 11;

  /**
   * Constants for number of discounts for the event.
   */
  CONST NUM_DISCOUNT = 6;

  /**
   * Page action
   */
  public $_action;

  /**
   * in Date
   */
  private $_inDate;

  /**
   * Function to set variables up before form is built
   *
   * @return void
   * @access public
   */
  function preProcess() {
    parent::preProcess();
  }

  /**
   * This function sets the default values for the form. For edit/view mode
   * the default values are retrieved from the database
   *
   * @access public
   *
   * @return None
   */
  function setDefaultValues() {
    $parentDefaults = parent::setDefaultValues();

    $eventId = $this->_id;
    $params = array();
    $defaults = array();
    if (isset($eventId)) {
      $params = array('id' => $eventId);
    }

    CRM_Event_BAO_Event::retrieve($params, $defaults);

    if (isset($eventId)) {
      require_once 'CRM/Price/BAO/Set.php';
      $price_set_id = CRM_Price_BAO_Set::getFor('civicrm_event', $eventId);

      if ($price_set_id) {
        $defaults['price_set_id'] = $price_set_id;
      }
      else {
        require_once 'CRM/Core/OptionGroup.php';
        CRM_Core_OptionGroup::getAssoc("civicrm_event.amount.{$eventId}", $defaults);
      }
    }

    //check if discounted
    require_once 'CRM/Core/BAO/Discount.php';
    $discountedEvent = CRM_Core_BAO_Discount::getOptionGroup($this->_id, "civicrm_event");

    if (!empty($discountedEvent)) {
      $defaults['is_discount'] = $i = 1;
      $totalLables = $maxSize = $defaultDiscounts = array();
      foreach ($discountedEvent as $optionGroupId) {
        $defaults["discount_name[$i]"] = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_OptionGroup', $optionGroupId, 'label');

        list($defaults["discount_start_date[$i]"]) = CRM_Utils_Date::setDateDefaults(CRM_Core_DAO::getFieldValue('CRM_Core_DAO_Discount', $optionGroupId,
            'start_date', 'option_group_id'
          ));
        list($defaults["discount_end_date[$i]"]) = CRM_Utils_Date::setDateDefaults(CRM_Core_DAO::getFieldValue('CRM_Core_DAO_Discount', $optionGroupId,
            'end_date', 'option_group_id'
          ));
        $name = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_OptionGroup', $optionGroupId, 'name');
        CRM_Core_OptionGroup::getAssoc($name, $defaultDiscounts[]);
        $i++;
      }
      $defaults["discounted_label"] = array();
      //avoid moving up value of lable when some labels don't
      //have a value ,fixed for CRM-3088
      foreach ($defaultDiscounts as $key => $val) {
        $totalLables[$key]['label'] = $val['label'];
        $totalLables[$key]['value'] = $val['value'];
        $totalLables[$key]['amount_id'] = $val['amount_id'];
        foreach ($val['weight'] as $v) {
          //take array of weight for setdefault
          $discountWeight[$key][] = $v;
        }
        foreach ($val['value'] as $v) {
          //take array of available value for particular
          //discount set
          $discountValue[$key][] = $v;
        }
        foreach ($val['label'] as $v) {
          //take array of label
          $discountLabel[$key][] = $v;
        }
        //combining the weight with amount array for set default
        $discountDefualt[] = array_combine($discountWeight[$key], $discountValue[$key]);
        //Add label array to default label
        $discounted_label = array_combine($discountWeight[$key], $discountLabel[$key]);
        $defaults["discounted_label"] = $discounted_label+$defaults["discounted_label"];

        foreach ($discountDefualt[$key] as $k => $v) {
          $defaults["discounted_value"][$k][$key + 1] = $v;
        }
        $maxSize[$key] = sizeof($val['label']);
      }

      $this->set('discountSection', 1);
      $this->buildQuickForm();
    }
    elseif (!empty($defaults['label'])) {
      //if Regular Fees are present in DB and event fee page is in update mode
      $defaults["discounted_label"] = $defaults['label'];
    }
    elseif (CRM_Utils_Array::value('label', $this->_submitValues)) {
      //if event is newly created, use submitted values for
      //discount labels
      if (is_array($this->_submitValues['label'])) {
        $k = 1;
        foreach ($this->_submitValues['label'] as $value) {
          if ($value) {
            $defaults["discounted_label"][$k] = $value;
            $k++;
          }
        }
      }
    }

    $defaults = array_merge($defaults, $parentDefaults);
    $defaults['id'] = $eventId;

    if (CRM_Utils_Array::value('value', $defaults)) {
      foreach ($defaults['value'] as $i => $v) {
        if ($defaults['amount_id'][$i] == $defaults['default_fee_id']) {
          $defaults['default'] = $i;
          break;
        }
      }
    }

    if (!empty($totalLables)) {
      $maxKey = count($totalLables) - 1;
      if (isset($maxKey) &&
        CRM_Utils_Array::value('value', $totalLables[$maxKey])
      ) {
        foreach ($totalLables[$maxKey]['value'] as $i => $v) {
          if ($totalLables[$maxKey]['amount_id'][$i] == CRM_Utils_Array::value('default_discount_fee_id', $defaults)) {
            $defaults['discounted_default'] = $i;
            break;
          }
        }
      }
    }

    if (!isset($defaults['default'])) {
      $defaults['default'] = 1;
    }

    if (!isset($defaults['discounted_default'])) {
      $defaults['discounted_default'] = 1;
    }

    if (!isset($defaults['is_monetary'])) {
      $defaults['is_monetary'] = 1;
    }

    if (!isset($defaults['fee_label'])) {
      $defaults['fee_label'] = ts('Event Fee(s)');
    }

    if (!isset($defaults['pay_later_text']) ||
      empty($defaults['pay_later_text'])
    ) {
      $defaults['pay_later_text'] = ts('I will send payment by check');
    }

    require_once 'CRM/Core/ShowHideBlocks.php';
    $this->_showHide = new CRM_Core_ShowHideBlocks();
    if (!$defaults['is_monetary']) {
      $this->_showHide->addHide('event-fees');
    }

    if (isset($defaults['price_set_id'])) {
      $this->_showHide->addHide('map-field');
    }
    $this->_showHide->addToTemplate();
    $this->assign('inDate', $this->_inDate);

    if (CRM_Utils_Array::value('payment_processor', $defaults)) {
      $defaults['payment_processor'] = array_fill_keys(explode(CRM_Core_DAO::VALUE_SEPARATOR,
          $defaults['payment_processor']
        ), '1');
    }
    return $defaults;
  }

  /**
   * Function to build the form
   *
   * @return None
   * @access public
   */
  public function buildQuickForm() {
    require_once 'CRM/Utils/Money.php';

    $this->addYesNo('is_monetary',
      ts('Paid Event'),
      NULL,
      NULL,
      array('onclick' => "return showHideByValue('is_monetary','0','event-fees','block','radio',false);")
    );

    //add currency element.
    $this->addCurrency('currency', ts('Currency'), FALSE);

    require_once 'CRM/Contribute/PseudoConstant.php';
    $paymentProcessor = &CRM_Core_PseudoConstant::paymentProcessor(FALSE, FALSE, "payment_processor_type != 'TaiwanACH' AND billing_mode != 7");
    $this->assign('paymentProcessor', $paymentProcessor);

    foreach($paymentProcessor as $pid => &$pvalue) {
      $pvalue .= "-".ts("ID")."$pid";
    }
    $this->addCheckBox('payment_processor', ts('Payment Processor'),
      array_flip($paymentProcessor),
      NULL, NULL, NULL, NULL,
      array('&nbsp;&nbsp;', '&nbsp;&nbsp;', '&nbsp;&nbsp;', '<br/>')
    );

    $this->add('select', 'contribution_type_id', ts('Contribution Type'),
      array('' => ts('- select -')) + CRM_Contribute_PseudoConstant::contributionType(NULL, FALSE, TRUE)
    );

    // add pay later options
    $this->addElement('checkbox', 'is_pay_later', ts('Enable Pay Later option?'), NULL,
      array('onclick' => "return showHideByValue('is_pay_later','','payLaterOptions','block','radio',false);")
    );
    $this->addElement('textarea', 'pay_later_text', ts('Pay Later Label'),
      CRM_Core_DAO::getAttribute('CRM_Event_DAO_Event', 'pay_later_text'),
      FALSE
    );
    $this->addElement('textarea', 'pay_later_receipt', ts('Pay Later Instructions'),
      CRM_Core_DAO::getAttribute('CRM_Event_DAO_Event', 'pay_later_receipt'),
      FALSE
    );

    $this->add('text', 'fee_label', ts('Fee Label'));

    require_once 'CRM/Price/BAO/Set.php';
    $price = CRM_Price_BAO_Set::getAssoc(FALSE, 'CiviEvent');
    if (CRM_Utils_System::isNull($price)) {
      $this->assign('price', FALSE);
    }
    else {
      $this->assign('price', TRUE);
    }
    $this->add('select', 'price_set_id', ts('Price Set'),
      array('' => ts('- none -')) + $price,
      NULL, array('onchange' => "return showHideByValue('price_set_id', '', 'map-field', 'block', 'select', false);")
    );
    $default = array();
    for ($i = 1; $i <= self::NUM_OPTION; $i++) {
      // label
      $this->add('text', "label[$i]", ts('Label'), CRM_Core_DAO::getAttribute('CRM_Core_DAO_OptionValue', 'label'));
      // value
      $this->add('text', "value[$i]", ts('Value'), CRM_Core_DAO::getAttribute('CRM_Core_DAO_OptionValue', 'value'));
      $this->addRule("value[$i]", ts('Please enter a valid money value for this field (e.g. %1).', array(1 => CRM_Utils_Money::format('99.99', ' '))), 'money');

      // default
      $default[] = $this->createElement('radio', NULL, NULL, NULL, $i);
    }

    $this->addGroup($default, 'default');

    $this->addElement('checkbox', 'is_discount', ts('Discounts by Signup Date?'), NULL,
      array('onclick' => "warnDiscountDel(); return showHideByValue('is_discount','','discount','block','radio',false);")
    );

    $discountSection = $this->get('discountSection');
    if (!$discountSection) {
      if ($_POST['_qf_Fee_submit']) {
        $discountSection = 2;
      }
    }

    $this->assign('discountSection', $discountSection);

    require_once 'CRM/Core/ShowHideBlocks.php';
    // form fields of Discount sets
    $defaultOption = array();
    $_showHide = new CRM_Core_ShowHideBlocks('', '');

    for ($i = 1; $i <= self::NUM_DISCOUNT; $i++) {
      //the show hide blocks
      $showBlocks = 'discount_' . $i;
      if ($i > 2) {
        $_showHide->addHide($showBlocks);
      }
      else {
        $_showHide->addShow($showBlocks);
      }

      //Increment by 1 of start date of previous end date.
      if (is_array($this->_submitValues) &&
        !empty($this->_submitValues['discount_name'][$i]) &&
        !empty($this->_submitValues['discount_name'][$i + 1]) &&
        isset($this->_submitValues['discount_end_date']) &&
        isset($this->_submitValues['discount_end_date'][$i]) &&
        $i < self::NUM_DISCOUNT - 1
      ) {
        $end_date = CRM_Utils_Date::processDate($this->_submitValues['discount_end_date'][$i]);
        if (!empty($this->_submitValues['discount_end_date'][$i + 1])
          && empty($this->_submitValues['discount_start_date'][$i + 1])
        ) {
          list($this->_submitValues['discount_start_date'][$i + 1]) = CRM_Utils_Date::setDateDefaults(date('Y-m-d', strtotime("+1 days $end_date")));
        }
      }
      //Decrement by 1 of end date from next start date.
      if ($i > 1 &&
        is_array($this->_submitValues) &&
        !empty($this->_submitValues['discount_name'][$i]) &&
        !empty($this->_submitValues['discount_name'][$i - 1]) &&
        isset($this->_submitValues['discount_start_date']) &&
        isset($this->_submitValues['discount_start_date'][$i])
      ) {
        $start_date = CRM_Utils_Date::processDate($this->_submitValues['discount_start_date'][$i]);
        if (!empty($this->_submitValues['discount_start_date'][$i])
          && empty($this->_submitValues['discount_end_date'][$i - 1])
        ) {
          list($this->_submitValues['discount_end_date'][$i - 1]) = CRM_Utils_Date::setDateDefaults(date('Y-m-d', strtotime("-1 days $start_date")));
        }
      }

      //discount name
      $this->add('text', 'discount_name[' . $i . ']', ts('Discount Name'),
        CRM_Core_DAO::getAttribute('CRM_Core_DAO_OptionValue', 'label')
      );

      //discount start date
      $this->addDate('discount_start_date[' . $i . ']', ts('Discount Start Date'), FALSE, array('formatType' => 'activityDate'));

      //discount end date
      $this->addDate('discount_end_date[' . $i . ']', ts('Discount End Date'), FALSE, array('formatType' => 'activityDate'));
    }
    $_showHide->addToTemplate();
    $this->addElement('submit', $this->getButtonName('submit'), ts('Add Discount Set to Fee Table'),
      array('class' => 'form-submit')
    );

    $this->buildAmountLabel();
    parent::buildQuickForm();
  }

  /**
   * Add local and global form rules
   *
   * @access protected
   *
   * @return void
   */
  function addRules() {
    $this->addFormRule(array('CRM_Event_Form_ManageEvent_Fee', 'formRule'));
  }

  /**
   * global validation rules for the form
   *
   * @param array $values posted values of the form
   *
   * @return array list of errors to be posted back to the form
   * @static
   * @access public
   */
  static function formRule($values) {
    $errors = array();
    if (CRM_Utils_Array::value('is_discount', $values)) {
      $occurDiscount = array_count_values($values['discount_name']);
      $countemptyrows = 0;
      $countemptyvalue = 0;
      for ($i = 1; $i <= self::NUM_DISCOUNT; $i++) {
        $start_date = $end_date = NULL;
        if (CRM_Utils_Array::value($i, $values['discount_name'])) {
          if (CRM_Utils_Array::value($i, $values['discount_start_date'])) {
            $start_date = ($values['discount_start_date'][$i]) ? CRM_Utils_Date::processDate($values['discount_start_date'][$i]) : 0;
          }
          if (CRM_Utils_Array::value($i, $values['discount_end_date'])) {
            $end_date = ($values['discount_end_date'][$i]) ? CRM_Utils_Date::processDate($values['discount_end_date'][$i]) : 0;
          }

          if ($start_date && $end_date && strcmp($end_date, $start_date) < 0) {
            $errors["discount_end_date[$i]"] = ts('The discount end date cannot be prior to the start date.');
          }

          if (!$start_date && !$end_date) {
            $errors["discount_start_date[$i]"] = $errors["discount_end_date[$i]"] = ts('Please specify either start date or end date.');
          }

          if ($i > 1) {
            $end_date_1 = ($values['discount_end_date'][$i - 1]) ? CRM_Utils_Date::processDate($values['discount_end_date'][$i - 1]) : 0;
            if ($start_date && $end_date_1 && strcmp($end_date_1, $start_date) >= 0) {
              $errors["discount_start_date[$i]"] = ts('Select non-overlapping discount start date.');
            }
            elseif (!$start_date && !$end_date_1) {
              $j = $i - 1;
              $errors["discount_start_date[$i]"] = $errors["discount_end_date[$j]"] = ts('Select either of the dates.');
            }
          }

          foreach ($occurDiscount as $key => $value) if ($value > 1 && $key <> '') {
            if ($key == $values['discount_name'][$i]) {
              $errors['discount_name[' . $i . ']'] = ts('%1 is already used for Discount Name.', array(1 => $key));
            }
          }

          //validation for discount labels and values
          for ($index = (self::NUM_OPTION); $index > 0; $index--) {
            $label = TRUE;
            if (empty($values['discounted_label'][$index]) && !empty($values['discounted_value'][$index][$i])) {
              $label = FALSE;
              if (!$label) {
                $errors["discounted_label[{$index}]"] = ts('Label cannot be empty.');
              }
            }
            if (!empty($values['discounted_label'][$index])) {
              $duplicateIndex = CRM_Utils_Array::key($values['discounted_label'][$index], $values['discounted_label']);

              if ((!($duplicateIndex === FALSE)) && (!($duplicateIndex == $index))) {
                $errors["discounted_label[{$index}]"] = ts('Duplicate label value');
              }
            }
            if (empty($values['discounted_label'][$index]) && empty($values['discounted_value'][$index][$i])) {
              $countemptyrows++;
            }
            if (empty($values['discounted_value'][$index][$i])) {
              $countemptyvalue++;
            }
          }
          if (CRM_Utils_Array::value('_qf_Fee_next', $values) && ($countemptyrows == 11 || $countemptyvalue == 11)) {
            $errors["discounted_label[1]"] = $errors["discounted_value[1][$i]"] = ts('At least one fee should be entered for your Discount Set. If you do not see the table to enter discount fees, click the "Add Discount Set to Fee Table" button.');
          }
        }
      }
    }

    if ($values['is_monetary']) {
      //check if contribution type is selected
      if (!$values['contribution_type_id']) {
        $errors['contribution_type_id'] = ts("Please select contribution type.");
      }

      //check for the event fee label (mandatory)
      if (!$values['fee_label']) {
        $errors['fee_label'] = ts("Please enter the fee label for the paid event.");
      }

      if (!CRM_Utils_Array::value('price_set_id', $values)) {
        //check fee label and amount
        $check = 0;
        $optionKeys = array();
        foreach ($values['label'] as $key => $val) {
          if (trim($val) && trim($values['value'][$key])) {
            $optionKeys[$key] = $key;
            $check++;
          }
        }

        $default = CRM_Utils_Array::value('default', $values);
        if ($default && !in_array($default, $optionKeys)) {
          $errors['default'] = ts("Please select an appropriate option as default.");
        }

        if (!$check) {
          if (!$values['label'][1]) {
            $errors['label[1]'] = ts("Please enter a label for at least one fee level.");
          }
          if (!$values['value'][1]) {
            $errors['value[1]'] = ts("Please enter an amount for at least one fee level.");
          }
        }
      }
      if (isset($values['is_pay_later'])) {
        if (empty($values['pay_later_text'])) {
          $errors['pay_later_text'] = ts('Please enter the Pay Later prompt to be displayed on the Registration form.');
        }
        if (empty($values['pay_later_receipt'])) {
          $errors['pay_later_receipt'] = ts('Please enter the Pay Later instructions to be displayed to your users.');
        }
      }
      if (empty($values['is_pay_later']) && empty($values['payment_processor'])) {
        $errors['payment_processor'] = ts('Payment processor is not set for this page');
        $errors['is_pay_later'] = ts('A payment processor must be selected for this event registration page, or the event must be configured to give users the option to pay later.');
      }
    }
    return empty($errors) ? TRUE : $errors;
  }


  public function buildAmountLabel() {
    require_once 'CRM/Utils/Money.php';

    $default = array();
    for ($i = 1; $i <= self::NUM_OPTION; $i++) {
      // label
      $this->add('text', "discounted_label[$i]", ts('Label'), CRM_Core_DAO::getAttribute('CRM_Core_DAO_OptionValue', 'label'));
      // value
      for ($j = 1; $j <= self::NUM_DISCOUNT; $j++) {
        $this->add('text', "discounted_value[$i][$j]", ts('Value'), array('size' => 10));
        $this->addRule("discounted_value[$i][$j]", ts('Please enter a valid money value for this field (e.g. %1).', array(1 => CRM_Utils_Money::format('99.99', ' '))), 'money');
      }

      // default
      $default[] = $this->createElement('radio', NULL, NULL, NULL, $i);
    }

    $this->addGroup($default, 'discounted_default');
  }

  /**
   * Process the form
   *
   * @return void
   * @access public
   */
  public function postProcess() {
    $params = array();
    $params = $this->exportValues();

    $this->set('discountSection', 0);

    if (CRM_Utils_Array::value('_qf_Fee_submit', $_POST)) {
      $this->buildAmountLabel();
      $this->set('discountSection', 2);
      return;
    }

    if (array_key_exists('payment_processor', $params) && !CRM_Utils_System::isNull($params['payment_processor'])) {
      $params['payment_processor'] = implode(CRM_Core_DAO::VALUE_SEPARATOR, array_keys($params['payment_processor']));
    }
    else {
      $params['payment_processor'] = 'null';
    }
    $params['is_pay_later'] = CRM_Utils_Array::value('is_pay_later', $params, 0);

    // Refs #23510, clear pay_later_receipt if is_pay_later doesn't be checked.
    if (empty($params['is_pay_later'])) {
      $params['pay_later_receipt'] = 'null';
    }

    if ($this->_id) {
      require_once 'CRM/Price/BAO/Set.php';

      // delete all the prior label values or discounts in the custom options table
      // and delete a price set if one exists
      if (!CRM_Price_BAO_Set::removeFrom('civicrm_event', $this->_id)) {
        require_once 'CRM/Core/OptionGroup.php';
        CRM_Core_OptionGroup::deleteAssoc("civicrm_event.amount.{$this->_id}");
        CRM_Core_OptionGroup::deleteAssoc("civicrm_event.amount.{$this->_id}.discount.%", "LIKE");
      }
    }

    if ($params['is_monetary']) {
      if ($params['price_set_id']) {
        CRM_Price_BAO_Set::addTo('civicrm_event', $this->_id, $params['price_set_id']);
      }
      else {
        // if there are label / values, create custom options for them
        $labels = CRM_Utils_Array::value('label', $params);
        $values = CRM_Utils_Array::value('value', $params);
        $default = CRM_Utils_Array::value('default', $params);

        $options = array();
        if (!CRM_Utils_System::isNull($labels) && !CRM_Utils_System::isNull($values)) {
          for ($i = 1; $i < self::NUM_OPTION; $i++) {
            if (!empty($labels[$i]) && !CRM_Utils_System::isNull($values[$i])) {
              $options[] = array('label' => trim($labels[$i]),
                'value' => CRM_Utils_Rule::cleanMoney(trim($values[$i])),
                'weight' => $i,
                'is_active' => 1,
                'is_default' => $default == $i,
              );
            }
          }
          if (!empty($options)) {
            $params['default_fee_id'] = NULL;
            CRM_Core_OptionGroup::createAssoc("civicrm_event.amount.{$this->_id}",
              $options,
              $params['default_fee_id']
            );
          }
        }

        if (CRM_Utils_Array::value('is_discount', $params) == 1) {
          // if there are discounted set of label / values,
          // create custom options for them
          $labels = CRM_Utils_Array::value('discounted_label', $params);
          $values = CRM_Utils_Array::value('discounted_value', $params);
          $default = CRM_Utils_Array::value('discounted_default', $params);

          if (!CRM_Utils_System::isNull($labels) && !CRM_Utils_System::isNull($values)) {
            for ($j = 1; $j <= self::NUM_DISCOUNT; $j++) {
              $discountOptions = array();
              for ($i = 1; $i < self::NUM_OPTION; $i++) {
                if (!empty($labels[$i]) &&
                  !CRM_Utils_System::isNull($values[$i][$j])
                ) {
                  $discountOptions[] = array('label' => trim($labels[$i]),
                    'value' => CRM_Utils_Rule::cleanMoney(trim($values[$i][$j])),
                    'weight' => $i,
                    'is_active' => 1,
                    'is_default' => $default == $i,
                  );
                }
              }

              if (!empty($discountOptions)) {
                $params['default_discount_fee_id'] = NULL;
                $discountOptionsGroupId = CRM_Core_OptionGroup::createAssoc("civicrm_event.amount.{$this->_id}.discount.{$params['discount_name'][$j]}",
                  $discountOptions,
                  $params['default_discount_fee_id'],
                  $params['discount_name'][$j]
                );

                $discountParams = array(
                  'entity_table' => 'civicrm_event',
                  'entity_id' => $this->_id,
                  'option_group_id' => $discountOptionsGroupId,
                  'start_date' => CRM_Utils_Date::processDate($params["discount_start_date"][$j]),
                  'end_date' => CRM_Utils_Date::processDate($params["discount_end_date"][$j]),
                );
                require_once 'CRM/Core/BAO/Discount.php';
                CRM_Core_BAO_Discount::add($discountParams);
              }
            }
          }
        }
      }
    }
    else {
      $params['contribution_type_id'] = '';
    }

    //update events table
    require_once 'CRM/Event/BAO/Event.php';
    $params['id'] = $this->_id;
    CRM_Event_BAO_Event::add($params);

    parent::endPostProcess();
  }

  /**
   * Return a descriptive name for the page, used in wizard header
   *
   * @return string
   * @access public
   */
  public function getTitle() {
    return ts('Event Fees');
  }
}

