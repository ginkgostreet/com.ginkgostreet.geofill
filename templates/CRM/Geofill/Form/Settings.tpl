{* HEADER *}
<div class="help">{$helpText}</div>
<div class="crm-submit-buttons">
  {include file="CRM/common/formButtons.tpl" location="top"}
</div>

{foreach from=$geofillPolicyFieldNames item=fieldName}
  <div class="crm-section crm-{$fieldName}">
    <div class="label">{$form.$settingName.$fieldName.label}</div>
    <div class="content">{$form.$settingName.$fieldName.html}</div>
    <div class="clear"></div>
  </div>
{/foreach}

{* FOOTER *}
<div class="crm-submit-buttons">
  {include file="CRM/common/formButtons.tpl" location="bottom"}
</div>
