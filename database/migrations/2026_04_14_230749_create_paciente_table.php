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
        Schema::create('paciente', function (Blueprint $table) {
            $table->id();
            $table->integer('cedula')->nullable()->unique();
            $table->string('nombres', 80);
            $table->string('apellidos', 80);
            $table->date('fecha_nacimiento');
            $table->char('sexo', 1)->comment('M / F');
            $table->string('telefono', 20)->nullable();
            $table->text('direccion')->nullable();
            $table->foreignId('etnia_id')->nullable()->constrained('etnia')->onDelete('set null');
            $table->integer('representante_id')->nullable();
            $table->foreign('representante_id')
                ->references('cedula')
                ->on('representante')
                ->onDelete('set null');
            $table->foreignId('sector_id')->nullable()->constrained('sector')->onDelete('set null');
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->index('activo', 'idx_paciente_activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paciente');
    }
};
