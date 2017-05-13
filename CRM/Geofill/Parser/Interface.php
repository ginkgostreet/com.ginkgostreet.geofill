<?php

interface CRM_Geofill_Parser_Interface {

  /**
   * Supplies the class with the payload from the geocoding request as well as
   * any other data needed to do its work (e.g., determine whether or not the
   * request succeeded in the first place).
   *
   * This is essentially a deferred constructor. It is deferred because the
   * ParserFactory doesn't (need to) know enough about any geocoding service to
   * be opinionated about data formats, success/failure indicators, etc. All of
   * that logic should be encapsulated in the parser class.
   *
   * @param array $data
   * @return void
   *   We can't actually enforce return values, but this method is not expected
   *   to return.
   */
  public function loadResult(array $data);

  /**
   * Returns a boolean indicating whether or not the request was successful.
   *
   * @return boolean
   *   We can't actually enforce return values, but this method is expected to
   *   return a boolean.
   */
  public function requestSuccessful();

  /*
   * The following methods return data formatted for the relevant database field.
   * For example, get_city returns a string, and get_country_id returns a numeric
   * ID.
   *
   * The methods break CiviCRM's camelCase naming convention to facilitate
   * dynamic generation of method names based on the database field names. Sorry,
   * not sorry.
   */

  public function get_city();

  public function get_country_id();

  public function get_county_id();

  public function get_postal_code();

  public function get_postal_code_suffix();

  public function get_state_province_id();

  public function get_street_name();

  public function get_street_number();

  public function get_street_unit();
}
