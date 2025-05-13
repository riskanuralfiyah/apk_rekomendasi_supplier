<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StokMasuk extends Model
{
    protected $table = 'stok_masuks';

    protected $fillable = [
        'tanggal',
        'id_bahan_baku',
        'jumlah_stok_masuk',
        'id_supplier',
    ];

    /**
     * Relasi ke model BahanBaku.
     */
    public function bahanBaku(): BelongsTo
    {
        return $this->belongsTo(BahanBaku::class, 'id_bahan_baku');
    }

    /**
     * Relasi ke model Supplier.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }
}
