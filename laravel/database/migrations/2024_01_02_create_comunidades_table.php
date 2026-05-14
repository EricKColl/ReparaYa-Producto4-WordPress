<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comunidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gestora_id')->constrained('gestoras')->onDelete('cascade');
            $table->string('nombre');       // Nombre de la comunidad, ej: "Comunidad Calle Goya 10"
            $table->string('direccion');    // Dirección completa
            $table->string('zona');         // Zona de la ciudad, ej: "Centro", "Norte", "Sur"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comunidades');
    }
};
