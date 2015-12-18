<?php

namespace App\Configuration;

use App\Configuration\Configuration;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigurationReader extends Configuration {
    /**
     * Creates a new class instance.
     *
     * @param string $output
     */
    public function __construct(OutputInterface $output)
    {
        parent::__construct($output);
    }
}
