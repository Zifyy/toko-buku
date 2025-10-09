<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\DetailBuku;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

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
}
