<?php

// app/Models/DataSupplier.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    // Tambahkan ini untuk menentukan nama tabel
    protected $table = 'suppliers';

    protected $fillable = [
        'nama_supplier',
        'alamat', 
        'no_telpon'
    ];

    public function penilaians()
    {
        return $this->hasMany(Penilaian::class, 'id_supplier');
    }

    // relasi: 1 supplier punya banyak hasil rekomendasi
    public function hasilRekomendasis()
    {
        return $this->hasMany(HasilRekomendasi::class, 'id_supplier');
    }

    public function stokMasuks()
    {
        return $this->hasMany(StokMasuk::class, 'id_supplier');
    }
}