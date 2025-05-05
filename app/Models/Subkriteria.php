<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subkriteria extends Model
{
    protected $table = 'subkriterias';

    protected $fillable = [
        'id_kriteria',
        'nama_subkriteria',
        'nilai'
    ];

    // Relasi ke Kriteria (Many-to-One)
    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'id_kriteria');
    }

    public function penilaians()
    {
        return $this->hasMany(Penilaian::class, 'id_subkriteria');
    }
}
