<?php

namespace Drupal\hello\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a hello block.
 *
 * @Block(
 *  id = "hello_block",
 *  admin_label = @Translation("Hello!")
 * )
 */
class HelloBlock extends BlockBase implements ContainerFactoryPluginInterface {

  var $currentUser;

  public function __construct(array $configuration, $plugin_id, $plugin_definition,DateFormatterInterface $dateFormatter, AccountProxyInterface $currentUser) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->dateFormatter = $dateFormatter;
    $this->currentUser = $currentUser;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('date.formatter'),
      $container->get('current_user')
    );
  }


  /**
   * Implements Drupal\Core\Block\BlockBase::build().
   */
  public function build() {
    //$dateformatter = \Drupal::service('date.formatter'); Without injection of dependencies
    //$date = $dateformatter->format(time(), 'long');
    $date = $this->dateFormatter->format(REQUEST_TIME, 'custom', 'H:i s\s'); //(time(), 'medium');
    $message =  $this->t('Welcome %name on our website. It is %date.', array('%name' => $this->currentUser->getAccountName(),'%date' => $date ));
    $build = array ('#markup' =>$message,
                    '#cache' => array(
                    'max-age' => '3600',
                    'keys' => ['hello_block'],
                    'contexts' => ['timezone', 'user'],
                    //Avoiding sharing cache issue
                    ),
              );

    return $build;
  }
}
