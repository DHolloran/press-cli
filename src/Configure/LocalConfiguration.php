<?php

namespace PressCLI\Configure;

use PressCLI\Exception\CommandException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LocalConfiguration extends Configuration
{
    public function __construct(InputInterface $input, OutputInterface $output)
    {
        parent::__construct($input, $output);
    }

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
        $stub = $this->readStub();

        return $stub;
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
}
