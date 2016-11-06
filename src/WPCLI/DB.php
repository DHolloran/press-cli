<?php
namespace KindlingCLI\WPCLI;

use KindlingCLI\WPCLI\CLI;

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
