<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_transaksi', function (Blueprint $table) {
            $table->enum('tipe_diskon', ['none', 'nominal', 'percent'])->default('none')->after('subtotal');
            $table->decimal('nilai_diskon', 12, 2)->default(0)->after('tipe_diskon');
            $table->decimal('subtotal_setelah_diskon', 12, 2)->default(0)->after('nilai_diskon');
        });
    }

    public function down(): void
    {
        Schema::table('detail_transaksi', function (Blueprint $table) {
            $table->dropColumn(['tipe_diskon', 'nilai_diskon', 'subtotal_setelah_diskon']);
        });
    }
};
