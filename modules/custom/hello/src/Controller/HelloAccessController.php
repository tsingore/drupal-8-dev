<?php
/**
 * @file
 * Contains \Drupal\hello\Controller\HelloAccessController.
 */

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;

class HelloAccessController extends ControllerBase {

  public function content() {
    $message = $this->t('Hello Access');
    return array('#markup' => $message,
                );


  }
}
