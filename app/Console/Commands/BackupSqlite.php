<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupSqlite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backup-sqlite';

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
        $sqlitePath = database_path('database.sqlite');
        $backupDir = storage_path('app/backups');
        $date = now()->format('Y-m-d_H-i-s');
        $backupFile = $backupDir . "/sqlite_backup_{$date}.sqlite";
        $zipFile = $backupDir . "/sqlite_backup_{$date}.zip";

        // Create backup directory if it doesn't exist
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        // Copy SQLite file
        if (!copy($sqlitePath, $backupFile)) {
            $this->error("Failed to copy SQLite database.");
            return 1;
        }

        // Zip the backup
        $zip = new \ZipArchive();
        if ($zip->open($zipFile, \ZipArchive::CREATE) === TRUE) {
            $zip->addFile($backupFile, basename($backupFile));
            $zip->close();

            // Optional: Delete raw backup after zipping
            unlink($backupFile);

            $this->info("Backup created: $zipFile");
        } else {
            $this->error("Failed to create zip archive.");
            return 1;
        }

        return 0;
    }
}
