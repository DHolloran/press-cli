<?php
namespace KindlingCLI\WPCLI;

use KindlingCLI\WPCLI\CLI;
use KindlingCLI\ThemeInstall\Zip;
use KindlingCLI\Option\Configuration;
use Symfony\Component\Console\Output\OutputInterface;

trait Theme
{
    /**
     * Deletes all unused default themes.
     */
    public static function themeDeleteDefaults()
    {
        $themes = [
            'twentyfifteen',
            'twentyfourteen',
        ];

        foreach ($themes as $theme) {
            self::themeDelete($theme);
        }
    }

    /**
     * Deletes a theme.
     *
     * @param  string $theme Theme name to delete.
     */
    public static function themeDelete($theme)
    {
        CLI::execCommand('theme', ['delete', $theme]);
    }

    /**
     * Installs a theme.
     *
     * @param  OutputInterface $output
     */
    public static function themeInstall(OutputInterface $output)
    {
        $config = Configuration::get();
        $url = $config['theme']['url'];
        $name = $config['theme']['name'];
        $directory = getcwd() . '/wp-content/themes';

        // Make sure the theme does not already exist.
        if (file_exists("{$directory}/{$name}")) {
            $output->writeln("Warning: Theme already exists in {$directory}/{$name}.");

            return;
        }

        // Download the theme to the themes directory.
        $theme = Zip::execute($url, $directory, $name);
        if (!$theme) {
            $output->writeln("<error>Error: Theme could not be downloaded from {$url}!</error>");
            return;
        }

        $output->writeln("Success: Theme successfully installed!");
    }
}
