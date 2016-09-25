<?php

$config = [
    'database' => [
        'user' => 'root',
        'password' => 'root',
        'prefix' => 'wp_',
        'name' => 'wp_test',
        'host' => '127.0.0.1:8889',
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
            'plugin' => 'disable-comments',
            'activate' => false,
        ],
        [
            'plugin' => '~/Desktop/plugins/advanced-custom-fields.zip',
            'activate' => true,
            'serial' => '',
        ],
        [
            'plugin' => '~/Desktop/plugins/gravityforms.zip',
            'activate' => true,
            'serial' => '',
        ],
        [
            'plugin' => '~/Desktop/plugins/wp-migrate-db-pro.zip',
            'activate' => true,
            'serial' => '',
        ],
    ],
    'user' => [
        'username' => 'dholloran',
        'email' => 'dholloran@matchboxdesigngroup.com',
        'password' => 'jooL8bYGrYdkVyHKvAeFDsiBnyEB9y',
    ],
    'theme' => [
        'type' => 'zip',
        'url' => 'https://github.com/matchboxdesigngroup/kindling/archive/1.0.1.zip',
        'name' => 'test-theme',
        'style-css' => [
            'theme-name' => 'Theme Name',
            'client' => 'Client Name',
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
        'title' => 'Site Title',
        'url' => 'test.dev',
    ],
    'commands' => [
        'preInstall' => [],
        'postInstallTheme' => [
            'npm install',
            'bower install',
        ],
        'postInstall' => [],
    ]
];
