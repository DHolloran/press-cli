<?php

$config = [
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
        // [
        //     'plugin' => 'wp-migrate-db-pro',
        //     'activate' => false,
        //     'location' => '~/Desktop/plugins/wp-migrate-db-pro.zip',
        // ],
    ],
    'user' => [
        'username' => '',
        'email' => '',
        'password' => '',
    ],
    'theme' => [
        'type' => 'zip',
        'url' => 'https://github.com/matchboxdesigngroup/kindling/archive/1.0.1.zip',
        'name' => '',
        'style-css' => [
            'theme-name' => '',
            'client' => '',
            'version' => '1.0.0-dev',
        ],
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
    'rewrite' => '/%postname%/',
    'commands' => [
        'preInstall' => [],
        'postInstallTheme' => [
            'npm install',
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
