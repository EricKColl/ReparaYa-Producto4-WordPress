<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Añadimos 3 campos nuevos a la tabla incidencias que ya existe:
     *   - gestora_id: si la incidencia viene de una gestora (null = cliente particular)
     *   - comunidad_id: comunidad de propietarios asociada (null si es particular)
     *   - precio_base: precio del servicio para calcular la comisión
     */
    public function up(): void
    {
        Schema::table('incidencias', function (Blueprint $table) {
            $table->foreignId('gestora_id')->nullable()->constrained('gestoras')->onDelete('set null');
            $table->foreignId('comunidad_id')->nullable()->constrained('comunidades')->onDelete('set null');
            $table->decimal('precio_base', 8, 2)->default(0.00);
        });
    }

    public function down(): void
    {
        Schema::table('incidencias', function (Blueprint $table) {
            $table->dropForeign(['gestora_id']);
            $table->dropForeign(['comunidad_id']);
            $table->dropColumn(['gestora_id', 'comunidad_id', 'precio_base']);
        });
    }
};
