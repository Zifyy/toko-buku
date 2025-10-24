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
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function __construct()
    {
        // Hanya role admin yang bisa akses controller ini
        $this->middleware('role:admin');
    }

    /**
     * Dashboard Admin (Dengan Pagination & Data Chart Terpisah)
     */
    public function dashboard(Request $request)
    {
        // ✅ DATA UNTUK CHART & STATISTIK (SEMUA DATA, TANPA FILTER)
        $allBuku = Buku::with(['kategori', 'detailBuku'])->get();
        
        // 🔍 DEBUG: Log semua kategori untuk analisis
        Log::info('=== DEBUG KATEGORI ===');
        foreach($allBuku as $buku) {
            if($buku->kategori) {
                Log::info("Buku: {$buku->judul} | Kategori: {$buku->kategori->nama_kategori} | ID: {$buku->kategori->id}");
            } else {
                Log::info("Buku: {$buku->judul} | Kategori: NULL");
            }
        }
        
        // ✅ DATA UNTUK LIST BUKU (DENGAN FILTER & PAGINATION)
        $query = Buku::with(['kategori', 'detailBuku']);

        // Filter Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                  ->orWhere('kode_buku', 'like', "%$search%")
                  ->orWhere('pengarang', 'like', "%$search%")
                  ->orWhere('penerbit', 'like', "%$search%");
            });
        }

        // Filter Kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori_id', $request->kategori);
        }

        // Filter Status Stok
        if ($request->has('stok_status') && $request->stok_status != '') {
            $status = $request->stok_status;
            $query->whereHas('detailBuku', function($q) use ($status) {
                if ($status == 'tersedia') {
                    $q->where('stok', '>', 5);
                } elseif ($status == 'menipis') {
                    $q->whereBetween('stok', [1, 5]);
                } elseif ($status == 'habis') {
                    $q->where('stok', 0);
                }
            });
        }

        $buku = $query->paginate(12);

        // ✅ STATISTIK UMUM
        $totalBuku = Buku::count();
        $totalKategori = Kategori::count();
        $totalDetailBuku = DetailBuku::count();
        $totalUser = User::count();

        // ✅ HITUNG DATA CHART DARI $allBuku (BUKAN $buku)
        
        // 🔥 PERBAIKAN DISTRIBUSI KATEGORI - Cek berbagai kemungkinan nama
        // Kemungkinan variasi penulisan di database
        $fiksiVariants = ['Fiksi', 'fiksi', 'FIKSI'];
        $nonFiksiVariants = ['Non-Fiksi', 'Non Fiksi', 'NonFiksi', 'non-fiksi', 'non fiksi', 'NON-FIKSI', 'NonFiksi'];
        
        $countFiksi = $allBuku->filter(function($item) use ($fiksiVariants) {
            if (!$item->kategori) return false;
            $kategoriNama = trim($item->kategori->nama_kategori);
            return in_array($kategoriNama, $fiksiVariants);
        })->count();
        
        $countNonFiksi = $allBuku->filter(function($item) use ($nonFiksiVariants) {
            if (!$item->kategori) return false;
            $kategoriNama = trim($item->kategori->nama_kategori);
            return in_array($kategoriNama, $nonFiksiVariants);
        })->count();
        
        // Debug log hasil perhitungan
        Log::info("Fiksi Count: $countFiksi");
        Log::info("Non-Fiksi Count: $countNonFiksi");
        
        // Jika masih 0, gunakan metode alternatif: groupBy langsung
        if ($countFiksi == 0 && $countNonFiksi == 0) {
            Log::info("Menggunakan metode alternatif groupBy");
            
            $kategoriGrouped = $allBuku->filter(function($item) {
                return $item->kategori !== null;
            })->groupBy(function($item) {
                return trim($item->kategori->nama_kategori);
            });
            
            // Ambil 2 kategori terbanyak
            $kategoriData = $kategoriGrouped->map(function($items, $key) {
                return [
                    'nama' => $key,
                    'jumlah' => $items->count()
                ];
            })->sortByDesc('jumlah')->take(2)->values();
            
            Log::info("Kategori Data (alternatif):", $kategoriData->toArray());
        } else {
            $kategoriData = collect([
                ['nama' => 'Fiksi', 'jumlah' => $countFiksi],
                ['nama' => 'Non-Fiksi', 'jumlah' => $countNonFiksi]
            ]);
        }
        
        // 2. Distribusi GENRE
        $genreData = $allBuku->filter(function($item) {
            return $item->kategori && $item->kategori->genre;
        })->groupBy(function($item) {
            return trim($item->kategori->genre);
        })->map(function($items, $key) {
            return [
                'nama' => $key,
                'jumlah' => $items->count()
            ];
        })->sortByDesc('jumlah')->values();

        // 3. Distribusi JENIS dengan sorting untuk konsistensi warna
        $jenisData = $allBuku->filter(function($item) {
            return $item->kategori && $item->kategori->jenis;
        })->groupBy(function($item) {
            return trim($item->kategori->jenis);
        })->map(function($items, $key) {
            return [
                'nama' => $key,
                'jumlah' => $items->count()
            ];
        })->sortByDesc('jumlah')->values();

        // 4. Status Stok
        $stokTersedia = $allBuku->filter(function($item) {
            return ($item->detailBuku->stok ?? 0) > 5;
        })->count();
        
        $stokMenipis = $allBuku->filter(function($item) {
            $stok = $item->detailBuku->stok ?? 0;
            return $stok > 0 && $stok <= 5;
        })->count();
        
        $stokHabis = $allBuku->filter(function($item) {
            return ($item->detailBuku->stok ?? 0) == 0;
        })->count();

        // 5. Top 5 Kategori berdasarkan total stok
        $topKategori = $allBuku->filter(function($item) {
            return $item->kategori !== null;
        })->groupBy('kategori_id')
            ->map(function($items, $key) {
                $kategori = $items->first()->kategori;
                $totalStok = $items->sum(function($item) {
                    return $item->detailBuku->stok ?? 0;
                });
                return [
                    'nama' => $kategori ? $kategori->nama_kategori : 'Tanpa Kategori',
                    'stok' => $totalStok
                ];
            })->sortByDesc('stok')->take(5)->values();

        // 6. Total Stok (dari semua buku)
        $totalStok = $allBuku->sum(function($item) {
            return $item->detailBuku->stok ?? 0;
        });

        // 7. Peringatan (dari semua buku)
        $bukuStokMenipis = $allBuku->filter(function($item) {
            $stok = $item->detailBuku->stok ?? 0;
            return $stok > 0 && $stok <= 5;
        });
        
        $bukuStokHabis = $allBuku->filter(function($item) {
            return ($item->detailBuku->stok ?? 0) == 0;
        });
        
        $bukuTanpaDetail = $allBuku->filter(function($item) {
            return !$item->detailBuku;
        });

        // ✅ DAFTAR KATEGORI UNTUK DROPDOWN FILTER
        $allKategori = Kategori::orderBy('nama_kategori')->get();
        
        // 🔍 Debug: Kirim info kategori unique untuk troubleshooting
        $uniqueKategoriNames = $allBuku->filter(function($item) {
            return $item->kategori !== null;
        })->pluck('kategori.nama_kategori')->unique()->values();
        
        Log::info("Unique Kategori Names:", $uniqueKategoriNames->toArray());

        return view('admin.dashboard', compact(
            'totalBuku',
            'totalKategori',
            'totalDetailBuku',
            'totalUser',
            'totalStok',
            'buku',
            'allKategori',
            'kategoriData',
            'genreData',
            'jenisData',
            'stokTersedia',
            'stokMenipis',
            'stokHabis',
            'topKategori',
            'bukuStokMenipis',
            'bukuStokHabis',
            'bukuTanpaDetail',
            'uniqueKategoriNames' // Untuk debugging di blade
        ));
    }

    /**
     * Dashboard Admin (Method lama untuk compatibility)
     */
    public function index()
    {
        return $this->dashboard(request());
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
     * Laporan Keuangan dengan Filter
     */
    public function laporanKeuangan(Request $request)
    {
        // Ambil filter dari request atau gunakan bulan/tahun saat ini sebagai default
        $filterBulan = $request->get('bulan', date('m'));
        $filterTahun = $request->get('tahun', date('Y'));

        // Query transaksi dengan filter dan pagination
        $transaksi = Transaksi::with(['kasir', 'detailTransaksi'])
            ->whereYear('tanggal_transaksi', $filterTahun)
            ->whereMonth('tanggal_transaksi', $filterBulan)
            ->orderBy('tanggal_transaksi', 'desc')
            ->paginate(15);

        // Total Transaksi (filtered)
        $totalTransaksi = Transaksi::whereYear('tanggal_transaksi', $filterTahun)
            ->whereMonth('tanggal_transaksi', $filterBulan)
            ->count();

        // Total Buku Terjual (filtered)
        $totalBukuTerjual = TransaksiDetail::whereHas('transaksi', function($query) use ($filterTahun, $filterBulan) {
            $query->whereYear('tanggal_transaksi', $filterTahun)
                  ->whereMonth('tanggal_transaksi', $filterBulan);
        })->sum('jumlah');

        // Total Pendapatan (filtered)
        $totalPendapatan = Transaksi::whereYear('tanggal_transaksi', $filterTahun)
            ->whereMonth('tanggal_transaksi', $filterBulan)
            ->sum('total');

        // Total Diskon (filtered)
        $totalDiskon = TransaksiDetail::whereHas('transaksi', function($query) use ($filterTahun, $filterBulan) {
            $query->whereYear('tanggal_transaksi', $filterTahun)
                  ->whereMonth('tanggal_transaksi', $filterBulan);
        })->sum(DB::raw('IFNULL(subtotal,0) - IFNULL(subtotal_setelah_diskon,subtotal)'));

        // Buku Terlaris (filtered) - Top 10
        $bukuTerlaris = TransaksiDetail::select(
                'buku_id',
                DB::raw('SUM(jumlah) as jumlah_terjual'),
                DB::raw('SUM(subtotal_setelah_diskon) as total_pendapatan')
            )
            ->whereHas('transaksi', function($query) use ($filterTahun, $filterBulan) {
                $query->whereYear('tanggal_transaksi', $filterTahun)
                      ->whereMonth('tanggal_transaksi', $filterBulan);
            })
            ->groupBy('buku_id')
            ->orderByDesc('jumlah_terjual')
            ->limit(10)
            ->with('buku')
            ->get();
        
        // Debug log
        Log::info('Buku Terlaris Count: ' . $bukuTerlaris->count());
        Log::info('Buku Terlaris Data: ', $bukuTerlaris->toArray());

        return view('admin.laporan', compact(
            'totalTransaksi',
            'totalBukuTerjual',
            'totalPendapatan',
            'totalDiskon',
            'transaksi',
            'bukuTerlaris',
            'filterBulan',
            'filterTahun'
        ));
    }
}