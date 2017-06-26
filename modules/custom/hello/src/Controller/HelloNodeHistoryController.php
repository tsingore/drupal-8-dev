<?php
/**
 * @file
 * Contains \Drupal\hello\Controller\HelloNodeHistoryController.
 */

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class HelloNodeHistoryController extends ControllerBase {

  protected $database;
  protected $dateFormatter;

  public function __construct(Connection $database, DateFormatterInterface $dateFormatter) {
    $this->database = $database;
    $this->dateFormatter = $dateFormatter;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
      $container->get('date.formatter')
    );
  }

  public function content(NodeInterface $node) {
    $query = $this->database->select('hello_node_history', 'hnh')
            ->fields('hnh', array('uid', 'update_time'))
            ->condition('nid', $node->id());
    //$result = $query->execute():

    $count = $query->countQuery()->execute()->fetchField();
    $message = array(
      '#theme' => 'hello_node_history',
      '#node' => $node,
      '#count' => $count,
    );

    $result = $query->extend('Drupal\Core\Database\Query\PagerSelectExtender')->limit('5')->execute();
    $rows = array();
    $userStorage = $this->entityTypeManager()->getStorage('user');
    foreach ($result as $record) {
      $rows[] = array(
        $userStorage->load($record->uid)->toLink(),
        $this->dateFormatter->format($record->update_time, 'medium'),
      );
    }

    $table = array(
      '#theme' => 'table',
      '#header' => array($this->t('Author'), $this->t('Update time')),
      '#rows' => $rows,
    );
    $pager = array( '#type' => 'pager');


    return array(
      'message' => $message,
      'table' => $table,
      'pager' => $pager,
    );
    /*return array(
      $table,
      $pager,
      '#cache' => array(
                    'keys'=> ['hello_node_history_pager:' . $node->id()],
                    'tags' => ['node:' . $node->id()],
                    'contexts' => ['url'],
                  ),
      );*/
  }
}
