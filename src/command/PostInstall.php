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
        $commands = $config['commands'];
    }

    /**
     * Executes the post install theme commands.
     */
    public static function executeThemeCommands()
    {
        $config = Configuration::get();
        $commands = $config['commands'];
    }
}
