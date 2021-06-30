<?php

use App\Http\Controllers\CSVController;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return 'test';
});

Route::post('/upload-file', [CSVController::class, 'upload'])->name('upload');
Route::get('/batch/pending', [CSVController::class, 'pendingJob']);
Route::get('/batch/{id}', [CSVController::class, 'batch'])->name('batch');
