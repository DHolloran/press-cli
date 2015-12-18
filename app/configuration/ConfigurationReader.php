<?php

namespace App\Configuration;

use Symfony\Component\Yaml\Parser;
use App\Configuration\Configuration;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigurationReader extends Configuration
{
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
     * Checks for an existing configuration file.
     */
    protected function checkIfConfigExists()
    {
        if (!file_exists($this->getJSONConfig()) and  !file_exists($this->getYAMLConfig())) {
            throw new \Exception("Configuration does not exist! Please run snutil init to get started.");
        }
    }

    /**
     * Read the configuration file.
     *
     * @return  array
     */
    public function config()
    {
        $this->checkIfConfigExists();

        switch ($this->getConfigType()) {
            case 'json':
                 return $this->jsonConfig();
                break;

            default:
                return $this->yamlConfig();
                break;
        }
    }

    /**
     * Read the JSON configuration.
     *
     * @return  array
     */
    protected function jsonConfig()
    {
        $file = $this->configFile($this->getJSONConfig());

        return json_decode($file, true);
    }

    /**
     * Read the YAML configuration.
     *
     * @return  array
     */
    protected function yamlConfig()
    {
        $yaml = new Parser();
        $file = $this->configFile($this->getYAMLConfig());

        return $yaml->parse($file);
    }

    /**
     * Reads the configuration file.
     *
     * @param  string $path
     *
     * @return mixed
     */
    protected function configFile($path)
    {
        if (!file_exists($path)) {
            throw new \Exception("Configuration {$path} does not exist! Please run snutil init to get started.");
        }

        return file_get_contents($path);
    }
}
