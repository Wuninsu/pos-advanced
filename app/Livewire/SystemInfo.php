<?php

namespace App\Livewire;

use App\Http\Controllers\UpdateController;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Title;
use Livewire\Component;
use ZipArchive;
use Illuminate\Http\UploadedFile;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Support\Facades\Log;

class SystemInfo extends Component
{
    public $files = [];
    public array $backups = [];

    public $currentVersion;
    public $latestVersion;
    public $updateAvailable = false;
    public int $progress = 0;

    public $updateUrl = 'https://drive.google.com/uc?export=download&id=1WguHAteSjuIIXvX-EArjwXdNV074sjw5';

    public $currentTab = 'overview';

    public function mount()
    {
        $this->loadBackups();
        $this->files = $this->listFilesFromGoogleDrive();
        $this->currentVersion = env('APP_VERSION');
    }


    public function switchTab($tabId)
    {
        $this->currentTab = $tabId;
    }

    public function checkForUpdate()
    {
        $updateData = UpdateController::checkVersion();
        dd($updateData);
        if ($updateData && isset($updateData['latestVersion'])) {
            $this->latestVersion = $updateData['latestVersion'];
            // continue as normal
        }
        // try {
        //     $response = Route::dispatch(Request::create('/api/latest-version', 'GET'));
        //     $data = json_decode($response->getContent(), true); // Decode JSON string to array

        //     $this->latestVersion = $data['latestVersion'] ?? null;

        //     if ($this->latestVersion && version_compare($this->latestVersion, $this->currentVersion, '>')) {
        //         $this->updateAvailable = true;
        //         session()->flash('message', "Latest Version: $this->latestVersion");
        //     } else {
        //         $this->updateAvailable = false;
        //         session()->flash('error', 'You are already using the latest version.');
        //     }
        // } catch (\Exception $e) {
        //     session()->flash('error', "Error fetching update: " . $e->getMessage());
        // }
    }



    public function readJsonFileFromDrive($fileName = 'echo-pos-version-config.json')
    {
        try {
            $client = new Client();
            $client->setAuthConfig(public_path('storage/google/credentials.json'));
            $client->addScope(Drive::DRIVE_READONLY);

            $tokenPath = public_path('storage/google/token.json');
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);

            if ($client->isAccessTokenExpired()) {
                return 'Token expired. Please reauthorize.';
            }

            $service = new Drive($client);

            // Search file by name
            $response = $service->files->listFiles([
                'q' => "name='$fileName' and mimeType='application/json'",
                'fields' => 'files(id, name)',
                'spaces' => 'drive'
            ]);

            dd($response);

            if (count($response->files) == 0) {
                return 'File not found on Google Drive.';
            }

            $fileId = $response->files[0]->id;

            // Download file content
            $content = $service->files->get($fileId, ['alt' => 'media']);

            $jsonString = $content->getBody()->getContents();
            $data = json_decode($jsonString, true);

            return $data; // return the array or do something with it

        } catch (\Exception $e) {
            return 'Error reading file from Google Drive: ' . $e->getMessage();
        }
    }


    public function fetchLatestVersionFromServer()
    {
        try {
            // External API call
            $response = Http::timeout(10)->get('https://updates.yourdomain.com/api/latest-version');

            if ($response->successful()) {
                $data = $response->json(); // Automatically parses JSON to array

                $this->latestVersion = $data['latestVersion'] ?? null;

                if ($this->latestVersion && version_compare($this->latestVersion, $this->currentVersion, '>')) {
                    $this->updateAvailable = true;
                    session()->flash('message', "Latest Version: $this->latestVersion");
                } else {
                    $this->updateAvailable = false;
                    session()->flash('error', 'You are already using the latest version.');
                }
            } else {
                session()->flash('error', 'Failed to fetch latest version. Server responded with status: ' . $response->status());
            }
        } catch (\Exception $e) {
            session()->flash('error', "Error fetching update: " . $e->getMessage());
        }
    }

    public function installUpdate()
    {
        DB::beginTransaction();
        $this->progress = 10;

        try {
            $zipFileName = 'update.zip';
            $updateFilePath = public_path('storage/' . $zipFileName);

            // 1. Download the update ZIP file
            $response = Http::timeout(30)->get($this->updateUrl);
            $this->progress = 30;

            if ($response->successful()) {
                Storage::disk('public')->put($zipFileName, $response->body());
                $this->progress = 50;
            } else {
                throw new \Exception('Failed to download the update.');
            }

            // 2. Backup current version
            $backupFile = 'backup_' . now()->format('YmdHis') . '.zip';
            $backupPath = public_path('storage/' . $backupFile);
            $this->createBackup($backupPath);
            $this->progress = 70;

            // 3. Extract the update ZIP file
            $zip = new ZipArchive;
            if ($zip->open($updateFilePath) === TRUE) {
                $zip->extractTo(base_path());
                $zip->close();
                $this->progress = 90;
            } else {
                throw new \Exception('Failed to extract update.');
            }

            // 4. Clean up
            Storage::disk('public')->delete($zipFileName);
            $this->progress = 100;

            DB::commit();

            session()->flash('success', 'Update installed successfully! Please refresh the application.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Update failed: ' . $e->getMessage());
            $this->progress = 0; // Reset on error
        }
    }

    private function createBackup($backupPath)
    {
        $zip = new ZipArchive;
        if ($zip->open($backupPath, ZipArchive::CREATE) === TRUE) {
            $files = File::allFiles(base_path());

            foreach ($files as $file) {
                $relativePath = str_replace(base_path() . '/', '', $file->getPathname());
                $zip->addFile($file->getPathname(), $relativePath);
            }

            $zip->close();
        } else {
            throw new \Exception('Failed to create backup before update.');
        }
    }

    public function backupAndUploadSQLite()
    {
        DB::beginTransaction();

        $timestamp = now()->format('Y-m-d_H-i-s');
        $sqlitePath = database_path('database.sqlite');
        $tempPath = storage_path("app/temp_backup.sqlite");
        $zipPath = storage_path("app/temp_backup.zip");

        try {
            // Step 1: Copy the database
            if (!copy($sqlitePath, $tempPath)) {
                throw new \Exception('Failed to copy SQLite file.');
            }

            // Step 2: Create ZIP archive
            $zip = new ZipArchive();
            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                $zip->addFile($tempPath, "sqlite_backup_{$timestamp}.sqlite");
                $zip->close();
            } else {
                throw new \Exception('Failed to create ZIP archive.');
            }

            // Step 3: Upload to local storage
            $uploaded = new UploadedFile(
                $zipPath,
                "sqlite_backup_{$timestamp}.zip",
                'application/zip',
                null,
                true
            );

            $uploadedPath = uploadFile($uploaded, 'backups', "sqlite_backup_{$timestamp}");

            // Step 4: Upload to Google Drive
            $this->uploadToGoogleDrive('storage/' . $uploadedPath);
            $this->loadBackups();
            $this->dispatch('backup-status', data: [
                'type' => 'success',
                'message' => '✅ Backup created and successfully uploaded to developers server!',
            ]);
            // Step 5: Clean up
            @unlink($tempPath);
            @unlink($zipPath);

            DB::commit();

            return $uploadedPath;
        } catch (\Throwable $e) {
            DB::rollBack();

            // Cleanup even if it fails
            @unlink($tempPath);
            @unlink($zipPath);

            // Log the error with context
            Log::error('SQLite backup failed', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);

            throw new \Exception('SQLite backup and upload failed. Check logs for details.');
        }
    }

    public function loadBackups()
    {
        $this->backups = Storage::files('backups');

        // Filter only zip files
        $this->backups = array_filter($this->backups, fn($file) => str_ends_with($file, '.zip'));

        // Sort by filename descending
        usort($this->backups, function ($a, $b) {
            return strcmp($b, $a); // Descending order
        });
    }

    public function download($filename)
    {
        return Storage::download($filename);
    }

    public function delete($filename)
    {
        Storage::delete($filename);
        $this->loadBackups(); // Refresh list
        session()->flash('message', 'Backup deleted successfully.');
    }


    function uploadToGoogleDrive($pathToFile)
    {
        try {
            $client = new \Google_Client();
            $client->setAuthConfig(public_path('storage/google/credentials.json'));
            $client->addScope(Drive::DRIVE_FILE);

            $tokenPath = public_path('storage/google/token.json');
            if (!file_exists($tokenPath)) {
                Log::error('Google token file missing.');
                return false;
            }

            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);

            if ($client->isAccessTokenExpired()) {
                Log::error('Google access token expired.');
                return false;
            }

            $service = new Drive($client);

            // Prepare file metadata and content
            $file = new DriveFile();
            $file->setName('PosCL1_backup_' . now()->format('Y-m-d_H-i-s') . '.zip');

            // Upload the file
            $result = $service->files->create($file, [
                'data' => file_get_contents($pathToFile),
                'mimeType' => 'application/zip',
                'uploadType' => 'multipart',
            ]);

            return true;
        } catch (\Throwable $e) {
            Log::error('Failed to upload to Google Drive', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return false;
        }
    }


    function listFilesFromGoogleDrive()
    {
        $client = new Client();
        $client->setAuthConfig(public_path('storage/google/credentials.json'));
        $client->addScope(Drive::DRIVE_READONLY); // Read-only access to the files

        $tokenPath = public_path('storage/google/token.json');
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);

        // Check if the token is expired
        if ($client->isAccessTokenExpired()) {
            return 'Token expired. Reauthorize.';
        }

        $service = new Drive($client);

        // List files from Google Drive
        $files = $service->files->listFiles([
            'pageSize' => 10, // Limit to 10 files
            'fields' => 'nextPageToken, files(id, name)',
        ]);

        $fileList = [];
        foreach ($files->getFiles() as $file) {
            $fileList[] = [
                'id' => $file->getId(),
                'name' => $file->getName(),
            ];
        }

        return $fileList;
    }



    #[Title('System Info')]
    public function render()
    {
        return view('livewire.system-info',  [
            'orders' => [],
            'logs' => [],
            'orderInfo' => [],
            'profile' => []
        ]);
    }
}
