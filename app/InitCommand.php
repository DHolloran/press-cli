<?php
namespace App;

use App\Configuration\ConfigurationWriter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitCommand extends Command
{
    protected $config;

    /**
     * Configure the command.
     */
    protected function configure()
    {
        $this
            ->setName('init')
            ->setDescription('Create a new configuration.')
            ->addOption(
               'config-type',
               null,
               InputArgument::OPTIONAL,
               'The configuration file type to create json|yaml.'
            )
            ->addOption(
               'global-config',
               null,
               InputArgument::OPTIONAL,
               'The configuration file type to create json|yaml.'
            )
            ->addOption(
               'force',
               null,
               InputOption::VALUE_NONE,
               'Will bypass configuration file exists check and will overwrite configuration file.'
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

        $configurationWriter = new ConfigurationWriter($output);
        $configurationWriter->setConfigType($configType);
        $configurationWriter->setForce($force);
        $configurationWriter->createConfig($output);
    }

}
