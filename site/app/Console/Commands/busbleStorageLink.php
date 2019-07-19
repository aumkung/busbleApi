<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class busbleStorageLink extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "busble:storage:link";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "symlinks storage to public";


    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $process = new Process(['ln', '-s', '/var/www/html/storage/app/public', '/var/www/html/public/storage']);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        $this->info('storage link success to [public/storage]');
    }
}