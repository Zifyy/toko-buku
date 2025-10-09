<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailBuku extends Model
{
    use HasFactory;

    protected $table = 'detail_buku';

    protected $fillable = [
        'buku_id',
        'harga',
        'stok',
    ];

    // 🔹 Relasi ke model Buku
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }
}