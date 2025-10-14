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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();

            // Kode transaksi: unik, panjang 20 (TRX + YmdHis + 3 random = 20)
            $table->string('kode_transaksi', 20)->unique();

            // Relasi ke users sebagai kasir
            $table->foreignId('kasir_id')->constrained('users')->onDelete('cascade');

            // Total pembayaran
            $table->decimal('total', 12, 2);

            // Tanggal transaksi, default current timestamp
            $table->dateTime('tanggal_transaksi')->useCurrent()->index();

            // created_at & updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
