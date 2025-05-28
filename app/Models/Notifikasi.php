<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasis';

    protected $fillable = [
        'id_user',
        'message',
        'is_read',
        'is_toasted',
        'is_deleted',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_toasted' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    /**
     * Relasi ke model User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
