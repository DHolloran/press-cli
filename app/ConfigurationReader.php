<?php

namespace App;

use Symfony\Component\Yaml\Dumper;

class ConfigurationReader {

    protected $yamlConfig;

    protected $jsonConfig;

    /**
     * Creates a new class instance.
     *
     * @param string $yamlConfig
     * @param string $jsonConfig
     */
    public function __construct($yamlConfig = 'config.yml', $jsonConfig = 'config.json')
    {
        $this->jsonConfig = $jsonConfig;
        $this->yamlConfig = $yamlConfig;
    }


    /**
     * Gets the value of yamlConfig.
     *
     * @return mixed
     */
    public function getYamlConfig()
    {
        return $this->yamlConfig;
    }

    /**
     * Gets the value of jsonConfig.
     *
     * @return mixed
     */
    public function getJsonConfig()
    {
        return $this->jsonConfig;
    }
}
