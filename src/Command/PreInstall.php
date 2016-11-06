<?php
namespace PressCLI\Command;

use PressCLI\Option\Configuration;

class PreInstall
{
    /**
     * Executes the pre-install commands.
     */
    public static function executeCommands()
    {
        $config = Configuration::get();

        // Run commands.
        foreach ($config['commands']['preInstall'] as $command) {
            system($command);
        }
    }
}
