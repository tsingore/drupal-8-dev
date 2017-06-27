<?php

namespace Drupal\config_split\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Drupal\Console\Annotations\DrupalCommand;

/**
 * Class ExportCommand.
 *
 * @package Drupal\config_split
 *
 * @DrupalCommand (
 *     extension="config_split",
 *     extensionType="module"
 * )
 */
class ExportCommand extends SplitCommandBase {

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this
      ->setName('config_split:export')
      ->setDescription($this->trans('commands.config_split.export.description'))
      ->addOption('split', NULL, InputOption::VALUE_OPTIONAL);
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $this->setupIo($input, $output);
    try {
      // Make the magic happen.
      $this->cliService->ioExport($input->getOption('split'), $this->getIo(), [$this, 't']);
    }
    catch (\Exception $e) {
      $this->getIo()->error($e->getMessage());
    }
  }

}
