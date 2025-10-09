<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';

    protected $fillable = ['nama_kategori', 'genre', 'jenis'];

    /**
     * Relasi ke Buku (One to Many)
     * Satu kategori bisa memiliki banyak buku
     */
    public function buku()
    {
        return $this->hasMany(Buku::class, 'kategori_id');
    }
}
