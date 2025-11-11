<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol; // Importa el model

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creem els rols
        Rol::create(['tipus' => 'Admin']);
        Rol::create(['tipus' => 'Venedor']);
        Rol::create(['tipus' => 'Comprador']);
    }
}