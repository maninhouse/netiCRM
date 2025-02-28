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

require_once "CRM/Core/Form.php";
require_once "CRM/Activity/BAO/Activity.php";
require_once "CRM/Activity/BAO/ActivityTarget.php";

/**
 * This class does pre processing for viewing an activity or their revisions
 *
 */
class CRM_Case_Form_ActivityView extends CRM_Core_Form {

  /**
   * Function to process the view
   *
   * @access public
   *
   * @return None
   */
  public function preProcess() {
    $contactID = CRM_Utils_Request::retrieve('cid', 'Integer', $this, TRUE);
    $activityID = CRM_Utils_Request::retrieve('aid', 'Integer', $this, TRUE);
    $revs = CRM_Utils_Request::retrieve('revs', 'Boolean', CRM_Core_DAO::$_nullObject);
    $caseID = CRM_Utils_Request::retrieve('caseID', 'Boolean', CRM_Core_DAO::$_nullObject);
    $activitySubject = CRM_Core_DAO::getFieldValue('CRM_Activity_DAO_Activity',
      $activityID,
      'subject'
    );
    $type = CRM_Utils_Request::retrieve('type', 'String', CRM_Core_DAO::$_nullObject);

    //check for required permissions, CRM-6264
    if ($activityID &&
      !CRM_Activity_BAO_Activity::checkPermission($activityID, CRM_Core_Action::VIEW)
    ) {
      return CRM_Core_Error::statusBounce(ts('You do not have permission to access this page.'));
    }

    $this->assign('contactID', $contactID);
    $this->assign('caseID', $caseID);
    $this->assign('type', $type);

    require_once 'CRM/Case/XMLProcessor/Report.php';
    $xmlProcessor = new CRM_Case_XMLProcessor_Report();
    $report = $xmlProcessor->getActivityInfo($contactID, $activityID, TRUE);

    require_once 'CRM/Core/BAO/File.php';
    $attachmentUrl = CRM_Core_BAO_File::attachmentInfo('civicrm_activity', $activityID);
    if ($attachmentUrl) {
      $report['fields'][] = array('label' => 'Attachment(s)',
        'value' => $attachmentUrl,
        'type' => 'Link',
      );
    }

    require_once 'CRM/Core/BAO/EntityTag.php';
    $tags = CRM_Core_BAO_EntityTag::getTag($activityID, 'civicrm_activity');
    if (!empty($tags)) {
      $allTag = CRM_Core_PseudoConstant::tag();
      foreach ($tags as $tid) {
        $tags[$tid] = $allTag[$tid];
      }
      $report['fields'][] = array('label' => 'Tags',
        'value' => implode('<br />', $tags),
        'type' => 'String',
      );
    }

    $this->assign('report', $report);

    $latestRevisionID = CRM_Activity_BAO_Activity::getLatestActivityId($activityID);

    $viewPriorActivities = array();
    $priorActivities = CRM_Activity_BAO_Activity::getPriorAcitivities($activityID);
    foreach ($priorActivities as $activityId => $activityValues) {
      if (CRM_Case_BAO_Case::checkPermission($activityId, 'view', NULL, $contactID)) {
        $viewPriorActivities[$activityId] = $activityValues;
      }
    }

    if ($revs) {
      $this->assign('revs', $revs);

      $this->assign('result', $viewPriorActivities);
      $this->assign('subject', $activitySubject);
      $this->assign('latestRevisionID', $latestRevisionID);
    }
    else {
      if (count($viewPriorActivities) > 1) {
        $this->assign('activityID', $activityID);
      }

      if ($latestRevisionID != $activityID) {
        $this->assign('latestRevisionID', $latestRevisionID);
      }
    }

    $parentID = CRM_Activity_BAO_Activity::getParentActivity($activityID);
    if ($parentID) {
      $this->assign('parentID', $parentID);
    }

    //viewing activity should get diplayed in recent list.CRM-4670
    $activityTypeID = CRM_Core_DAO::getFieldValue('CRM_Activity_DAO_Activity', $activityID, 'activity_type_id');

    $activityTargetContacts = CRM_Activity_BAO_ActivityTarget::retrieveTargetIdsByActivityId($activityID);
    if (!empty($activityTargetContacts)) {
      $recentContactId = $activityTargetContacts[0];
    }
    else {
      $recentContactId = $contactID;
    }

    if (!isset($caseID)) {
      $caseID = CRM_Core_DAO::getFieldValue('CRM_Case_DAO_CaseActivity', $activityID, 'case_id', 'activity_id');
    }

    require_once 'CRM/Utils/Recent.php';
    $url = CRM_Utils_System::url('civicrm/case/activity/view',
      "reset=1&aid={$activityID}&cid={$recentContactId}&caseID={$caseID}&context=home"
    );

    require_once 'CRM/Contact/BAO/Contact.php';
    $recentContactDisplay = CRM_Contact_BAO_Contact::displayName($recentContactId);
    // add the recently created Activity
    $activityTypes = CRM_Core_Pseudoconstant::activityType(TRUE, TRUE);

    $title = "";
    if (isset($activitySubject)) {
      $title = $activitySubject . ' - ';
    }

    $title = $title . $recentContactDisplay . ' (' . $activityTypes[$activityTypeID] . ')';

    require_once 'CRM/Case/BAO/Case.php';
    $recentOther = array();
    if (CRM_Case_BAO_Case::checkPermission($activityID, 'edit')) {
      $recentOther['editUrl'] = CRM_Utils_System::url('civicrm/case/activity',
        "reset=1&action=update&id={$activityID}&cid={$recentContactId}&caseid={$caseID}&context=home"
      );
    }
    if (CRM_Case_BAO_Case::checkPermission($activityID, 'delete')) {
      $recentOther['deleteUrl'] = CRM_Utils_System::url('civicrm/case/activity',
        "reset=1&action=delete&id={$activityID}&cid={$recentContactId}&caseid={$caseID}&context=home"
      );
    }

    CRM_Utils_Recent::add($title,
      $url,
      $activityID,
      'Activity',
      $recentContactId,
      $recentContactDisplay,
      $recentOther
    );
  }
}

