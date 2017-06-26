<?php
/**
 * @file
 * Contains \Drupal\hello\Controller\HelloController.
 */

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;

class HelloController extends ControllerBase {

  public function content($param) {
    //$userName = $param ; /* $this->currentUser()->getAccountName(); */
    $message = $this->t('Your on the Hello page. Your username is  %name ! URL parameter is %param',  array('%name' => $this->currentUser()->getAccountName(), '%param' => $param));
    return array('#markup' => $message,
                  '#cache' => array(
                  'keys' => ['hello_page'],
                  'contexts' => ['user', 'url.path'],
                  'tags' => ['user:'.$this->currentUser()->id()],
                  ),
                );
  }
}
