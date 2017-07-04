<?php

namespace PressCLI;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CLI
{
    /**
     * Creates
     *
     * @param InputInterface  $input  [description]
     * @param OutputInterface $output [description]
     */
    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
    }

    /**
     * Executes a command.
     *
     * @param  string $command
     *
     * @return string|null
     */
    public function execute($command)
    {
        $output = [];
        exec(escapeshellcmd($command), $output, $return_var);

        if (0 !== intval($return_var)) {
            throw new \Exception("Command {$command} failed", $return_var);
        }

        return implode("\n", $output);
    }

    /**
     * Executes a command and passes the output through.
     *
     * @param  string $command
     */
    public function passthru($command)
    {
        passthru(escapeshellcmd($command));
    }

    /**
     * Outputs an info message.
     *
     * @param  string $message
     *
     * @return PressCLI\CLI
     */
    public function info($message)
    {
        return $this->message("<info>{$message}</info>");
    }

    /**
     * Outputs a success message.
     *
     * @param  string $message
     *
     * @return PressCLI\CLI
     */
    public function success($message)
    {
        return $this->message("\n<comment>{$message}</comment>");
    }

    /**
     * Outputs a warning message.
     *
     * @param  string $message
     *
     * @return PressCLI\CLI
     */
    public function warning($message)
    {
        return $this->message("\n<comment>{$message}</comment>");
    }

    /**
     * Output a spacer line.
     *
     * @return PressCLI\CLI
     */
    public function spacer()
    {
        return $this->message("\n");
    }

    /**
     * Output a message.
     *
     * @param  string $message
     *
     * @return PressCLI\CLI
     */
    protected function message($message)
    {
        $this->output->writeln($message);

        return $this;
    }
}
