<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    protected $table = 'penilaians';

    protected $fillable = [
        'id_supplier',
        'id_kriteria',
        'id_subkriteria',
        'nilai_subkriteria'
    ];

    protected $casts = [
        'nilai_subkriteria' => 'integer', // Cast ke integer
    ];

    // Relasi ke Supplier (Many-to-One)
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }

    // Relasi ke Kriteria (Many-to-One)
    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'id_kriteria');
    }

    // Relasi ke Subkriteria (Many-to-One)
    public function subkriteria()
    {
        return $this->belongsTo(Subkriteria::class, 'id_subkriteria');
    }
}