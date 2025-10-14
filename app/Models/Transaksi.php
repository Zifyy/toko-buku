<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';

    protected $fillable = [
        'kode_transaksi',
        'kasir_id',
        'total',
        'tanggal_transaksi',
    ];

    // 🕒 Tambahkan agar Eloquent tahu ini adalah datetime
    protected $casts = [
        'tanggal_transaksi' => 'datetime',
    ];

    // 🔁 Relasi
    public function kasir()
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }

    public function detailTransaksi()
    {
        return $this->hasMany(TransaksiDetail::class, 'transaksi_id');
    }

    // 🎯 Auto-generate kode transaksi unik
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaksi) {
            if (!$transaksi->kode_transaksi) {
                $transaksi->kode_transaksi = 'TRX-' . strtoupper(Str::random(8));
            }
        });
    }
}
