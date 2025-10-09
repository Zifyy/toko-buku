<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_buku', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel buku, 1 buku hanya boleh punya 1 detail
            $table->foreignId('buku_id')
                  ->unique()
                  ->constrained('buku')
                  ->onDelete('cascade');

            // Harga disimpan sebagai decimal, 12 digit total, 2 digit di belakang koma
            $table->decimal('harga', 12, 2)->default(0);

            // Stok default = 0
            $table->unsignedInteger('stok')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_buku');
    }
};
