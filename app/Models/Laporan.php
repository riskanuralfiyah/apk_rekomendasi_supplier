<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporans';

    protected $dates = 'bulan';

    protected $fillable = [
        'bulan',
        'tahun',
        'id_bahan_baku',
        'ukuran',
        'stok_awal',
        'total_stok_masuk',
        'total_stok_keluar',
        'sisa_stok',
    ];

    /**
     * Relasi ke model BahanBaku.
     */
    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'id_bahan_baku');
    }
}
