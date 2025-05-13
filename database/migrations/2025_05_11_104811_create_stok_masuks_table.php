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
        Schema::create('stok_masuks', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('id_bahan_baku')->constrained('bahan_bakus')->onDelete('cascade');
            $table->integer('jumlah_stok_masuk');
            $table->foreignId('id_supplier')->constrained('suppliers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_masuks');
    }
};
