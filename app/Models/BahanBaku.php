<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    use HasFactory;

    protected $table = 'bahan_bakus';

    protected $fillable = [
        'nama_bahan_baku',
        'ukuran',
        'stok_minimum',
        'jumlah_stok',
    ];

    public function stokMasuks()
    {
        return $this->hasMany(StokMasuk::class, 'id_bahan_baku');
    }

    public function stokKeluars()
    {
        return $this->hasMany(StokKeluar::class, 'id_bahan_baku');
    }

    public function laporans()
    {
        return $this->hasMany(Laporan::class, 'id_bahan_baku');
    }

}
