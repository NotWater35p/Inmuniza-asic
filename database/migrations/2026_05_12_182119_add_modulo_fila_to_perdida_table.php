<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perdida', function (Blueprint $table) {
            $table->foreignId('modulo_id')
                ->nullable()
                ->after('fecha')
                ->constrained('modulo')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('perdida', function (Blueprint $table) {
            $table->dropForeign(['modulo_id']);
            $table->dropColumn('modulo_id');
        });
    }
};