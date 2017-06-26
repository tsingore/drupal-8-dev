<?php

namespace Drupal\email_form\Form;

use Drupal\Core\Database\Connection;
use Drupal\Core\Form\FormStateInterface;
use Drupal\reusable_forms\Form\ReusableFormBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines the EmailForm class.
 */
class EmailForm extends ReusableFormBase {

  protected $database;

  /**
   * {@inheritdoc}.
   */
  public function __construct(Connection $database) {
    $this->database = $database;
  }

  /**
   * {@inheritdoc}.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database')
    );
  }

  /**
   * {@inheritdoc}.
   */
  public function getFormId() {
    return 'email_form';
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['email'] = array(
      '#type' => 'email',
      '#title' => $this->t('Your email'),
      '#required' => TRUE,
    );
    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Identifiant du noeud.
    $nid = $this->entity->id();
    $this->database->merge('email_form')
      ->key(array('nid' => $nid, 'email' => $form_state->getValue('email')))
      ->fields(array('nid' => $nid, 'email' => $form_state->getValue('email')))
      ->execute();
  }
}
