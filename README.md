# Geodata Filler (com.ginkgostreet.geofill)
When CiviCRM geocodes an address, the geocoding service generally returns a full
collection of address fields, including street, city, etc. CiviCRM, however,
typically uses only the latitude and longitude fields and discards everything else.

This extension allows site administrators to set a policy for what to do with that information on a
field-by-field basis: discard it, fill in missing data, or overwrite existing data.
By default, this extension preserves the core behavior of discarding the additional data.

## Dependencies
- This extension requires CiviCRM version 4.7.7 or greater, due to its reliance upon
  [hook_civicrm_geocoderFormat](https://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_geocoderFormat),
  which was introduced in that version.
- To get anything out of this extension, you must use a [supported geocoding service](CRM/Geofill/Parser)
  or [implement your own](#extensibility).

## Configuration
Settings are managed in CiviCRM by visiting *Administer > System Settings > Geodata Filler.* A link is also provided
from the core *Mapping and Geocoding* settings screen.

## Extensibility
This extension does not support every geocoding service that has been integrated with CiviCRM. For a list of currently
supported services, have a look at [CRM/Geofill/Parser](CRM/Geofill/Parser).

Adding another service is a two-step process:

### Implement `hook_civicrm_geofill_parser()`
This extension introduces a new hook `hook_civicrm_geofill_parser`, which allows a developer to register a
geodata-parsing class for the geocoding service of her choosing.

#### Definition
`hook_civicrm_geofill_parser(array &$registry)`

#### Parameters
- array $registry - A reference to the registry of parser class names, keyed by $geoProvider name as passed
  to `hook_civicrm_geocoderFormat`.

#### Return
- void

#### Example
Geodata Filler [eats its own dog food](https://github.com/ginkgostreet/com.ginkgostreet.geofill/blob/d14789e84a84367906100302dc78507e780a76a3/geofill.php#L149-L151).

### Write a Parser Class
It must implement [CRM_Geofill_Parser_Interface](CRM/Geofill/Parser/Interface.php).

## Thanks for Stopping By
Feedback, [bug reports](https://github.com/ginkgostreet/com.ginkgostreet.geofill/issues), and pull requests are welcome.
