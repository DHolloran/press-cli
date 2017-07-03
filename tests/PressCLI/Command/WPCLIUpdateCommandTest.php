<?php

namespace Tests\PressCLI\Command;

use Tests\PressCLI\BaseTestCase;
use PressCLI\Command\WPCLIUpdateCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

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
     * @group failing
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
        $commandTester->execute(array(
            'command'  => $command->getName(),
        ));

        // Execute wp-cli
        exec("{$this->wp} --info", $wpinfoOutput, $wpinfoStatus);

        // Assert
        $this->assertEquals(0, $commandTester->getStatusCode());
        $this->assertEquals(0, $wpinfoStatus);
        $this->assertFileNotExists($this->phar);
        $this->assertFileExists($this->bin);
        $this->assertFileExists($this->wp);
    }

    /**
     * Installs WP-CLI stub so we can test that updating works.
     *
     * @return [type] [description]
     */
    protected function instalWPCLIStub()
    {
        if (!file_exists($this->bin)) {
            mkdir($this->bin, 0777, true);
        }

        if (!file_exists($this->wp)) {
            exec("cp {$this->dir}/tests/stubs/wp {$this->wp}");
        }
    }
}
