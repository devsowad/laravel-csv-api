<?php

use App\Http\Controllers\CSVController;
use Illuminate\Support\Facades\Route;

Route::post('/', [CSVController::class, 'upload'])->name('upload');
Route::get('/batch/{id}', [CSVController::class, 'batch'])->name('batch');
