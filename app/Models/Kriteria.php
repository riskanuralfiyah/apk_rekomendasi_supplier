<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{

    protected $table = 'kriterias';

    protected $fillable = ['nama_kriteria', 'kategori', 'bobot'];
    
    // Mutator untuk mengubah input user (50 -> 0.50)
    public function setBobotAttribute($value)
    {
        $this->attributes['bobot'] = $value / 100;
    }
    
    // Accessor untuk menampilkan (0.50 -> 50)
    public function getBobotPersenAttribute()
    {
        return $this->bobot * 100;
    }

    public function subkriterias()
    {
        return $this->hasMany(Subkriteria::class, 'id_kriteria');
    }

    public function penilaians()
    {
        return $this->hasMany(Penilaian::class, 'id_kriteria');
    }

    // Relasi Subkriteria memiliki banyak Perhitungan
    public function perhitungans()
    {
        return $this->hasMany(Perhitungan::class, 'id_subkriteria');
    }

}