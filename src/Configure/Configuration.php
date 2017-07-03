<?php

namespace PressCLI\Configure;

abstract class Configuration
{
    /**
     * Gets the configuration path.
     *
     * @return string
     */
    abstract protected function getPath();

    /**
     * Gets the configuration stub path.
     *
     * @return string
     */
    abstract protected function getStubPath();

    /**
     * Gets the configuration to write to the configuration file.
     *
     * @return Illuminate\Support\Collection
     */
    abstract protected function getConfigurationtoWrite();

    /**
     * Reads the configuration.
     *
     * @param string $path
     *
     * @return Illuminate\Support\Collection
     */
    protected function read($path = null)
    {
        $path = is_null($path) ? $this->getPath() : $path;

        if (file_exists($path)) {
            return collect(json_decode(file_get_contents($path), true));
        }

        return collect([]);
    }

    /**
     * Reads the stub file.
     *
     * @return Illuminate\Support\Collection
     */
    protected function readStub()
    {
        return $this->read($this->getStubPath());
    }

    /**
     * Writes the configuration.
     *
     * @param string $path
     */
    public function write($path = null)
    {
        $path = is_null($path) ? $this->getPath() : $path;

        file_put_contents(
            $path,
            $this->getConfigurationtoWrite()->toJson()
        );
    }
}
