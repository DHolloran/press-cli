<?php

namespace App\Configuration;

use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Configuration {
    protected $yamlConfig = 'config.yml';

    protected $jsonConfig = 'config.json';

    protected $configType = 'yaml';

    protected $globalConfig = '~/snutil-config.yaml';

    protected $output;

    /**
     * Creates a new class instance.
     *
     * @param string $output
     */
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    abstract protected function checkIfConfigExists();

    /**
     * The configuration values.
     *
     * @param   array $config
     *
     * @return  array
     */
    public function defaultValues($config = [])
    {
        return array_merge([], $config);
    }

    /**
     * Gets the value of yamlConfig.
     *
     * @return mixed
     */
    public function getYAMLConfig()
    {
        return $this->yamlConfig;
    }

    /**
     * Gets the value of jsonConfig.
     *
     * @return mixed
     */
    public function getJSONConfig()
    {
        return $this->jsonConfig;
    }

    /**
     * Gets the value of globalConfig.
     *
     * @return mixed
     */
    public function getGlobalConfig()
    {
        return $this->globalConfig;
    }

    /**
     * Sets the value of globalConfig.
     *
     * @param mixed $globalConfig the global config
     *
     * @return self
     */
    public function setGlobalConfig($globalConfig)
    {
        $this->globalConfig = $globalConfig;

        return $this;
    }

    /**
     * Sets the value of yamlConfig.
     *
     * @param mixed $yamlConfig the yaml config
     *
     * @return self
     */
    public function setYAMLConfig($yamlConfig)
    {
        $this->yamlConfig = $yamlConfig;

        return $this;
    }

    /**
     * Sets the value of jsonConfig.
     *
     * @param mixed $jsonConfig the json config
     *
     * @return self
     */
    public function setJSONConfig($jsonConfig)
    {
        $this->jsonConfig = $jsonConfig;

        return $this;
    }

    /**
     * Gets the value of configType.
     *
     * @return mixed
     */
    public function getConfigType()
    {
        return $this->configType;
    }

    /**
     * Sets the value of configType.
     *
     * @param mixed $configType the config type
     *
     * @return self
     */
    public function setConfigType($configType)
    {
        $this->configType = $configType;

        return $this;
    }
}
