<?php

namespace Tests\PressCLI\Command;

use Tests\PressCLI\BaseTestCase;
use PressCLI\Command\WPCLIUpdateCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @codingStandardsIgnoreStart
 */
class WPCLIUpdateCommandTest extends BaseTestCase
{
    /**
     * Setup the world.
     */
    public function setUp()
    {
        parent::setUp();

        $this->removeWPCLIExecutable();
        $this->instalWPCLIStub();
    }

    /**
     * Tear down the world.
     */
    public function tearDown()
    {
        $this->removeWPCLIExecutable();
    }

    /**
     * @test
     */
    public function it_updates_the_wp_cli_executable()
    {
        // Setup
        $application = new Application();
        $application->add(new WPCLIUpdateCommand());
        $command = $application->find('wp-cli:update');
        $commandTester = new CommandTester($command);

        $this->instalWPCLIStub();

        // Execute press wp-cli:install
        $commandTester->execute([
            'command'  => $command->getName(),
        ]);

        // Execute wp-cli
        exec("{$this->wp} --info", $wpinfoOutput, $wpinfoStatus);

        // Assert
        $this->assertEquals(0, $commandTester->getStatusCode());
        $this->assertEquals(0, $wpinfoStatus);
        $this->assertFileNotExists($this->phar);
        $this->assertFileExists($this->bin);
        $this->assertFileExists($this->wp);
    }
}
