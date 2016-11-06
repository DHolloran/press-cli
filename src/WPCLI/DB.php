<?php
namespace PressCLI\WPCLI;

use PressCLI\WPCLI\CLI;

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
