<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            // metode pembayaran: cash / qris
            $table->enum('metode_pembayaran', ['cash', 'qris'])
                ->default('cash')
                ->after('kasir_id');

            // total diskon keseluruhan transaksi
            $table->decimal('total_diskon', 12, 2)
                ->default(0)
                ->after('total');

            // total setelah diskon
            $table->decimal('total_setelah_diskon', 12, 2)
                ->default(0)
                ->after('total_diskon');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn(['metode_pembayaran', 'total_diskon', 'total_setelah_diskon']);
        });
    }
};
