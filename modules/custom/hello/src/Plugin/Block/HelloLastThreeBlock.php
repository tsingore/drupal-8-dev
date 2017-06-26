<?php

namespace Drupal\hello\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;

/**
 * Provides an acive sessions number block.
 *
 * @Block(
 *  id = "hello_last_three",
 *  admin_label = @Translation("Hello Last Three Article")
 * )
 */
class HelloLastThreeBlock extends BlockBase {

  /**
   * Implements Drupal\Core\Block\BlockBase::build().
   */
  public function build() {
    /* Version bis */
    $current_node = \Drupal::service('current_route_match')->getParameter('node');
    if(is_a($current_node, 'Drupal\node\Entity\Node')) $current_node_type = $current_node->getType();
    $query = \Drupal::entityTypeManager()->getStorage('node')->getQuery();

    if(!empty($current_node_type)){
      $query->condition('type', $current_node_type);
    }
    else{
      $query->condition('type', 'article');
    }

    /* Version 1 */
    //$query = \Drupal::entityTypeManager()->getStorage('node')->getQuery();
    //$query->condition('type', 'article');
    $nids = $query->range(0,3)->sort('created', 'desc')->execute();

    $nodes =  \Drupal::entityTypeManager()->getStorage('node')->loadMultiple($nids);

    $items = array();
    foreach($nodes as $node){
      $items[] = $node->toLink();
    }

    $list = array(
        '#theme' => 'item_list',
        '#items' => $items,
        '#cache' => array(
          'max-age' => '1',
          'tag' => ['node_list'],
          'contexts' => ['url'],
        )
    );
    return $list;

  }
}
