<?php

namespace App\Configuration;

use Symfony\Component\Yaml\Dumper;
use App\Configuration\Configuration;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigurationWriter extends Configuration {
    protected $force = false;

    /**
     * Creates a new class instance.
     *
     * @param string $output
     */
    public function __construct(OutputInterface $output)
    {
        parent::__construct($output);
    }

    /**
     * Creates a configuration file.
     *
     * @param string  $configType
     * @param boolean $force
     */
    public function createConfig()
    {
        $this->checkIfConfigExists($this->getForce());

        switch ($this->getConfigType()) {
            case 'json':
                $this->createJSONConfig();
                break;

            default:
                $this->createYAMLConfig();
                break;
        }
    }

    /**
     * Checks for an existing configuration file.
     */
    protected function checkIfConfigExists()
    {
        if ($this->getForce()) {
            return;
        }

        if ( file_exists($this->getJSONConfig()) or file_exists($this->getYAMLConfig()) ) {
            throw new \Exception("Configuration file already exists!");
        }
    }

    /**
     * Creates a new YAML configuration file.
     */
    protected function createYAMLConfig()
    {
        $dumper = new Dumper();

        $yaml = $dumper->dump($this->defaultValues(), 2);

        $this->createConfigurationFile($this->getYAMLConfig(), $yaml);
    }

    /**
     * Creates a new JSON configuration file.
     *
     * @param OutputInterface $output
     */
    protected function createJSONConfig()
    {
        $json = json_encode($this->defaultValues(), JSON_PRETTY_PRINT);

        $this->createConfigurationFile($this->getJSONConfig(), $json);
    }

    /**
     * Creates a new configuration file.
     *
     * @param string $name
     * @param string $contents
     */
    protected function createConfigurationFile($name, $contents)
    {
        $file = file_put_contents($name, $contents);

        if ( false === $file ) {
            throw new \Exception('Configuration file could not be created!');
        }

        $configTypePath = getcwd() . "/{$name}";

        $this->output->writeln("<info>Configuration created successfully at {$configTypePath}</info>");
    }

    /**
     * Gets the value of force.
     *
     * @return mixed
     */
    public function getForce()
    {
        return $this->force;
    }

    /**
     * Sets the value of force.
     *
     * @param mixed $force the force
     *
     * @return self
     */
    public function setForce($force)
    {
        $this->force = $force;

        return $this;
    }
}
