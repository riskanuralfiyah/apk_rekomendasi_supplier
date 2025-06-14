<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratPemesanan extends Model
{
    use HasFactory;

    protected $table = 'surat_pemesanans';

    protected $fillable = [
        'nomor_surat',
        'id_supplier',
    ];

    /**
     * Relasi ke model Supplier
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }
}
