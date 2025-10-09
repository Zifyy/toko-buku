<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

        // Untuk sekarang kita tampilkan hasil di halaman transaksi dulu (sementara)
        return view('kasir.transaksi', compact('cartData'));
    }
}
