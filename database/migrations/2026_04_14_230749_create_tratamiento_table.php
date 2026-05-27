<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tratamiento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jornada_id')->nullable()->constrained('jornada')->onDelete('set null');
            $table->foreignId('paciente_id')->nullable()->constrained('paciente')->onDelete('cascade');
            $table->foreignId('vacuna_id')->constrained('vacuna');
            $table->tinyInteger('dosis_aplicada')->default(1);
            $table->boolean('es_descargo_rapido')->default(false);
            $table->enum('subtipo_paciente', [
                'general',
                'personal_salud',
                'dialisis',
                'privado_libertad',
                'trabajador_sexual',
                'embarazada',
            ])->default('general');
            $table->date('fecha_aplicacion');
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index('fecha_aplicacion', 'idx_tratamiento_fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tratamiento');
    }
};