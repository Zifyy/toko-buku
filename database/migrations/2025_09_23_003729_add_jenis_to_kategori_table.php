<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('kategori', function (Blueprint $table) {
        if (!Schema::hasColumn('kategori', 'jenis')) {
            $table->string('jenis', 100)->nullable()->after('genre');
        }
    });
}


    public function down(): void
    {
        Schema::table('kategori', function (Blueprint $table) {
            $table->dropColumn('jenis');
        });
    }
};
