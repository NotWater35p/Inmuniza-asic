<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modulo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asic_id')->constrained('asic')->onDelete('cascade');
            $table->string('rif', 20)->unique();
            $table->string('nombre', 150);
            $table->string('municipio', 100)->nullable();
            $table->string('parroquia', 100)->nullable();
            $table->enum('tipo_establecimiento', [
                'CP1', 'CP2', 'CP3', 'HOSPITAL', 'CDI',
                'IVSS', 'IPASME', 'SANIDAD MILITAR', 'PRIVADO', 'OTROS'
            ])->default('CP1');
            $table->text('direccion')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->integer('jefe_cedula')->nullable();
            $table->foreign('jefe_cedula')->references('cedula')->on('personal')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modulo');
    }
};