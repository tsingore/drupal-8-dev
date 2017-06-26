<?php

namespace Drupal\annonce\EventSubscriber;

use Drupal\Core\Database\Connection;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class AnnonceEventDispatcher.
 *
 * @package Drupal\annonce
 */
class AnnonceEventDispatcher implements EventSubscriberInterface {

  /**
   * Drupal\Core\Session\AccountProxy definition.
   *
   * @var \Drupal\Core\Session\AccountProxy
   */
  protected $currentUser;
  protected $currentRouteMatch;
  protected $database;

  /**
   * Constructs a new AnnonceEventDispatcher object.
   */
  public function __construct(AccountProxyInterface $current_user, RouteMatchInterface $currentRouteMatch, Connection $database) {
    $this->currentUser = $current_user;
    $this->currentRouteMatch = $currentRouteMatch;
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['called'];

    return $events;
  }


  /**
   * This method is called whenever the kernel.request event is
   * dispatched.
   *
   * @param GetResponseEvent $event
   */
  public function called($event) {
    if($this->currentRouteMatch->getParameter('annonce')) {
      $this->database->insert('annonce_history')
                     ->fields(
                       array(
                         'aid' => $this->currentRouteMatch->getParameter('annonce')->id(),
                         'uid' => $this->currentUser->id(),
                         'date' => REQUEST_TIME,
                         ))->execute();
    }
  }

}
