<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilRekomendasi extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak menggunakan konvensi default
    protected $table = 'hasil_rekomendasis';

    // Tentukan kolom-kolom yang boleh diisi secara mass-assignment
    protected $fillable = [
        'id_supplier', 
        'skor_akhir', 
        'peringkat'
    ];

    // Relasi dengan model Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }
}
