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
        Schema::create('personal', function (Blueprint $table) {
            $table->integer('cedula')->primary();
            $table->foreignId('asic_id')->constrained('asic');
            $table->string('nombre', 50);
            $table->string('apellido', 50);
            $table->foreignId('cargo_id')->constrained('cargo');
            $table->string('telefono', 20)->nullable();
            $table->string('correo', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal');
    }
};
