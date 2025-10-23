<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Migration ini menambahkan kolom 'nama_buku' untuk menyimpan snapshot
     * nama buku pada saat transaksi, sehingga data tetap ada meskipun
     * buku dihapus dari database.
     */
    public function up(): void
    {
        Schema::table('detail_transaksi', function (Blueprint $table) {
            // Tambah kolom nama_buku setelah kolom buku_id
            $table->string('nama_buku', 255)->after('buku_id');
            
            // Ubah foreign key buku_id agar tidak cascade delete
            // Drop constraint lama
            $table->dropForeign(['buku_id']);
            
            // Buat ulang foreign key dengan onDelete('set null')
            // dan buat kolom buku_id nullable
            $table->foreignId('buku_id')
                ->nullable()
                ->change();
                
            $table->foreign('buku_id')
                ->references('id')
                ->on('buku')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_transaksi', function (Blueprint $table) {
            // Hapus kolom nama_buku
            $table->dropColumn('nama_buku');
            
            // Kembalikan foreign key seperti semula
            $table->dropForeign(['buku_id']);
            
            $table->foreignId('buku_id')
                ->nullable(false)
                ->change();
                
            $table->foreign('buku_id')
                ->references('id')
                ->on('buku')
                ->onDelete('cascade');
        });
    }
};