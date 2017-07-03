<?php

namespace Tests\PressCLI;

use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    /**
     * Base Press CLI path.
     *
     * @var string
     */
    protected $dir;

    /**
     * Press CLI bin path.
     *
     * @var string
     */
    protected $bin;

    /**
     * WP-CLI executable path.
     *
     * @var string
     */
    protected $wp;

    /**
     * The wp-cli.phar path when downloaded.
     *
     * @var string
     */
    protected $phar;

    /**
     * The test site directory.
     *
     * @var string
     */
    protected $site;

    /**
     * Install stubs path.
     *
     * @var string
     */
    protected $stubs;

    /**
     * Test stubs path.
     *
     * @var string
     */
    protected $testStubs;

    /**
     * Setup the world.
     */
    public function setUp()
    {
        // Get the directories/files needed.
        $this->dir = __dir__ . '/../..';
        $this->bin = "{$this->dir}/bin/test";
        $this->wp = "{$this->bin}/wp";
        $this->phar = "{$this->dir}/wp-cli.phar";
        $this->site = "{$this->dir}/test-site";
        $this->stubs = "{$this->dir}/stubs";
        $this->testStubs = "{$this->dir}/test/stubs";

        if (!defined('PRESS_DIR')) {
            define('PRESS_DIR', $this->dir);
        }

        if (!defined('PRESS_BIN')) {
            define('PRESS_BIN', $this->bin);
        }

        if (!defined('PRESS_WP')) {
            define('PRESS_WP', $this->wp);
        }

        if (!defined('PRESS_CONFIG_NAME')) {
            define('PRESS_CONFIG_NAME', '.press-cli.test.json');
        }

        if (!defined('PRESS_GLOBAL_CONFIG')) {
            define('PRESS_GLOBAL_CONFIG', "{$this->dir}/.press-cli.test.global.json");
        }

        if (!defined('PRESS_STUBS')) {
            define('PRESS_STUBS', PRESS_DIR . '/stubs');
        }
    }

    /**
     * Removes the wp-cli executable.
     */
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

    /**
     * Installs WP-CLI stub so we can test that updating works.
     */
    protected function instalWPCLIStub()
    {
        $this->createBinDirectory();

        if (!file_exists($this->wp)) {
            exec("cp {$this->testStubs}/wp {$this->wp}");
        }
    }

    /**
     * Creates the bin directory.
     */
    protected function createBinDirectory()
    {
        if (!file_exists($this->bin)) {
            mkdir($this->bin, 0777, true);
        }
    }

    /**
     * Creates the test site directory.
     */
    protected function createSiteDirectory()
    {
        if (!file_exists($this->site)) {
            mkdir($this->site, 0777, true);
        }
    }

    /**
     * Removes the test site directory.
     */
    protected function removeSiteDirectory()
    {
        var_dump('Remove site directory');
    }

    /**
     * Resets the test site directory.
     */
    protected function resetSiteDirectory()
    {
        $this->removeSiteDirectory();
        $this->createSiteDirectory();
    }

    /**
     * Removes the global configuration.
     */
    protected function removeGlobalConfiguration()
    {
        if (file_exists(PRESS_GLOBAL_CONFIG)) {
            unlink(PRESS_GLOBAL_CONFIG);
        }
    }
}
