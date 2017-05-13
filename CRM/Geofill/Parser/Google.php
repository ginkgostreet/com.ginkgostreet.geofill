<?php

class CRM_Geofill_Parser_Google implements CRM_Geofill_Parser_Interface {

  private $addressComponents = array();
  private $componentPropertyMap = array(
    // Google component name => address property
    'administrative_area_level_1' => 'state_province',
    'administrative_area_level_2' => 'county',
    'country' => 'country',
    'locality' => 'city',
    'postal_code' => 'postal_code',
    'postal_code_suffix' => 'postal_code_suffix',
    'route' => 'street_name',
    'street_number' => 'street_number',
    'subpremise' => 'street_unit',
  );
  private $requestSuccess;

  public function loadResult(array $data) {
    $result = CRM_Utils_Array::value('xml', $data);
    $this->requestSuccess = (isset($result->status) && (string) $result->status === 'OK');
    $this->parseAddressComponents($result);
  }

  /**
   * Assigns address components from the geocoding payload to the relevant
   * properties on this object.
   *
   * NOTE: There are parseable address components this method doesn't presently
   * handle (e.g., street number) which may be of interest to other developers.
   *
   * @param SimpleXMLElement $geodata
   */
  private function parseAddressComponents(SimpleXMLElement $geodata) {
    if (isset($geodata->result->address_component) && is_a($geodata->result->address_component, 'SimpleXMLElement')) {
      foreach ($geodata->result->address_component as $component) {
        $property = $this->getAddressProperty($component->type);
        if ($property) {
          // TODO: is the short name always the best to use? should this be configurable?
          $this->addressComponents[$property] = (string) $component->short_name;
        }
      }
    }
  }

  /**
   * Helper method to map Google component types to CiviCRM address properties.
   *
   * @param SimpleXMLElement $componentType
   * @return string|NULL
   *   Returns NULL if the component type doesn't map to a property.
   */
  private function getAddressProperty(SimpleXMLElement $componentType) {
    // There are more types than we care about. For instance, many address
    // components are tagged as "political." We care only about tags that
    // help us identity which part of an address we have.
    foreach ($componentType as $type) {
      $type = (string) $type;
      $fieldName = CRM_Utils_Array::value($type, $this->componentPropertyMap);
      if ($fieldName) {
        break;
      }
    }
    return $fieldName;
  }

  public function requestSuccessful() {
    return $this->requestSuccess;
  }

  public function get_city() {
    return CRM_Utils_Array::value('city', $this->addressComponents);
  }

  public function get_county_id() {
    $countyId = CRM_Utils_Array::value('county_id', $this->addressComponents);
    if (!$countyId) {
      $countyName = CRM_Utils_Array::value('county', $this->addressComponents);
      $stateProvinceId = $this->get_state_province_id();
      if ($countyName && $stateProvinceId) {
        $stateCounties = CRM_Core_PseudoConstant::countyForState($stateProvinceId);

        // first try with the provided county name
        $countyId = CRM_Utils_Array::key($countyName, $stateCounties);

        // failing that, try with a sanitized county name
        if (!$countyId) {
          $cruft = array('County', 'County of');
          $countyName = trim(str_replace($cruft, '', $countyName));
          $countyId = CRM_Utils_Array::key($countyName, $stateCounties);
        }

        if ($countyId) {
          $this->addressComponents['county_id'] = $countyId;
        }
      }
    }
    return $countyId;
  }

  public function get_country_id() {
    $countryId = CRM_Utils_Array::value('country_id', $this->addressComponents);
    if (!$countryId) {
      $countryAbbr = CRM_Utils_Array::value('country', $this->addressComponents);
      if ($countryAbbr) {
        $countryId = CRM_Core_PseudoConstant::getKey('CRM_Core_BAO_Address', 'country_id', $countryAbbr);
      }

      if ($countryId) {
        $this->addressComponents['country_id'] = $countryId;
      }
    }
    return $countryId;
  }

  public function get_postal_code() {
    return CRM_Utils_Array::value('postal_code', $this->addressComponents);
  }

  public function get_postal_code_suffix() {
    return CRM_Utils_Array::value('postal_code_suffix', $this->addressComponents);
  }

  public function get_state_province_id() {
    $stateProvinceId = CRM_Utils_Array::value('state_province_id', $this->addressComponents);
    if (!$stateProvinceId) {
      $stateProvinceAbbr = CRM_Utils_Array::value('state_province', $this->addressComponents);
      $countryId = $this->get_country_id();
      if ($stateProvinceAbbr && $countryId) {
        $statesInCountry = civicrm_api3('address', 'getoptions', array(
          'context' => 'abbreviate',
          'country_id' => $countryId,
          'field' => 'state_province_id',
        ));
        $stateProvinceId = CRM_Utils_Array::key($stateProvinceAbbr, $statesInCountry['values']);
      }

      if ($stateProvinceId) {
        $this->addressComponents['state_province_id'] = $stateProvinceId;
      }
    }
    return $stateProvinceId;
  }

  public function get_street_name() {
    return CRM_Utils_Array::value('street_name', $this->addressComponents);
  }

  public function get_street_number() {
    return CRM_Utils_Array::value('street_number', $this->addressComponents);
  }

  public function get_street_unit() {
    return CRM_Utils_Array::value('street_unit', $this->addressComponents);
  }

}
