<?php
namespace App;

use App\Install\Install;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NewCommand extends Command
{
    protected $config;

    /**
     * Configure the command.
     */
    protected function configure()
    {
        $this
            ->setName('new')
            ->setDescription('Create a new configuration.')
            ->addArgument(
                'domain',
                InputArgument::REQUIRED,
                'The sites domain name.'
            )
            ->addOption(
               'config-type',
               null,
               InputArgument::OPTIONAL,
               'The configuration file type to create json|yaml.'
            )
            ->addOption(
               'force',
               null,
               InputOption::VALUE_NONE,
               'Bypass check for existing install.'
            );
    }

    /**
     * Execute the command.
     *
     * @param   InputInterface   $input
     * @param   OutputInterface  $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configType = $input->getOption('config-type');
        $force = $input->getOption('force');
        $domain = $input->getArgument('domain');
        $question = $this->getHelper('question');

        $install = new Install($domain, $output, $question);
        $install->run();

        // $this->executeInitCommand($configType, $force, $output);
    }

    protected function executeInitCommand($configType, $force, OutputInterface $output)
    {
        $command = $this->getApplication()->find('init');

        $arguments = array(
            'command' => 'init',
            '--config-type' => (is_null($configType)) ? 'yaml' : $configType,
            '--force'  => (1 == $force),
        );

        $greetInput = new ArrayInput($arguments);
        $returnCode = $command->run($greetInput, $output);
    }

}
