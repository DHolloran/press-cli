<?php
namespace KindlingCLI\WPCLI;

use KindlingCLI\WPCLI\DB;
use KindlingCLI\WPCLI\Core;
use KindlingCLI\WPCLI\Theme;
use KindlingCLI\WPCLI\Plugin;
use KindlingCLI\WPCLI\Rewrite;

class WP
{
    use Core, DB, Plugin, Rewrite, Theme;
}
