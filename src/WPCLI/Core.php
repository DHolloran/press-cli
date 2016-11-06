<?php
namespace PressCLI\WPCLI;

use PressCLI\WPCLI\CLI;
use PressCLI\Option\Configuration;

trait Core
{
    /**
     * Downloads WordPress core.
     */
    public static function coreDownload()
    {
        CLI::execCommand('core', ['download']);
    }

    /**
     * Creates the wp-config.php
     */
    public static function coreConfig()
    {
        $config = Configuration::get();
        $dbConfig = $config['database'];
        $options = [
            'skip-check' => '',
            'dbuser' => $dbConfig['user'],
            'dbpass' => $dbConfig['password'],
            'dbprefix' => $dbConfig['prefix'],
            'dbname' => $dbConfig['name'],
            'dbhost' => $dbConfig['host'],
            // 'extra-php' => "define( 'WP_DEBUG', true );\ndefine( 'WP_DEBUG_LOG', true );",
        ];

        CLI::execCommand('core', ['config'], $options);
    }

    /**
     * Install WordPress.
     */
    public static function coreInstall()
    {
        $config = Configuration::get();
        $options = [
            'url' => $config['site']['url'],
            'title' => $config['site']['title'],
            'admin_user' => $config['user']['name'],
            'admin_password' => $config['user']['password'],
            'admin_email' => $config['user']['email'],
            'skip-email' => '',
        ];

        CLI::execCommand('core', ['install'], $options);
    }
}
