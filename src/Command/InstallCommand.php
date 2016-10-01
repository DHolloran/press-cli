<?php
namespace KindlingCLI\Command;

use KindlingCLI\WPCLI\WP;
use KindlingCLI\Option\Validator;
use KindlingCLI\Command\PostInstall;
use KindlingCLI\Option\Configuration;
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
        // Make sure the configuration exists.
        if (!Configuration::exists()) {
            $output->writeln(
                '<error>Configuration file not present run press init {project-name} to get started.</error>'
            );
            exit(1);
        }

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
