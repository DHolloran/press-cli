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
        // Execute
        $this->executeGlobalConfiguration();

        // Assert configuration was correctly created
        $this->assertFileExists(PRESS_GLOBAL_CONFIG);
        $this->assertEquals(
            $this->getStubConfiguration(),
            $this->readConfiguration(PRESS_GLOBAL_CONFIG)
        );
    }

    /**
     * @test
     */
    public function it_creates_the_local_configuration_in_current_directory()
    {
        // Setup
        $stub = $this->getFilledStub();

        // Execute
        $this->executeLocalConfiguration($stub);

        // Assert
        $this->assertFileExists($this->getLocalPath());
        $this->assertEquals(
            $stub,
            $this->readConfiguration($this->getLocalPath())
        );
    }

    /**
     * @test
     */
    public function it_creates_the_local_configuration_in_a_specified_directory()
    {
        // Setup
        $stub = $this->getFilledStub();
        $path = "{$this->siteRoot}/custom-path-site";

        // Make sure it doesn't already exist
        $this->assertFileNotExists($path);

        // Execute
        $this->executeLocalConfiguration($stub, ['--path' => $path]);

        // Assert
        $this->assertFileExists($path);
        $this->assertEquals(
            $stub,
            $this->readConfiguration("{$path}/" . PRESS_CONFIG_NAME)
        );
    }

    /**
     * @test
     */
    public function it_creates_the_global_configuration_when_creating_the_local_configuration_if_not_present()
    {
        // Setup
        $stub = $this->getFilledStub();

        // Make sure the global configuration does not already exist.
        $this->assertFileNotExists(PRESS_GLOBAL_CONFIG);

        // Execute
        $this->executeLocalConfiguration($stub);

        // Assert
        $this->assertFileExists(PRESS_GLOBAL_CONFIG);
    }

    /**
     * @test
     */
    public function it_does_not_overwrite_the_global_configuration()
    {
        // Setup
        file_put_contents(PRESS_GLOBAL_CONFIG, $content = '{}');

        // Execute
        $this->executeGlobalConfiguration();

        // Assert configuration was correctly created
        $this->assertEquals($content, file_get_contents(PRESS_GLOBAL_CONFIG));
    }

    /**
     * @test
     */
    public function it_does_not_overwrite_the_local_configuration()
    {
        // Setup
        mkdir($this->site, 0755, true);
        file_put_contents($this->getLocalPath(), $content = '{}');
        $stub = $this->getFilledStub();

        // Execute
        $this->executeLocalConfiguration($stub);

        // Assert configuration was correctly created
        $this->assertEquals($content, file_get_contents($this->getLocalPath()));
    }

    /**
     * @test
     */
    public function it_allows_global_configuration_to_be_forcibly_overwritten()
    {
        // Setup
        file_put_contents(PRESS_GLOBAL_CONFIG, $content = '{}');

        // Execute
        $this->executeGlobalConfiguration(['--force' => null]);

        // Assert configuration was correctly created
        $this->assertNotEquals($content, file_get_contents(PRESS_GLOBAL_CONFIG));
    }

    /**
     * @test
     */
    public function it_allows_local_configuration_to_be_forcibly_overwritten()
    {
        // Setup
        mkdir($this->site, 0755, true);
        file_put_contents($this->getLocalPath(), $content = '{}');
        $stub = $this->getFilledStub();

        // Execute
        $this->executeLocalConfiguration($stub, ['--force' => null]);

        // Assert configuration was correctly created
        $this->assertNotEquals($content, file_get_contents($this->getLocalPath()));
    }

    /**
     * Executes the global configuration command.
     *
     * @param  array $commandInput
     * @param  array $options
     *
     * @return CommandTester
     */
    protected function executeGlobalConfiguration($commandInput = [], $options = [])
    {
        // Setup
        $application = new Application();
        $application->add(new ConfigureCommand());
        $command = $application->find('configure');
        $commandTester = new CommandTester($command);

        // Execute press configure --global
        $commandTester->execute(array_merge([
            'command'  => $command->getName(),
            '--global' => null,
        ], $commandInput), $options);

        return $commandTester;
    }

    /**
     * Executes the local configuration
     *
     * @param  Illuminate\Support\Collection $stub
     * @param  array $commandInput
     * @param  array $options
     * @param  array $inputs
     *
     * @return CommandTester
     */
    protected function executeLocalConfiguration($stub, $commandInput = [], $options = [], $inputs = [])
    {
        // Setup
        $application = new Application();
        $application->add(new ConfigureCommand());
        $command = $application->find('configure');
        $commandTester = new CommandTester($command);

        // Set current working directory to site root
        chdir($this->siteRoot);

        $commandTester->setInputs($this->getInputs($stub, $inputs));

        // Execute press configure
        $commandTester->execute(array_merge([
            'command'  => $command->getName(),
            'project-name' => $this->projectName,
        ], $commandInput), $options);

        return $commandTester;
    }

    /**
     * Fills the stub configuration file with test data.
     *
     * @return Illuminate\Support\Collection
     */
    protected function getFilledStub()
    {
        $configuration = $this->getStubConfiguration();

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
        return rcollect(json_decode(file_get_contents($path), true));
    }

    /**
     * Gets the command options.
     *
     * @param  Illuminate\Support\Collection $stub
     * @param  array $overrides
     *
     * @return array
     */
    protected function getInputs($stub, $overrides = [])
    {
        return array_merge([
            'database_user' => $stub->get('database')->get('user'),
            'database_password' => $stub->get('database')->get('password'),
            'database_prefix' => $stub->get('database')->get('prefix'),
            'database_name' => $stub->get('database')->get('name'),
            'database_host' => $stub->get('database')->get('host'),
            'user_name' => $stub->get('user')->get('name'),
            'user_email' => $stub->get('user')->get('email'),
            'user_password' => $stub->get('user')->get('password'),
            'site_title' => $stub->get('site')->get('title'),
            'site_url' => $stub->get('site')->get('url'),
            'theme_install' => 'yes',
            'theme_type' => 'zip',
            'theme_url' => '',
            'theme_name' => $this->projectName,
        ], $overrides);
    }

    /**
     * Gets the local configuration path.
     *
     * @return string
     */
    protected function getLocalPath()
    {
        return "{$this->site}/" . PRESS_CONFIG_NAME;
    }
}
