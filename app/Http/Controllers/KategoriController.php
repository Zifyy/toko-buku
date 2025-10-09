<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Tampilkan semua kategori
     */
    public function index()
    {
        $kategori   = Kategori::all();
        $genres     = Kategori::whereNotNull('genre')->pluck('genre')->unique()->values();
        $jenisList  = Kategori::whereNotNull('jenis')->pluck('jenis')->unique()->values();

        return view('kategori.index', compact('kategori', 'genres', 'jenisList'));
    }

    /**
     * Simpan kategori baru
     */
    public function store(Request $request)
    {
        // Ambil input genre
        $genre = $request->genre === '__new'
            ? $request->input('genre_manual')
            : $request->input('genre');

        // Ambil input jenis
        $jenis = $request->jenis === '__new'
            ? $request->input('jenis_manual')
            : $request->input('jenis');

        $request->validate([
            'nama_kategori' => 'required|in:Fiksi,Non Fiksi',
            'genre'         => 'nullable|string|max:100',
            'genre_manual'  => 'nullable|string|max:100',
            'jenis'         => 'nullable|string|max:100',
            'jenis_manual'  => 'nullable|string|max:100',
        ]);

        Kategori::create([
            'nama_kategori' => $request->nama_kategori,
            'genre'         => $genre ?? '',
            'jenis'         => $jenis ?? '',
        ]);

        return redirect()->route('kategori.index')
                         ->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Form edit kategori
     */
    public function edit(Kategori $kategori)
    {
        return view('kategori.edit', compact('kategori'));
    }

    /**
     * Update kategori
     */
    public function update(Request $request, Kategori $kategori)
    {
        $genre = $request->genre === '__new'
            ? $request->input('genre_manual')
            : $request->input('genre');

        $jenis = $request->jenis === '__new'
            ? $request->input('jenis_manual')
            : $request->input('jenis');

        $request->validate([
            'nama_kategori' => 'required|in:Fiksi,Non Fiksi',
            'genre'         => 'nullable|string|max:100',
            'genre_manual'  => 'nullable|string|max:100',
            'jenis'         => 'nullable|string|max:100',
            'jenis_manual'  => 'nullable|string|max:100',
        ]);

        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'genre'         => $genre ?? '',
            'jenis'         => $jenis ?? '',
        ]);

        return redirect()->route('kategori.index')
                         ->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Hapus kategori
     */
    public function destroy(Kategori $kategori)
    {
        $kategori->delete();
        return redirect()->route('kategori.index')
                         ->with('success', 'Kategori berhasil dihapus!');
    }
}
