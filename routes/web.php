<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'This is working';
});

// Route::get('/', [CSVController::class, 'home'])->name('upload');
// Route::post('/', [CSVController::class, 'upload'])->name('upload');
// Route::get('/batch/{id}', [CSVController::class, 'batch'])->name('batch');
