<?php

namespace PressCLI\Configure;

use PressCLI\Exception\CommandException;
use PressCLI\Configure\GlobalConfiguration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LocalConfiguration extends Configuration
{
    /**
     * Gets the install root path.
     *
     * @return string
     */
    protected function getRootPath()
    {
        $path = getcwd();
        return $path;
    }

    /**
     * Gets the project name.
     *
     * @return string
     */
    protected function getProjectName()
    {
        $name = $this->input->getArgument('project-name');

        if (!isset($name)) {
            throw new CommandException([
                'Project name missing.',
                '$ press configure <project-name>',
            ]);
        }

        return $name;
    }

    /**
     * {@inheritDoc}
     */
    protected function getPath()
    {
        $this->createProjectDirectory($path = "{$this->getRootPath()}/{$this->getProjectName()}");

        return "{$path}/" . PRESS_CONFIG_NAME;
    }

    /**
     * {@inheritDoc}
     */
    protected function getStubPath()
    {
        return PRESS_STUBS . "/.press-cli.json";
    }

    /**
     * {@inheritDoc}
     */
    protected function getConfigurationtoWrite()
    {
        $configuration = $this->getGlobalConfiguration();
        $configuration = (new ConfigurationDefaults($configuration, $this->input))->set();
        $configuration = (new ConfigurationQuestion(
           $this->questionHelper,
           $this->input,
           $this->output,
           $configuration
        ))->ask($configuration);

        return $configuration;
    }

    /**
     * Creates the project directory.
     *
     * @param  string $path
     */
    protected function createProjectDirectory($path)
    {
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
    }

    protected function getGlobalConfiguration()
    {
        $global = new GlobalConfiguration($this->input, $this->output, $this->command);
        if (!$global->exists()) {
            $global->write();
        }

        return $global->read();
    }
}
