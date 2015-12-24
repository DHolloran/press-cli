<?php

namespace App\Install;

use App\Install\Database;
use Symfony\Component\Console\Output\OutputInterface;

class Install
{
    protected $domain;

    protected $database;

    protected $output;

    protected $directory_name;

    protected $directory_path;

    public function __construct($domain, OutputInterface $output)
    {
        $this->domain = $domain;

        $this->output = $output;

        $this->directory_name = $this->makeDirectoyNameFromDomain($domain);

        $this->directory_path = getcwd() . "/{$this->directory_name}";

        $this->database = new Database($domain, $output);

        $this->wpcli = new WPCli($domain, $output, $this->database, $this->directory_path);
    }

    public function run()
    {
        $this->makeDirectory();
        $this->wpcli->install();
    }

    protected function makeDirectory()
    {
        if ( $this->checkDirectoryExists() ) {
            $this->output->writeln("<comment>The directory {$this->directory_name} already exists!</comment>");
            return;
        }

        $this->output->writeln("<info>Directory {$this->directory_path} created</info>");
        mkdir($this->directory_path);
    }

    protected function checkDirectoryExists()
    {
        return file_exists($this->directory_path);
    }

    protected function makeDirectoyNameFromDomain($domain)
    {
        $directory_name = str_replace( array( '-', '_', '.' ), ' ', $domain );
        $directory_name = preg_replace("/[^A-Za-z0-9 ]/", "", $directory_name);
        $directory_name = preg_replace('!\s+!', ' ', $directory_name);
        $directory_name = str_replace( array( ' ' ), '-', $directory_name );

        return $directory_name;
    }
}
