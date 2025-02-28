{*
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
*}
{*Javascript function controls showing and hiding of form elements based on html type.*}
{literal}
<script type="text/Javascript">
function custom_option_html_type( ) {
    var html_type_name = document.getElementsByName("data_type[1]")[0].value;
    var data_type_id   = document.getElementsByName("data_type[0]")[0].value;

    if ( !html_type_name && !data_type_id ) {
        return;
    }

    if ( data_type_id < 4 ) {
        if ( html_type_name != "Text" ) {
            cj("#showoption").show();
            cj("#hideDefault").hide();
            cj("#hideDesc").hide();
            cj("#searchByRange").hide();
            cj("#searchable").show();
        } else {
            cj("#showoption").hide();
            cj("#hideDefault").show();
            cj("#hideDesc").show();
            cj("#searchable").show();
        }
    } else {
        if ( data_type_id == 9 ) { 
            document.getElementById("default_value").value = '';
            cj("#hideDefault").hide();
            cj("#searchable").hide();
            cj("#hideDesc").hide();
        } else if ( data_type_id == 11 ) {
            cj("#hideDefault").hide();
        } else {
            cj("#hideDefault").show();
            cj("#searchable").show();
            cj("#hideDesc").show();
        }
        cj("#showoption").hide();
    }

    var radioOption, checkBoxOption;

    for ( var i=1; i<=11; i++) {
        radioOption = 'radio'+i;
        checkBoxOption = 'checkbox'+i	
        if ( data_type_id < 4 ) {
            if ( html_type_name != "Text") {
                if ( html_type_name == "CheckBox" || html_type_name == "Multi-Select" || html_type_name == "AdvMulti-Select") {
                    cj("#"+checkBoxOption).show();
                    cj("#"+radioOption).hide();
                } else {
                    cj("#"+radioOption).show();
                    cj("#"+checkBoxOption).hide();
                }
            }
        }
    }

    if ( data_type_id < 4 ) {	
        if (html_type_name == "CheckBox" || html_type_name == "Radio") {
            cj("#optionsPerLine").show();
        } else {
            cj("#optionsPerLine").hide();
        }
    }

    if ( data_type_id == 5) {
        cj("#startDateRange").show();
        cj("#endDateRange").show();
        cj("#includedDatePart").show();
    } else {
        cj("#startDateRange").hide();
        cj("#endDateRange").hide();
        cj("#includedDatePart").hide();
    }

    if ( data_type_id == 0 ) {
        cj("#textLength").show();
    } else {
        cj("#textLength").hide();
    }

    if ( data_type_id == 4 ) {
        cj("#noteColumns").show();
        cj("#noteRows").show();
    } else {
        cj("#noteColumns").hide();
        cj("#noteRows").hide();
    }

    if ( data_type_id > 3) {
        cj("#optionsPerLine").hide();
    }

    {/literal}{if $form.is_external_membership_id}{literal}
    if( html_type_name == 'Text'){
        cj('.crm-custom-field-form-block-is_external_membership_id').show();
    }else{
        cj('.crm-custom-field-form-block-is_external_membership_id').hide();
    }
    {/literal}{/if}{literal}

    {/literal}{if $action eq 1}{literal}
    clearSearchBoxes( );
    {/literal}{/if}{literal}
}
</script>
{/literal}
<div class="crm-block crm-form-block crm-custom-field-form-block">
<div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl" location="top"}</div>
    <table class="form-layout">
        <tr class="crm-custom-field-form-block-label">
            <td class="label">{$form.label.label}
            {if $action == 2}
                {include file='CRM/Core/I18n/Dialog.tpl' table='civicrm_custom_field' field='label' id=$id}
            {/if}
            </td>
            <td class="html-adjust">{$form.label.html}</td>
        </tr>
        <tr class="crm-custom-field-form-block-data_type">
            <td class="label">{$form.data_type.label}</td>
            <td class="html-adjust">{$form.data_type.html}
                {if $action neq 4 and $action neq 2}
                    <br /><span class="description">{ts}Select the type of data you want to collect and store for this contact. Then select from the available HTML input field types (choices are based on the type of data being collected).{/ts}</span>
                {/if}
            </td>
        </tr>
        <tr class="crm-custom-field-form-block-text_length"  id="textLength" {if !( $action eq 1 || $action eq 2 ) && ($form.data_type.value.0.0 != 0)}class="hide-block"{/if}>
            <td class="label">{$form.text_length.label}</td>
            <td class="html-adjust">{$form.text_length.html}</td> 
        </tr>

        {if $form.is_external_membership_id}
        <tr class="crm-custom-field-form-block-is_external_membership_id">
            <td class="label">{$form.is_external_membership_id.label}</td>
            <td class="html-adjust">{$form.is_external_membership_id.html}
            {if $action neq 4}
                <span class="description">
                    {ts}Is this field used for corresponding membership data when importing? The external membership ID field only can apply to one custom field at one time.{/ts}<br/>
                    {if $current_external_membership_id_field_title}
                        {capture assign=group_url}{crmURL p='civicrm/admin/custom/group/field' q="reset=1&action=browse&gid=`$current_external_membership_id_group_id`" h=0 a=1 fe=1}{/capture}
                        {capture assign=group_link}<a href="{$group_url}" target="_blank">{$current_external_membership_id_group_title}: {$current_external_membership_id_field_title}</a>{/capture}
                        {ts}If you enable this, the origin external membership ID field setting will be replaced.{/ts}<br/>{ts 1=$group_link}Current external membership ID field is %1{/ts}
                    {/if}
                </span>
            {/if}
            </td>
        </tr>
        {/if}
        
        <tr id='showoption' {if $action eq 1 or $action eq 2 }class="hide-block"{/if}>
            <td colspan="2">
            <table class="form-layout-compressed">
                {* Conditionally show table for setting up selection options - for field types = radio, checkbox or select *}
                {include file="CRM/Custom/Form/Optionfields.tpl"}
            </table>
            </td>
        </tr>
        {if $form.parent}
        <tr>
            <td class="label">{$form.parent.label}</td>
            <td class="html-adjust">{$form.parent.html}</td>
        </tr>
        {/if}
        <tr  class="crm-custom-field-form-block-options_per_line" id="optionsPerLine" {if $action neq 2 && ($form.data_type.value.0.0 >= 4 && $form.data_type.value.1.0 neq 'CheckBox' || $form.data_type.value.1.0 neq 'Radio' )}class="hide-block"{/if}>
            <td class="label">{$form.options_per_line.label}</td>	
            <td class="html-adjust">{$form.options_per_line.html|crmReplace:class:two}</td>
        </tr>
	    <tr  class="crm-custom-field-form-block-start_date_years" id="startDateRange" {if $action neq 2 && ($form.data_type.value.0.0 != 5)}class="hide-block"{/if}>
            <td class="label">{$form.start_date_years.label}</td>
            <td class="html-adjust">{$form.start_date_years.html} {ts}years prior to current date.{/ts}</td> 
        </tr>
        <tr class="crm-custom-field-form-block-end_date_years" id="endDateRange" {if $action neq 2 && ($form.data_type.value.0.0 != 5)}class="hide-block"{/if}>
            <td class="label">{$form.end_date_years.label}</td>
            <td class="html-adjust">{$form.end_date_years.html} {ts}years after the current date.{/ts}</td> 
        </tr>
        <tr  class="crm-custom-field-form-block-date_format"  id="includedDatePart" {if $action neq 2 && ($form.data_type.value.0.0 != 5)}class="hide-block"{/if}>
            <td class="label">{$form.date_format.label}</td>
            <td class="html-adjust">{$form.date_format.html}&nbsp;&nbsp;&nbsp;{$form.time_format.label}&nbsp;&nbsp;{$form.time_format.html}</td> 
        </tr>
        <tr  class="crm-custom-field-form-block-note_rows"  id="noteRows" {if $action neq 2 && ($form.data_type.value.0.0 != 4)}class="hide-block"{/if}>
            <td class="label">{$form.note_rows.label}</td>
            <td class="html-adjust">{$form.note_rows.html}</td> 
        </tr>
	    <tr class="crm-custom-field-form-block-note_columns" id="noteColumns" {if $action neq 2 && ($form.data_type.value.0.0 != 4)}class="hide-block"{/if}>
            <td class="label">{$form.note_columns.label}</td>
            <td class="html-adjust">{$form.note_columns.html}</td>
        </tr>
        <tr class="crm-custom-field-form-block-weight" >
            <td class="label">{$form.weight.label}</td>
            <td>{$form.weight.html|crmReplace:class:two}
                {if $action neq 4}
                <span class="description">{ts}Weight controls the order in which fields are displayed in a group. Enter a positive integer - lower numbers are displayed ahead of higher numbers.{/ts}</span>
                {/if}
            </td>
        </tr>
        <tr class="crm-custom-field-form-block-default_value" id="hideDefault" {if $action eq 2 && ($form.data_type.value.0.0 < 4 && $form.data_type.value.1.0 NEQ 'Text')}class="hide-block"{/if}>
            <td title="hideDefaultValTxt" class="label">{$form.default_value.label}</td>
            <td title="hideDefaultValDef" class="html-adjust">{$form.default_value.html}</td>
        </tr>
        <tr  class="crm-custom-field-form-block-description"  id="hideDesc" {if $action neq 4 && $action eq 2 && ($form.data_type.value.0.0 < 4 && $form.data_type.value.1.0 NEQ 'Text')}class="hide-block"{/if}>
            <td title="hideDescTxt" class="label">&nbsp;</td>
            <td title="hideDescDef" class="html-adjust"><span class="description">{ts}If you want to provide a default value for this field, enter it here. For date fields, format is YYYY-MM-DD.{/ts}</span></td>
        </tr>
        <tr class="crm-custom-field-form-block-help_pre">
            <td class="label">{$form.help_pre.label} {if $action == 2}{include file='CRM/Core/I18n/Dialog.tpl' table='civicrm_custom_field' field='help_pre' id=$id}{/if}</td>
            <td class="html-adjust">{$form.help_pre.html|crmReplace:class:huge}</td>
        </tr>
        <tr class="crm-custom-field-form-block-help_post">
            <td class="label">{$form.help_post.label} {if $action == 2}{include file='CRM/Core/I18n/Dialog.tpl' table='civicrm_custom_field' field='help_post' id=$id}{/if}</td>
            <td class="html-adjust">{$form.help_post.html|crmReplace:class:huge}
                {if $action neq 4}
                    <span class="description">{ts}Explanatory text displayed for this field. Pre help is displayed inline on the form (above the field). Post help is displayed in a pop-up - users click the help balloon to view help text.{/ts}</span>
                {/if}
            </td>
        </tr>
        <tr id ="searchable" class="crm-custom-field-form-block-is_searchable">
            <td class="label">{$form.is_searchable.label}</td>
            <td class="html-adjust">{$form.is_searchable.html}
                {if $action neq 4}
                    <br /><span class="description">{ts}Can you search on this field in the Advanced and component search forms? NOTE: This feature is available to custom fields used for <strong>Contacts (individuals, organizations and househoulds), Contributions, Pledges, Memberships, Event Participants, Activities, and Relationships</strong>.{/ts}</span>
                {/if}        
            </td>
        </tr>
        <tr id="searchByRange" class="crm-custom-field-form-block-is_search_range">
	    <td class="label">{$form.is_search_range.label}</td>
            <td class="html-adjust">{$form.is_search_range.html}</td>
        </tr>
        <tr class="crm-custom-field-form-block-is_active">
            <td class="label">{$form.is_active.label}</td>
            <td class="html-adjust">{$form.is_active.html}</td>
        </tr>    
        <tr>
        <td colspan=2>
            <div class="crm-accordion-wrapper crm-accordion_title-accordion crm-accordion-closed">
            <div class="crm-accordion-header">
            <div class="zmdi crm-accordion-pointer"></div>
            {ts}Advanced options{/ts}
            </div><!-- /.crm-accordion-header -->
            <div class="crm-accordion-body">
                <table>
                    <tr class="crm-custom-field-form-block-is_required">
                        <td class="label">{$form.is_required.label}</td>
                        <td class="html-adjust">{$form.is_required.html}
                        {if $action neq 4}
                        <span class="description">{ts}When 'Required' is active, it is necessary to fill value on contact add/edit page. If you need this feature to visitor, please enable it in profiles setting.{/ts}</span>
                        {/if}</td>
                    </tr>
                    <tr class="crm-custom-field-form-block-is_view">
                        <td class="label">{$form.is_view.label}</td>
                        <td class="html-adjust">{$form.is_view.html}
                            <span class="description">{ts}Is this field set by PHP code (via a custom hook). This field will not be updated by CiviCRM.{/ts}</span>
                        </td>
                    </tr>
                </table>
            </div><!-- /.crm-accordion-body -->
          </div><!-- /.crm-accordion-wrapper -->
        </td></tr>
    </table>
   	    {if $action ne 4}
	       <div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl" location="bottom"}</div>
	    {else}
	       <div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl" location="bottom"}</div>
	    {/if} {* $action ne view *}
    </div> 
{literal}
<script type="text/javascript">
    //when page is reload, build show hide boxes
    //as per data type and html type selected.
    custom_option_html_type( );

    function showSearchRange(chkbox) {
        var html_type = document.getElementsByName("data_type[1]")[0].value;
      	var data_type = document.getElementsByName("data_type[0]")[0].value;

        if ( ((data_type == 1 || data_type == 2 || data_type == 3) && (html_type == "Text")) || data_type == 5) {
            if (chkbox.checked) {
              document.getElementsByName("is_search_range")[0].checked = true;
              cj("#searchByRange").show();
            }
            else {
              clearSearchBoxes( );
              document.getElementsByName("is_searchable")[0].checked   = false; 
              document.getElementsByName("is_search_range")[1].checked = true;
            }
        }
    }
      
    //should not clear search boxes for update mode. 
    function clearSearchBoxes( ) {
      document.getElementsByName("is_search_range")[1].checked = true;
      cj("#searchByRange").hide();
    }
</script>
{/literal}
{* Give link to view/edit choice options if in edit mode and html_type is one of the multiple choice types *}
{if $action eq 2 AND ($form.data_type.value.1.0 eq 'CheckBox' OR ($form.data_type.value.1.0 eq 'Radio' AND $form.data_type.value.0.0 neq 6) OR $form.data_type.value.1.0 eq 'Select' OR ($form.data_type.value.1.0 eq 'Multi-Select' AND $dontShowLink neq 1 ) ) }
    <div class="action-link-button">
        <a href="{crmURL p="civicrm/admin/custom/group/field/option" q="reset=1&action=browse&fid=`$id`&gid=`$gid`"}">&raquo; {ts}View / Edit Multiple Choice Options{/ts}</a>
    </div>
{/if}
{literal}
<script type="text/javascript">
cj().crmaccordions();
</script>
{/literal}

{include file="CRM/common/sidePanel.tpl" type="iframe" src="https://neticrm.tw/CRMDOC/Data+and+Input+Field+Type+-+Alphanumeric:+Text" triggerText="Description of Data and Input Field Type" triggerIcon="zmdi-help-outline" width="400px"}
  {literal}
<script type="text/Javascript">
  cj(function() {
    if (cj(".nsp-container").length) {
      cj(".nsp-container:not(.visually-hidden)").addClass("visually-hidden");

      let childType = cj("select#data_type\\[1\\]").val(),
          parentTypeIndex = cj("select#data_type\\[0\\]").val(),
          parentTypeMapping = [
            "Alphanumeric",
            "Integer",
            "Number",
            "Money",
            "Note",
            "Date",
            "Yes or No",
            "State/Province",
            "Country",
            "File",
            "Link",
            "Contact Reference"
          ],
          parentType = parentTypeMapping[parentTypeIndex],
          defaultDocURL = "https://neticrm.tw/CRMDOC/Data and Input Field Type - Alphanumeric: Text";

      let sidePanelShow = function() {
        cj(".nsp-container.visually-hidden").removeClass("visually-hidden");

        if (!cj(".nsp-container.is-initialized.is-opened").length) {
          window.neticrmSidePanelInstance.open();
        }
      }

      let sidePanelHide = function() {
        cj(".nsp-container:not(.visually-hidden)").addClass("visually-hidden");

        if (cj(".nsp-container.is-initialized.is-opened").length) {
          window.neticrmSidePanelInstance.close();
        }
      }

      let setDocURL = function(parentType , childType) {
        if (parentType.indexOf("/") != -1) {
          parentType = parentType.replaceAll(/\//g, "");
        }

        let docURL = "https://neticrm.tw/CRMDOC/Data and Input Field Type - " + parentType + ": " + childType;

        if (defaultDocURL !== docURL) {
          cj(".nsp-container .nsp-iframe").attr("src", docURL);
        }
      }

      let trigger = "select#data_type\\[0\\], select#data_type\\[1\\]";

      setTimeout(function() {
        setDocURL(parentType, childType);
      }, 2000);


      cj(".crm-container").on("focus", trigger, function() {
        sidePanelShow();
      });

      cj(".crm-container").on("blur", trigger, function() {
        sidePanelHide();
      });

      cj(".crm-container").on("change", "select#data_type\\[0\\]", function() {
        childType = cj("select#data_type\\[1\\]").val();
        parentTypeIndex = cj(this).val();
        parentType = parentTypeMapping[parentTypeIndex];
        setDocURL(parentType, childType);
      });

      cj(".crm-container").on("change", "select#data_type\\[1\\]", function() {
        childType = cj(this).val();
        parentTypeIndex = cj("select#data_type\\[0\\]").val();
        parentType = parentTypeMapping[parentTypeIndex];
        setDocURL(parentType, childType);
      });
    }
  });
</script>
{/literal}