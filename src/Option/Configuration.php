<?php
namespace PressCLI\Option;

use PressCLI\Option\GlobalConfiguration;
use PressCLI\Option\DefaultConfiguration;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Output\OutputInterface;

class Configuration
{
    use DefaultConfiguration;

    /**
     * Configuration file name.
     *
     * @var string
     */
    protected static $configFileName = '.press-cli.json';

    /**
     * Creates the configuration file.
     *
     * @param string          $directory
     * @param string          $name
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param QuestionHelper  $helper
     */
    public static function create($directory, $name, InputInterface $input, OutputInterface $output, QuestionHelper $helper)
    {
        // Create the global configuration if needed.
        if (!GlobalConfiguration::exists()) {
            $output->writeln('');
            GlobalConfiguration::create($output);
            $output->writeln('');
        }

        // Start local configuration.
        $output->writeln("\n<info>Creating project configuration...</info>");
        $configFile = rtrim($directory, '/') . '/' . self::$configFileName;

        if (file_exists($configFile)) {
            $output->writeln("<comment>Project configuration already exists at {$configFile}.</comment>");

            return;
        }

        // Add configuration from skeleton.
        $skeleton = self::configSkeleton($name, $input, $output, $helper);
        self::write($skeleton, $configFile);

        $output->writeln("<info>\nProject configuration created at {$configFile}!</info>");
    }

    /**
     * Writes the configuration to the file.
     *
     * @param array  $config
     * @param string $configFile
     */
    protected static function write($config, $configFile = null)
    {
        $configFile = is_null($configFile) ? self::$configFileName : $configFile;
        $config = json_encode($config, JSON_PRETTY_PRINT);
        $config = stripslashes($config);
        file_put_contents($configFile, $config);
    }

    /**
     * Removes the user password.
     */
    public static function cleanUpConfigForVCS()
    {
        $global = GlobalConfiguration::get();
        $afterInstall = isset($global['settings']['afterInstall']) ? $global['settings']['afterInstall'] : [];

        if (!$afterInstall) {
            return;
        }

        $config = self::get();

        // Remove user password.
        $removeUserPassword = isset($afterInstall['removeUserPassword']) ? (bool) $afterInstall['removeUserPassword'] : false;
        if ($removeUserPassword) {
            $config['user']['password'] = '';
        }

        // Remove user email.
        $removeUserPassword = isset($afterInstall['removeUserEmail']) ? (bool) $afterInstall['removeUserEmail'] : false;
        if ($removeUserPassword) {
            $config['user']['email'] = '';
        }

        // Remove user name.
        $removeUserPassword = isset($afterInstall['removeUserName']) ? (bool) $afterInstall['removeUserName'] : false;
        if ($removeUserPassword) {
            $config['user']['name'] = '';
        }

        self::write($config);
    }

    /**
     * Get the configuration.
     *
     * @return array
     */
    public static function get()
    {
        if (!self::exists()) {
            return [];
        }

        $config = json_decode(file_get_contents(self::$configFileName), true);

        return $config;
    }

    /**
     * Configuration skeleton
     *
     * @return string
     */
    protected static function configSkeleton($name, InputInterface $input, OutputInterface $output, QuestionHelper $helper)
    {
        $config = self::getDefaultConfiguration();

        // Merge global and local.
        $config = self::emptyConfig($config);
        $config = self::mergeConfiguration($config, GlobalConfiguration::get());
        $config = self::removeDuplicatePlugins($config);

        // Build out some standard stuff.
        $config['database']['name'] = self::makeDbName($name);
        $config['site']['url'] = self::makeURL($name);
        $config['theme']['name'] = self::makeThemeName($name);

        // Validate the configuration options.
        $config = Validator::validate($config, $helper, $input, $output);

        // Remove settings for local configuration.
        if (isset($config['settings'])) {
            unset($config['settings']);
        }

        return $config;
    }

    /**
     * Removes duplicate plugins.
     *
     * @param  array $config
     *
     * @return array
     */
    protected static function removeDuplicatePlugins($config)
    {
        $plugins = [];
        foreach ($config['plugins'] as $plugin) {
            $key = $plugin['plugin'];
            $plugins[ $key ] = $plugin;
        }

        $config['plugins'] = array_values($plugins);

        return $config;
    }

    /**
     * Merges the global configuration into the local configuration.
     *
     * @param  array $config1
     * @param  array $config2
     *
     * @return array
     */
    protected static function mergeConfiguration($config1, $config2)
    {
        foreach ($config2 as $key => $value) {
            if (array_key_exists($key, $config1) && is_array($value)) {
                $config1[$key] = self::mergeConfiguration($config1[$key], $config2[$key]);
            } else {
                $config1[$key] = $value;
            }
        }

        return $config1;
    }

    /**
     * Creates the database name from the initialization name.
     *
     * @param  string $name The name.
     *
     * @return string       The database name.
     */
    protected static function makeDbName($name)
    {
        $dbname = self::slugify($name, '_');

        return "wp_{$dbname}";
    }

    /**
     * Creates the URL from the initialization name.
     *
     * @param  string $name The name.
     *
     * @return string       The URL.
     */
    protected static function makeURL($name)
    {
        $url = self::slugify($name);

        return "http://{$url}.dev";
    }

    /**
     * Creates the theme name from the initialization name.
     *
     * @param  string $name The name.
     *
     * @return string       The theme name.
     */
    protected static function makeThemeName($name)
    {
        $themeName = self::slugify($name);

        return $themeName;
    }

    /**
     * Sanitizes the value.
     *
     * @param  string $value     The value.
     * @param  string $separator The separator to use for spaces.
     *
     * @return string            The sanitized value.
     */
    protected static function slugify($value, $separator = '-')
    {
        $value = str_replace([' ', '-', '_'], $separator, $value);
        $value = preg_replace('/[^A-Za-z0-9-_]/um', '', $value);
        $value = strtolower($value);

        return $value;
    }

    /**
     * Checks if the configuration exists.
     *
     * @return boolean
     */
    public static function exists()
    {
        return file_exists(self::$configFileName);
    }

    /**
     * Empty configuration values.
     *
     * @param  array $config The configuration.
     *
     * @return array         The emptied configuration.
     */
    protected static function emptyConfigValues($config) {
        return array_map(function($value) {
            if (is_array($value)) {
                $value = self::emptyConfigValues($value);
                return $value;
            }

            return '';
        }, $config);
    }

    /**
     * Empties the configuration.
     *
     * @param  array $config The configuration.
     *
     * @return array         The emptied configuration.
     */
    protected static function emptyConfig($config) {
        $config['plugins'] = [];
        $config['menus'] = [];
        $config['commands'] = isset($config['commands']) ? $config['commands'] : [];
        $config['commands'] = array_map(function() {
            return [];
        }, $config['commands']);

        $config = self::emptyConfigValues($config);

        return $config;
    }
}
