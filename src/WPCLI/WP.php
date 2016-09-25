<?php
namespace KindlingCLI\WPCLI;

use KindlingCLI\WPCLI\DB;
use KindlingCLI\WPCLI\Core;
use KindlingCLI\WPCLI\Post;
use KindlingCLI\WPCLI\Theme;
use KindlingCLI\WPCLI\Plugin;
use KindlingCLI\WPCLI\Rewrite;
use KindlingCLI\WPCLI\Menu;

class WP
{
    use Core, DB, Plugin, Rewrite, Theme, Post, Menu;
}
