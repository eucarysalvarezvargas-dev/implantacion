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
        Schema::table('medico_consultorio', function (Blueprint $table) {
            // Índice para filtrar rápidamente horarios activos/inactivos de un médico
            $table->index(['medico_id', 'status'], 'idx_medico_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medico_consultorio', function (Blueprint $table) {
            $table->dropIndex('idx_medico_status');
        });
    }
};
