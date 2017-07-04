<?php

namespace Tests\PressCLI;

use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    /**
     * Testing data.
     *
     * @var Faker\Factory
     */
    protected $faker;

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
     * The test site root directory.
     *
     * @var string
     */
    protected $siteRoot;

    /**
     * The test site name.
     *
     * @var string
     */
    protected $projectName;

    /**
     * The test site directory.
     *
     * @var string
     */
    protected $site;

    /**
     * the install database name.
     *
     * @var string
     */
    protected $databaseName;

    /**
     * The install site URL.
     *
     * @var string
     */
    protected $siteURL;

    /**
     * The install site administrator details.
     *
     * @var Illuminate\Support\Collection
     */
    protected $siteAdmin;

    /**
     * The install site details.
     *
     * @var Illuminate\Support\Collection
     */
    protected $siteDetails;

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
        // Setup faker
        $this->faker = \Faker\Factory::create();

        // Get the directories/files needed.
        $this->dir = __dir__ . '/../..';
        $this->bin = "{$this->dir}/bin/test";
        $this->wp = "{$this->bin}/wp";
        $this->phar = "{$this->dir}/wp-cli.phar";
        $this->siteRoot = "{$this->dir}/test-server";
        $this->projectName = implode('-', $this->faker->randomElements(
            $this->faker->words(4),
            $this->faker->numberBetween(1, 4)
        ));
        $this->site = "{$this->siteRoot}/{$this->projectName}";
        $this->stubs = "{$this->dir}/stubs";
        $this->testStubs = "{$this->dir}/test/stubs";

        // Set required configuration details.
        $cleanName = preg_replace("/[^A-Za-z0-9-_]/", '', $this->projectName);
        $this->databaseName = 'wp_' . str_replace('-', '_', $cleanName);
        $this->siteDetails = collect([
            'title' => $this->faker->sentence(6, true),
            'url' => "http://{$cleanName}.dev",
        ]);
        $this->siteAdmin = collect([
           'name' => $this->faker->userName,
           'email' => $this->faker->safeEmail,
           'password' => $this->faker->password,
        ]);

        // Set required constants;
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
            mkdir($this->bin, 0755, true);
        }
    }

    /**
     * Creates the test site root directory.
     */
    protected function createSiteRootDirectory()
    {
        if (!file_exists($this->siteRoot)) {
            mkdir($this->siteRoot, 0755, true);
        }
    }

    /**
     * Removes the test site root directory.
     */
    protected function removeSiteRootDirectory()
    {
        if (file_exists($this->siteRoot)) {
            $this->recursiveRemoveDirectory($this->siteRoot);
        }
    }

    protected function recursiveRemoveDirectory($directory)
    {
        if (is_dir($directory)) {
            $objects = scandir($directory);
            foreach ($objects as $object) {
                if ($object != '.' and $object != '..') {
                    $path = "{$directory}/{$object}";
                    if (is_dir($path)) {
                        $this->recursiveRemoveDirectory($path);
                    } else {
                        unlink($path);
                    }
                }
            }

            rmdir($directory);
        }
    }

    /**
     * Resets the test site root directory.
     */
    protected function resetSiteRootDirectory()
    {
        $this->removeSiteRootDirectory();
        $this->createSiteRootDirectory();
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
