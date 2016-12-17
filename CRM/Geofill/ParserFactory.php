<?php

class CRM_Geofill_ParserFactory {

  /**
   * Factory method for instantiating a geodata parser.
   *
   * @param string $name
   *   Name of the parser class or of the $geoProvider as passed to hook_civicrm_geocoderFormat.
   * @return object
   *   Implements CRM_Geofill_Parser_Interface
   * @throws CRM_Core_Exception
   */
  public static function create($name) {
    $class = NULL;
    if (class_exists($name)) {
      $class = $name;
    }
    else {
      $class = self::providerToParserClass($name);
    }

    if (!$class) {
      throw new CRM_Core_Exception('No parser class found for geoProvider', 'geofill_no_parser_class', array('geoProvider' => $name));
    }

    $parser = new $class;
    $interfaces = class_implements($parser);
    if (!in_array('CRM_Geofill_Parser_Interface', $interfaces)) {
      throw new CRM_Core_Exception('Parser class does not implement required interface', 'geofill_parser_class_interface', array('parser_class' => $class));
    }
    return $parser;
  }

  /**
   * Translates provider short names to parser class names.
   *
   * @param string $provider
   *   The $geoProvider as passed to hook_civicrm_geocoderFormat.
   * @return string|NULL
   *   The name of the parser class, or NULL if none has been declared for this
   *   provider.
   */
  private static function providerToParserClass($provider) {
    $registry = array();
    CRM_Geofill_Hook::parser($registry);
    return CRM_Utils_Array::value($provider, $registry);
  }

}
