<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asic_id')->constrained('asic');
            $table->foreignId('vacuna_id')->constrained('vacuna');
            $table->string('lote', 50)->nullable();
            $table->date('fecha_llegada');
            $table->date('fecha_vencimiento')->nullable();
            $table->integer('cantidad');
            $table->integer('cantidad_disponible')->default(0); 
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index(['asic_id', 'vacuna_id'], 'idx_carga_asig');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carga');
    }
};