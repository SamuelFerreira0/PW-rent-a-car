<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('veiculo', function (Blueprint $table) {
            $table->unique('matricula', 'veiculo_matricula_unique');
        });
    }

    public function down(): void
    {
        Schema::table('veiculo', function (Blueprint $table) {
            $table->dropUnique('veiculo_matricula_unique');
        });
    }
};
