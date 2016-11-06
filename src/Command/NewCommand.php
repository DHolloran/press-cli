<?php
namespace PressCLI\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NewCommand extends Command
{
    /**
     * Configure create command.
     */
    protected function configure()
    {
        $this->setName('new')
             ->setDescription('Creates a new project directory, configuration and runs the install.')
             ->addArgument('project-name', InputArgument::REQUIRED, 'The project name.');
    }

    /**
     * Execute create command.
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Configure the install.
        $name = $input->getArgument('project-name');
        $configure = $this->getApplication()->find('configure');
        $configure->run(new ArrayInput(['project-name' => $name]), $output);

        // Go into the newly configured install.
        chdir($name);
        $output->writeln('');

        $install = $this->getApplication()->find('install');
        $install->run(new ArrayInput([]), $output);
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
