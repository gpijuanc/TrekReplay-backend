<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\ViatgeController;
use App\Http\Controllers\CarretVirtualController;

// Rutes públiques (no necessiten token)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutes protegides (necessiten token)
Route::middleware('auth:sanctum')->group(function () {
    
    Route::apiResource('viatges', ViatgeController::class);
    Route::post('/generar-enllac', [ViatgeController::class, 'generarEnllacAfiliat']);
    Route::post('/viatges/{viatge}/upload-principal', [ViatgeController::class, 'uploadImatgePrincipal']); 
    Route::post('/viatges/{viatge}/upload-foto', [ViatgeController::class, 'uploadFotoGaleria']); 
    Route::get('/carret', [CarretVirtualController::class, 'index']);
    Route::post('/carret', [CarretVirtualController::class, 'store']); 
    Route::delete('/carret/{viatge_id}', [CarretVirtualController::class, 'destroy']); 
    Route::get('/my-viatges', [ViatgeController::class, 'myViatges']); 
});