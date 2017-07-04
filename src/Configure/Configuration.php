<?php

namespace PressCLI\Configure;

use PressCLI\CLI;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Configuration
{
    /**
     * Console input interface
     *
     * @var InputInterface
     */
    protected $input;

    /**
     * Console output interface.
     *
     *
     * @var OutputInterface
     */
    protected $output;

    /**
     * Console command interface.
     *
     * @var Command
     */
    protected $command;

    /**
     * Console question helper.
     *
     * @var Symfony\Component\Console\Helper\QuestionHelper
     */
    protected $questionHelper;

    /**
     * Command line helper.
     *
     * @var CLI
     */
    protected $cli;

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param Command         $command
     */
    public function __construct(InputInterface $input, OutputInterface $output, Command $command)
    {
        $this->input = $input;
        $this->output = $output;
        $this->command = $command;
        $this->questionHelper = $command->getHelper('question');
        $this->cli = (new CLI($input, $output));
    }

    /**
     * Gets the configuration path.
     *
     * @return string
     */
    abstract protected function getPath();

    /**
     * Gets the configuration stub path.
     *
     * @return string
     */
    abstract protected function getStubPath();

    /**
     * Gets the configuration to write to the configuration file.
     *
     * @return Illuminate\Support\Collection
     */
    abstract protected function getConfigurationtoWrite();

    /**
     * Reads the configuration.
     *
     * @param string $path
     *
     * @return Illuminate\Support\Collection
     */
    protected function read($path = null)
    {
        $path = is_null($path) ? $this->getPath() : $path;

        if ($this->exists($path)) {
            return rcollect(json_decode(file_get_contents($path), true));
        }

        return collect([]);
    }

    /**
     * Reads the stub file.
     *
     * @return Illuminate\Support\Collection
     */
    protected function readStub()
    {
        return $this->read($this->getStubPath());
    }

    /**
     * Checks if the configuration exists.
     *
     * @param  null|string $path
     *
     * @return boolean
     */
    protected function exists($path = null)
    {
        $path = is_null($path) ? $this->getPath() : $path;

        return file_exists($path);
    }

    /**
     * Writes the configuration.
     *
     * @param string $path
     */
    public function write($path = null)
    {
        $path = is_null($path) ? $this->getPath() : $path;

        if (!$this->shouldWrite($path)) {
            $this->cli->warning("Configuration already exists at {$path}.");

            return;
        }

        file_put_contents(
            $path,
            json_encode($this->getConfigurationtoWrite()->toArray(), JSON_PRETTY_PRINT)
        );

        $this->cli->success("Configuration written to {$path}.");
    }

    /**
     * Checks if the configuration should be written.
     *
     * @param  string $path
     *
     * @return boolean
     */
    protected function shouldWrite($path)
    {
        if ($this->input->getOption('force')) {
            return true;
        }

        return !$this->exists($path);
    }
}
