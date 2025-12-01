<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/setup-database-force', function () {
    try {
        // 1. Borrar y crear tablas
        Artisan::call('migrate:fresh', ['--force' => true]);
        $migrateOutput = Artisan::output();

        // 2. Poblar datos (Seeders)
        Artisan::call('db:seed', ['--force' => true]);
        $seedOutput = Artisan::output();

        return "<h1>Base de datos configurada correctamente</h1>
                <h3>Migrate Output:</h3><pre>$migrateOutput</pre>
                <h3>Seed Output:</h3><pre>$seedOutput</pre>";
    } catch (\Exception $e) {
        return "<h1>Error</h1><pre>" . $e->getMessage() . "</pre>";
    }
});

Route::get('/clear-cache', function () {
    try {
        // Neteja tota la memòria cau (Configuració, Rutes, Vistes)
        Illuminate\Support\Facades\Artisan::call('optimize:clear');
        return "<h1>Memòria cau netejada correctament! ✅</h1>";
    } catch (\Exception $e) {
        return "<h1>Error</h1><pre>" . $e->getMessage() . "</pre>";
    }
});
