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
        Schema::create('viatge', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuari_id'); // FK Venedor
            $table->string('titol');
            $table->json('pais')->nullable();
            $table->string('imatge_principal')->nullable(); // Ruta a la imatge
            $table->longText('blog'); 
            $table->enum('tipus_viatge', ['Paquet Tancat', 'Afiliats']);
            $table->decimal('preu', 8, 2)->nullable(); // Només per Paquets Tancats (recorda valor màxim 999999.99 no et flipes fent test)
            $table->boolean('publicat')->default(false);
            $table->timestamps();
            $table->foreign('usuari_id')->references('id')->on('usuaris');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('viatge');
    }
};
