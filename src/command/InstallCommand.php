<?php
namespace KindlingCLI\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InstallCommand extends Command
{
    protected function configure()
    {
         $this->setName('install')
              ->setDescription('Installs WordPress, required plugins, theme and dependencies');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'Installation Started',
            '============',
            '',
        ]);
    }
}
