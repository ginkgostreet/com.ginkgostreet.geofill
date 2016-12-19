<div id="geofill_policy">
  <h1>{$sectionHeader}</h1>
  <div class="help">{$helpText}</div>
  <table class="form-layout-compressed">
    <tbody>
      {foreach from=$geofillPolicyFieldNames item=fieldName}
        <tr class="crm-{$fieldName}">
          <td>{$form.$fieldName.label}</td>
          <td>{$form.$fieldName.html}</td>
        </tr>
      {/foreach}
    <tbody>
  </table>
</div>