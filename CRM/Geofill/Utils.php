<?php

class CRM_Geofill_Utils {

  const POLICY_IGNORE = 0;
  const POLICY_FILL = 1;
  const POLICY_OVERWRITE = 2;

  /**
   * Returns a list of address fields that should be updated based on the
   * configured policy.
   *
   * @param array $addressComponents
   *   The address that was passed to the geocoder.
   * @return array
   *   The fields for which updates are permitted.
   */
  public static function getWritableAddressComponents($addressComponents) {
    $policy = Civi::settings()->get('geofill_field_policy');

    $writable = array();
    foreach ($policy as $fieldName => $flag) {
      switch ($flag) {
        case self::POLICY_OVERWRITE:
          $writable[] = $fieldName;
          break;
        case self::POLICY_FILL:
          // For some reason, some empty address components are represented with
          // a string 'null.' Arguably, this is a core bug that should be
          // corrected rather than worked around.
          if (!CRM_Utils_Array::value($fieldName, $addressComponents) || CRM_Utils_Array::value($fieldName, $addressComponents) === 'null') {
            $writable[] = $fieldName;
          }
          break;
      }
    }

    return $writable;
  }

  /**
   * Converts machine names to labels for address fields.
   *
   * @param string $name
   *   Machine name
   * @return string
   *   Human-readable label
   */
  public static function nameToLabel($name) {
    try {
      $api = civicrm_api3('Address', 'getfield', array(
        'name' => $name,
        'action' => "create",
      ));
      $label = $api['values']['title'];
    }
    catch (CiviCRM_API3_Exception $e) {
      $label = $name;
    }
    return $label;
  }

}
