<?php

use App\Http\Controllers\RowController;
use App\Http\Controllers\UploadFileController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.basic')->group(function () {
    Route::post('/upload-file', [UploadFileController::class, 'upload']);
    Route::get('/rows', [RowController::class, 'index']);
});

