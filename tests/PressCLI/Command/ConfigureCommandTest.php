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

        $this->resetSiteDirectory();
    }

    /**
     * Tear down the world.
     */
    public function tearDown()
    {
        $this->removeGlobalConfiguration();
        $this->removeSiteDirectory();
    }

    /**
     * @test
     * @group failing
     */
    public function it_creates_the_global_configuration()
    {
        // Setup
        $application = new Application();
        $application->add(new ConfigureCommand());
        $command = $application->find('configure');
        $commandTester = new CommandTester($command);

        // Execute press configure --global
        $commandTester->execute(array(
            'command'  => $command->getName(),
            '--global' => 'y',
        ));

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
    public function it_creates_the_local_configuratio()
    {
        $this->assertTrue(true);
    }

    /**
     * @test
     * @group failing
     */
    public function it_merges_the_global_and_local_configurations()
    {
        $this->assertTrue(true);
    }

    /**
     * Gets the stub configuration to test against.
     *
     * @param  string $type
     *
     * @return Illuminate\Support\Collection
     */
    protected function getStubConfiguration($type = null)
    {
        if (is_null($type)) {
            $path = "{$this->stubs}/.press-cli.json";
        } else {
            $path = "{$this->stubs}/.press-cli.{$type}.json";
        }

        return $this->readConfiguration($path);
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
