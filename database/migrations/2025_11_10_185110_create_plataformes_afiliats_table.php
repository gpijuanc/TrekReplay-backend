<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plataformes_afiliats', function (Blueprint $table) {
            $table->id();
            $table->string('empresa')->unique(); // Ex: "Booking.com"
            $table->string('url_base'); // Ex: "booking.com"
            $table->string('valor_afiliat'); // El teu ID per a aquesta plataforma
            $table->string('id_afiliat'); // El nom del paràmetre (Ex: "aid")
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plataformes_afiliats');
    }
};
