<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaksi;
use App\Models\Buku;
use App\Models\DetailBuku;

class TransaksiController extends Controller
{
    /**
     * Tampilkan halaman checkout dengan validasi cart
     */
    public function store(Request $request)
    {
        // Ambil cart_data dari request
        $cartData = $request->input('cart_data');

        if (!$cartData) {
            return redirect()->route('kasir.dashboard')
                ->with('error', 'Keranjang masih kosong. Silakan pilih buku terlebih dahulu.');
        }

        // Decode cart data
        $cartArray = json_decode($cartData, true);

        if (!is_array($cartArray) || empty($cartArray)) {
            return redirect()->route('kasir.dashboard')
                ->with('error', 'Data keranjang tidak valid.');
        }

        // Validasi dan sanitize setiap item
        $validatedCart = [];
        $errors = [];

        foreach ($cartArray as $item) {
            // Cek apakah item memiliki struktur yang benar
            if (!isset($item['id']) || !isset($item['title']) || !isset($item['price']) || !isset($item['quantity'])) {
                continue;
            }

            // Ambil data buku dari database
            $buku = Buku::with('detailBuku')->find($item['id']);

            if (!$buku) {
                $errors[] = "Buku '{$item['title']}' tidak ditemukan.";
                continue;
            }

            if (!$buku->detailBuku) {
                $errors[] = "Detail buku '{$item['title']}' tidak tersedia.";
                continue;
            }

            $stok = $buku->detailBuku->stok ?? 0;
            $harga = $buku->detailBuku->harga ?? 0;

            // Validasi stok
            if ($stok <= 0) {
                $errors[] = "Buku '{$buku->judul}' sudah habis.";
                continue;
            }

            // Validasi harga
            if ($harga <= 0) {
                $errors[] = "Harga buku '{$buku->judul}' belum tersedia.";
                continue;
            }

            // Validasi quantity vs stok
            $quantity = (int) $item['quantity'];
            if ($quantity > $stok) {
                $errors[] = "Stok buku '{$buku->judul}' hanya tersedia {$stok} item.";
                $quantity = $stok; // Adjust ke stok maksimal
            }

            if ($quantity <= 0) {
                continue;
            }

            // Add to validated cart
            $validatedCart[] = [
                'id' => $buku->id,
                'title' => $buku->judul,
                'price' => $harga,
                'quantity' => $quantity,
                'stok' => $stok
            ];
        }

        // Jika tidak ada item valid
        if (empty($validatedCart)) {
            $errorMessage = 'Tidak ada item valid di keranjang.';
            if (!empty($errors)) {
                $errorMessage .= ' ' . implode(' ', $errors);
            }
            return redirect()->route('kasir.dashboard')->with('error', $errorMessage);
        }

        // Jika ada error tapi masih ada item valid, tampilkan warning
        if (!empty($errors)) {
            session()->flash('warning', implode(' ', $errors));
        }

        // Tampilkan halaman checkout
        return view('kasir.transaksi', ['cart' => $validatedCart]);
    }

    /**
     * Proses pembayaran dan simpan transaksi
     */
    public function finalize(Request $request)
    {
        // Ambil cart_data dari request
        $cartData = $request->input('cart_data');

        if (!$cartData) {
            return redirect()->route('kasir.dashboard')
                ->with('error', 'Keranjang masih kosong.');
        }

        // Decode cart data
        $cartArray = json_decode($cartData, true);

        if (!is_array($cartArray) || empty($cartArray)) {
            return redirect()->route('kasir.dashboard')
                ->with('error', 'Data keranjang tidak valid.');
        }

        DB::beginTransaction();
        try {
            $totalTransaksi = 0;
            $detailTransaksi = [];

            // Validasi ulang setiap item sebelum menyimpan
            foreach ($cartArray as $item) {
                $buku = Buku::with('detailBuku')->find($item['id']);

                if (!$buku || !$buku->detailBuku) {
                    throw new \Exception("Buku dengan ID {$item['id']} tidak ditemukan.");
                }

                $stok = $buku->detailBuku->stok;
                $harga = $buku->detailBuku->harga;
                $quantity = (int) $item['quantity'];

                // Validasi stok
                if ($stok < $quantity) {
                    throw new \Exception("Stok buku '{$buku->judul}' tidak mencukupi. Tersedia: {$stok}, Diminta: {$quantity}");
                }

                // Hitung subtotal
                $subtotal = $harga * $quantity;
                $totalTransaksi += $subtotal;

                // Simpan detail untuk nanti
                $detailTransaksi[] = [
                    'buku_id' => $buku->id,
                    'judul' => $buku->judul,
                    'harga' => $harga,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal
                ];
            }

            // Buat transaksi utama
            $transaksi = Transaksi::create([
                'kasir_id' => auth()->id(),
                'tanggal_transaksi' => now(),
                'total' => $totalTransaksi,
            ]);

            // Kurangi stok dan simpan detail transaksi
            foreach ($detailTransaksi as $detail) {
                $buku = Buku::with('detailBuku')->find($detail['buku_id']);
                
                // Kurangi stok
                $buku->detailBuku->stok -= $detail['quantity'];
                $buku->detailBuku->save();

                // Jika Anda punya tabel detail_transaksi, simpan di sini
                // DetailTransaksi::create([
                //     'transaksi_id' => $transaksi->id,
                //     'buku_id' => $detail['buku_id'],
                //     'jumlah' => $detail['quantity'],
                //     'harga' => $detail['harga'],
                //     'subtotal' => $detail['subtotal']
                // ]);
            }

            DB::commit();

            // Redirect ke halaman nota/sukses dengan data transaksi
            return redirect()->route('kasir.nota', $transaksi->id)
                ->with('success', 'Transaksi berhasil diproses!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan nota/struk transaksi
     */
    public function nota($id)
    {
        $transaksi = Transaksi::with('kasir')->findOrFail($id);
        
        // Jika Anda punya relasi detail transaksi:
        // $transaksi = Transaksi::with(['kasir', 'details.buku'])->findOrFail($id);

        return view('kasir.nota', compact('transaksi'));
    }

    /**
     * Tampilkan riwayat transaksi kasir
     */
    public function riwayat()
    {
        $transaksi = Transaksi::where('kasir_id', auth()->id())
            ->orderByDesc('tanggal_transaksi')
            ->paginate(15);

        return view('kasir.riwayat', compact('transaksi'));
    }

    /**
     * Detail transaksi tertentu
     */
    public function detail($id)
    {
        $transaksi = Transaksi::with('kasir')->findOrFail($id);
        
        // Pastikan kasir hanya bisa lihat transaksinya sendiri
        if ($transaksi->kasir_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            abort(403, 'Anda tidak memiliki akses ke transaksi ini.');
        }

        // Jika Anda punya relasi detail transaksi:
        // $transaksi = Transaksi::with(['kasir', 'details.buku'])->findOrFail($id);

        return view('kasir.riwayat', compact('transaksi'));
    }

    /**
     * Cek ketersediaan buku (untuk AJAX request)
     */
    public function cekBuku($id)
    {
        $buku = Buku::with('detailBuku')->find($id);

        if (!$buku) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }

        if (!$buku->detailBuku) {
            return response()->json([
                'success' => false,
                'message' => 'Detail buku tidak tersedia'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $buku->id,
                'judul' => $buku->judul,
                'stok' => $buku->detailBuku->stok ?? 0,
                'harga' => $buku->detailBuku->harga ?? 0,
                'available' => ($buku->detailBuku->stok > 0 && $buku->detailBuku->harga > 0)
            ]
        ]);
    }

    /**
     * Batalkan transaksi (opsional - jika diizinkan)
     */
    public function cancel($id)
    {
        DB::beginTransaction();
        try {
            $transaksi = Transaksi::findOrFail($id);

            // Validasi: hanya kasir yang buat transaksi atau admin yang bisa cancel
            if ($transaksi->kasir_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
                throw new \Exception('Anda tidak memiliki akses untuk membatalkan transaksi ini.');
            }

            // Validasi: transaksi tidak boleh terlalu lama (misalnya max 1 jam)
            if ($transaksi->tanggal_transaksi->diffInHours(now()) > 1) {
                throw new \Exception('Transaksi tidak dapat dibatalkan setelah 1 jam.');
            }

            // Kembalikan stok (jika Anda punya detail transaksi)
            // foreach ($transaksi->details as $detail) {
            //     $buku = Buku::with('detailBuku')->find($detail->buku_id);
            //     if ($buku && $buku->detailBuku) {
            //         $buku->detailBuku->stok += $detail->jumlah;
            //         $buku->detailBuku->save();
            //     }
            // }

            // Tandai transaksi sebagai dibatalkan (tambahkan kolom 'status' di tabel jika perlu)
            // $transaksi->status = 'cancelled';
            // $transaksi->save();

            // Atau hapus transaksi (tidak direkomendasikan untuk audit trail)
            // $transaksi->delete();

            DB::commit();

            return redirect()->route('kasir.riwayat')
                ->with('success', 'Transaksi berhasil dibatalkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}