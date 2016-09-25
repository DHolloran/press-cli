<?php
namespace KindlingCLI\WPCLI;

use KindlingCLI\WPCLI\CLI;
use KindlingCLI\Option\Configuration;
use Symfony\Component\Console\Output\OutputInterface;

trait Rewrite
{
    /**
     * Flushes the rewrite rules.
     */
    public static function rewriteFlush()
    {
        CLI::execCommand('rewrite', ['flush']);
    }

    /**
     * Sets the default permalink rewrite structure.
     */
    public static function rewriteSetStructure()
    {
        $structure = "'/%postname%/'";

        CLI::execCommand('rewrite', ['structure', $structure], ['hard' => '']);
    }
}
