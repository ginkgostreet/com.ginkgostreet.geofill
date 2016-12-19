CRM.$(function($) {
  var settingsTable = $('form.CRM_Admin_Form_Setting_Mapping table.form-layout-compressed');
  settingsTable.after('<fieldset class="geofill_policy"><legend></legend></fieldset>');

  var fieldset = $('fieldset.geofill_policy');
  var legend = fieldset.find('legend');

  var sectionHeader = $('#geofill_policy h1');
  legend.text(sectionHeader.text());
  sectionHeader.remove();

  fieldset.append($('#geofill_policy .help'));
  fieldset.append($('#geofill_policy table'));
});