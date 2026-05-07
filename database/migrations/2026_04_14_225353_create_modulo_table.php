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
        Schema::create('modulo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asic_id')->constrained('asic')->onDelete('cascade');
            $table->string('rif', 20)->unique();
            $table->string('nombre', 150);
            $table->text('direccion')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('responsable', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modulo');
    }
};
