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
        Schema::create('hasil_rekomendasis', function (Blueprint $table) {
            $table->id();  // Kolom id otomatis sebagai primary key
            $table->foreignId('id_supplier')->constrained('suppliers')->onDelete('cascade');  // Menghubungkan ke tabel suppliers
            $table->decimal('skor_akhir', 5, 2);  // Skor akhir dengan 4 digit desimal
            $table->integer('peringkat');  // Peringkat
            $table->timestamps();  // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_rekomendasis');
    }
};
