<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gestoras', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');           // Nombre de la empresa gestora
            $table->string('email')->unique();  // Email de acceso
            $table->string('password');         // Contraseña encriptada
            $table->string('telefono')->nullable();
            $table->decimal('comision', 5, 2)->default(5.00); // % comisión, ej: 5.00 = 5%
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gestoras');
    }
};
