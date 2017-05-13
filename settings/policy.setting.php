<?php

return array(
  'geofill_field_policy' => array(
    'group_name' => 'Geodata Filler',
    'group' => 'com.ginkgostreet.geofill',
    'name' => 'geofill_field_policy',
    'type' => 'Array',
    'default' => array(
      'city' => CRM_Geofill_Utils::POLICY_FILL,
      'county_id' => CRM_Geofill_Utils::POLICY_FILL,
      'country_id' => CRM_Geofill_Utils::POLICY_FILL,
      'postal_code' => CRM_Geofill_Utils::POLICY_FILL,
      'postal_code_suffix' => CRM_Geofill_Utils::POLICY_FILL,
      'state_province_id' => CRM_Geofill_Utils::POLICY_FILL,
      'street_name' => CRM_Geofill_Utils::POLICY_FILL,
      'street_number' => CRM_Geofill_Utils::POLICY_FILL,
      'street_unit' => CRM_Geofill_Utils::POLICY_FILL,
    ),
    'is_domain' => 1,
    'is_contact' => 0,
    'help_text' => ts("Set a policy for each address field for handling additional data returned from a geocoding service. Data can be discarded (CiviCRM's default behavior), used to fill in missing data, or used to overwrite existing data.", array('domain' => 'com.ginkgostreet.geofill')),
  ),
);
