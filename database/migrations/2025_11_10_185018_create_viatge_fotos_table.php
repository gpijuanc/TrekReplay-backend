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
        Schema::create('viatge_fotos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('viatge_id'); // FK
            $table->string('imatge_url'); 
            $table->string('alt_text')->nullable();
            $table->timestamps();
            $table->foreign('viatge_id')->references('id')->on('viatge')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('viatge_fotos');
    }
};
