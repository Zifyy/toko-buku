<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\DetailBuku; // ✅ tambahkan
use App\Models\User;       // ✅ tambahkan
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index()
    {
        // 🔹 Hitung total data
        $totalBuku = Buku::count();
        $totalKategori = Kategori::count();
        $totalDetailBuku = DetailBuku::count();
        $totalUser = User::count();

        // 🔹 Ambil data buku + relasi kategori & detail
        $buku = Buku::with(['kategori', 'detailBuku'])->paginate(5); // ✅ gunakan relasi "detail" sesuai Model Buku

        return view('admin.buku.index', compact(
            'totalBuku',
            'totalKategori',
            'totalDetailBuku',
            'totalUser',
            'buku'
        ));
    }

    public function create()
    {
        $kategori = Kategori::all();
        return view('buku.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'         => 'required|max:100',
            'penerbit'      => 'required|max:100',
            'pengarang'     => 'required|max:100',
            'tahun_terbit'  => 'required|digits:4',
            'kategori_id'   => 'required|exists:kategori,id',
            'cover'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // 🔹 Cari kategori
        $kategori = Kategori::findOrFail($request->kategori_id);

        // 🔹 Tentukan prefix kode
        $prefix = $kategori->nama_kategori === 'Non Fiksi' ? 'BKN' : 'BKF';

        // 🔹 Ambil kode terakhir
        $lastBuku = Buku::where('kode_buku', 'LIKE', $prefix . '%')
                        ->orderBy('kode_buku', 'desc')
                        ->first();

        $lastNumber = $lastBuku ? intval(substr($lastBuku->kode_buku, 3)) : 0;
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        $validated['kode_buku'] = $prefix . $newNumber;

        // 🔹 Upload cover
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('cover'), $filename);
            $validated['cover'] = $filename;
        }

        Buku::create($validated);

        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        $kategori = Kategori::all();
        return view('buku.edit', compact('buku', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        $validated = $request->validate([
            'judul'         => 'required|max:100',
            'penerbit'      => 'required|max:100',
            'pengarang'     => 'required|max:100',
            'tahun_terbit'  => 'required|digits:4',
            'kategori_id'   => 'required|exists:kategori,id',
            'cover'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // 🔹 Regenerate kode jika kategori berubah
        if ($buku->kategori_id != $request->kategori_id) {
            $kategori = Kategori::findOrFail($request->kategori_id);
            $prefix = $kategori->nama_kategori === 'Non Fiksi' ? 'BKN' : 'BKF';

            $lastBuku = Buku::where('kode_buku', 'LIKE', $prefix . '%')
                            ->orderBy('kode_buku', 'desc')
                            ->first();

            $lastNumber = $lastBuku ? intval(substr($lastBuku->kode_buku, 3)) : 0;
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

            $validated['kode_buku'] = $prefix . $newNumber;
        } else {
            $validated['kode_buku'] = $buku->kode_buku;
        }

        // 🔹 Upload cover baru
        if ($request->hasFile('cover')) {
            if ($buku->cover && file_exists(public_path('cover/' . $buku->cover))) {
                unlink(public_path('cover/' . $buku->cover));
            }

            $file = $request->file('cover');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('cover'), $filename);
            $validated['cover'] = $filename;
        }

        $buku->update($validated);

        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);

        if ($buku->cover && file_exists(public_path('cover/' . $buku->cover))) {
            unlink(public_path('cover/' . $buku->cover));
        }

        $buku->delete();

        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil dihapus!');
    }
}
