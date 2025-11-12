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
        Schema::table('plataformes_afiliats', function (Blueprint $table) {
            // 1. Esborrem els camps antics (que eren massa simples)
            $table->dropColumn('valor_afiliat');
            $table->dropColumn('id_afiliat');
            
            // 2. Afegim el nou camp de plantilla
            // Aquesta plantilla contindrà el format de l'enllaç d'afiliat
            $table->string('url_template');
            
            // (Opcional) Guardem el nostre ID d'afiliat per a aquesta plataforma
            $table->string('platform_affiliate_id'); 
        });
    }

    public function down(): void // Per si hem de revertir
    {
        Schema::table('plataformes_afiliats', function (Blueprint $table) {
            $table->string('valor_afiliat');
            $table->string('id_afiliat');
            $table->dropColumn('url_template');
            $table->dropColumn('platform_affiliate_id');
        });
    }
};
