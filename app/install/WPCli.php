<?php

namespace App\Install;

use App\Install\Database;
use Exception;
use Symfony\Component\Console\Output\OutputInterface;

class WPCli
{
    protected $domain;
    protected $output;
    protected $database;
    protected $directory_path;
    protected $executable = 'wp';

    public function __construct($domain, OutputInterface $output, Database $database, $directory_path)
    {
        $this->domain = $domain;
        $this->output = $output;
        $this->database = $database;
        $this->directory_path = $directory_path;
    }

    /**
     * Install.
     */
    public function install()
    {
        $this->isInstalled();

        $this->coreDownload();

        $this->coreWpConfig();

        $this->dbCreate();
    }

    /**
     * Download WordPress core.
     */
    protected function coreDownload()
    {
        $this->output->writeLn('<info>Downloading WordPress.</info>');
        $this->executeCommand('core', 'download');
    }

    /**
     * Create a database for WordPress.
     */
    protected function dbCreate()
    {
        $db_name = $this->database->getDBName();
        $this->output->writeLn("<info>Installing WordPress database {$db_name}.</info>");
        $this->executeCommand('db', 'create');
    }

    /**
     * Create wp-config.
     */
    protected function coreWpConfig()
    {
        $this->output->writeLn('<info>Creating wp-config.</info>');
        $args = [
            'dbname'   => $this->database->getDBName(),
            'dbuser'   => $this->database->getDBUser(),
            'dbpass'   => $this->database->getDBPassword(),
            'dbhost'   => $this->database->getDBHost(),
            'dbprefix' => $this->database->getDBTablePrefix(),
        ];
        $this->executeCommand('core', 'config', $args);
    }

    protected function coreInstall()
    {
        // wp core install
        // --url=$this->domain
        // --title="WP Test"
        // --admin_user=wp_test_user
        // --admin_password=1234
        // --admin_email=dtholloran@gmail.com
    }

    /**
     * Checks if WP-CLI is installed.
     *
     * @return  boolean
     */
    public function isInstalled() {
        $exec = shell_exec('which wp');

        if ( empty($exec) ) {
            throw new Exception("WP-CLI is not installed\nPlease visit http://wp-cli.org/ to install WP-CLI");
        }

        return ! empty($exec);
    }

    /**
     * Builds up the command arguments.
     *
     * @param   array  $args
     *
     * @return  array
     */
    protected function buildCommandArgs( $args )
    {
        $args = array_map('trim', $args);

        $command_args = [];
        foreach ($args as $arg_key => $arg) {
            $command_args[] = "--{$arg_key}={$arg}";
        }

        return implode(' ', $command_args);
    }

    /**
     * Execute command.
     *
     * @param   string  $base_command
     * @param   string  $command
     * @param   array   $args
     */
    protected function executeCommand($base_command, $command, $args = []) {
        $args = array_merge([
            'path' => $this->directory_path
        ], $args);
        $args = $this->buildCommandArgs($args);

        $exec = shell_exec("{$this->executable} {$base_command} {$command} {$args}");
        $this->output->writeLn($exec);
    }
}
