<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\DetailBuku;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        // Hanya role admin yang bisa akses controller ini
        $this->middleware('role:admin');
    }

    /**
     * Dashboard Admin
     */
    public function index()
    {
        $totalBuku       = Buku::count();
        $totalKategori   = Kategori::count();
        $totalDetailBuku = DetailBuku::count();
        $totalUser       = User::count();

        // Ambil semua data buku dengan kategori dan detailnya
        $buku = Buku::with(['kategori', 'detailBuku'])->get();

        return view('admin.dashboard', compact(
            'totalBuku',
            'totalKategori',
            'totalDetailBuku',
            'totalUser',
            'buku'
        ));
    }

    /**
     * Laporan Data Buku
     */
    public function laporan()
    {
        $buku = Buku::with('kategori')->get();
        return view('admin.laporan', compact('buku'));
    }

    /**
     * Dashboard Admin (Dengan Pagination)
     */
    public function dashboard(Request $request)
    {
        $query = Buku::with(['kategori', 'detailBuku']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                  ->orWhere('kode_buku', 'like', "%$search%")
                  ->orWhere('pengarang', 'like', "%$search%")
                  ->orWhere('penerbit', 'like', "%$search%");
            });
        }

        $buku = $query->paginate(12);

        $totalBuku = Buku::count();
        $totalKategori = Kategori::count();
        $totalDetailBuku = DetailBuku::count();
        $totalUser = User::count();

        return view('admin.dashboard', compact(
            'totalBuku',
            'totalKategori',
            'totalDetailBuku',
            'totalUser',
            'buku'
        ));
    }

    /**
     * Laporan Keuangan
     */
    public function laporanKeuangan()
    {
        // Ringkasan
        $totalTransaksi = Transaksi::count();
        $totalBukuTerjual = TransaksiDetail::sum('jumlah');
        $totalPendapatan = Transaksi::sum('total');
        $totalDiskon = TransaksiDetail::sum(DB::raw('IFNULL(subtotal,0) - IFNULL(subtotal_setelah_diskon,subtotal)'));

        // Detail transaksi (relasi kasir dan detailTransaksi)
        $transaksi = Transaksi::with(['kasir', 'detailTransaksi'])->orderByDesc('tanggal_transaksi')->get();

        // Buku terlaris
        $bukuTerlaris = TransaksiDetail::select(
                'buku_id',
                DB::raw('SUM(jumlah) as jumlah_terjual'),
                DB::raw('SUM(subtotal_setelah_diskon) as total_pendapatan')
            )
            ->groupBy('buku_id')
            ->orderByDesc('jumlah_terjual')
            ->with('buku')
            ->get()
            ->map(function($item) {
                return (object)[
                    'judul' => $item->buku->judul ?? '-',
                    'jumlah_terjual' => $item->jumlah_terjual,
                    'total_pendapatan' => $item->total_pendapatan,
                ];
            });

        return view('admin.laporan', compact(
            'totalTransaksi',
            'totalBukuTerjual',
            'totalPendapatan',
            'totalDiskon',
            'transaksi',
            'bukuTerlaris'
        ));
    }
}
