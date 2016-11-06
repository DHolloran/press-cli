<?php
namespace PressCLI\WPCLI;

use PressCLI\WPCLI\CLI;
use PressCLI\Option\Configuration;

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
        $config = Configuration::get();
        $rewrite = isset($config['rewrite']) ? $config['rewrite'] : [];
        $rewrite = isset($rewrite['structure']) ? $rewrite['structure'] : '/%postname%/';

        CLI::execCommand('rewrite', ['structure', "'{$rewrite}'"], ['hard' => '']);
        CLI::execCommand('rewrite', ['flush'], ['hard' => '']);
    }
}
