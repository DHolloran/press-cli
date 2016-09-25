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
        // @codingStandardsIgnoreStart
        // @todo Check for .kindling.yaml before executing and throw error if not found.
        // @todo Split sections into there own commands? That way you can install via `$ kindling install:all` or `$kindling install:section`
        // @todo Allow for disabling of certain sections via flag?
        // @todo Post Git pull commands?
        // @todo Install PHPUnit scaffold once theme is installed.
        // @todo Initialize Git repository and remotes.
        // @todo Read in files as templates. '.gitignore' => 'https://wpengine.com/wp-content/uploads/2013/10/recommended-gitignore-no-wp.txt'
        // @codingStandardsIgnoreEnd

        $output->writeln("<info>== Running Pre-install Commands =======================</info>");

        // Run pre-install commands.
        PreInstall::executeCommands();

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

        $output->writeln("\n<info>== Running Post Theme Install Commands =======================</info>");

        // Run post install theme commands.
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

        $output->writeln("\n<info>== Setting up menus =======================</info>");

        // Create menus from theme menu locations?
        WP::menuCreateAll();

        $output->writeln("\n<info>== Setting Permalink Structure =======================</info>");

        // Set rewrite rules
        WP::rewriteSetStructure();

        $output->writeln("\n<info>== Running Post Install Commands =======================</info>");

        // Run post install commands.
        PostInstall::executeCommands();

        $output->writeln("\n<info>Install Completed!</info>");
    }
}
