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
        Schema::create('jornada', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asic_id')->constrained('asic');
            $table->date('fecha_jornada');
            $table->text('descripcion')->nullable();
            $table->integer('personal_responsable');
            $table->foreign('personal_responsable')
                ->references('cedula')
                ->on('personal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jornada');
    }
};
