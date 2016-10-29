#Press CLI
Press CLI is a WordPress installer to help you get up an running faster. It installs WordPress, themes, plugins and more! With the ability to set sane defaults for all your projects with the ability to customize on a prer project basis is need. Learn more about Press CLI at [https://pressc.li/](https://pressc.li/).

## Requirements
- Laravel Valet (Optional, allows for automatically setting up .dev domains) https://laravel.com/docs/valet#installation
- Mac OS X
- Composer
- PHP 5.6+

## Install
1. Install Composer if not already installed.
    - [Installation instructions](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx)
    - Make sure the `~/.composer/vendor/bin directory` is in your system's "PATH".
2. Install PHP if not already installed, you can use [Homebrew](http://brew.sh/) to easily install PHP.
    - Install Hombrew or if Homebrew was already installed make sure it is up to date via `brew update`.
    - Install PHP 7.0 `via brew install homebrew/php/php70`.
3. Install Press CLI via `composer global require dholloran/press-cli`.
4. Install Laravel Valet
    - [Installation Instructions](https://laravel.com/docs/valet#installation)

## Usage
1. `$ press configure --global` creates the global configuration file at `~/.press-cli.json`.
	**Note:** *The global configuration will also be created if it does not exist by the `press new` and `press configure` commands*
2. `press new <project-name>` creates a local `.press-cli.json` and runs the instal.
3. `press configure <project-name>` creates a local configuration without running the install.
4. `press install` runs the install process if a `.press-cli.json` exists in the current directory.

## Attribution
- [WP-CLI](http://wp-cli.org/)
