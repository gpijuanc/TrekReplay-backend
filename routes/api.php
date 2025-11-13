<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; // Importem el controlador
use App\Http\Controllers\ViatgeController; //importem el controlador de viatge
use App\Http\Controllers\CarretVirtualController; //importem el controlador del carret

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
 
    Route::post('/viatges/{viatge}/upload-principal', [ViatgeController::class, 'uploadImatgePrincipal']);   // Per a la imatge principal (Portada)
    Route::post('/viatges/{viatge}/upload-foto', [ViatgeController::class, 'uploadFotoGaleria']);    // Per a les fotos de la galeria (viatge_fotos)
    // GESTIÓ DEL CARRET VIRTUAL
    Route::get('/carret', [CarretVirtualController::class, 'index']); // Llistar el meu carret
    Route::post('/carret', [CarretVirtualController::class, 'store']); // Afegir al carret
    Route::delete('/carret/{viatge_id}', [CarretVirtualController::class, 'destroy']); // Esborrar del carret
});