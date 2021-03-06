<?php
namespace PressCLI\WPCLI;

use PressCLI\WPCLI\CLI;
use PressCLI\Option\Configuration;
use Symfony\Component\Console\Output\OutputInterface;

trait Plugin
{
    /**
     * Deletes all unused default plugins.
     */
    public static function pluginDeleteDefaults()
    {
        $plugins = [
            'akismet',
            'hello',
        ];

        foreach ($plugins as $plugin) {
            self::pluginDelete($plugin);
        }
    }

    /**
     * Deletes a plugin.
     *
     * @param  string $plugin Theme name to delete.
     */
    public static function pluginDelete($plugin)
    {
        CLI::execCommand('plugin', ['delete', $plugin]);
    }

    /**
     * Installs all of the plugins from the configuration.
     *
     * @param  OutputInterface $output
     */
    public static function pluginInstallAll(OutputInterface $output)
    {
        $config = Configuration::get();
        $plugins = isset($config['plugins']) ? $config['plugins'] : [];
        foreach ($plugins as $plugin) {
            $version = isset($plugin['version']) ? $plugin['version'] : '';
            $activate = isset($plugin['activate']) ? (bool) $plugin['activate'] : false;
            $location = isset($plugin['location']) ? $plugin['location'] : '';

            // Let the user know which custom plugin is being installed.
            if ($location) {
                $locVersion = $version ? "($version)" : '';
                $output->writeln(trim("Installing {$plugin['plugin']} {$locVersion}"));
            }

            // Install the plugin.
            $install = $location ? $location : $plugin['plugin'];
            self::pluginInstall($install, $activate, $version);

            $output->writeln('');
        }
    }

    /**
     * Installs and optionally activates plugin.
     *
     * @param string  $plugin   A plugin slug, the path to a local zip file, or URL to a remote zip file.
     * @param boolean $activate Optional, if true the plugin will be activated immediately after install.
     * @param string  $version  Optional, version of plugin to install. Default stable.
     */
    public static function pluginInstall($plugin, $activate = true, $version = null)
    {
        $options = [ 'force' => '' ];

        if ($activate) {
            $options['activate'] = '';
        }

        if (! is_null($version) && $version) {
            $options['version'] = $version;
        }

        CLI::execCommand('plugin', ['install', $plugin], $options);
    }
}
