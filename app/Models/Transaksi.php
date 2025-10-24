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
        'jumlah_bayar',    // ✅ TAMBAHKAN INI
        'kembalian',       // ✅ TAMBAHKAN INI
    ];

    // 🕒 Konversi otomatis ke datetime
    protected $casts = [
        'tanggal_transaksi' => 'datetime',
    ];

    // 🔁 Relasi utama ke kasir (user yang melakukan transaksi)
    public function kasir()
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }

    // 🔁 Alias relasi untuk kompatibilitas dengan $transaksi->user
    public function user()
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }

    // 🔁 Relasi ke detail transaksi (DIPERBAIKI: nama method disesuaikan)
    public function detailTransaksi()
    {
        return $this->hasMany(TransaksiDetail::class, 'transaksi_id');
    }

    // 🔁 Alias relasi untuk kompatibilitas (opsional, jika ada kode lain yang pakai)
    public function transaksiDetail()
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