<?php

namespace PressCLI\Command;

use PressCLI\CLI;
use PressCLI\WPCLI\Install as WPInstall;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WPCLIInstallCommand extends Command
{
    protected function configure()
    {
        $this->setName('wp-cli:install')
             ->setDescription('Installs the wp-cli executable.')
             ->setHelp('Installs the wp-cli executable.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        (new WPInstall(new CLI($input, $output)))->execute();
    }
}
