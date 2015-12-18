<?php
namespace App;

use App\ConfigurationReader;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitCommand extends Command
{
    protected $config;

    /**
     * Creates a new class instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->config = new ConfigurationReader;
    }

    /**
     * Configure the command.
     */
    protected function configure()
    {
        $this
            ->setName('init')
            ->setDescription('Create a new configuration.')
            ->addOption(
               'config',
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
        $config = $input->getOption('config');
        $force = $input->getOption('force');
        $this->createConfig($config, $output, $force);
    }

    /**
     * Creates a configuration file.
     *
     * @param string          $config
     * @param OutputInterface $output
     * @param boolean         $force
     */
    protected function createConfig($config, OutputInterface $output, $force = false)
    {
        $this->checkIfConfigExists($force);

        switch ($config) {
            case 'json':
                $this->createJSONConfig($output);
                break;

            default:
                $this->createYAMLConfig($output);
                break;
        }
    }

    /**
     * Checks for an existing configuration file.
     *
     * @param boolean $force
     */
    protected function checkIfConfigExists($force = false)
    {
        if ( $force ) {
            return;
        }

        if ( file_exists($this->config->getJsonConfig()) or file_exists($this->config->getYamlConfig()) ) {
            throw new \Exception("Configuration file already exists!");
        }
    }

    /**
     * Creates a new YAML configuration file.
     *
     * @param OutputInterface $output
     */
    protected function createYAMLConfig(OutputInterface $output)
    {
        $dumper = new Dumper();

        $yaml = $dumper->dump($this->configurationValues(), 2);

        $this->createConfigurationFile($this->config->getYamlConfig(), $yaml, $output);
    }

    /**
     * Creates a new JSON configuration file.
     *
     * @param OutputInterface $output
     */
    protected function createJSONConfig(OutputInterface $output)
    {
        $json = json_encode($this->configurationValues(), JSON_PRETTY_PRINT);

        $this->createConfigurationFile($this->config->getJsonConfig(), $json, $output);
    }

    /**
     * Creates a new configuration file.[createConfigurationFile description]
     *
     * @param string          $name
     * @param string          $contents
     * @param OutputInterface $output
     */
    protected function createConfigurationFile($name, $contents, OutputInterface $output)
    {
        $file = file_put_contents($name, $contents);

        if ( false === $file ) {
            throw new \Exception('Configuration file could not be created!');
        }

        $configPath = getcwd() . "/{$name}";

        $output->writeln("<info>Configuration created successfully at {$configPath}</info>");
    }

    /**
     * The configuration values.
     *
     * @param   array $config
     *
     * @return  array
     */
    protected function configurationValues($config = [])
    {
        return array_merge([
            'config1' => 'test1',
            'config2' => 'test2',
        ], $config);
    }
}
