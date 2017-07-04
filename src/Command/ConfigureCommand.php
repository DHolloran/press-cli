<?php

namespace PressCLI\Command;

use PressCLI\Configure\LocalConfiguration;
use PressCLI\Configure\GlobalConfiguration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigureCommand extends Command
{
    protected function configure()
    {
        $this->setName('configure')
             ->setDescription('Creates a new project directory and configuration.')
             ->addArgument('project-name', InputArgument::OPTIONAL, 'The project name.')
             ->addOption(
                 'global',
                 '-g',
                 InputOption::VALUE_NONE,
                 'Creates the global configuration if it does not already exist.'
             )
             ->addOption(
                 'path',
                 '-p',
                 InputOption::VALUE_REQUIRED,
                 'Creates the global configuration if it does not already exist.'
             )
             ->addOption(
                 'force',
                 '-f',
                 InputOption::VALUE_NONE,
                 'Force overwriting the configuration'
             );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('global')) {
            (new GlobalConfiguration($input, $output, $this))->write();
        } else {
            (new LocalConfiguration($input, $output, $this))->write();
        }
    }
}
