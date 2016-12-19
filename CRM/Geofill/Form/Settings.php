<?php

/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_Geofill_Form_Settings extends CRM_Core_Form {

  private $settingMetaData;
  private $settingName = 'geofill_field_policy';
  private $settingValue;

  private function getSettingMetaData() {
    if (!$this->settingMetaData) {
      $result = civicrm_api3('setting', 'getfields', array(
        'filters' => array('group' => 'com.ginkgostreet.geofill'),
      ));
      $this->settingMetaData = $result['values'][$this->settingName];
    }
    return $this->settingMetaData;
  }

  private function getSettingValue() {
    if (!$this->settingValue) {
      $this->settingValue = Civi::settings()->get($this->settingName);
    }
    return $this->settingValue;
  }

  public function setDefaultValues() {
    $defaults = array();
    foreach ($this->getSettingValue() as $key => $value) {
      $fieldName = $this->smartifyFieldName($key);
      $defaults[$fieldName] = $value;
    }
    return $defaults;
  }

  /**
   * Smarty is a little funny when it comes to field names with brackets. On the
   * form class side, an array of bracketed field names is treated as an array
   * of strings (e.g., in $this->setDefaultValues). On the template side an
   * array of bracketed field names is treated as an array of arrays. This array:
   *
   *   array(
   *     'my[setting]',
   *     'my[stuff]',
   *   );
   *
   * is interpeted in the template file as:
   *
   *   array(
   *     'my' => array(
   *       'setting',
   *       'stuff',
   *     );
   *   );
   *
   * This is a small helper method to assist with this issue.
   *
   * @param string $fieldName
   * @return string
   */
  public function smartifyFieldName($fieldName) {
    return "{$this->settingName}[{$fieldName}]";
  }

  public function buildQuickForm() {
    $fieldNames = array_keys($this->getSettingValue());
    foreach ($fieldNames as $fieldName) {
      $label = CRM_Geofill_Utils::nameToLabel($fieldName);
      $this->addRadio($this->smartifyFieldName($fieldName), $label, $this->getOptions(), array(), NULL, TRUE);
    }

    // See comment for $this->smartifyFieldName().
    $this->assign('settingName', $this->settingName);
    $this->assign('geofillPolicyFieldNames', $fieldNames);

    $settingMetaData = $this->getSettingMetaData();
    $this->assign('helpText', $settingMetaData['help_text']);

    $this->addButtons(array(
      array(
        'type' => 'submit',
        'name' => ts('Submit'),
        'isDefault' => TRUE,
      ),
    ));
  }

  public function postProcess() {
    $submission = $this->exportValues();
    array_walk($submission['geofill_field_policy'], function (&$item) {
      // consistency is good; since the defaults are integers, we stick with that
      $item = (int) $item;
    });

    Civi::settings()->set('geofill_field_policy', $submission['geofill_field_policy']);
  }

  public function getOptions() {
    return array(
      CRM_Geofill_Utils::POLICY_IGNORE => ts('Discard', array('domain' => 'com.ginkgostreet.geofill')),
      CRM_Geofill_Utils::POLICY_FILL => ts('Fill', array('domain' => 'com.ginkgostreet.geofill')),
      CRM_Geofill_Utils::POLICY_OVERWRITE => ts('Overwrite', array('domain' => 'com.ginkgostreet.geofill')),
    );
  }

}
