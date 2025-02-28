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
<div class="crm-block crm-form-block crm-miscellaneous-form-block">
  <div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl" location="top"}</div>
{if $admin}
<fieldset>
    <table class="form-layout">
        <tr class="crm-miscellaneous-form-block-dashboardCacheTimeout">
            <td class="label">{$form.dashboardCacheTimeout.label}</td>
            <td>{$form.dashboardCacheTimeout.html}<br />
                <span class="description">{ts}The number of minutes to cache dashlet content on dashboard.{/ts}</span></td>
        </tr>
    </table>
</fieldset>
{/if}

<fieldset>
    <table class="form-layout">
        <tr class="crm-miscellaneous-form-block-doNotAttachPDFReceipt">
            <td class="label">{$form.doNotAttachPDFReceipt.label}</td>
            <td>{$form.doNotAttachPDFReceipt.html}<br />
                <p class="description">{ts}If enabled, PDF receipt wont't be an attachment during event signup or online contribution.{/ts}</p>
            </td>
        </tr>
     {if $admin}
        <tr class="crm-miscellaneous-form-block-logging">
          <td class="label">{$form.logging.label}</td>
          <td>
            {$form.logging.html}<br />
            <p class="description">{ts}If enabled, all actions performed on non-cache tables will be logged (in the respective log_* tables).{/ts}</p>
            <p class="description">{ts}(This functionality currenly cannot be enabled on multilingual installations.){/ts}</p>
          </td>
        </tr>
        <tr class="crm-miscellaneous-form-block-wkhtmltopdfPath">
            <td class="label">{$form.wkhtmltopdfPath.label}</td>
            <td>{$form.wkhtmltopdfPath.html}<br />
                <p class="description">{ts}If wkhtmltopdf is installed, CiviCRM will use it to generate PDF form letters.{/ts}</p>
            </td>
        </tr>
        <tr class="crm-miscellaneous-form-block-versionCheck">
            <td class="label">{$form.versionCheck.label}</td>
            <td>{$form.versionCheck.html}<br />
                <p class="description">{ts}If enabled, CiviCRM automatically checks availablity of a newer version of the software. New version alerts will be displayed on the main CiviCRM Administration page.{/ts}</p>
                <p class="description">{ts}When enabled, statistics about your CiviCRM installation are reported anonymously to the CiviCRM team to assist in prioritizing ongoing development efforts. The following information is gathered: CiviCRM version, versions of PHP, MySQL and framework (Drupal/Joomla/standalone), and default language. Counts (but no actual data) of the following record types are reported: contacts, activities, cases, relationships, contributions, contribution pages, contribution products, contribution widgets, discounts, price sets, profiles, events, participants, tell-a-friend pages, grants, mailings, memberships, membership blocks, pledges, pledge blocks and active payment processor types.{/ts}</p></td>
        </tr>
        <tr class="crm-miscellaneous-form-block-maxAttachments">
            <td class="label">{$form.maxAttachments.label}</td>
            <td>{$form.maxAttachments.html}<br />
                <span class="description">{ts}Maximum number of files (documents, images, etc.) which can attached to emails or activities.{/ts}</span></td>
        </tr>
        <tr class="crm-miscellaneous-form-block-maxFileSize">
            <td class="label">{$form.maxFileSize.label} (in MB)</td>
            <td>{$form.maxFileSize.html}<br />
                <span class="description">{ts}Maximum Size of file (documents, images, etc.) which can attached to emails or activities.<br />Note: php.ini should support this file size.{/ts}</span></td>
        </tr>
        <tr>
            <td class="label">{$form.docURLBase.label}</td>
            <td>
              {$form.docURLBase.html}<br />
              <span class="description">{ts}With trailing slash.{/ts}</span>
            </td>
        </tr>
      {/if}
    </table>
</fieldset>
<fieldset><legend>{ts}reCAPTCHA Keys{/ts}</legend>
    <div class="description">
        {ts}reCAPTCHA is a free service that helps prevent automated abuse of your site. To use reCAPTCHA on public-facing CiviCRM forms: sign up at <a href="http://recaptcha.net">recaptcha.net</a>; enter the provided public and private reCAPTCHA keys here; then enable reCAPTCHA under Advanced Settings in any Profile.{/ts}
    </div>
    <table class="form-layout">
        <tr class="crm-miscellaneous-form-block-recaptchaPublicKey">
            <td class="label">{$form.recaptchaPublicKey.label}</td>
            <td>{$form.recaptchaPublicKey.html}</td>
        </tr>
        <tr class="crm-miscellaneous-form-block-recaptchaPrivateKey">
            <td class="label">{$form.recaptchaPrivateKey.label}</td>
            <td>{$form.recaptchaPrivateKey.html}</td>
        </tr>
        </table>
           <div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl" location="bottom"}</div>
    </fieldset>
</div>
