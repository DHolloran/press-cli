<?php
namespace PressCLI\Lib;

use Symfony\Component\Console\Output\OutputInterface;

trait FileSystem
{
    /**
     * Create project directory.
     *
     * @return InitCommand
     */
    protected function createProjectDirectory($directory, OutputInterface $output)
    {
        $output->writeln("<info>Creating project directory...</info>");

        if (file_exists($directory)) {
            $output->writeln("<comment>Project directory already exists at {$directory}.</comment>");

            return $this;
        }

        mkdir($directory, 0755, true);

        $output->writeln("<info>Project directory created at {$directory}!</info>");

        return $this;
    }
}
