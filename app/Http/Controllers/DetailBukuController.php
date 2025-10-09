<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\DetailBuku;
use Illuminate\Http\Request;

class DetailBukuController extends Controller
{
    /**
     * Tampilkan daftar detail buku (harga & stok)
     */
    public function index()
    {
        $detailBuku = DetailBuku::with('buku')->get();

        // Ambil semua ID buku yang sudah punya detail
        $usedBukuIds = DetailBuku::pluck('buku_id')->toArray();

        // Ambil buku yang belum punya detail
        $availableBuku = \App\Models\Buku::whereNotIn('id', $usedBukuIds)->get();

        return view('detail-buku.index', compact('detailBuku', 'availableBuku'));
    }

    /**
     * Simpan detail buku baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:buku,id|unique:detail_buku,buku_id',
            'harga'   => 'required',
            'stok'    => 'required|integer|min:0',
        ], [
            'buku_id.unique' => 'Detail untuk buku ini sudah ada.',
        ]);

        // Normalisasi harga: hapus titik ribuan agar tersimpan utuh di DB
        $harga = str_replace('.', '', $request->harga);

        DetailBuku::create([
            'buku_id' => $request->buku_id,
            'harga'   => $harga,
            'stok'    => $request->stok,
        ]);

        return redirect()->route('detail-buku.index')
                         ->with('success', 'Detail buku berhasil ditambahkan!');
    }

    /**
     * Update detail buku
     */
    public function update(Request $request, DetailBuku $detail_buku)
    {
        $request->validate([
            'buku_id' => 'required|exists:buku,id|unique:detail_buku,buku_id,' . $detail_buku->id,
            'harga'   => 'required',
            'stok'    => 'required|integer|min:0',
        ], [
            'buku_id.unique' => 'Detail untuk buku ini sudah ada.',
        ]);

        // Normalisasi harga input
        $harga = str_replace('.', '', $request->harga);

        $detail_buku->update([
            'buku_id' => $request->buku_id,
            'harga'   => $harga,
            'stok'    => $request->stok,
        ]);

        return redirect()->route('detail-buku.index')
                         ->with('success', 'Detail buku berhasil diperbarui!');
    }

    /**
     * Hapus detail buku
     */
    public function destroy(DetailBuku $detail_buku)
    {
        $detail_buku->delete();
        return redirect()->route('detail-buku.index')
                         ->with('success', 'Detail buku berhasil dihapus!');
    }
}
