<?php
namespace PressCLI\WPCLI;

use PressCLI\WPCLI\DB;
use PressCLI\WPCLI\Core;
use PressCLI\WPCLI\Post;
use PressCLI\WPCLI\Theme;
use PressCLI\WPCLI\Plugin;
use PressCLI\WPCLI\Rewrite;
use PressCLI\WPCLI\Menu;

class WP
{
    use Core, DB, Plugin, Rewrite, Theme, Post, Menu;
}
