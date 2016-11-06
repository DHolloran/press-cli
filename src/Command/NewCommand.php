<?php
namespace PressCLI\Command;

use PressCLI\Lib\FileSystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NewCommand extends Command
{
    use FileSystem;

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
}
