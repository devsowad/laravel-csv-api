<?php

use App\Http\Controllers\CSVController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::post('/upload-file', [CSVController::class, 'upload'])->name('upload');
    
    Route::get('/batches', [CSVController::class, 'batches'])->name('batch');
    Route::get('/batch/pending', [CSVController::class, 'pendingJob']);
    Route::get('/batch/{id}', [CSVController::class, 'batch'])->name('batch');
});

require __DIR__ . './auth.php';
