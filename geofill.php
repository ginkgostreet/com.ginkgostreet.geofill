<?php

require_once 'geofill.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function geofill_civicrm_config(&$config) {
  _geofill_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @param array $files
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function geofill_civicrm_xmlMenu(&$files) {
  _geofill_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function geofill_civicrm_install() {
  _geofill_civix_civicrm_install();
}

/**
* Implements hook_civicrm_postInstall().
*
* @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
*/
function geofill_civicrm_postInstall() {
  _geofill_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function geofill_civicrm_uninstall() {
  _geofill_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function geofill_civicrm_enable() {
  _geofill_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function geofill_civicrm_disable() {
  _geofill_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   Based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function geofill_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _geofill_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function geofill_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _geofill_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 */
function geofill_civicrm_navigationMenu(&$menu) {
  _geofill_civix_insert_navigation_menu($menu, 'Administer/System Settings', array(
    'label' => ts('Geodata Filler ', array('domain' => 'com.ginkgostreet.geofill')),
    'name' => 'admin_geofill',
    'url' => 'civicrm/admin/geofill',
    'permission' => 'administer CiviCRM',
    'operator' => 'NULL',
    'separator' => 0,
  ));
  _geofill_civix_navigationMenu($menu);
}

/**
 * Implements hook_civicrm_geocoderFormat().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_geocoderFormat
 */
function geofill_civicrm_geocoderFormat($geoProvider, &$values, $xml) {
  $addressComponents = CRM_Geofill_Utils::getWritableAddressComponents($values);
  if (!count($addressComponents)) {
    return;
  }

  try {
    $parser = CRM_Geofill_ParserFactory::create($geoProvider);
  }
  catch (CRM_Core_Exception $e) {
    Civi::log()->error($e->getMessage(), $e->getErrorData());
  }

  $parser->loadResult(array('xml' => $xml));
  if (!$parser->requestSuccessful()) {
    return;
  }

  foreach ($addressComponents as $field) {
    $method = 'get_' . $field;
    $fetchedValue = $parser->$method();
    if ($fetchedValue) {
      $values[$field] = $fetchedValue;
    }
  }
}

/**
 * Implements hook_civicrm_geofill_parser.
 *
 * @param array $registry
 */
function geofill_civicrm_geofill_parser(&$registry) {
  $registry['Google'] = 'CRM_Geofill_Parser_Google';
}

/**
 * Implements hook_civicrm_buildForm().
 *
 * Links to extension settings from the core mapping/geocoding settings screen.
 * Also redirects users there after submitting the core form.
 *
 * @link https://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_buildForm
 */
function geofill_civicrm_buildForm($formName, CRM_Core_Form &$form) {
  if ($formName !== 'CRM_Admin_Form_Setting_Mapping') {
    return;
  }

  $url = CRM_Utils_System::url('civicrm/admin/geofill');
  $form->controller->setDestination($url);

  $message = ts("%1Geodata Filler (com.ginkgostreet.geofill)%2 is installed on this system, providing additional configurations for your geocoding services. To access them, you may either save your changes to this form (you will be redirected to the extension settings screen afterward), or %3go there directly%4, abandoning any changes you've made to this form.", array(1 => '<strong>', 2 => '</strong>', 3 => '<a href="' . $url . '">', 4 => '</a>', 'domain' => 'com.ginkgostreet.geofill'));
  CRM_Core_Region::instance('page-body')->add(array(
    'markup' => '<p class="help">' . $message . '</p>',
    'weight' => -10,
  ));
}
