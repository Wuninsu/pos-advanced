<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

use App\Http\Controllers\UpdateController;
use Illuminate\Support\Facades\Storage;

Route::get('/latest-version', function () {

    $filePath = 'latest_version.txt';

    if (Storage::disk('local')->exists($filePath)) {
        return response()->json([
            'latestVersion' => trim(Storage::disk('local')->get($filePath)),
        ]);
    } else {
        return response()->json(['error' => 'Version file not found'], 404);
    }
});
