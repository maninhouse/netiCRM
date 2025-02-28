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
{if $paid} {* We retrieve this tpl when event is selected - keep it empty if event is not paid *} 
    <table class="form-layout">
    {if $priceSet}
    	{if $action eq 2 and $hasPayment} {* Updating *}
        {if $lineItem}
          <tr class="crm-event-eventfees-form-block-line_items">
            <td class="label">{ts}Event Fees{/ts}</td>
            <td>{include file="CRM/Price/Page/LineItem.tpl" context="Event"}</td>
          </tr>
        {else}
          <tr class="crm-event-eventfees-form-block-event_level">
            <td class="label">{ts}Event Level{/ts}</td>
            <td>{$fee_level}&nbsp;{if $fee_amount}- {$fee_amount|crmMoney:$fee_currency}{/if}</td>
          </tr>
        {/if}
      {else} {* New participant *}
        <tr class="crm-event-eventfees-form-block-price_set_amount">
          <td class="label" style="padding-top: 10px;">{$form.amount.label}</td>
          <td class="view-value"><table class="form-layout">{include file="CRM/Price/Form/PriceSet.tpl"}</table></td>
        </tr>
      {/if}
    {else} {* NOT Price Set *}
     <tr>
     <td class ='html-adjust' colspan=2>
     	<table class="form-layout" style="width: auto;">
        {if $discount and $hasPayment}
            <tr class="crm-event-eventfees-form-block-discount"><td class="label">&nbsp;&nbsp;{ts}Discount Set{/ts}</td><td class="view-value">{$discount}</td></tr>
        {elseif $form.discount_id.label}
            <tr class="crm-event-eventfees-form-block-discount_id"><td class="label">&nbsp;&nbsp;{$form.discount_id.label}</td><td>{$form.discount_id.html}</td></tr>
        {/if}
        {if $action EQ 2 and $hasPayment and $onlinePayment}
            <tr class="crm-event-eventfees-form-block-fee_level"><td class="label">&nbsp;&nbsp;{ts}Event Level{/ts}</td><td class="view-value"><span class="bold">{$fee_level}&nbsp;{if $fee_amount}- {$fee_amount|crmMoney:$fee_currency}{/if}</span></td></tr>
        {else}
            <tr class="crm-event-eventfees-form-block-fee_amount"><td class="label">{ts}Event Level{/ts}</td><td>{$form.amount.html}
            {if $hasPayment && !$onlinePendingContributionId}
              <div class="description has-payment-notice font-red" style="display:none">{ts}This participant have completed or cancelled contribution record. You need to update related contribution belone this participant manually.{/ts}</div>
              <script>{literal}
                cj(document).ready(function($){
                  $("input[name=amount]").click(function(){
                    $(".has-payment-notice").show();
                  });
                });
              {/literal}</script>
            {/if}
        {/if}
        {if $action EQ 1}
            <br />&nbsp;&nbsp;<span class="description">{ts}Event Fee Level (if applicable).{/ts}</span>
        {/if}
        </td></tr>
        {if $coupon.coupon_track_id}
          <tr class="crm-event-eventfees-form-block-coupon">
            <td class="label">{$form.coupon.label}</td>
            <td>{$form.coupon.html} - {$coupon.description}</td>
          </tr>
        {/if}
     	</table>
     </td>
     </tr>
    {/if}

    { if $accessContribution and ! $participantMode and ($action neq 2 or !$rows.0.contribution_id or $onlinePendingContributionId) }
        <tr class="crm-event-eventfees-form-block-record_contribution">
            <td class="label">{$form.record_contribution.label}</td>
            <td>{$form.record_contribution.html}<br />
                <span class="description">{ts}Check this box to enter payment information. You will also be able to generate a customized receipt.{/ts}</span>
            </td>
        </tr>
        <tr id="payment_information" class="crm-event-eventfees-form-block-payment_information">
           <td class ='html-adjust' colspan=2>
           <fieldset><legend>{ts}Payment Information{/ts}</legend>
             <table id="recordContribution" class="form-layout" style="width:auto;">
                <tr class="crm-event-eventfees-form-block-contribution_type_id">
                    <td class="label">{$form.contribution_type_id.label}<span class="marker"> *</span></td>
                    <td>{$form.contribution_type_id.html}<br /><span class="description">{ts}Select the appropriate contribution type for this payment.{/ts}</span></td>
                </tr>
                <tr class="crm-event-eventfees-form-block-total_amount"><td class="label">{$form.total_amount.label}</td><td>{$form.total_amount.html|crmMoney:$currency}({ts}Original{/ts}: {$original_total_amount})<br/><span class="description">{ts}Actual payment amount for this registration.{/ts}</span></td></tr>
                <tr>
                    <td class="label" >{$form.receive_date.label}</td>
                    <td>{include file="CRM/common/jcalendar.tpl" elementName=receive_date}</td>
                </tr>
                <tr class="crm-event-eventfees-form-block-payment_instrument_id"><td class="label">{$form.payment_instrument_id.label}</td><td>{$form.payment_instrument_id.html}</td></tr>
                <tr id="checkNumber" class="crm-event-eventfees-form-block-check_number"><td class="label">{$form.check_number.label}</td><td>{$form.check_number.html|crmReplace:class:six}</td></tr>
                {if $showTransactionId }
                    <tr class="crm-event-eventfees-form-block-trxn_id"><td class="label">{$form.trxn_id.label}</td><td>{$form.trxn_id.html}</td></tr>	
                {/if}
                <tr class="crm-event-eventfees-form-block-contribution_status_id"><td class="label">{$form.contribution_status_id.label}</td><td>{$form.contribution_status_id.html}</td></tr>      
             </table>
           </fieldset>
           </td>
        </tr>

        {* Record contribution field only present if we are NOT in submit credit card mode (! participantMode). *}
        {include file="CRM/common/showHideByFieldValue.tpl"
            trigger_field_id    ="record_contribution"
            trigger_value       =""
            target_element_id   ="payment_information"
            target_element_type ="table-row"
            field_type          ="radio"
            invert              = 0
        }
    {/if}
    </table>{* paid table *}
{/if}

{* credit card block when it is live or test mode*}
{if $participantMode and $paid}
  <div class="spacer"></div>
  {include file='CRM/Core/BillingBlock.tpl'}
{/if}
{if ($email OR $batchEmail) and $outBound_option != 2}
    <fieldset id="send_confirmation_receipt"><legend>{ts}Registration Confirmation{/ts}</legend>
      <table class="form-layout" style="width:auto;">
		 <tr class="crm-event-eventfees-form-block-send_receipt"> 
            <td class="label">{ts}Send Confirmation{/ts}</td>
            <td>{$form.send_receipt.html}<br>
                <span class="description">{ts 1=$email}Automatically email a confirmation to %1?{/ts}</span></td>
        </tr>
	<tr id="from-email" class="crm-event-eventfees-form-block-from_email_address">
            <td class="label">{$form.from_email_address.label}</td>
            <td>{$form.from_email_address.html} {help id ="id-from_email" file="CRM/Contact/Form/Task/Email.hlp"}</td>
    	</tr>
        <tr id='notice' class="crm-event-eventfees-form-block-receipt_text">
 			<td class="label">{$form.receipt_text.label}</td>
            <td><span class="description">
                {ts}Enter a message you want included at the beginning of the confirmation email. EXAMPLE: 'Thanks for registering for this event.'{/ts}
                </span><br />
                {$form.receipt_text.html|crmReplace:class:huge}
            </td>
        </tr>
      </table>
    </fieldset>
{elseif $context eq 'standalone' and $outBound_option != 2 }
    <fieldset id="email-receipt" style="display:none;"><legend>{ts}Registration Confirmation{/ts}</legend>
      <table class="form-layout" style="width:auto;">
    	 <tr class="crm-event-eventfees-form-block-send_receipt"> 
            <td class="label">{ts}Send Confirmation{/ts}</td>
            <td>{$form.send_receipt.html}<br>
                <span class="description">{ts 1='<span id="email-address"></span>'}Automatically email a confirmation to %1?{/ts}</span>
            </td>
        </tr>
	<tr id="from-email" class="crm-event-eventfees-form-block-from_email_address">
            <td class="label">{$form.from_email_address.label}</td>
            <td>{$form.from_email_address.html} {help id ="id-from_email" file="CRM/Contact/Form/Task/Email.hlp"}</td>
    	</tr>
        <tr id='notice' class="crm-event-eventfees-form-block-receipt_text">
    		<td class="label">{$form.receipt_text.label}</td>
            <td><span class="description">
                {ts}Enter a message you want included at the beginning of the confirmation email. EXAMPLE: 'Thanks for registering for this event.'{/ts}
                </span><br />
                {$form.receipt_text.html|crmReplace:class:huge}</td>
        </tr>
      </table>
    </fieldset>
{/if}

{if ($email and $outBound_option != 2) OR $context eq 'standalone' } {* Send receipt field only present if contact has a valid email address. *}
{include file="CRM/common/showHideByFieldValue.tpl"
    trigger_field_id    ="send_receipt"
    trigger_value       =""
    target_element_id   ="notice"
    target_element_type ="table-row"
    field_type          ="radio"
    invert              = 0
}
{include file="CRM/common/showHideByFieldValue.tpl"
    trigger_field_id    ="send_receipt"
    trigger_value       =""
    target_element_id   ="from-email"
    target_element_type ="table-row"
    field_type          ="radio"
    invert              = 0
}
{/if}

{if $paid and ($action eq 1 or ( $action eq 2 and !$hasPayment) ) and !$participantMode}
{include file="CRM/common/showHideByFieldValue.tpl"
    trigger_field_id    ="payment_instrument_id"
    trigger_value       = '4'
    target_element_id   ="checkNumber"
    target_element_type ="table-row"
    field_type          ="select"
    invert              = 0
}
{/if}

{if $context eq 'standalone' and $outBound_option != 2 }
<script type="text/javascript">
{literal}
cj( function( ) {
    setInterval(function(){
      if (window.contact_select_id != cj("input[name='contact_select_id[1]']").val()) {
        window.contact_select_id = cj("input[name='contact_select_id[1]']").val();
        if (window.contact_select_id) {
          checkEmail( );
        }
      }
    }, 1000);
    checkEmail( );
});
function checkEmail( ) {
    var contactID = window.contact_select_id;
    if ( contactID ) {
        var postUrl = "{/literal}{crmURL p='civicrm/ajax/getemail' h=0}{literal}";
        cj.post( postUrl, {contact_id: contactID},
            function ( response ) {
                if ( response ) {
                    cj("#email-receipt").show( );
                    if ( cj("#send_receipt").is(':checked') ) {
                        cj("#notice").show( );
                    }

                    cj("#email-address").html( response );
                } else {
                    cj("#send_receipt").prop("checked", false);
                    cj("#email-receipt").hide( );
                    cj("#notice").hide( );
                }
            }
        );
    }
}
{/literal}
</script>
{/if}

{if $onlinePendingContributionId}
<script type="text/javascript">
{literal}
  function confirmStatus( pStatusId, cStatusId ) {
     if ( (pStatusId == cj("#status_id").val() ) && (cStatusId == cj("#contribution_status_id").val()) ) {
         var allow = confirm( '{/literal}{ts}The Payment Status for this participant is Completed. The Participant Status is set to Pending from pay later. Click Cancel if you want to review or modify these values before saving this record{/ts}{literal}.' );
         if ( !allow ) return false;
     }
  }

  function checkCancelled( statusId, pStatusId, cStatusId ) {
    //selected participant status is 'cancelled'
    if ( statusId == pStatusId ) {
       cj("#contribution_status_id").val( cStatusId );

       //unset value for send receipt check box.
       cj("#send_receipt").attr( "checked", false );
       cj("#send_confirmation_receipt").hide( );

       // set receive data to null.
       document.getElementById("receive_date[M]").value = null;
       document.getElementById("receive_date[d]").value = null;
       document.getElementById("receive_date[Y]").value = null;
    } else {
       cj("#send_confirmation_receipt").show( );
    }
    sendNotification();
  }

{/literal}
</script>
{/if}
<script>{literal}
function fillTotalAmount( totalAmount ) {
  if ( !totalAmount ) {
    {/literal}{if $eventFeeBlockValues}{literal}
    var eventFeeBlockValues = {/literal}{$eventFeeBlockValues}{literal};
    if (cj("#feeBlock").length) {
      totalAmount = eval('eventFeeBlockValues.amount_id_' + cj("#feeBlock").find("[name=amount]:checked").val());
    }
    if (!totalAmount) {
      totalAmount = eval('eventFeeBlockValues.amount_id_'+{/literal}{$form.amount.value}{literal});
    }
    {/literal}{/if}{literal}
	}
  if (totalAmount) {
    cj('#total_amount').val( totalAmount );
  }
}
cj('#record_contribution').click(function(){
  fillTotalAmount();
});
{/literal}
{if $showFeeBlock && $feeBlockPaid && !$priceSet && $action neq 2}
  fillTotalAmount();
{/if}
</script>

{* ADD mode if *}
