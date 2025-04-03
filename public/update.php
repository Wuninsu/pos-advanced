<?php
$latest_version_url = "https://github.com/wuninsu/echo-pos-system/releases/latest/download/update.zip";
$destination = __DIR__ . "/../update.zip";

// Download the latest version
file_put_contents($destination, file_get_contents($latest_version_url));

// Extract update
$zip = new ZipArchive;
if ($zip->open($destination) === TRUE) {
    $zip->extractTo(__DIR__ . "/../");
    $zip->close();
    echo "Update complete!";
    unlink($destination); // Delete ZIP file after extraction
} else {
    echo "Failed to update.";
}
