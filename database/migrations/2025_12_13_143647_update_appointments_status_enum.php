<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        
        // solución temporal para limpiar datos
        DB::statement("UPDATE appointments SET status = 'pending' WHERE status NOT IN ('pending', 'confirmed', 'attended', 'canceled')");
        
        // modificar el ENUM con los nuevos valores
        DB::statement("ALTER TABLE appointments MODIFY status ENUM('pending', 'confirmed', 'attended', 'canceled') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir a los valores originales
        DB::statement("ALTER TABLE appointments MODIFY status ENUM('pending', 'confirmed', 'attended') DEFAULT 'pending'");
    }
};
