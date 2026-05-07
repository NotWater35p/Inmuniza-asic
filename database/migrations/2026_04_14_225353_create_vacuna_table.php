<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vacuna', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->foreignId('marca_id')->constrained('marca');
            $table->enum('tipo', ['vacuna', 'suero', 'insumo'])->default('vacuna');
            $table->string('presentacion', 50)->nullable();
            $table->string('enfermedad', 100)->nullable();
            $table->string('dosificacion', 100)->nullable();
            $table->string('via_administracion', 50)->nullable();
            $table->string('intervalo', 50)->nullable();
            $table->string('refuerzo', 50)->nullable();
            $table->tinyInteger('numero_dosis')->default(1);
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vacuna');
    }
};