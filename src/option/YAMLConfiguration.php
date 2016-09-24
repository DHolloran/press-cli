<?php
namespace KindlingCLI\Option;

use Symfony\Component\Console\Output\OutputInterface;

class YAMLConfiguration
{
    protected static $configFileName = '.kindling.yaml';

    /**
     * Creates the configuration file.
     *
     * @param  string          $directory
     * @param  OutputInterface $output
     */
    public static function createConfiguration($directory, OutputInterface $output)
    {
        $output->writeln("<info>Creating project configuration...</info>");
        $configFile = rtrim( $directory, '/' ) . '/' . self::$configFileName;

        if (file_exists($configFile)) {
            $output->writeln("<comment>Project configuration already exists at {$configFile}!</comment>");

            return;
        }

        file_put_contents($configFile, self::configSkeleton());

        $output->writeln("<info>Project configuration created at {$configFile}!</info>");
    }

    /**
     * Get the configuration.
     *
     * @return array
     */
    public static function get()
    {
        // Place holder for configuration.
        include KCLI_EXEC_DIR . '/config.php';

        return $config;
    }

    /**
     * Configuration skeleton
     *
     * @return string
     */
    protected static function configSkeleton()
    {
        return '# @todo';
    }
}
