<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CountyController;
use App\Http\Controllers\Api\PostalCodeController;
use Illuminate\Support\Facades\Route;

// Nyilvános végpontok
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/counties', [CountyController::class, 'index']);
Route::get('/counties/{county}', [CountyController::class, 'show']);
Route::get('/counties/{county}/postal-codes', [CountyController::class, 'postalCodes']);

Route::get('/postal-codes', [PostalCodeController::class, 'index']);
Route::get('/postal-codes/{postalCode}', [PostalCodeController::class, 'show']);

// Adatmódosítás csak tokennel
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/counties', [CountyController::class, 'store']);
    Route::put('/counties/{county}', [CountyController::class, 'update']);
    Route::delete('/counties/{county}', [CountyController::class, 'destroy']);

    Route::post('/postal-codes', [PostalCodeController::class, 'store']);
    Route::put('/postal-codes/{postalCode}', [PostalCodeController::class, 'update']);
    Route::delete('/postal-codes/{postalCode}', [PostalCodeController::class, 'destroy']);
});
