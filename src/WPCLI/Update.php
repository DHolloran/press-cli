<?php

namespace PressCLI\WPCLI;

use PressCLI\CLI;

class Update
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
        $this->cli->execute($this->wp->getPath() . ' cli update');
        $this->cli->info('WP-CLI updated!');
    }
}
