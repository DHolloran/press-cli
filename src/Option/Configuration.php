<?php
namespace KindlingCLI\Option;

use Symfony\Component\Yaml\Yaml;
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
        $output->writeln("<info>Creating project configuration...</info>");
        $configFile = rtrim($directory, '/') . '/' . self::$configFileName;

        if (file_exists($configFile)) {
            $output->writeln("<comment>Project configuration already exists at {$configFile}.</comment>");

            return;
        }

        // Add configuration.
        $config = Yaml::dump(self::configSkeleton($name));
        file_put_contents($configFile, $config);

        $output->writeln("<info>Project configuration created at {$configFile}!</info>");
    }

    /**
     * Get the configuration.
     *
     * @return array
     */
    public static function get()
    {
        if (!self::configExists()) {
            return [];
        }

        Yaml::parse(file_get_contents(self::$configFileName));

        return $config;
    }

    /**
     * Configuration skeleton
     *
     * @return string
     */
    protected static function configSkeleton($name)
    {
        include KCLI_EXEC_DIR . '/template.config.php';

        $config['database']['name'] = self::makeDbName($name);
        $config['site']['url'] = self::makeURL($name);
        $config['theme']['name'] = self::makeThemeName($name);

        return $config;
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
    public static function configExists()
    {
        return file_exists(self::$configFileName);
    }
}
