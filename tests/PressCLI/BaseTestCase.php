<?php

namespace Tests\PressCLI;

use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    protected $dir;
    protected $bin;
    protected $wp;
    protected $phar;

    /**
     * Setup the world.
     */
    public function setUp()
    {
        // Get the directories/files needed.
        $this->dir = __dir__ . '/../../..';
        $this->bin = "{$this->dir}/bin/test";
        $this->wp = "{$this->bin}/wp";
        $this->phar = "{$this->dir}/wp-cli.phar";

        // Setup required constants.
        define('PRESS_DIR', $this->dir);
        define('PRESS_BIN', $this->bin);
        define('PRESS_WP', $this->wp);
    }

    protected function removeWPCLIExecutable()
    {
        // Remove wp executable
        if (file_exists($this->wp)) {
            unlink($this->wp);
        }

        // Remove bin directory
        if (file_exists($this->bin)) {
            rmdir($this->bin);
        }
    }
}
