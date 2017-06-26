<?php

namespace Drupal\hello\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;

/**
 * IMplements a Calculator form.
 */
class CalculatorForm extends FormBase {

  protected $state;

  /*public function __construct(StateInterface $state) {
    $this->state = $state;
  }*/

  /*public static function create(ContainerInterface $container) {
    return new static(
        $container->get('state')
      );
  }*/

  /**
    *{@inheritdoc}.
    */
  public function getFormId() {
    return 'calculator_form';
  }

  /**
   *
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    if(isset($form_state->getRebuildInfo()['result'])){
      $form['result'] = [
        '#markup' => '<h2>' . $this->t('Result : ') . $form_state->getRebuildInfo()['result'] . '</h2>'
      ];
    }

    $form['first_value'] = [
      '#type' => 'textfield',
      '#title' => $this->t('First value'),
      '#default_value' => $node->title,
      '#size' => '60',
      '#description' => t('Enter first value'),
      '#maxlength' => '128',
      '#required' => TRUE,
      /*'#ajax' => array(
                        'callback' => array($this, 'AjaxValidateNumeric'),
                        'event' => 'change',
                      ),
      '#suffix' => '<span class = "text_message"></spans>',*/
    ];

    $form['operator'] = [
      '#type' => 'radios',
      '#title' => $this->t('Operation'),
      '#default_value' => 1,
      '#options' => array(0 => $this->t('Add'), 1 => $this->t('Substract'), 2 => $this->t('Multiply'), '3' => $this->t('Divide')),
      '#description' => $this->t('Choose the operation for processing'),
    ];

    $form['second_value'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Second value'),
      '#default_value' => $node->title,
      '#size' => '60',
      '#description' => t('Enter second value'),
      '#maxlength' => '128',
      '#required' => TRUE,
      /*'#ajax' => array(
                        'callback' => array($this, 'AjaxValidateNumeric'),
                        'event' => 'change' ,
                      ),
      '#suffix' => '<span class = "text_message"></spans>',*/
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Calculate'),
    ];

    return $form;
  }

  public function AjaxValidateNumeric(array &$form, FormStateInterface $form_state) {

        $response = new AjaxResponse();

        $field = $form_state->getTriggeringElement()['#name'];
        $css = ['border' => '2px solid green'];
        $message = $this->t('Ok');

        $second_value = $form_state->getValue('second_value');
        $operator = $form_state->getValue('operator');

        if(!is_numeric($first_value) || !is_numeric($second_value)){
            $css = ['border' => '2px solid red'];
            $message = $this->t('%field must be numeric !', array('%field' => $form[$field]['#title']));
        }

        $response->addCommand(new CssCommand('#edit-first-value', $css));
        $response->addCommand(new HtmlCommand('.text_message', $message));

         return $response;
    }

  public function validateForm(array &$form, FormStateInterface $form_state) {
          $first_value = $form_state->getValue('first_value');
          $second_value = $form_state->getValue('second_value');
          $operator = $form_state->getValue('operator');

          if(!is_numeric($first_value) || !is_numeric($second_value)){
              $form_state->setErrorByName('field', t('values must be numerics !'));
          }
          if($operator == 3 && $second_value == 0){
              $form_state->setErrorByName('field', t('second value cannot be 0 !'));
          }
      }

  /*
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $firstValue = $form_state->getValue('first_value');
    $secondValue = $form_state->getValue('second_value');
    $operator = $form_state->getValue('operator');
    $result = 0;

    switch($operator) {
        case 0:
          $result = $firstValue + $secondValue;
          break;
        case 1:
          $result = $firstValue - $secondValue;
          break;
        case 2:
          $result = $firstValue * $secondValue;
          break;
        case 3:
          $result = $firstValue / $secondValue;
          break;
    }

    $form_state->addRebuildInfo('result', $result);
    $form_state->setRebuild();

    \Drupal::service('state')->set('hello_form_submission_time', REQUEST_TIME);

    //\Drupal::state()->set('hello_form_submission_time', REQUEST_TIME);

    //$this->state->set('hello_form_submission_time', REQUEST_TIME);
  }
}
