<?php

/**
 * @file
 * Contains \Drupal\hello\Form\HelloBlockColorConfigForm.
 */
namespace Drupal\hello\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements an hello admin form.
 */
class HelloBlockColorConfigForm extends ConfigFormBase {

  protected $entityTypeManager;

  /**
   * {@inheritdoc}.
   */
  public function getFormId() {
    return 'hello_block_color_config_form';
  }

  /**
   * {@inheritdoc}.
   */
  protected function getEditableConfigNames() {
    return ['hello.config'] ;
  }

  /**
   * {@inheritdoc}.
   */
   public function buildForm(array $form, FormStateInterface $form_state) {

     $color = $this->config('hello.config')->get('color');

     $form['color'] = [
       '#type' => 'select',
       '#title' => $this->t('Choose a color'),
       '#options' => [
         '' => $this->t('No color'),
         'yellow-class' => $this->t('Green'),
         'red-class' => $this->t('Red'),
         'blue-class' => $this->t('Blue'),
       ],
       '#default_value' => $color,
     ];

     return parent::buildForm($form, $form_state);
   }

   /**
    * {@inheritdoc}.
    */
   public function submitForm(array &$form, FormStateInterface $form_state) {
     $this->config('hello.config')
     ->set('color', $form_state->getValue('color'))
     ->save();

     \Drupal::service('entity_type.manager')->getViewBuilder('block')->resetCache();

     parent::submitForm($form, $form_state);
   }
}
