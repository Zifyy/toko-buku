<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel transaksi & buku
            $table->foreignId('transaksi_id')->constrained('transaksi')->onDelete('cascade');
            $table->foreignId('buku_id')->constrained('buku')->onDelete('cascade');

            // Jumlah pembelian
            $table->integer('jumlah');

            // Harga per buku
            $table->decimal('harga_satuan', 12, 2);

            // Subtotal sebelum diskon
            $table->decimal('subtotal', 12, 2);

            // 🔹 Tambahan kolom untuk fitur diskon
            // tipe_diskon: persentase (%) atau nominal (Rp)
            $table->enum('tipe_diskon', ['persen', 'nominal'])->nullable();

            // nilai diskon: misal 10 (%) atau 5000 (Rp)
            $table->decimal('nilai_diskon', 12, 2)->default(0);

            // subtotal setelah diskon diterapkan
            $table->decimal('subtotal_setelah_diskon', 12, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_transaksi');
    }
};
