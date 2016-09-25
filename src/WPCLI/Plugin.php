<?php
namespace KindlingCLI\WPCLI;

use KindlingCLI\WPCLI\CLI;
use KindlingCLI\Option\YAMLConfiguration;
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
     */
    public static function pluginInstallAll()
    {
        $config = YAMLConfiguration::get();
        foreach ($config['plugins'] as $plugin) {
            self::pluginInstall($plugin['plugin'], (bool) $plugin['activate']);
        }
    }

    /**
     * Installs and optionally activates plugin.
     *
     * @param string  $plugin   A plugin slug, the path to a local zip file, or URL to a remote zip file.
     * @param boolean $activate If true, the plugin will be activated immediately after install.
     */
    public static function pluginInstall($plugin, $activate = true)
    {
        $options = [];

        if ($activate) {
            $options['activate'] = '';
        }

        CLI::execCommand('plugin', ['install', $plugin], $options);
    }
}
