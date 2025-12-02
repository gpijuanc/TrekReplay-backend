<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. Si la capçalera Authorization s'ha perdut, la recuperem
        if (empty($_SERVER['HTTP_AUTHORIZATION'])) {
            
            // A vegades Apache la mou a REDIRECT_HTTP_AUTHORIZATION
            if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                $_SERVER['HTTP_AUTHORIZATION'] = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
            } 
            // O la posa a les variables d'autenticació PHP
            elseif (isset($_SERVER['PHP_AUTH_USER'])) {
                $_SERVER['HTTP_AUTHORIZATION'] = 'Basic ' . base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $_SERVER['PHP_AUTH_PW']);
            } 
            elseif (isset($_SERVER['PHP_AUTH_DIGEST'])) {
                $_SERVER['HTTP_AUTHORIZATION'] = $_SERVER['PHP_AUTH_DIGEST'];
            }
        }
    }}
