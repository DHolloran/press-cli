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
        // @todo Split sections into there own commands?
        // @todo Allow for disabling of certain sections via flag?
        // @todo Post Git pull commands?

        $output->writeln("<info>== Running Pre-install Commands =======================</info>");

        // @todo Run pre-install commands.

        $output->writeln("\n<info>== Installing WordPress =======================</info>");

        // Check/Download WordPress
        WP::coreDownload($output);

        // Check/Create wp-config.php
        WP::coreConfig($output);

        // Check/Create database
        WP::dbCreate();

        // Check/Run install
        WP::coreInstall($output);

        $output->writeln("\n<info>== Setting up Theme =======================</info>");

        // Delete default themes (Except latest)
        WP::themeDeleteDefaults();
        $output->writeln('');

        // Check/Download/Merge theme (Zip/Tar/Git/Other?)
        WP::themeInstall($output);

        // @todo Rewrite style.css. Will probably have to happen before activation in theme install.

        // @todo Install PHPUnit scaffold.

        $output->writeln("\n<info>== Running Post Theme Install Commands =======================</info>");

        // Run post install theme commands.
        // @todo Disable with flag?
        PostInstall::executeThemeCommands();

        $output->writeln("\n<info>== Setting up plugins =======================</info>");

        // Remove default plugins
        WP::pluginDeleteDefaults();
        $output->writeln('');

        // @todo License paid plugins
        // define( 'WPMDB_LICENCE', 'XXXXX' );
        // Possibly activate paid plugins separately after licensing.

        // Install wp.org plugins
        WP::pluginInstallAll($output);

        $output->writeln("\n<info>== Cleaning up default posts =======================</info>");

        // Remove default posts
        WP::postDeleteDefault();

        // @todo Create menus from theme menu locations?

        $output->writeln("\n<info>== Setting Permalink Structure =======================</info>");

        // Set rewrite rules
        WP::rewriteSetStructure();

        $output->writeln("\n<info>== Running Post Install Commands =======================</info>");

        // Run post install commands.
        // @todo Disable with flag?
        PostInstall::executeCommands();

        $output->writeln("\n<info>== Install Completed =======================</info>");
    }
}
