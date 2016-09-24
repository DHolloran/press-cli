<?php
namespace KindlingCLI\WPCLI;

class CLI
{
    /**
     * Executes a WP CLI command.
     *
     * @todo Set an install path.
     *
     * @param  string $command   The command to execute.
     * @param  array  $arguments Optional, command arguments. Default none.
     * @param  array  $options   Optional, command options. Default none.
     *
     * @return string The output from the executed command or NULL if an error occurred or the command produces no output.
     */
    public static function execCommand($command, $arguments = [], $options = [])
    {
        $arguments = trim(implode(' ', $arguments));
        $options = self::execOptions($options);
        $command = trim("./../vendor/bin/wp {$command}");
        $command = implode(' ', array_filter([$command, $arguments, $options]));

        return system($command);
    }

    protected static function execOptions($options)
    {
        $options = array_map(function ($key, $value) {
            if ($value) {
                return "--{$key}='{$value}'";
            }

            return "--{$key}";
        }, array_keys($options), $options);

        return trim(implode(' ', $options));
    }
}
