<?php

namespace PressCLI\Command;

use PressCLI\CLI;
use PressCLI\WPCLI\WP;
use PressCLI\WPCLI\Update as WPUpdate;
use PressCLI\WPCLI\Install as WPInstall;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WPCLIUpdateCommand extends Command
{
    protected function configure()
    {
        $this->setName('wp-cli:update')
             ->setDescription('Updates the wp-cli executable.')
             ->setHelp('Updates the wp-cli executable.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $wp = new WP($cli = new CLI($input, $output));

        if ($wp->isInstalled()) {
            (new WPUpdate($cli))->execute();
        } else {
            (new WPInstall($cli))->execute();
        }
    }
}
