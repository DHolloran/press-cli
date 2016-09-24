<?php
namespace KindlingCLI\WPCLI;

use KindlingCLI\WPCLI\CLI;
use KindlingCLI\Option\YAMLConfiguration;
use Symfony\Component\Console\Output\OutputInterface;

trait DB
{
    /**
     * Creates the database.
     */
    public static function dbCreate()
    {
        CLI::execCommand('db', ['create']);
    }
}
