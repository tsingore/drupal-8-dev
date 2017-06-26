<?php

namespace Drupal\boum\Form;

use Drupal\Core\Database\Connection;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\reusable_forms\Form\ReusableFormBase;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Class Like.
 *
 * @package Drupal\boum\Form
 */
class BoumForm extends ReusableFormBase {

  protected $database;

  public function __construct(Connection $database) {
    $this->database = $database;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'boum_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $nid = $this->entity->id();
    $number = $this->database->select('boum_node', 'n')
              ->condition('nid', $nid)
              ->countQuery()
              ->execute()
              ->fetchField();

    $form['boum'] = array(
      '#type' => 'markup',
      '#markup' => $number . ' like(s)',
    );

    if(!$this->currentUser()->isAnonymous()){
      $user_likes_number = $this->database->select('boum_node', 'n')
      ->condition('nid', $nid)
      ->condition('uid', $this->currentUser()->id())
      ->countQuery()
      ->execute()
      ->fetchField();

      if(!$user_likes_number){
        $form['boum'] = array(
          '#type' => 'checkbox',
          '#title' => $this->t('I like it !'),
          '#required' => TRUE
        );
      }else{
        $form['user_message'] = array(
          '#type' => 'markup',
          '#markup' => 'You like it !',
        );
      }
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Adding a like
    /*Récupère numberoflikes dans la base de données*/

    if(!$this->currentUser()->isAnonymous()) {
      $nid = $this->entity->id();

      $this->database->merge('boum_node')
        ->key(array('nid' => $nid, 'uid' => $this->currentUser()->id()))
        ->fields(array('uid' => $nid, 'uid' => $this->currentUser()->id()))
        ->execute();
      }

      /*$nid = $this->entity->id();
      $this->database->insert('node_like')
          ->fileds(array('nid' => $nid, 'uid' => $this->currentUser()->id()))
          ->execute();
    */

  }

}
