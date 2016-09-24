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
    public function get()
    {
        return [
            'database' => [
                'user' => 'root',
                'password' => 'root',
                'prefix' => 'wp_',
            ],
            'plugins' => [
                'jetpack' => [ 'activate' => false ],
                'crop-thumbnails' => [ 'activate' => true ],
                'query-monitor' => [ 'activate' => true ],
                'force-regenerate-thumbnails' => [ 'activate' => true ],
            ],
            'user' => [
                'username' => 'dholloran',
                'email' => 'dholloran@matchboxdesigngroup.com',
                'password' => 'jooL8bYGrYdkVyHKvAeFDsiBnyEB9y',
            ],
            // 'theme' => [
            //     'zip' => '',
            //     'root' => '/',
            //     'name' => 'test-theme',
                // Update style.css
            // ],
            'site' => [
                'title' => 'Site Title',
            ],
            'commands' => [
                'postInstall' => [
                    'npm install',
                    'bower install'
                ],
            ]
        ];
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
