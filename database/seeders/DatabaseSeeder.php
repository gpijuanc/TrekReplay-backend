<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Cridem els seeders de dades bàsiques
        $this->call([
            RolSeeder::class,
            PlataformesAfiliatSeeder::class,
        ]);

        // 2. (Opcional) Creem usuaris de prova
        // \App\Models\Usuari::factory(10)->create();
    }
}