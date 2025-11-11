<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PlataformesAfiliat; // Importa el model

class PlataformesAfiliatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PlataformesAfiliat::create([
            'empresa' => 'Booking.com',
            'url_base' => 'booking.com',
            'valor_afiliat' => 'EL_TEU_ID_DE_BOOKING', // El teu ID d'afiliat
            'id_afiliat' => 'aid' // El nom del paràmetre que utilitza Booking
        ]);

        PlataformesAfiliat::create([
            'empresa' => 'Kayak',
            'url_base' => 'kayak.com',
            'valor_afiliat' => 'EL_TEU_ID_DE_KAYAK',
            'id_afiliat' => 'mcid'
        ]);

        // Afegeix aquí altres plataformes (HeyMondo, GetYourGuide, etc.)
    }
}