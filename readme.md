## Requirements
- Git
- Laravel Valet
- WP-CLI?
- Mac OS X
- WP Migrate DB Pro?
- Composer
- PHP v?

## General Flow
- Setup config in users home directory (Initial run/setup command)
    - Database User
    - Database Password
    - Database prefix (Default to wp_)
    - WP Migrate DB Pro serial?
    - Other plugin serials
        - May need to add the option key for where the plugin stores the serial
        - This may require adding "support" for specific plugins individually
    - Download links for paid plugins?
        - Possibly location on computer that houses paid plugins
        - WP-CLI plugin update `wp plugin update` or some other way if that does not work with the paid plugins
    - List of plugins to always install
        - Add the ability to select if plugin should be activated
    - Generic user details?
    - Path to sites directory?
    - Theme URL
        - Should this be a zip or the master branch?
        - Maybe check for (.zip, .tar.gz, .tar, .git, other to allow for both)
    - Git branch?
    - Theme directory location? (Ours is WP root how is this handled?)
    - Theme rename maybe just use root directory name this may be better in the individual site config or passed as a parameter/question?
    - Should we run npm install
    - Should we run bower install
    - Should we run composer install
    - Other Commands to run in theme on completion
    - wp-config.php addtions/changes?
- Individual Site Options (CLI/Config)
    - possibly have a `$ command init --dir={directoryname}` that creates the directory then changes into the directory and creates a config file
    - then have `$ command install {options}` that takes care of installing all the things
    - Site title
    - Confirm generic user details
    - Confirm other global settings possibly at least command running options

## Database/URLs
- DB Name: wp_{directoryname}
- URL: {directoryname}.dev

## Todo
- Global configuration creation.
- [ ] src/Option/Configuration.php
    - [ ] Build configuration skeleton creation. Merge global configuration with default configuration and write to .kindling.yaml

- [ ] config.php
    - [ ] **database:name** Use `$ kindling init {$name}` to create wp_{name}
    - [ ] **plugins** Merge global/local together. Possibly add all global to local config so we can disable if needed.
    - [ ] **user:username** Verify via CLI input
    - [ ] **user:email** Verify via CLI input
    - [ ] **user:password** Verify via CLI input
    - [ ] **theme:type** Allow for tar and git theme types
    - [ ] **theme:name** Get/Verify from CLI Input (Possibly site title, client name, or init name)
    - [ ] **theme:style-css:theme-name** Get/Verify from CLI Input (Possibly site title, client name, theme:name or init name)
    - [ ] **theme:style-css:client** Verify via CLI input
    - [ ] **theme:style-css:version** Verify via CLI input
    - [ ] **site:title** Get from CLI Input
    - [ ] **site:url** Use `$ kindling init {$name}` to create {name}.dev

- [ ] src/Command/InstallCommand.php
    - [ ] Check for .kindling.yaml before executing and throw error if not found.
    - [ ] Split sections into there own commands? That way you can install via `$ kindling install:all` or `$kindling install:section`
    - [ ] Allow for disabling of certain sections via flag?
    - [ ] Post Git pull commands?
    - [ ] Install PHPUnit scaffold once theme is installed.
    - [ ] Initialize Git repository and remotes.
    - [ ] Read in files as templates. '.gitignore' => 'https://wpengine.com/wp-content/uploads/2013/10/recommended-gitignore-no-wp.txt'
    - [ ] License paid plugins
        - [ ] WP Migrate DB Pro: `define( 'WPMDB_LICENCE', 'XXXXX' );`
        - [ ] Possibly activate paid plugins separately after licensing.
