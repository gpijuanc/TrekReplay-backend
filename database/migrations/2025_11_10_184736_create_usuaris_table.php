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
        Schema::create('usuaris', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id'); // FK
            $table->string('nom');
            $table->string('correu')->unique();
            $table->string('contrasenya');
            $table->boolean('OTA')->default(false); // El teu camp "OTA (Boolean)"
            $table->timestamps();

            // Definim la Clau Forana (FK)
            $table->foreign('role_id')->references('id')->on('rols');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuaris');
    }
};
