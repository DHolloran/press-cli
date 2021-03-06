<?php
namespace PressCLI\Option;

trait DefaultConfiguration
{
    /**
     * The default Press CLI configuration.
     *
     * @return array The default configuration.
     */
    public static function getDefaultConfiguration() {
        return [
            'database' => [
                'user' => 'root',
                'password' => 'root',
                'prefix' => 'wp_',
                'name' => '',
                'host' => '127.0.0.1',
            ],
            'plugins' => [
                [
                    'plugin' => 'jetpack',
                    'activate' => false,
                ],
                [
                    'plugin' => 'crop-thumbnails',
                    'activate' => true,
                ],
                [
                    'plugin' => 'query-monitor',
                    'activate' => true,
                ],
                [
                    'plugin' => 'force-regenerate-thumbnails',
                    'activate' => true,
                ],
                [
                    'plugin' => 'force-regenerate-thumbnails',
                    'activate' => true,
                ],
                [
                    'plugin' => 'mailtrap-for-wp',
                    'activate' => false,
                ],
            ],
            'user' => [
                'name' => '',
                'email' => '',
                'password' => '',
            ],
            'theme' => [
                'type' => 'zip',
                'url' => 'https://github.com/matchboxdesigngroup/kindling/archive/1.0.1.zip',
                'name' => '',
            ],
            'menus' => [
                [
                    'name' => 'Primary Navigation',
                    'location' => 'primary_navigation',
                ],
                [
                    'name' => 'Footer Navigation',
                    'location' => 'footer_navigation',
                ],
                [
                    'name' => 'Social Menu',
                    'location' => 'social_menu',
                ],
            ],
            'site' => [
                'title' => '',
                'url' => '',
            ],
            'rewrite' => [
                'structure' => '/%postname%/',
            ],
            'commands' => [
                'preInstall' => [],
                'postInstallTheme' => [
                    'yarn',
                    'bower install',
                ],
                'postInstall' => [],
            ],
            'settings' => [
                'afterInstall' => [
                    'removeUserPassword' => true,
                    'removeUserEmail' => true,
                    'removeUserName' => true,
                ],
            ],
        ];
    }
}
