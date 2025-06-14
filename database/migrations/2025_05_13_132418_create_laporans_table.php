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
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->integer('bulan'); // bulan sebagai integer (1-12)
            $table->integer('tahun'); // tahun sebagai integer (misal: 2025)
            $table->foreignId('id_bahan_baku')->constrained('bahan_bakus')->onDelete('cascade');
            $table->string('ukuran');
            $table->integer('stok_awal');
            $table->integer('total_stok_masuk');
            $table->integer('total_stok_keluar');
            $table->integer('sisa_stok');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
