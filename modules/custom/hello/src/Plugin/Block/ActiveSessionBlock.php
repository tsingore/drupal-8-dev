<?php

namespace Drupal\hello\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;

/**
 * Provides an acive sessions number block.
 *
 * @Block(
 *  id = "hello_count_block",
 *  admin_label = @Translation("Hello count")
 * )
 */
class HelloCountBlock extends BlockBase {
  /**
   * Implements Drupal\Core\Block\BlockBase::build().
   */
  public function build() {
    $database = \Drupal::database();

    $number = $database->select('sessions', 's')
                       ->countQuery()
                       ->execute()
                       ->fetchField();

    //$number = $result->fetchArray();

    $message =  $this->t('Session number: %number', array('%number' => $number));
    $build = array ('#markup' => $message,
                    '#cache' => array('max-age' => '100', 'keys' => ['hello:sessions'],),
                    );

    return $build;
  }
}
