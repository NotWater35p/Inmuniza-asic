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
        Schema::create('despacho', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asic_id')->constrained('asic');
            $table->foreignId('modulo_id')->constrained('modulo');
            $table->foreignId('vacuna_id')->constrained('vacuna');
            $table->date('fecha_envio');
            $table->integer('responsable_envio');
            $table->foreign('responsable_envio')
                ->references('cedula')
                ->on('personal');
            $table->integer('cantidad');
            $table->timestamps();
            
            $table->index(['asic_id', 'vacuna_id'], 'idx_despacho_asig');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('despacho');
    }
};
