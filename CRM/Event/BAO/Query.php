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
class CRM_Event_BAO_Query {

  static function &getFields() {
    $fields = array();
    require_once 'CRM/Event/DAO/Event.php';
    require_once 'CRM/Core/DAO/Discount.php';
    $fields = array_merge($fields, CRM_Event_DAO_Event::import());
    $fields = array_merge($fields, self::getParticipantFields());
    $fields = array_merge($fields, CRM_Core_DAO_Discount::export());
    $fields = array_merge($fields, CRM_Core_DAO_Track::export());

    return $fields;
  }

  static function &getParticipantFields($onlyParticipant = FALSE) {
    require_once 'CRM/Event/BAO/Participant.php';
    $fields = &CRM_Event_BAO_Participant::importableFields('Individual', TRUE, $onlyParticipant);
    return $fields;
  }

  /**
   * build select for CiviEvent
   *
   * @return void
   * @access public
   */
  static function select(&$query) {
    if (($query->_mode & CRM_Contact_BAO_Query::MODE_EVENT) ||
      CRM_Utils_Array::value('participant_id', $query->_returnProperties)
    ) {

      $query->_select['participant_id'] = "civicrm_participant.id as participant_id";
      $query->_element['participant_id'] = 1;
      $query->_tables['civicrm_participant'] = $query->_whereTables['civicrm_participant'] = 1;

      //add fee level
      if (CRM_Utils_Array::value('participant_fee_level', $query->_returnProperties)) {
        $query->_select['participant_fee_level'] = "civicrm_participant.fee_level as participant_fee_level";
        $query->_element['participant_fee_level'] = 1;
      }

      //add fee amount
      if (CRM_Utils_Array::value('participant_fee_amount', $query->_returnProperties)) {
        $query->_select['participant_fee_amount'] = "civicrm_participant.fee_amount as participant_fee_amount";
        $query->_element['participant_fee_amount'] = 1;
      }

      //add fee currency
      if (CRM_Utils_Array::value('participant_fee_currency', $query->_returnProperties)) {
        $query->_select['participant_fee_currency'] = "civicrm_participant.fee_currency as participant_fee_currency";
        $query->_element['participant_fee_currency'] = 1;
      }

      //add event title also if event id is select
      if (CRM_Utils_Array::value('event_id', $query->_returnProperties) ||
        CRM_Utils_Array::value('event_title', $query->_returnProperties)
      ) {
        $query->_select['event_id'] = "civicrm_event.id as event_id";
        $query->_select['event_title'] = "civicrm_event.title as event_title";
        $query->_element['event_id'] = 1;
        $query->_element['event_title'] = 1;
        $query->_tables['civicrm_event'] = 1;
        $query->_whereTables['civicrm_event'] = 1;
      }

      //add start date / end date
      if (CRM_Utils_Array::value('event_start_date', $query->_returnProperties)) {
        $query->_select['event_start_date'] = "civicrm_event.start_date as event_start_date";
        $query->_element['event_start_date'] = 1;
      }

      if (CRM_Utils_Array::value('event_end_date', $query->_returnProperties)) {
        $query->_select['event_end_date'] = "civicrm_event.end_date as event_end_date";
        $query->_element['event_end_date'] = 1;
      }

      //event type
      if (CRM_Utils_Array::value('event_type', $query->_returnProperties)) {
        $query->_tables['civicrm_event'] = 1;
        $query->_select['event_type'] = "event_type.label as event_type";
        $query->_element['event_type'] = 1;
        $query->_tables['event_type'] = 1;
        $query->_whereTables['event_type'] = 1;
      }

      if (CRM_Utils_Array::value('event_type_id', $query->_returnProperties)) {
        $query->_tables['civicrm_event'] = 1;
        $query->_select['event_type_id'] = "civicrm_event.event_type_id as event_type_id";
        $query->_element['event_type_id'] = 1;
        $query->_tables['event_type'] = 1;
        $query->_whereTables['event_type'] = 1;
      }

      //add status and status_id
      if (CRM_Utils_Array::value('participant_status', $query->_returnProperties) ||
        CRM_Utils_Array::value('participant_status_id', $query->_returnProperties)
      ) {
        $query->_select['participant_status'] = "participant_status.label as participant_status";
        $query->_select['participant_status_id'] = "participant_status.id as participant_status_id";
        $query->_element['participant_status_id'] = 1;
        $query->_element['participant_status'] = 1;
        $query->_tables['civicrm_participant'] = 1;
        $query->_tables['participant_status'] = 1;
        $query->_whereTables['civicrm_participant'] = 1;
        $query->_whereTables['participant_status'] = 1;
      }

      //add role
      if (CRM_Utils_Array::value('participant_role', $query->_returnProperties)) {
        $query->_select['participant_role'] = "participant_role.label as participant_role";
        $query->_element['participant_role'] = 1;
        $query->_tables['civicrm_participant'] = 1;
        $query->_tables['participant_role'] = 1;
        $query->_whereTables['civicrm_participant'] = 1;
        $query->_whereTables['participant_role'] = 1;
      }

      if (CRM_Utils_Array::value('participant_role_id', $query->_returnProperties)) {
        $query->_select['participant_role_id'] = "civicrm_participant.role_id as participant_role_id";
        $query->_element['participant_role_id'] = 1;
        $query->_tables['civicrm_participant'] = 1;
        $query->_tables['participant_role'] = 1;
        $query->_whereTables['civicrm_participant'] = 1;
        $query->_whereTables['participant_role'] = 1;
      }

      //add register date
      if (CRM_Utils_Array::value('participant_register_date', $query->_returnProperties)) {
        $query->_select['participant_register_date'] = "civicrm_participant.register_date as participant_register_date";
        $query->_element['participant_register_date'] = 1;
      }

      //add source
      if (CRM_Utils_Array::value('participant_source', $query->_returnProperties)) {
        $query->_select['participant_source'] = "civicrm_participant.source as participant_source";
        $query->_element['participant_source'] = 1;
        $query->_tables['civicrm_participant'] = $query->_whereTables['civicrm_participant'] = 1;
      }

      //participant note
      if (CRM_Utils_Array::value('participant_note', $query->_returnProperties)) {
        $query->_select['participant_note'] = "civicrm_note_participant.note as participant_note";
        $query->_element['participant_note'] = 1;
        $query->_tables['participant_note'] = 1;
      }

      if (CRM_Utils_Array::value('participant_is_pay_later', $query->_returnProperties)) {
        $query->_select['participant_is_pay_later'] = "civicrm_participant.is_pay_later as participant_is_pay_later";
        $query->_element['participant_is_pay_later'] = 1;
      }

      if (CRM_Utils_Array::value('participant_is_test', $query->_returnProperties)) {
        $query->_select['participant_is_test'] = "civicrm_participant.is_test as participant_is_test";
        $query->_element['participant_is_test'] = 1;
      }

      if (CRM_Utils_Array::value('participant_registered_by_id', $query->_returnProperties)) {
        $query->_select['participant_registered_by_id'] = "civicrm_participant.registered_by_id as participant_registered_by_id";
        $query->_element['participant_registered_by_id'] = 1;
      }

      // get discount name
      if (CRM_Utils_Array::value('participant_discount_name', $query->_returnProperties)) {
        $query->_select['participant_discount_name'] = "discount_name.label as participant_discount_name";
        $query->_element['participant_discount_name'] = 1;
        $query->_tables['civicrm_discount'] = 1;
        $query->_tables['participant_discount_name'] = 1;
        $query->_whereTables['civicrm_discount'] = 1;
        $query->_whereTables['participant_discount_name'] = 1;
      }

      if (CRM_Utils_Array::value('participant_line_item', $query->_returnProperties)) {
        $query->_select['participant_line_item'] = "line_item.label as participant_line_item";
        $query->_element['participant_line_item'] = 1;
        $query->_tables['civicrm_line_item'] = 1;
        $query->_tables['participant_line_item'] = 1;
        $query->_whereTables['civicrm_line_item'] = 1;
        $query->_whereTables['participant_line_item'] = 1;
      }
    }
  }

  static function where(&$query) {
    $isTest = FALSE;
    $grouping = NULL;
    foreach (array_keys($query->_params) as $id) {
      if (substr($query->_params[$id][0], 0, 6) == 'event_' ||
        substr($query->_params[$id][0], 0, 12) == 'participant_'
      ) {
        if ($query->_mode == CRM_Contact_BAO_QUERY::MODE_CONTACTS) {
          $query->_useDistinct = TRUE;
        }
        if ($query->_params[$id][0] == 'participant_test') {
          $isTest = TRUE;
        }
        $grouping = $query->_params[$id][3];
        self::whereClauseSingle($query->_params[$id], $query);
      }
    }

    if ($grouping !== NULL &&
      !$isTest
    ) {
      $values = array('participant_test', '=', 0, $grouping, 0);
      self::whereClauseSingle($values, $query);
    }
  }


  static function whereClauseSingle(&$values, &$query) {
    list($name, $op, $value, $grouping, $wildcard) = $values;
    switch ($name) {
      case 'event_start_date_low':
      case 'event_start_date_high':
        $query->dateQueryBuilder($values,
          'civicrm_event', 'event_start_date', 'start_date', 'Start Date'
        );
        return;

      case 'event_end_date_low':
      case 'event_end_date_high':
        $query->dateQueryBuilder($values,
          'civicrm_event', 'event_end_date', 'end_date', 'End Date'
        );
        return;

      case 'event_id':
        $query->_where[$grouping][] = "civicrm_event.id $op {$value}";
        $eventTitle = CRM_Core_DAO::getFieldValue('CRM_Event_DAO_Event', $value, 'title');
        $query->_qill[$grouping][] = ts('Event') . " $op {$eventTitle}";
        $query->_tables['civicrm_event'] = $query->_whereTables['civicrm_event'] = 1;
        return;

      case 'event_type_id':
        require_once 'CRM/Core/OptionGroup.php';
        require_once 'CRM/Utils/Array.php';

        $eventTypes = CRM_Core_OptionGroup::values("event_type");
        $query->_where[$grouping][] = "civicrm_participant.event_id = civicrm_event.id and civicrm_event.event_type_id $op {$value}";
        if ($op == 'IN') {
          $string = CRM_Core_DAO::singleValueQuery("SELECT GROUP_CONCAT(label) FROM civicrm_option_value WHERE value $op {$value} AND option_group_id = (SELECT id FROM civicrm_option_group WHERE name = 'event_type')");
        }
        else {
          $string = $eventTypes[$value];
        }
        $query->_qill[$grouping][] = ts('Event Type - %1', array(1 => $string));
        $query->_tables['civicrm_event'] = $query->_whereTables['civicrm_event'] = 1;
        return;

      case 'participant_test':
        $query->_where[$grouping][] = CRM_Contact_BAO_Query::buildClause("civicrm_participant.is_test",
          $op,
          $value,
          "Integer"
        );
        if ($value) {
          $query->_qill[$grouping][] = ts("Find Test Participants");
        }
        $query->_tables['civicrm_participant'] = $query->_whereTables['civicrm_participant'] = 1;
        return;

      case 'participant_fee_id':
        $feeLabels = $priceValues = array();
        if (is_array($value)) {
          foreach ($value as $k => $val) {
            list($priceType, $priceOption) = explode(':', $val, 2);
            if ($priceType == 'priceset') {
              if (CRM_Utils_Rule::positiveInteger($priceOption)) {
                // refs #25295, price option can search by id
                $priceValues[] = $priceOption;
              }
              elseif (strstr($priceOption, ',')){
                $commaSeperated = explode(',', $priceOption);
                foreach($commaSeperated as $cval) {
                  if (CRM_Utils_Rule::positiveInteger($cval)) {
                    $priceValues[] = $cval;
                  }
                }
              }
            }
            else {
              $label = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_OptionValue', $val, 'label');
              if ($label) {
                $feeLabels[] = CRM_Core_DAO::escapeString(trim($label));
              }
            }
          }
        }
        else {
          $feeLabels[] = CRM_Core_DAO::escapeString(trim($value));
        }
        if (!empty($feeLabels)) {
          $feeLabel = implode('|', preg_replace('/[()*^$%\[\]\|]/', '.', $feeLabels));
          $query->_where[$grouping][] = "civicrm_participant.fee_level REGEXP '$feeLabel'";
          $query->_qill[$grouping][] = ts("Fee level").' '.ts('IN').' '.implode(', ', $feeLabels);
        }
        elseif (!empty($priceValues)) {
          $query->_where[$grouping][] = "participant_line_item.price_field_value_id IN (".implode(",", $priceValues).")";
          $query->_qill[$grouping][] = ts("Fee level").' '.ts('IN').' '.implode(', ', $feeLabels);
          $query->_tables['participant_line_item'] = $query->_whereTables['participant_line_item'] = 1;
        }
        $query->_tables['civicrm_participant'] = $query->_whereTables['civicrm_participant'] = 1;
        return;

      case 'participant_fee_amount':
        $query->_where[$grouping][] = CRM_Contact_BAO_Query::buildClause("civicrm_participant.fee_amount",
          $op,
          $value,
          "Money"
        );
        if ($value) {
          $query->_qill[$grouping][] = ts("Fee Amount") . " $op $value";
        }
        $query->_tables['civicrm_participant'] = $query->_whereTables['civicrm_participant'] = 1;
        return;

      case 'participant_fee_amount_high':
      case 'participant_fee_amount_low':
        $query->numberRangeBuilder($values,
          'civicrm_participant', 'participant_fee_amount', 'fee_amount', 'Fee Amount'
        );
        return;

      case 'participant_pay_later':
        $query->_where[$grouping][] = CRM_Contact_BAO_Query::buildClause("civicrm_participant.is_pay_later",
          $op,
          $value,
          "Integer"
        );
        if ($value) {
          $query->_qill[$grouping][] = ts("Find Pay Later Participants");
        }
        $query->_tables['civicrm_participant'] = $query->_whereTables['civicrm_participant'] = 1;
        return;
      case 'participant_register_date_low':
      case 'participant_register_date_high':
        $query->dateQueryBuilder($values,
          'civicrm_participant', 'participant_register_date', 'register_date', 'Registered'
        );
        return;
      case 'participant_status':
      case 'participant_status_id':
        $statusTypes = CRM_Event_PseudoConstant::participantStatus(NULL, NULL, 'label');
        $val = array();
        if (is_array($value)) {
          foreach ($value as $k => $v) {
            if (CRM_Utils_Rule::positiveInteger($k)) {
              $val[$k] = $k;
            }
            else {
              $statusId = array_search($v, $statusTypes);
              if (!empty($statusId)) {
                $val[$statusId] = $statusId;
              }
            }
          }
        }
        else {
          if (CRM_Utils_Rule::positiveInteger($value)) {
            $val[$value] = $value;
          }
          else {
            $statusId = array_search($value, $statusTypes);
            if (!empty($statusId)) {
              $val[$statusId] = $statusId;
            }
          }
        }

        $op = 'IN';
        $status = implode(",", $val);
        $status = "({$status})";

        require_once 'CRM/Event/PseudoConstant.php';
        $names = array();
        if (!empty($val)) {
          foreach ($val as $id => $dontCare) {
            $names[] = $statusTypes[$id];
          }
        }

        $query->_qill[$grouping][] = ts('Participant Status %1', array(1 => ts($op))) . ' ' . implode(' ' . ts('or') . ' ', $names);

        $query->_where[$grouping][] = CRM_Contact_BAO_Query::buildClause("civicrm_participant.status_id",
          $op,
          $status,
          "Integer"
        );
        $query->_tables['civicrm_participant'] = $query->_whereTables['civicrm_participant'] = 1;
        return;

      case 'participant_role_id':
        $val = array();
        if (is_array($value)) {
          foreach ($value as $k => $v) {
            if ($v) {
              $val[$k] = $k;
            }
          }
        }
        else {
          $value = array($value => 1);
        }

        require_once 'CRM/Event/PseudoConstant.php';
        $roleTypes = CRM_Event_PseudoConstant::participantRole();

        $names = array();
        if (!empty($val)) {
          foreach ($val as $id => $dontCare) {
            $names[] = $roleTypes[$id];
          }
        }
        else {
          $names[] = $roleTypes[$value];
        }

        $query->_qill[$grouping][] = ts('Participant Role %1', array(1 => $op)) . ' ' . implode(' ' . ts('or') . ' ', $names);
        $query->_where[$grouping][] = " civicrm_participant.role_id REGEXP '[[:<:]]" . implode('[[:>:]]|[[:<:]]', array_keys($value)) . "[[:>:]]' ";

        $query->_tables['civicrm_participant'] = $query->_whereTables['civicrm_participant'] = 1;
        return;

      case 'participant_source':
        $query->_where[$grouping][] = CRM_Contact_BAO_Query::buildClause("civicrm_participant.source",
          $op,
          $value,
          "String"
        );
        $query->_qill[$grouping][] = ts("Participant Source") . " $op $value";
        $query->_tables['civicrm_participant'] = $query->_whereTables['civicrm_participant'] = 1;
        return;

      case 'participant_register_date':
        $query->dateQueryBuilder($values,
          'civicrm_participant', 'participant_register_date', 'register_date', 'Register Date'
        );
        return;

      case 'participant_id':
        $query->_where[$grouping][] = "civicrm_participant.id $op $value";
        $query->_tables['civicrm_participant'] = $query->_whereTables['civicrm_participant'] = 1;
        return;

      case 'event_id':
        $query->_where[$grouping][] = CRM_Contact_BAO_Query::buildClause("civicrm_event.id",
          $op,
          $value,
          "Integer"
        );
        $query->_tables['civicrm_event'] = $query->_whereTables['civicrm_event'] = 1;
        $title = CRM_Core_DAO::getFieldValue('CRM_Event_DAO_Event', $value, "title");
        $query->_qill[$grouping][] = ts('Event') . " $op $value";
        return;

      case 'participant_contact_id':
        $query->_where[$grouping][] = CRM_Contact_BAO_Query::buildClause("civicrm_participant.contact_id",
          $op,
          $value,
          "Integer"
        );
        $query->_tables['civicrm_participant'] = $query->_whereTables['civicrm_participant'] = 1;
        return;

      case 'event_is_public':
        $query->_where[$grouping][] = CRM_Contact_BAO_Query::buildClause("civicrm_event.is_public",
          $op,
          $value,
          "Integer"
        );
        $query->_tables['civicrm_event'] = $query->_whereTables['civicrm_event'] = 1;
        return;
    }
  }

  static function from($name, $mode, $side) {
    $from = NULL;
    switch ($name) {
      case 'civicrm_participant':
        if ($mode & CRM_Contact_BAO_Query::MODE_EVENT) {
          $from = " INNER JOIN civicrm_participant ON civicrm_participant.contact_id = contact_a.id ";
        }
        else {
          $from = " $side JOIN civicrm_participant ON civicrm_participant.contact_id = contact_a.id ";
        }
        break;

      case 'civicrm_event':
        $from = " INNER JOIN civicrm_event ON civicrm_participant.event_id = civicrm_event.id ";
        break;

      case 'event_type':
        $from = " $side JOIN civicrm_option_group option_group_event_type ON (option_group_event_type.name = 'event_type')";
        $from .= " $side JOIN civicrm_option_value event_type ON (civicrm_event.event_type_id = event_type.value AND option_group_event_type.id = event_type.option_group_id ) ";
        break;

      case 'participant_note':
        $from .= " $side JOIN civicrm_note civicrm_note_participant ON ( civicrm_note_participant.entity_table = 'civicrm_participant' AND
          civicrm_participant.id = civicrm_note_participant.entity_id AND civicrm_note_participant.note IS NOT NULL)";
        break;

      case 'participant_status':
        $from .= " $side JOIN civicrm_participant_status_type participant_status ON (civicrm_participant.status_id = participant_status.id) ";
        break;

      case 'participant_role':
        $from = " $side JOIN civicrm_option_group option_group_participant_role ON (option_group_participant_role.name = 'participant_role')";
        $from .= " $side JOIN civicrm_option_value participant_role ON (civicrm_participant.role_id = participant_role.value 
                               AND option_group_participant_role.id = participant_role.option_group_id ) ";
        break;

      case 'participant_discount_name':
        $from = " $side JOIN civicrm_discount discount ON ( civicrm_participant.discount_id = discount.id )";
        $from .= " $side JOIN civicrm_option_group discount_name ON ( discount_name.id = discount.option_group_id ) ";
        break;

      case 'participant_line_item':
        $from = " $side JOIN civicrm_line_item participant_line_item ON ( civicrm_participant.id = participant_line_item.entity_id AND participant_line_item.entity_table = 'civicrm_participant')";
        break;

      case 'civicrm_track':
        if ($mode & CRM_Contact_BAO_Query::MODE_EVENT) {
          $from = " $side JOIN civicrm_track ON civicrm_track.entity_table = 'civicrm_participant' AND civicrm_track.entity_id = civicrm_participant.id";
        }
        break;
    }
    return $from;
  }

  /**
   * getter for the qill object
   *
   * @return string
   * @access public
   */
  function qill() {
    return (isset($this->_qill)) ? $this->_qill : "";
  }

  static function defaultReturnProperties($mode) {
    $properties = NULL;
    if ($mode & CRM_Contact_BAO_Query::MODE_EVENT) {
      $properties = array(
        'contact_type' => 1,
        'contact_sub_type' => 1,
        'sort_name' => 1,
        'display_name' => 1,
        'event_id' => 1,
        'event_title' => 1,
        'event_start_date' => 1,
        'event_end_date' => 1,
        'event_type' => 1,
        'event_type_id' => 1,
        'participant_id' => 1,
        'participant_status' => 1,
        'participant_role_id' => 1,
        //'participant_note'          => 1,
        'participant_register_date' => 1,
        'participant_source' => 1,
        'participant_fee_level' => 1,
        'participant_is_test' => 1,
        'participant_is_pay_later' => 1,
        'participant_fee_amount' => 1,
        'participant_discount_name' => 1,
        'participant_fee_currency' => 1,
        'participant_registered_by_id' => 1,
      );

      // also get all the custom participant properties
      require_once "CRM/Core/BAO/CustomField.php";
      $fields = CRM_Core_BAO_CustomField::getFieldsForImport('Participant');
      if (!empty($fields)) {
        foreach ($fields as $name => $dontCare) {
          $properties[$name] = 1;
        }
      }
    }

    return $properties;
  }

  static function buildSearchForm(&$form) {
    $dataURLEvent = CRM_Utils_System::url('civicrm/ajax/event',
      "reset=1",
      FALSE, NULL, FALSE
    );
    $dataURLEventType = CRM_Utils_System::url('civicrm/ajax/eventType',
      "reset=1",
      FALSE, NULL, FALSE
    );
    $dataURLEventFee = CRM_Utils_System::url('civicrm/ajax/eventFee',
      "reset=1",
      FALSE, NULL, FALSE
    );

    $form->assign('dataURLEvent', $dataURLEvent);
    $form->assign('dataURLEventType', $dataURLEventType);
    $form->assign('dataURLEventFee', $dataURLEventFee);

    $form->add('text', 'event_id', ts('Event Name'), array('id' => 'event_id'));
    $eventType = CRM_Event_PseudoConstant::eventType();
    $form->addSelect('event_type', ts('Event Type'), $eventType, array('multiple' => 'multiple', 'style' => 'width: 100%;'));
    $levels = array();
    $where = array();
    $where[] = "ce.entity_table = 'civicrm_event'";
    if ($form->_eventId) {
      $where[] = "ce.entity_id = $form->_eventId";
      $levels = CRM_Price_BAO_Field::getPriceLevels($where);
      $eventFeeBlock = array();
      CRM_Core_OptionGroup::getAssoc("civicrm_event.amount.{$form->_eventId}", $eventFeeBlock, TRUE);
      if (!empty($eventFeeBlock)){
        foreach($eventFeeBlock as $amount_id => $detail) {
          $levels[$amount_id] = $detail['label'] . ' - ' . $detail['value'];
        }
      }
      $form->addSelect('participant_fee_id', ts('Fee Level'), $levels, array('multiple' => 'multiple'));
    }
    else {
      // performance issue, use only text search
      $form->addElement('text', 'participant_fee_id', ts('Fee Level'));
    }

    //elements for assigning value operation
    $form->add('hidden', 'event_type_id', '', array('id' => 'event_type_id'));
    $form->addDate('event_start_date_low', ts('Event Dates - From'), FALSE, array('formatType' => 'searchDate'));
    $form->addDate('event_end_date_high', ts('To'), FALSE, array('formatType' => 'searchDate'));

    $form->addDate('participant_register_date_low', ts('Registered - From'), FALSE, array('formatType' => 'searchDate'));
    $form->addDate('participant_register_date_high', ts('To'), FALSE, array('formatType' => 'searchDate'));

    require_once 'CRM/Event/PseudoConstant.php';
    $attrs = array('multiple' => 'multiple');
    $status = CRM_Event_PseudoConstant::participantStatus(NULL, NULL, 'label');
    asort($status);
    //$form->_participantStatus =& $form->addElement('checkbox', "participant_status_id[$id]", null,$Name);
    $form->addElement('select', 'participant_status_id', 'Participant Status', $status, $attrs);

    $roles = CRM_Event_PseudoConstant::participantRole();
    $form->addElement('select', 'participant_role_id', 'Participant Role', $roles, $attrs);

    $form->addElement('checkbox', 'participant_test', ts('Find Test Participants?'));
    $form->addElement('checkbox', 'participant_pay_later', ts('Find Pay Later Participants?'));
    $form->addElement('text', 'participant_fee_amount_low', ts('From'), array('size' => 8, 'maxlength' => 8));
    $form->addElement('text', 'participant_fee_amount_high', ts('To'), array('size' => 8, 'maxlength' => 8));

    $form->addRule('participant_fee_amount_low', ts('Please enter a valid money value.'), 'money');
    $form->addRule('participant_fee_amount_high', ts('Please enter a valid money value.'), 'money');
    // add all the custom  searchable fields
    require_once 'CRM/Core/BAO/CustomGroup.php';
    $extends = array('Participant');
    $groupDetails = CRM_Core_BAO_CustomGroup::getGroupDetail(NULL, TRUE, $extends);
    if ($groupDetails) {
      require_once 'CRM/Core/BAO/CustomField.php';
      $form->assign('participantGroupTree', $groupDetails);
      foreach ($groupDetails as $group) {
        foreach ($group['fields'] as $field) {
          $fieldId = $field['id'];
          $elementName = 'custom_' . $fieldId;
          CRM_Core_BAO_CustomField::addQuickFormElement($form,
            $elementName,
            $fieldId,
            FALSE, FALSE, TRUE
          );
        }
      }
    }

    $form->assign('validCiviEvent', TRUE);
  }

  static function searchAction(&$row, $id) {}

  static function tableNames(&$tables) {
    //add participant table
    if (CRM_Utils_Array::value('civicrm_event', $tables)) {
      $tables = array_merge(array('civicrm_participant' => 1), $tables);
    }
  }
}

