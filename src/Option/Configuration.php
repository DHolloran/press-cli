<?php
namespace KindlingCLI\Option;

use KindlingCLI\Option\GlobalConfiguration;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Output\OutputInterface;

class Configuration
{
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
        $config = json_encode($skeleton, JSON_PRETTY_PRINT);
        $config = stripslashes($config);
        file_put_contents($configFile, $config);

        $output->writeln("<info>\nProject configuration created at {$configFile}!</info>");
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
        include KCLI_EXEC_DIR . '/templates/config.php';

        // Merge global and local.
        $config = self::mergeConfiguration($config, GlobalConfiguration::get());
        $config = self::removeDuplicatePlugins($config);

        // Build out some standard stuff.
        $config['database']['name'] = self::makeDbName($name);
        $config['site']['url'] = self::makeURL($name);
        $config['theme']['name'] = self::makeThemeName($name);

        // Validate the configuration options.
        $config = Validator::validate($config, $helper, $input, $output);

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
        $dbname = self::sanitizeValue($name);
        $dbname = str_replace('-', '_', $dbname);
        $dbname = strtolower($dbname);

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
        $url = self::sanitizeValue($name);
        $url = str_replace('_', '-', $url);
        $url = strtolower($url);

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
        $themeName = self::sanitizeValue($name);
        $themeName = str_replace('_', '-', $themeName);
        $themeName = strtolower($themeName);

        return $themeName;
    }

    /**
     * Creates the database name from the initialization name.
     *
     * @param  string $value The value.
     *
     * @return string       The sanitized value.
     */
    protected static function sanitizeValue($value)
    {
        $value = str_replace(' ', '-', $value);

        return preg_replace('/[^A-Za-z0-9-_]/um', '', $value);
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
}
