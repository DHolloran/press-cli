<?php
namespace KindlingCLI\Option;

use Symfony\Component\Yaml\Yaml;
use KindlingCLI\Option\GlobalConfiguration;
use Symfony\Component\Console\Output\OutputInterface;

class Configuration
{
    /**
     * Configuration file name.
     *
     * @var string
     */
    protected static $configFileName = '.kindling.yml';

    /**
     * Creates the configuration file.
     *
     * @param  string          $directory
     * @param  string          $name
     * @param  OutputInterface $output
     */
    public static function create($directory, $name, OutputInterface $output)
    {
        // Create the global configuration if needed.
        if (!GlobalConfiguration::exists()) {
            $output->writeln('');
            GlobalConfiguration::create($output);
            $output->writeln('');
        }

        // Start local configuration.
        $output->writeln("<info>Creating project configuration...</info>");
        $configFile = rtrim($directory, '/') . '/' . self::$configFileName;

        if (file_exists($configFile)) {
            $output->writeln("<comment>Project configuration already exists at {$configFile}.</comment>");

            return;
        }

        // Add configuration from skeleton.
        $config = Yaml::dump(self::configSkeleton($name));
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

        $config = Yaml::parse(file_get_contents(self::$configFileName));

        return $config;
    }

    /**
     * Configuration skeleton
     *
     * @return string
     */
    protected static function configSkeleton($name)
    {
        include KCLI_EXEC_DIR . '/templates/config.php';

        $config['database']['name'] = self::makeDbName($name);
        $config['site']['url'] = self::makeURL($name);
        $config['theme']['name'] = self::makeThemeName($name);

        $config = self::mergeGlobalConfiguration($config);
        ksort($config);

        return $config;
    }

    /**
     * Merges the global configuration into the local configuration.
     *
     * @param  array $local The local configuration.
     *
     * @return array         The merged configuration.
     */
    protected static function mergeGlobalConfiguration($local)
    {
        $global = GlobalConfiguration::get();

        foreach ($local as $key => $value) {
            if (array_key_exists($key, $global) && is_array($value)) {
                $global[$key] = self::mergeGlobalConfiguration($global[$key], $local[$key]);
            } else {
                $global[$key] = $value;
            }
        }

        return $global;
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
