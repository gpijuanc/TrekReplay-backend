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
            $table->string('empresa')->unique();
            $table->string('url_base');
            $table->string('url_template');
            $table->string('platform_affiliate_id')->nullable();            
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