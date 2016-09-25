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
        'type' => 'zip', // zip, tar, git
        'url' => '',
        'root' => '/',
        'name' => 'test-theme',
        'style-css' => [
            // @todo allow for 'variable' => 'Something' to rewrite {{variable}} in style.css.
        ],
    ],
    'site' => [
        'title' => 'Site Title',
        'url' => 'test.dev',
        'client' => 'Test Client',
    ],
    'commands' => [
        'postInstall' => [
            'npm install',
            'bower install'
        ],
    ]
];
