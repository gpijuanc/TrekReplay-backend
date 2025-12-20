<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\ViatgeController;
use App\Http\Controllers\CarretVirtualController;

// Rutes públiques (no necessiten token)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/viatges', [ViatgeController::class, 'index']);
Route::get('/viatges/{viatge}', [ViatgeController::class, 'show']);

// Rutes protegides (necessiten token)
Route::middleware('auth:sanctum')->group(function () {
    
    Route::post('/viatges', [ViatgeController::class, 'store']);
    Route::put('/viatges/{viatge}', [ViatgeController::class, 'update']);
    Route::delete('/viatges/{viatge}', [ViatgeController::class, 'destroy']);
    Route::post('/generar-enllac', [ViatgeController::class, 'generarEnllacAfiliat']);
    Route::post('/viatges/{viatge}/upload-principal', [ViatgeController::class, 'uploadImatgePrincipal']); 
    Route::post('/viatges/{viatge}/upload-foto', [ViatgeController::class, 'uploadFotoGaleria']); 
    Route::get('/carret', [CarretVirtualController::class, 'index']);
    Route::post('/carret', [CarretVirtualController::class, 'store']); 
    Route::delete('/carret/{viatge_id}', [CarretVirtualController::class, 'destroy']); 
    Route::get('/my-viatges', [ViatgeController::class, 'myViatges']); 
});

Route::get('/debug-headers', function () {
    return response()->json(request()->headers->all());
});