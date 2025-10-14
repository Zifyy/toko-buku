<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('transaksi_detail', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel transaksi & buku
            $table->foreignId('transaksi_id')
                ->constrained('transaksi')
                ->onDelete('cascade');

            $table->foreignId('buku_id')
                ->constrained('buku')
                ->onDelete('cascade');

            // Jumlah buku yang dibeli
            $table->unsignedInteger('jumlah');

            // Harga per buku
            $table->decimal('harga_satuan', 12, 2);

            // Subtotal sebelum diskon
            $table->decimal('subtotal', 12, 2);

            // Diskon (optional)
            $table->enum('tipe_diskon', ['persen', 'nominal'])->nullable();
            $table->decimal('nilai_diskon', 12, 2)->default(0);

            // Subtotal setelah diskon
            $table->decimal('subtotal_setelah_diskon', 12, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Rollback migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_detail');
    }
};
