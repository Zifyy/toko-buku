<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaksi;
use App\Models\Buku; // Pastikan model Buku di-import

class TransaksiController extends Controller
{
    public function index()
    {
        return view('kasir.transaksi');
    }

    public function store(Request $request)
    {
        $cartData = json_decode($request->input('cart_data'), true);

        if (!$cartData || empty($cartData)) {
            return redirect()->back()->with('error', 'Keranjang masih kosong.');
        }

        // Untuk sekarang tampilkan hasil di halaman transaksi dulu
        return view('kasir.transaksi', compact('cartData'));
    }

    public function prosesTransaksi(Request $request)
    {
        $cartData = json_decode($request->input('cart_data'), true);

        if (!$cartData || empty($cartData)) {
            return redirect()->back()->with('error', 'Keranjang masih kosong.');
        }

        $total = collect($cartData)->sum(fn ($item) => $item['harga'] * $item['jumlah']);

        DB::beginTransaction();
        try {
            // ✅ Tidak perlu membuat kode_transaksi manual lagi,
            // Model Transaksi akan auto generate-nya di event booted()
            $transaksi = Transaksi::create([
                'kasir_id'          => auth()->id(),
                'tanggal_transaksi' => now(),
                'total'             => $total,
            ]);

            // (Jika nanti ingin simpan detail transaksi, bisa tambahkan di sini)
            // foreach ($cartData as $item) {
            //     $transaksi->detail()->create([
            //         'produk_id' => $item['id'],
            //         'jumlah'    => $item['jumlah'],
            //         'harga'     => $item['harga'],
            //     ]);
            // }

            DB::commit();
            return redirect()->route('transaksi.sukses', $transaksi->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function sukses($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        return view('kasir.transaksi_sukses', compact('transaksi'));
    }

    public function riwayat()
    {
    $transaksi = Transaksi::where('kasir_id', auth()->id())
        ->orderByDesc('tanggal_transaksi')
        ->paginate(10);

    return view('kasir.riwayat', compact('transaksi'));
    }

    public function cekBuku($itemId)
    {
        $buku = Buku::find($itemId);
        if (!$buku) {
            // Data buku tidak ditemukan
        }
    }
}
