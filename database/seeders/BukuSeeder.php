<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BukuSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('buku')->insert([
            [
                'judul' => 'Laravel Untuk Pemula',
                'pengarang' => 'Andi Setiawan',
                'penerbit' => 'Informatika Press',
                'tahun_terbit' => 2022,
            ],
            [
                'judul' => 'Belajar PHP Modern',
                'pengarang' => 'Budi Santoso',
                'penerbit' => 'Gramedia',
                'tahun_terbit' => 2021,
            ],
            [
                'judul' => 'Database MySQL Lengkap',
                'pengarang' => 'Citra Dewi',
                'penerbit' => 'Deepublish',
                'tahun_terbit' => 2020,
            ],
            [
                'judul' => 'Algoritma dan Pemrograman',
                'pengarang' => 'Dedi Kusnadi',
                'penerbit' => 'Erlangga',
                'tahun_terbit' => 2019,
            ],
            [
                'judul' => 'Framework Laravel Lanjutan',
                'pengarang' => 'Eko Prasetyo',
                'penerbit' => 'Informatika Press',
                'tahun_terbit' => 2023,
            ],
        ]);
    }
}
