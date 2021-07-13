<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CSVController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::post('/upload-file', [CSVController::class, 'upload']);

    Route::get('/batches', [CSVController::class, 'history']);
    Route::get('/batch/pending', [CSVController::class, 'batches']);
    Route::post('/batch/progress', [CSVController::class, 'batches']);
    Route::get('/batch/{id}', [CSVController::class, 'batch']);

    Route::apiResource('/file', FileController::class);
});

Route::prefix('auth')->group(function () {

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'me']);
    });
});
