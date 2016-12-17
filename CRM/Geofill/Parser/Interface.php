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

  /*
   * The following methods return parsed data as strings. Where possible,
   * abbreviations should be avoided (e.g., return 'Florida' instead of 'FL').
   * If abbreviations or short names are needed, retrieve them in differently
   * named methods.
   */

  public function getFullStreetAddress();

  public function getFullCity();

  public function getFullStateProvince();

  public function getFullPostalCode();

  public function getFullPostalCodeSuffix();

  public function getFullCountry();

  public function getFullCounty();
}
