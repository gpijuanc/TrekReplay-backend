<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; // Importem el controlador
use App\Http\Controllers\ViatgeController; //importem el controlador de viatge

// Rutes públiques (no requereixen token)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutes protegides (requereixen token)
Route::middleware('auth:sanctum')->group(function () {
    
    // Ruta d'usuari (ja hi era)
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // 2. AFEGEIX AQUESTES LÍNIES PER AL CRUD DE VIATGES
    Route::apiResource('viatges', ViatgeController::class);
});