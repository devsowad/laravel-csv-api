<?php

use App\Http\Controllers\CSVController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::post('/upload-file', [CSVController::class, 'upload'])->name('upload');

    Route::get('/batches', [CSVController::class, 'history'])->name('batch');
    Route::get('/batch/pending', [CSVController::class, 'batches']);
    Route::post('/batch/progress', [CSVController::class, 'batches']);
    Route::get('/batch/{id}', [CSVController::class, 'batch'])->name('batch');

    Route::apiResource('/file', FileController::class);
});

require __DIR__ . './auth.php';
