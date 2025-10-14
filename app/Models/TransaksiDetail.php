<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    use HasFactory;

    protected $table = 'transaksi_detail';

    protected $fillable = [
        'transaksi_id',
        'buku_id',
        'jumlah',
        'harga_satuan',
        'subtotal',
        'tipe_diskon',
        'nilai_diskon',
        'subtotal_setelah_diskon',
    ];

    /**
     * Relasi ke transaksi (many to one)
     */
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }

    /**
     * Relasi ke buku
     */
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }

    /**
     * Hitung subtotal otomatis saat jumlah atau harga berubah
     */
    public function hitungSubtotal(): void
    {
        $this->subtotal = $this->jumlah * $this->harga_satuan;

        // Jika ada diskon, hitung subtotal setelah diskon
        if ($this->tipe_diskon && $this->nilai_diskon > 0) {
            if ($this->tipe_diskon === 'persen') {
                $diskon = ($this->subtotal * $this->nilai_diskon) / 100;
            } else {
                $diskon = $this->nilai_diskon;
            }

            $this->subtotal_setelah_diskon = max($this->subtotal - $diskon, 0);
        } else {
            $this->subtotal_setelah_diskon = $this->subtotal;
        }
    }

    /**
     * Event boot model untuk otomatis menghitung subtotal sebelum disimpan.
     */
    protected static function booted()
    {
        static::saving(function ($detail) {
            $detail->hitungSubtotal();
        });
    }
}
