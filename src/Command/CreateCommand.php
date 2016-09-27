<?php
namespace KindlingCLI\Command;

use KindlingCLI\Option\Configuration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCommand extends Command
{
    /**
     * Configure create command.
     */
    protected function configure()
    {
        $this->setName('create')
              ->setDescription('Creates a new project directory and configuration.')
              ->addArgument('name', InputArgument::REQUIRED, 'The project name');
    }

    /**
     * Execute create command.
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $directory = getcwd() . "/{$name}";

        $this->createProjectDirectory($directory, $output);

        Configuration::create($directory, $name, $output);

        $output->writeln("<info>Project created!</info>");
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
