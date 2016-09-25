<?php
namespace KindlingCLI\WPCLI;

use KindlingCLI\WPCLI\CLI;
use KindlingCLI\Option\YAMLConfiguration;
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
}
