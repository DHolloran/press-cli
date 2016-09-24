<?php
namespace KindlingCLI\Command;

use KindlingCLI\WPCLI\WP;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InstallCommand extends Command
{
    protected function configure()
    {
         $this->setName('install')
              ->setDescription('Installs WordPress, required plugins, theme and dependencies');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Check/Download WordPress
        WP::coreDownload($output);

        // Check/Create wp-config.php
        WP::coreConfig($output);

        // Check/Create database
        WP::dbCreate();

        // Check/Run install
        WP::coreInstall($output);

        // Check/Download/Merge theme


        // Rewrite style.css


        // Activate Theme


        // Install wp.org plugins


        // Activate required plugins


        // Flush rewrite rules
    }
}
