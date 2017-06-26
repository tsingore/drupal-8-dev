<?php
/**
 * @file
 * Contains \Drupal\hello\Controller\HelloNodeListController.
 */

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;

class HelloNodeListController extends ControllerBase {

  public function content($nodetype) {

    $timestart = microtime() ;
    $memstart = memory_get_usage();



    $query = $this->entityTypeManager()->getStorage('node')->getQuery();
    if($nodetype){
      $query->condition('type', $nodetype);
    }
    $nids = $query->pager('10')->execute();

    $nodes = $this->entityTypeManager()->getStorage('node')->loadMultiple($nids);

    $items = array();
    foreach($nodes as $node){
      $items[] = $node->toLink();
    }

    $list = array(
        '#theme' => 'item_list',
        '#items' => $items,
    );

    $pager = array(
      '#type' => 'pager',
    );

    $timeend = microtime() ;
    $memend = memory_get_usage();

    $perftime = $timeend - $timestart;
    $perfmem = $memend - $memstart;
    kint($perftime);
    kint($perfmem);



    return array(
      'list' => $list,
      'pager' => $pager
    );



  }
}
