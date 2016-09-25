<?php
namespace KindlingCLI\Command;

use KindlingCLI\Option\Configuration;

class PostInstall
{
    /**
     * Executes the post install commands.
     */
    public static function executeCommands()
    {
        $config = Configuration::get();

        // Run commands.
        foreach ($config['commands']['postInstall'] as $command) {
            system($command);
        }
    }

    /**
     * Executes the post install theme commands.
     */
    public static function executeThemeCommands()
    {
        $config = Configuration::get();
        $rootDir = getcwd();
        $themeDir = "{$rootDir}/wp-content/themes/{$config['theme']['name']}";

        // Change into the theme directory.
        chdir($themeDir);

        // Run commands.
        foreach ($config['commands']['postInstallTheme'] as $command) {
            system($command);
        }

        // Change back to root directory.
        chdir($rootDir);
    }
}
