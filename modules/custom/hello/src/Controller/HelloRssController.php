<?php
/**
 * @file
 * Contains \Drupal\hello\Controller\HelloRssController.
 */

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
//use Symfony\Component\HttpFoundation\AcceptHeader;

class HelloRssController extends ControllerBase {

  public function content() {


    //$response->headers->set('Content-Type', 'application/rss+xml');
    //$response->headers->set('Content-Type', 'application/json');
    //$response->setContent(json_encode(array(1, 2, 3)));

    $response = new JsonResponse(array('tata', 'toto'));


    return $response;
  }
}
