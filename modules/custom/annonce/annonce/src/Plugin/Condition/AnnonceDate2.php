<?php

namespace Drupal\annonce\Plugin\Condition;

use Drupal\Core\Condition\ConditionPluginBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\Context\ContextDefinition;

/**
* Provides a 'Date condition' condition to enable a condition based in module selected status.
*
* @Condition(
*   id = "annonce_date2",
*   label = @Translation("Date condition"),
* )
*
*/
class AnnonceDate2 extends ConditionPluginBase {

/**
* {@inheritdoc}
*/
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
  {
      return new static(
      $configuration,
      $plugin_id,
      $plugin_definition
      );
  }

/**
 * Creates a new AnnonceDate2 object.
 *
 * @param array $configuration
 *   The plugin configuration, i.e. an array with configuration values keyed
 *   by configuration option name. The special key 'context' may be used to
 *   initialize the defined contexts by setting it to an array of context
 *   values keyed by context names.
 * @param string $plugin_id
 *   The plugin_id for the plugin instance.
 * @param mixed $plugin_definition
 *   The plugin implementation definition.
 */
 public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    //$this->condition = $plugin_factory->createInstance('annonce_date2');
 }

 /**
   * {@inheritdoc}
   */
 public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
   //$config = $this->config('path_message.settings');
   //$this->condition->setConfiguration($config->get('annonce_date2'));


   $form = parent::buildConfigurationForm($form, $form_state);
   /*$form['phone_number'] = array(
     '#type' => 'tel',
     '#title' => t('Telephone'),
     '#required' => TRUE,
   );*/
     // Sort all modules by their names.
    /*$modules = system_rebuild_module_data();
     uasort($modules, 'system_sort_modules_by_info_name');

     $options = [NULL => t('Select a module')];
     foreach($modules as $module_id => $module) {
         $options[$module_id] = $module->info['name'];
     }*/
     $form['beginning_date'] = [
         '#type' => 'date',
         '#title' => $this->t('Select a start date'),
         '#default_value' => $this->configuration['beginning_date'],
         '#description' => $this->t('Date of beginning of display'),
     ];

     $form['ending_date'] = [
         '#type' => 'date',
         '#title' => $this->t('Select an end date'),
         '#default_value' => $this->configuration['ending_date'],
         '#description' => $this->t('Day of end of display'),
     ];

      $form['negate']['#access'] = FALSE;
      //$form += $this->condition->buildConfigurationForm($form, $form_state);

     return $form;
 }

/**
 * {@inheritdoc}
 */
 public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
     $this->configuration['beginning_date'] = $form_state->getValue('beginning_date');
     $this->configuration['ending_date'] = $form_state->getValue('ending_date');
     parent::submitConfigurationForm($form, $form_state);
 }

/**
 * {@inheritdoc}
 */
 public function defaultConfiguration() {
    return ['module' => ''] + parent::defaultConfiguration();
 }

/**
  * Evaluates the condition and returns TRUE or FALSE accordingly.
  *
  * @return bool
  *   TRUE if the condition has been met, FALSE otherwise.
  */
  public function evaluate() {
    $dateStart = strtotime($this->configuration['beginning_date']);
    $dateEnd = strtotime($this->configuration['ending_date']);
    if (empty($dateStart) && empty($dateEnd)) {
        return TRUE;
    }

    if (!empty($dateStart) && empty($dateEnd)) {
      if($dateStart <= REQUEST_TIME) {
        return TRUE;
      }
      else return FALSE;
    }

    if (empty($dateStart) && !empty($dateEnd)) {
      if($dateEnd >= REQUEST_TIME) {
        return TRUE;
      }
    }

    if (!empty($dateStart) && !empty($dateEnd)) {
      if($dateEnd <= REQUEST_TIME && $dateEnd >= REQUEST_TIME) {
        return TRUE;
      }
    }

    else return FALSE;
  }

/**
 * Provides a human readable summary of the condition's configuration.
 */
 public function summary()
 {
     $module = $this->getContextValue('module');
     $modules = system_rebuild_module_data();

     $status = ($modules[$module]->status)?t('enabled'):t('disabled');

     return t('The module @module is @status.', ['@module' => $module, '@status' => $status]);
 }

}
