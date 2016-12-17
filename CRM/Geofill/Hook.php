<?php

class CRM_Geofill_Hook {

  /**
   * This hook is invoked after a CRM_Utils_Geocode request. It allows the
   * declaration of geodata parsers, one of which is loaded by this extension to
   * process the payload.
   *
   * @param array $registry
   *   Registry of parser class names keyed by $geoProvider names as passed to
   *   hook_civicrm_geocoderFormat.
   *
   * @return null
   *   The return value is ignored.
   */
  static function parser(array &$registry) {
    return CRM_Utils_Hook::singleton()->invoke(1, $registry, CRM_Utils_Hook::$_nullObject,
      CRM_Utils_Hook::$_nullObject, CRM_Utils_Hook::$_nullObject, CRM_Utils_Hook::$_nullObject, CRM_Utils_Hook::$_nullObject,
      'civicrm_geofill_parser'
    );
  }

}
