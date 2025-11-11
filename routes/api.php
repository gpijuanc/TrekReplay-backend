<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; // Importem el controlador

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rutes públiques (no requereixen token)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);