<?php

namespace PressCLI\Configure;

class GlobalConfiguration extends Configuration
{
    /**
     * {@inheritDoc}
     */
    protected function getPath()
    {
        return PRESS_GLOBAL_CONFIG;
    }

    /**
     * {@inheritDoc}
     */
    protected function getStubPath()
    {
        return PRESS_STUBS . "/.press-cli.json";
    }

    /**
     * {@inheritDoc}
     */
    protected function getConfigurationtoWrite()
    {
        $stub = $this->readStub();

        return $stub;
    }
}
