<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ZipArchive;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class UpdateController extends Controller
{
    // public function update()
    // {
    //     $updateData = json_decode(file_get_contents("https://your-server.com/updates/update.json"), true);
    //     $updateZipUrl = $updateData["update_url"];
    //     $savePath = base_path("update.zip");

    //     // Download Update
    //     file_put_contents($savePath, fopen($updateZipUrl, 'r'));

    //     $zip = new ZipArchive;
    //     if ($zip->open($savePath) === TRUE) {
    //         $zip->extractTo(base_path()); // Extract to Laravel root
    //         $zip->close();
    //         unlink($savePath);

    //         // Run Laravel Commands
    //         shell_exec("composer install --no-dev");
    //         shell_exec("php artisan migrate --force");
    //         shell_exec("php artisan optimize:clear");

    //         return response()->json(["message" => "Update applied successfully!"]);
    //     } else {
    //         return response()->json(["error" => "Update failed!"], 500);
    //     }
    // }

    // Return system information
    public function getSystemInfo()
    {
        return response()->json([
            'appVersion' => env('APP_VERSION', '1.0.0'),  // Current app version (from .env)
            'phpVersion' => phpversion(),  // Current PHP version
            'osVersion' => php_uname()  // Current OS version
        ]);
    }

    public function checkUpdate()
    {
        // Get the current version of the app (this can be hardcoded or read from a config file or database)
        $currentVersion = env('APP_VERSION', '1.0.0');  // Example version from .env file

        // Get the latest version from your update service (e.g., a file or a remote server)
        $latestVersion = file_get_contents("http://127.0.0.1:8000/storage/latest_version.txt");
        // $latestVersion = file_get_contents("https://your-server.com/updates/latest_version.txt");
        // Compare versions
        if (version_compare($latestVersion, $currentVersion, '>')) {
            // A new version is available
            return response()->json([
                'updateAvailable' => true,
                'updateUrl' => "https://your-server.com/updates/update.zip",
                'latestVersion' => $latestVersion
            ]);
        }

        // No update available
        return response()->json([
            'updateAvailable' => false
        ]);
    }


    static public function checkVersion()
    {
        $envVersion = env('APP_VERSION');

        $url = 'https://raw.githubusercontent.com/Wuninsu/echo-pos-system/refs/heads/main/version.json';

        $response = Http::withoutVerifying()->get($url);
        if (!$response->successful()) {
            return false;
        }

        $data = $response->json();

        if (!isset($data['latest_version'])) {
            return false;
        }

        return version_compare($data['latest_version'], $envVersion, '>');
    }

    public function update()
    {
        // Get update details from your own versioning API
        $response = Http::get(url('/api/latest-version'));

        if (!$response->successful()) {
            return response()->json(["error" => "Failed to fetch update info."], 500);
        }

        $updateData = $response->json();

        // Check if latest_file key exists, and it contains the URL
        if (!isset($updateData['latest_file'])) {
            return response()->json(["error" => "latest_file not found in response."], 404);
        }

        $updateZipUrl = $updateData['latest_file'];  // This is the URL of the update ZIP file

        // Generate a dynamic file name using timestamp (you can also use a unique ID or version number)
        $timestamp = now()->timestamp; // You can also use unique IDs if you prefer
        $savePath = public_path("storage/updates/update-{$timestamp}.zip");

        // Ensure the updates directory exists
        if (!Storage::exists(public_path('storage/updates'))) {
            Storage::makeDirectory(public_path('storage/updates')); // Create the directory if it doesn't exist
        }

        // Download the zip file from the update URL and save it locally
        try {
            file_put_contents($savePath, fopen($updateZipUrl, 'r'));
        } catch (\Exception $e) {
            return response()->json(["error" => "Failed to download update file."], 500);
        }

        // Extract the contents of the zip file
        $zip = new ZipArchive;
        if ($zip->open($savePath) === true) {
            $zip->extractTo(base_path()); // Extract files to Laravel root
            $zip->close();
            unlink($savePath); // Delete the zip after extraction

            // Run post-update commands
            shell_exec("composer install --no-dev");
            shell_exec("php artisan migrate --force");
            shell_exec("php artisan optimize:clear");

            // Get the latest version from the update data
            $latestVersion = $updateData['latest_version'];

            // Update the APP_VERSION in the .env file
            $this->updateEnvVersion($latestVersion);

            return response()->json(["message" => "Update applied successfully!"]);
        } else {
            return response()->json(["error" => "Failed to extract update."], 500);
        }
    }


    // Function to update APP_VERSION in the .env file
    protected function updateEnvVersion($latestVersion)
    {
        $envFilePath = base_path('.env');

        // Read the .env file
        $envContent = file_get_contents($envFilePath);

        // Update the APP_VERSION line
        $envContent = preg_replace('/^APP_VERSION=.*/m', 'APP_VERSION=' . $latestVersion, $envContent);

        // Write the changes back to the .env file
        file_put_contents($envFilePath, $envContent);

        // Clear the config cache so the new .env is loaded
        Artisan::call('config:cache');
    }
}
