<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';

    protected $fillable = [
        'kode_buku',
        'judul',
        'penerbit',
        'pengarang',
        'tahun_terbit',
        'cover',
        'kategori_id', // hanya simpan foreign key
    ];

    /**
     * Relasi ke kategori
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    /**
     * Relasi ke detail buku (harga & stok)
     */
    public function detailBuku()
    {
        return $this->hasOne(DetailBuku::class, 'buku_id');
    }

    /**
     * Event model: hapus detail buku saat buku dihapus
     */
    protected static function booted()
    {
        static::deleting(function ($buku) {
            if ($buku->detailBuku) {
                $buku->detailBuku->delete();
            }
        });
    }
}
