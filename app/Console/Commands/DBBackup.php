<?php

namespace App\Console\Commands;

use GuzzleHttp\Psr7\UploadedFile;
use Illuminate\Console\Command;

class DBBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:db-backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = 'database' . date('d-m-Y');
        $path = UploadFile('', 'backups', $name);
    }
}
