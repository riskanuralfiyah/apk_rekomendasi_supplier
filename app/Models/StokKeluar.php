<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokKeluar extends Model
{
    use HasFactory;

    protected $table = 'stok_keluars';

    protected $fillable = [
        'tanggal',
        'id_bahan_baku',
        'jumlah_stok_keluar',
        'keterangan',
    ];

    // relasi ke model BahanBaku
    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'id_bahan_baku');
    }
}
