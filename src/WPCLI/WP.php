<?php

namespace PressCLI\WPCLI;

use PressCLI\CLI;

class WP
{
    protected $bin;
    protected $path;
    public $cli;

    public function __construct(CLI $cli)
    {
        $this->cli = $cli;
        $this->bin = PRESS_BIN;
        $this->path = PRESS_WP;
    }

    /**
     * Get the WP-CLI bin directory.
     *
     * @return string
     */
    public function getBin()
    {
        return $this->bin;
    }

    /**
     * Get the WP-CLI executable path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Checks if the WP-CLI has been installed.
     *
     * @return boolean
     */
    public function isInstalled()
    {
        return file_exists($this->getPath());
    }
}
