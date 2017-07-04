<?php

namespace PressCLI\Configure;

use Symfony\Component\Console\Input\InputInterface;

class ConfigurationDefaults
{
    /**
     * The configuration
     *
     * @var Illuminate\Support\Collection
     */
    protected $configuration;

    /**
     * Console input interface
     *
     * @var InputInterface
     */
    protected $input;

    /**
     * @param Illuminate\Support\Collection $configuration
     * @param InputeInterface $input
     */
    public function __construct($configuration, InputInterface $input)
    {
        $this->configuration = $configuration;
        $this->input = $input;
    }

    /**
     * Sets default configuration.
     *
     * @return Illuminate\Support\Collection
     */
    public function set()
    {
        // Database
        $this->setDatabase();

        // Site
        $this->setSite();

        return $this->configuration;
    }

    /**
     * Sets the database defaults.
     */
    protected function setDatabase()
    {
        // Set the database name.
        $database = $this->configuration->get('database', collect([]))
                    ->put('name', "wp_{$this->getCleanSiteName('_')}");

        // Update the configuration
        $this->configuration->put('database', $database);
    }

    /**
     * Sets the site defaults.
     */
    protected function setSite()
    {
        // Set the site url.
        $name = $this->getCleanSiteName();
        $site = $this->configuration->get('site', collect([]))
                    ->put('url', "http://{$name}.dev")
                    ->put('title', ucwords(str_replace('-', ' ', $name)));

        // Update the configuration
        $this->configuration->put('site', $site);
    }

    /**
     * Gets clean site name.
     *
     * @return string
     */
    protected function getCleanSiteName($seperator = '-')
    {
        $name = $this->input->getArgument('project-name');
        $name = preg_replace("/[^A-Za-z0-9-_ ]/", '', $name);
        $name = str_replace(['-', '_', ' '], $seperator, $name);

        return $name;
    }
}
