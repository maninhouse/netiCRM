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
<fieldset><legend>{ts}Upload CSV File{/ts}</legend>
  <table class="form-layout">
    <tr>
        <td class="label">{$form.uploadFile.label}</td>
        <td class="html-adjust with-help-link">{$form.uploadFile.html}
          <a class="help-link new-window-link" href="https://neticrm.tw/resources/2614" target="_blank">{ts}Imported tutorial and example file{/ts}</a>
          <div class="description">{ts}File format must be comma-separated-values (CSV). File must be UTF8 encoded if it contains special characters (e.g. accented letters, etc.).{/ts}</div>
        </td>
    </tr>
    <tr><td class="label"></td><td>{ts 1=$uploadSize}Maximum Upload File Size: %1 MB{/ts}</td></tr>
    <tr>
        <td></td>
        <td>{$form.skipColumnHeader.html} {$form.skipColumnHeader.label}
            <div class="description">{ts}Check this box if the first row of your file consists of field names (Example: 'First Name','Last Name','Email'){/ts}</div>
        </td>
    </tr>
  </table>
</fieldset>
