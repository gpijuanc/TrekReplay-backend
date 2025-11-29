<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PlataformesAfiliat;

class PlataformesAfiliatSeeder extends Seeder
{
   public function run(): void
    {
        PlataformesAfiliat::truncate(); 

        PlataformesAfiliat::create([
            'empresa' => 'Booking.com',
            'url_base' => 'booking.com',
            'platform_affiliate_id' => 'EL_TEU_ID_DE_BOOKING',
            'url_template' => 'aid={PLATFORM_ID}&label={CREATOR_ID}'
        ]);

        PlataformesAfiliat::create([
            'empresa' => 'Revolut',
            'url_base' => 'revolut.com/referral',
            'platform_affiliate_id' => 'roco5rg9n!NOV1-25-AR-H1',
            'url_template' => 'referral-code={PLATFORM_ID}&geo-redirect&subid={CREATOR_ID}'
        ]);
        
        PlataformesAfiliat::create([
            'empresa' => 'Trip.com',
            'url_base' => 'trip.com/sale',
            'platform_affiliate_id' => 'JHM3K7',
            'url_template' => 'locale=es-ES&referCode={PLATFORM_ID}&subid={CREATOR_ID}'
        ]);
    }
}