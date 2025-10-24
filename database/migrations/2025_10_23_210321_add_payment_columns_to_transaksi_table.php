<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            // Tambahkan kolom jumlah_bayar setelah kolom total
            $table->decimal('jumlah_bayar', 15, 0)->after('total')->default(0);
            
            // Tambahkan kolom kembalian setelah kolom jumlah_bayar
            $table->decimal('kembalian', 15, 0)->after('jumlah_bayar')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            // Hapus kolom jika migration di-rollback
            $table->dropColumn(['jumlah_bayar', 'kembalian']);
        });
    }
};