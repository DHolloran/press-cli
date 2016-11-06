<?php
namespace PressCLI\Command;

use RuntimeException;
use PressCLI\Option\Configuration;
use PressCLI\Option\GlobalConfiguration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigureCommand extends Command
{
    /**
     * Configure create command.
     */
    protected function configure()
    {
        $this->setName('configure')
             ->setDescription('Creates a new project directory and configuration.')
             ->addArgument('project-name', InputArgument::OPTIONAL, 'The project name.')
             ->addOption(
                 'global',
                 null,
                 InputOption::VALUE_NONE,
                 'Creates the global configuration if it does not already exist.'
             );
    }

    /**
     * Execute create command.
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $global = $input->getOption('global');
        if ($global) {
            GlobalConfiguration::create($output);

            return;
        }

        $name = $input->getArgument('project-name');
        if (!$name) {
            throw new RuntimeException("Project name is required when not using the --global option!");
        }

        // Handle directory creation and local configuration.
        $directory = getcwd() . "/{$name}";
        $this->createProjectDirectory($directory, $output);
        Configuration::create($directory, $name, $input, $output, $this->getHelper('question'));
    }

    /**
     * Create project directory.
     *
     * @return InitCommand
     */
    protected function createProjectDirectory($directory, OutputInterface $output)
    {
        $output->writeln("<info>Creating project directory...</info>");

        if (file_exists($directory)) {
            $output->writeln("<comment>Project directory already exists at {$directory}.</comment>");

            return $this;
        }

        mkdir($directory, 0755, true);

        $output->writeln("<info>Project directory created at {$directory}!</info>");

        return $this;
    }
}
