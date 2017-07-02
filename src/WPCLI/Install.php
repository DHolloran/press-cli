<?php

namespace PressCLI\WPCLI;

use PressCLI\CLI;

class Install
{
    protected $cli;
    protected $wp;

    public function __construct(CLI $cli)
    {
        $this->cli = $cli;
        $this->wp = new WP($cli);
    }

    /**
     * Executes the install.
     */
    public function execute()
    {
        if ($this->wp->isInstalled()) {
            $this->cli->info('WP-CLI is already installed.');
            return;
        }

        $this->cli->info('Installing WP-CLI, this may take a moment.');
        $this->download();
        $this->store();
        $this->cli->info('WP-CLI installed!');
    }

    /**
     * Downloads the WP-CLI.
     */
    public function download()
    {
        chdir(PRESS_DIR);

        if (!file_exists('wp-cli.phar')) {
            $this->cli->passthru('curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar');
        }
    }

    /**
     * Makes the bin directory if it does not exist.
     */
    protected function makeBin()
    {
        if (!file_exists($this->wp->getBin())) {
            mkdir($this->wp->getBin());
        }
    }

    /**
     * Stores WP-CLI in the bin directory.
     */
    protected function store()
    {
        $this->makeBin();

        $this->cli->execute('chmod +x wp-cli.phar');
        $this->cli->execute('mv wp-cli.phar ' . $this->wp->getPath());
    }
}
