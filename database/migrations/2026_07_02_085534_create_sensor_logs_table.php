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
    Schema::create('sensor_logs', function (Blueprint $table) {
        $table->id();
        $table->integer('nilai_cahaya');
        $table->boolean('status_lampu'); // 1 = Hidup, 0 = Mati
        $table->timestamps(); // Mengisi otomatis created_at (waktu data masuk)
    });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_logs');
    }
};
