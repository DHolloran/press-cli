<?php

$config = [
    'database' => [
        'user' => 'root',
        'password' => 'root',
        'prefix' => 'wp_',
        'name' => 'wp_test',  // @todo Use `$ kindling init {$name}` to create wp_{name}
        'host' => '127.0.0.1:8889',
    ],
    'plugins' => [
        // @todo Merge global/local together.
        // Possibly add all global to local config so we can disable if needed.
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
        'username' => 'dholloran',  // @todo Verify from CLI Input
        'email' => 'dholloran@matchboxdesigngroup.com',  // @todo Verify from CLI Input
        'password' => 'jooL8bYGrYdkVyHKvAeFDsiBnyEB9y',  // @todo Verify from CLI Input
    ],
    'theme' => [
        'type' => 'zip', // @todo zip, tar, git
        'url' => 'https://github.com/matchboxdesigngroup/kindling/archive/1.0.1.zip',
        'name' => 'test-theme',  // @todo Get/Verify from CLI Input (Possibly site title, client name, or init name)
        'style-css' => [
            'theme-name' => 'Theme Name', // @todo Get from CLI Input (Possibly use `$ kindling init {$name}`)
            'client' => 'Client Name', // @todo Get from CLI Input
            'version' => '1.0.0-dev', // @todo Verify from CLI Input
        ],
    ],
    'site' => [
        'title' => 'Site Title', // @todo Get from CLI Input
        'url' => 'test.dev', // @todo Use `$ kindling init {$name}` to create {name}.dev
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
