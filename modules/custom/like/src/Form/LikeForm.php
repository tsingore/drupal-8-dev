<?php

namespace Drupal\like\Form;

use Drupal\Core\Database\Connection;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\reusable_forms\Form\ReusableFormBase;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Class Like.
 *
 * @package Drupal\like\Form
 */
class LikeForm extends ReusableFormBase {

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
    return 'like_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $nid = $this->entity->id();
    $number = $this->database->select('like_node', 'n')
              ->condition('nid', $nid)
              ->countQuery()
              ->execute()
              ->fetchField();

    $form['like'] = array(
      '#type' => 'markup',
      '#markup' => $like_number . ' like(s)',
    );

    if(!$this->currentUser()->isAnonymous()){
      $form['like'] = array(
        '#type' => 'checkbox',
        '#title' => t('Like node'),
        '#description' => t('Like the node'),
      );
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Adding a like
    /*Récupère numberoflikes dans la base de données

    if(!$this->currentUser()->isAnonymous()) {
      $nid = $this->entity->id();

      $this->database->merge('like_node')
        ->key(array('nid' => $nid, 'uid' => $this->currentUser()->id()))
        ->fields(array('uid' => $nid, 'uid' => $this->currentUser()->id()))
        ->execute();
      }

      $nid = $this->entity->id();
      $this->database->insert('node_like')
          ->fileds(array('nid' => $nid, 'uid' => $this->currentUser()->id()))
          ->execute();
    */

  }

}
