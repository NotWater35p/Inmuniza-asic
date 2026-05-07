<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('modulo', function (Blueprint $table) {
        $table->integer('sispai_fila')->nullable()->after('tipo_establecimiento');
    });
}

public function down()
{
    Schema::table('modulo', function (Blueprint $table) {
        $table->dropColumn('sispai_fila');
    });
}
};
