<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perdida', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vacuna_id')->constrained('vacuna')->onDelete('cascade');
            $table->string('lote', 50)->nullable();
            $table->integer('cantidad');
            $table->enum('motivo', [
                'Vencimiento',
                'Rotura',
                'Cadena de frío',
                'Otro'
            ])->default('Vencimiento');
            $table->text('observacion')->nullable();
            $table->date('fecha');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perdida');
    }
};