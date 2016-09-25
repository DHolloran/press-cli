<?php
namespace KindlingCLI\Command;

use KindlingCLI\WPCLI\WP;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use KindlingCLI\Command\PostInstall;

class InstallCommand extends Command
{
    protected function configure()
    {
         $this->setName('install')
              ->setDescription('Installs WordPress, required plugins, theme and dependencies');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Run pre-install commands.


        // Check/Download WordPress
        WP::coreDownload($output);

        // Check/Create wp-config.php
        WP::coreConfig($output);

        // Check/Create database
        WP::dbCreate();

        // Check/Run install
        WP::coreInstall($output);

        // Remove default themes (Except latest)
        WP::themeDeleteDefaults();

        // Check/Download/Merge theme (Zip/Tar/Git/Other?)


        // Rewrite style.css


        // Activate Theme


        // Remove default plugins
        WP::pluginDeleteDefaults();
        // License paid plugins
        // define( 'WPMDB_LICENCE', 'XXXXX' );
        // Possibly activate paid plugins separately after licensing.

        // Install wp.org plugins
        WP::pluginInstallAll();

        // Remove default posts
        WP::postDeleteDefault();

        // Create menus from theme menu locations?

        // Set rewrite rules
        WP::rewriteSetStructure();

        // Run post install commands.
        // PostInstall::executeCommands();
    }
}
