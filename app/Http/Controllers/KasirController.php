<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\DetailBuku;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 🏠 Dashboard Kasir - Menampilkan daftar buku
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $buku = Buku::with(['kategori', 'detailBuku'])
            ->when($search, function ($query, $search) {
                $query->where('judul', 'like', "%{$search}%")
                    ->orWhere('kode_buku', 'like', "%{$search}%")
                    ->orWhere('pengarang', 'like', "%{$search}%")
                    ->orWhereHas('kategori', function ($q) use ($search) {
                        $q->where('nama_kategori', 'like', "%{$search}%");
                    });
            })
            ->orderBy('judul')
            ->paginate(12);

        return view('kasir.dashboard', compact('buku', 'search'));
    }

    /**
     * 🛒 Simpan data cart ke session
     */
    public function storeTransaction(Request $request)
    {
        $data = $request->validate([
            'cart_data' => 'required|string',
        ]);

        $cart = json_decode($data['cart_data'], true);

        if (!is_array($cart) || empty($cart)) {
            return back()->with('error', 'Data keranjang tidak valid atau kosong.');
        }

        $total = 0;
        foreach ($cart as $item) {
            if (!isset($item['price'], $item['quantity'])) continue;
            $total += $item['price'] * $item['quantity'];
        }

        session(['cart' => $cart, 'cart_total' => $total]);

        return redirect()->route('kasir.transaksi')->with('success', 'Keranjang berhasil disimpan.');
    }

    /**
     * 💳 Halaman checkout
     */
    public function showTransaksi()
    {
        $cart = session('cart', []);
        $total = session('cart_total', 0);

        return view('kasir.transaksi', compact('cart', 'total'));
    }

    /**
     * 💾 Simpan transaksi ke database
     */
    public function finalize(Request $request)
    {
        $cart = json_decode($request->input('cart_data'), true);

        if (!$cart || count($cart) === 0) {
            return redirect()->route('kasir.transaksi')->with('error', 'Keranjang kosong!');
        }

        DB::beginTransaction();

        try {
            $total = 0;
            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            $transaksi = Transaksi::create([
                'kasir_id' => auth()->id(),
                'tanggal_transaksi' => now(),
                'total' => $total,
            ]);

            foreach ($cart as $item) {
                $buku = Buku::find($item['id']);
                $detail = DetailBuku::where('buku_id', $item['id'])->first();

                if (!$buku) {
                    DB::rollBack();
                    return redirect()->route('kasir.transaksi')->with('error', 'Data buku tidak ditemukan.');
                }

                if ($detail->stok < $item['quantity']) {
                    DB::rollBack();
                    return redirect()->route('kasir.transaksi')
                        ->with('error', "Stok buku '{$buku->judul}' tidak cukup.");
                }

                TransaksiDetail::create([
                    'transaksi_id' => $transaksi->id,
                    'buku_id' => $buku->id,
                    'jumlah' => $item['quantity'],
                    'harga_satuan' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);

                $detail->decrement('stok', $item['quantity']);
            }

            DB::commit();
            session()->forget(['cart', 'cart_total']);

            return redirect()->route('kasir.transaksi.nota', $transaksi->id)
                ->with('success', 'Transaksi berhasil disimpan!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route('kasir.transaksi')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * 🧾 Nota Transaksi
     */
    public function nota($id)
    {
        $transaksi = Transaksi::with(['kasir', 'detailTransaksi.buku'])->findOrFail($id);
        return view('kasir.nota', compact('transaksi'));
    }
}
