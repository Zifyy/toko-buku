<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Support\Facades\Session;

class KasirController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // jika sudah pakai RoleMiddleware pada routes, ini optional:
        // $this->middleware('role:kasir');
    }

    /**
     * Tampilkan dashboard kasir (daftar buku).
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $buku = Buku::with(['kategori', 'detailBuku'])
            ->when($search, function ($query, $search) {
                return $query->where('judul', 'like', "%{$search}%")
                             ->orWhere('kode_buku', 'like', "%{$search}%")
                             ->orWhere('pengarang', 'like', "%{$search}%")
                             ->orWhereHas('kategori', function ($q) use ($search) {
                                 $q->where('nama_kategori', 'like', "%{$search}%")
                                   ->orWhere('genre', 'like', "%{$search}%")
                                   ->orWhere('jenis', 'like', "%{$search}%");
                             });
            })
            ->orderBy('judul')
            ->paginate(12); // paginate supaya tabel/card tidak memuat terlalu banyak

        return view('kasir.dashboard', compact('buku', 'search'));
    }

    /**
     * Terima cart JSON dari client, simpan ke session lalu redirect ke halaman transaksi.
     */
    public function storeTransaction(Request $request)
    {
        $data = $request->validate([
            'cart_data' => 'required|string',
        ]);

        $cart = json_decode($data['cart_data'], true);

        if (!is_array($cart)) {
            return redirect()->back()->with('error', 'Data keranjang tidak valid.');
        }

        // Hitung total (basic)
        $total = 0;
        foreach ($cart as $id => $item) {
            $price = isset($item['price']) ? floatval($item['price']) : 0;
            $qty = isset($item['quantity']) ? intval($item['quantity']) : 0;
            $total += ($price * $qty);
        }

        session(['cart' => $cart, 'cart_total' => $total]);

        return redirect()->route('kasir.transaksi')->with('success', 'Keranjang berhasil disimpan. Silakan konfirmasi transaksi.');
    }

    /**
     * Tampilkan halaman konfirmasi transaksi.
     */
    public function transaksi(Request $request)
    {
        // Ambil data keranjang dari POST
        $cart = json_decode($request->cart_data, true);

        // Kirim ke view checkout
        return view('kasir.transaksi', compact('cart'));
    }

    /**
     * Finalize transaksi (saat ini: dummy -> hapus session, beri pesan sukses).
     * Nanti di sini masukkan logic menyimpan ke DB (transaksi + detail_transaksi).
     */
    public function finalizeTransaction(Request $request)
    {
        $cart = session('cart', []);
        $total = session('cart_total', 0);

        if (empty($cart)) {
            return redirect()->route('kasir.dashboard')->with('error', 'Keranjang kosong.');
        }

        // TODO: Simpan transaksi ke DB (transaksi + detail).
        // Untuk saat ini kita hanya hapus session (simulasi sukses)
        session()->forget(['cart', 'cart_total']);

        return redirect()->route('kasir.dashboard')->with('success', 'Transaksi berhasil (simpanan dummy).');
    }

    /**
     * Tampilkan halaman konfirmasi transaksi (versi show).
     */
    public function showTransaksi(Request $request)
    {
        // Ambil data keranjang dari session, localStorage, atau set kosong
        $cart = []; // default kosong

        // Jika pakai session:
        // $cart = session('cart', []);

        return view('kasir.transaksi', compact('cart'));
    }
}
