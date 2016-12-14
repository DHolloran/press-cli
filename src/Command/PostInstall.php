<?php
namespace PressCLI\Command;

use PressCLI\Option\Configuration;

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
        $themeName = isset($config['theme']['name']) ? $config['theme']['name'] : '';
        $commands = isset($config['commands']['postInstallTheme']) ? $config['commands']['postInstallTheme'] : [];
        if ( ! $themeName || ! $commands ) {
            return;
        }

        $rootDir = getcwd();
        $themeDir = "{$rootDir}/wp-content/themes/{$themeName}";

        // Change into the theme directory.
        chdir($themeDir);

        // Run commands.
        foreach ($commands as $command) {
            system($command);
        }

        // Change back to root directory.
        chdir($rootDir);
    }
}
