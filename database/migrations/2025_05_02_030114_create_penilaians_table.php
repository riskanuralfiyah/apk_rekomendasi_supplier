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
        Schema::create('penilaians', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys
            $table->foreignId('id_supplier')->constrained('suppliers')->onDelete('cascade');
            $table->foreignId('id_kriteria')->constrained('kriterias')->onDelete('cascade');
            $table->foreignId('id_subkriteria')->constrained('subkriterias')->onDelete('cascade');
            
            // Nilai sebagai integer
            $table->integer('nilai_subkriteria');
            
            $table->timestamps();
            
            // Composite index untuk mencegah duplikasi data
            $table->unique(['id_supplier', 'id_kriteria']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaians');
    }
};