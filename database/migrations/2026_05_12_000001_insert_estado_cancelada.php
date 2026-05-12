<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!DB::table('estado_reserva')->where('tipo', 'Cancelada')->exists()) {
            DB::table('estado_reserva')->insert(['tipo' => 'Cancelada']);
        }
    }

    public function down(): void
    {
        DB::table('estado_reserva')->where('tipo', 'Cancelada')->delete();
    }
};
