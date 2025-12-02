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
        // Esborra la cache de configuració
        Illuminate\Support\Facades\Artisan::call('config:clear');
        // Esborra la cache de rutes
        Illuminate\Support\Facades\Artisan::call('route:clear');
        // Esborra altres caches
        Illuminate\Support\Facades\Artisan::call('cache:clear');
        
        return "<h1>✅ Cache netejada! Laravel ara llegirà el nou cors.php</h1>";
    } catch (\Exception $e) {
        return "<h1>Error</h1><pre>" . $e->getMessage() . "</pre>";
    }
});

// Definim la ruta i desactivem el middleware que busca la taula de sessions/cache
Route::get('/install-tfm', function () {
    try {
        // 1. Neteja TOTAL de la memòria cau (perquè trobi les rutes i configs noves)
        Artisan::call('optimize:clear');
        
        // 2. Crea les taules (Migracions)
        Artisan::call('migrate:fresh', ['--force' => true]);
        $migrateOutput = Artisan::output();

        // 3. Omple les dades (Seeders)
        Artisan::call('db:seed', ['--force' => true]);
        $seedOutput = Artisan::output();

        return "
            <div style='font-family:sans-serif; padding:20px;'>
                <h1 style='color:green;'>✅ INSTAL·LACIÓ COMPLETADA</h1>
                <p>La base de dades ja té les taules i els usuaris creats.</p>
                <hr>
                <h3>Detalls Tècnics:</h3>
                <pre style='background:#f4f4f4; padding:10px;'>$migrateOutput</pre>
                <pre style='background:#f4f4f4; padding:10px;'>$seedOutput</pre>
            </div>
        ";
    } catch (\Exception $e) {
        // Si falla, mostrem l'error netament
        return "<h1 style='color:red;'>Error Crític</h1><pre>" . $e->getMessage() . "</pre>";
    }
})->withoutMiddleware([
    \Illuminate\Session\Middleware\StartSession::class,
    \Illuminate\View\Middleware\ShareErrorsFromSession::class,
    \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
]);
