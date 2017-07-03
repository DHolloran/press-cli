<?php

namespace Tests\PressCLI\Command;

use Tests\PressCLI\BaseTestCase;
use PressCLI\Command\ConfigureCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @codingStandardsIgnoreStart
 */
class ConfigureCommandTest extends BaseTestCase
{
    /**
     * Setup the world.
     */
    public function setUp()
    {
        parent::setUp();

        $this->resetSiteRootDirectory();
    }

    /**
     * Tear down the world.
     */
    public function tearDown()
    {
        $this->removeGlobalConfiguration();
        $this->removeSiteRootDirectory();
    }

    /**
     * @test
     */
    public function it_creates_the_global_configuration()
    {
        // Setup
        $application = new Application();
        $application->add(new ConfigureCommand());
        $command = $application->find('configure');
        $commandTester = new CommandTester($command);

        // Execute press configure --global
        $commandTester->execute([
            'command'  => $command->getName(),
            '--global' => 'y',
        ]);

        // Assert configuration was correctly created
        $this->assertFileExists(PRESS_GLOBAL_CONFIG);
        $this->assertEquals(
            $this->getStubConfiguration(),
            $this->readConfiguration(PRESS_GLOBAL_CONFIG)
        );
    }

    /**
     * @test
     * @group failing
     */
    public function it_creates_the_local_configuration_in_current_directory()
    {
        // Setup
        $application = new Application();
        $application->add(new ConfigureCommand());
        $command = $application->find('configure');
        $commandTester = new CommandTester($command);

        // Set current working directory to site root
        chdir($this->siteRoot);

        // Execute press configure
        $commandTester->execute([
            'command'  => $command->getName(),
            'project-name' => $this->projectName,
        ]);

        // Assert configuration was correctly created
        $configurationPath = "{$this->site}/" . PRESS_CONFIG_NAME;
        $this->assertFileExists($configurationPath);
        $this->assertEquals(
            $this->getStubConfiguration(),
            $this->fillLocalConfiguration($configurationPath)
        );
    }

    /**
     * @test
     * @group failing
     */
    public function it_creates_the_local_configuration_in_a_specified_directory()
    {
        $this->assertTrue(false);
    }

    /**
     * @test
     * @group failing
     */
    public function it_creates_the_global_configuration_when_creating_the_local_configuration_if_not_present()
    {
        $this->assertTrue(false);
    }

    /**
     * @test
     * @group failing
     */
    public function it_merges_the_global_and_local_configurations()
    {
        $this->assertTrue(false);
    }

    /**
     * Fills the local configuration file with test data.
     *
     * @param  string $configurationPath
     *
     * @return Illuminate\Support\Collection
     */
    protected function fillLocalConfiguration($configurationPath)
    {
        $configuration = $this->readConfiguration($configurationPath);

        // Setup database
        $database = $configuration->get('database', []);
        $database['name'] = $this->databaseName;
        $configuration->put('database', $database);

        // Set user
        $user = $configuration->get('user', []);
        $user['name'] = $this->siteAdmin->get('name');
        $user['email'] = $this->siteAdmin->get('email');
        $user['password'] = $this->siteAdmin->get('password');
        $configuration->put('user', $user);

        // Set Theme
        $theme = $configuration->get('theme', []);
        $theme['name'] = $this->projectName;
        $configuration->put('theme', $theme);

        // Set site details
        $site = $configuration->get('site', []);
        $site['title'] = $this->siteDetails->get('title');
        $site['url'] = $this->siteDetails->get('url');
        $configuration->put('site', $site);

        return $configuration;
    }

    /**
     * Gets the stub configuration to test against.
     *
     * @param  string $type
     * @param  string $directory
     *
     * @return Illuminate\Support\Collection
     */
    protected function getStubConfiguration($type = null, $directory = null)
    {
        $directory = is_null($directory) ? $this->stubs : $directory;
        $name = is_null($type) ? '.press-cli.json' : ".press-cli.{$type}.json";

        return $this->readConfiguration("{$directory}/{$name}");
    }

    /**
     * Reads a configuration file.
     *
     * @param  string $path
     *
     * @return Illuminate\Support\Collection
     */
    protected function readConfiguration($path)
    {
        return collect(json_decode(file_get_contents($path), true));
    }
}
