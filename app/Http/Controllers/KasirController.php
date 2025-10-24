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
     * 🛒 Simpan data cart ke session dan validasi
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

        // Validasi setiap item di keranjang
        $validatedCart = [];
        $errors = [];

        foreach ($cart as $item) {
            if (!isset($item['id'], $item['title'], $item['price'], $item['quantity'])) {
                continue;
            }

            $buku = Buku::with('detailBuku')->find($item['id']);

            if (!$buku || !$buku->detailBuku) {
                $errors[] = "Buku '{$item['title']}' tidak ditemukan.";
                continue;
            }

            $stok = $buku->detailBuku->stok ?? 0;
            $harga = $buku->detailBuku->harga ?? 0;

            if ($stok <= 0) {
                $errors[] = "Buku '{$buku->judul}' sudah habis.";
                continue;
            }

            if ($harga <= 0) {
                $errors[] = "Harga buku '{$buku->judul}' belum tersedia.";
                continue;
            }

            $quantity = (int) $item['quantity'];
            if ($quantity > $stok) {
                $errors[] = "Stok buku '{$buku->judul}' hanya tersedia {$stok} item.";
                continue;
            }

            $validatedCart[] = [
                'id' => $buku->id,
                'title' => $buku->judul,
                'price' => $harga,
                'quantity' => $quantity,
            ];
        }

        if (empty($validatedCart)) {
            $errorMessage = 'Tidak ada item valid di keranjang.';
            if (!empty($errors)) {
                $errorMessage .= ' Detail: ' . implode(' ', $errors);
            }
            return back()->with('error', $errorMessage);
        }

        $total = 0;
        foreach ($validatedCart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        session(['cart' => $validatedCart, 'cart_total' => $total]);

        if (!empty($errors)) {
            return redirect()->route('kasir.transaksi')->with('warning', implode(' ', $errors));
        }

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
     * 💾 Simpan transaksi ke database dengan jumlah bayar dan kembalian
     */
    public function finalize(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'cart_data' => 'required|string',
            'jumlah_bayar' => 'required|numeric|min:0',
            'kembalian' => 'required|numeric|min:0',
        ]);

        $cart = json_decode($validated['cart_data'], true);

        if (!$cart || count($cart) === 0) {
            return redirect()->route('kasir.transaksi')->with('error', 'Keranjang kosong!');
        }

        $jumlahBayar = (int) $validated['jumlah_bayar'];
        $kembalian = (int) $validated['kembalian'];

        DB::beginTransaction();

        try {
            $total = 0;
            
            // Hitung total dan validasi stok sekali lagi
            foreach ($cart as $item) {
                $buku = Buku::with('detailBuku')->find($item['id']);
                
                if (!$buku || !$buku->detailBuku) {
                    DB::rollBack();
                    return redirect()->route('kasir.transaksi')
                        ->with('error', 'Data buku tidak ditemukan.');
                }

                $detail = $buku->detailBuku;

                if ($detail->stok < $item['quantity']) {
                    DB::rollBack();
                    return redirect()->route('kasir.transaksi')
                        ->with('error', "Stok buku '{$buku->judul}' tidak cukup. Tersedia: {$detail->stok}");
                }

                $total += $item['price'] * $item['quantity'];
            }

            // Validasi jumlah bayar
            if ($jumlahBayar < $total) {
                DB::rollBack();
                return redirect()->route('kasir.transaksi')
                    ->with('error', 'Jumlah bayar tidak boleh kurang dari total belanja!');
            }

            // Validasi kembalian
            if ($kembalian != ($jumlahBayar - $total)) {
                DB::rollBack();
                return redirect()->route('kasir.transaksi')
                    ->with('error', 'Kembalian tidak sesuai!');
            }

            // Buat transaksi
            $transaksi = Transaksi::create([
                'kasir_id' => auth()->id(),
                'tanggal_transaksi' => now(),
                'total' => $total,
                'jumlah_bayar' => $jumlahBayar,
                'kembalian' => $kembalian,
            ]);

            // Simpan detail dan kurangi stok
            foreach ($cart as $item) {
                $buku = Buku::with('detailBuku')->find($item['id']);

                TransaksiDetail::create([
                    'transaksi_id' => $transaksi->id,
                    'buku_id' => $buku->id,
                    'nama_buku' => $buku->judul, // Snapshot nama buku
                    'jumlah' => $item['quantity'],
                    'harga_satuan' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);

                // Kurangi stok
                $buku->detailBuku->decrement('stok', $item['quantity']);
            }

            DB::commit();
            
            // Hapus session cart
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
        $transaksi = Transaksi::with(['kasir', 'detailTransaksi'])
            ->findOrFail($id);
            
        // Pastikan kasir hanya bisa lihat transaksinya sendiri
        if ($transaksi->kasir_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses ke transaksi ini.');
        }

        return view('kasir.nota', compact('transaksi'));
    }

    /**
     * 📋 Riwayat Transaksi Kasir
     */
    public function riwayat()
    {
        $transaksi = Transaksi::where('kasir_id', auth()->id())
            ->with('detailTransaksi')
            ->orderByDesc('tanggal_transaksi')
            ->paginate(15);

        return view('kasir.riwayat', compact('transaksi'));
    }
}