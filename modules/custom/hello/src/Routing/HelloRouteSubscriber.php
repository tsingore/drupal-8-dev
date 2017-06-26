<?php

namespace Drupal\hello\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class HelloRouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {

    /*$routeListModule = $collection->get('system.modules_list');
    $routeUninstallModule = $collection->get('system.modules_uninstall');
    $routeListModule->setRequirement('_access', 'FALSE');
    $routeUninstallModule->setRequirement('_access', 'FALSE');*/
  }
}
