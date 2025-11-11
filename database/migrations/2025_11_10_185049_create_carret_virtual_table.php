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
        Schema::create('carret_virtual', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuari_id'); // FK a usuaris
            $table->unsignedBigInteger('viatge_id'); // FK a viatge (Paquet Tancat)
            $table->timestamp('temps_afegit')->useCurrent(); // El teu camp added_at
            $table->timestamps();

            // Claus Foranes (FK)
            $table->foreign('usuari_id')->references('id')->on('usuaris')->onDelete('cascade');
            $table->foreign('viatge_id')->references('id')->on('viatge')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carret_virtual');
    }
};
