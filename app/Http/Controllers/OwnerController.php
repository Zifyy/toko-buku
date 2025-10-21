<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\User;
use App\Models\DetailBuku;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;

class OwnerController extends Controller
{
    public function __construct()
    {
        // Hanya role "owner" yang bisa mengakses controller ini
        $this->middleware('role:owner');
    }

    /**
     * 🏠 Dashboard Owner
     */
    public function dashboard()
    {
        $stats = $this->getGlobalStats();

        // Buku terlaris singkat
        $bukuTerlaris = $this->getBukuTerlaris(5);

        return view('owner.dashboard', array_merge($stats, [
            'bukuTerlaris' => $bukuTerlaris,
        ]));
    }

    /**
     * 📚 Halaman Data Buku (Read Only untuk Owner)
     */
    public function buku(Request $request)
    {
        $stats = $this->getGlobalStats();
        $bukuTerlaris = $this->getBukuTerlaris(5);

        // Support pencarian sederhana (sesuai permintaan sebelumnya)
        $query = Buku::with(['kategori', 'detailBuku'])->orderByDesc('created_at');

        if ($request->filled('search')) {
            $s = $request->input('search');
            $query->where(function ($q) use ($s) {
                $q->where('judul', 'like', "%{$s}%")
                  ->orWhere('kode_buku', 'like', "%{$s}%")
                  ->orWhere('pengarang', 'like', "%{$s}%");
            });
        }

        $buku = $query->paginate(12)->withQueryString(); // 12 per page seperti permintaan awal

        return view('owner.data_buku', array_merge($stats, [
            'buku' => $buku,
            'bukuTerlaris' => $bukuTerlaris,
        ]));
    }

    /**
     * 👥 Halaman Data User (List)
     */
    public function user(Request $request)
    {
        $stats = $this->getGlobalStats();
        $bukuTerlaris = $this->getBukuTerlaris(5);

        $users = User::orderByDesc('created_at')->paginate(15);

        return view('owner.data_user', array_merge($stats, [
            'users' => $users,
            'bukuTerlaris' => $bukuTerlaris,
        ]));
    }

    /**
     * ➕ Form Tambah User
     */
    public function createUser()
    {
        $stats = $this->getGlobalStats();
        $bukuTerlaris = $this->getBukuTerlaris(5);

        return view('owner.create_user', array_merge($stats, [
            'bukuTerlaris' => $bukuTerlaris,
        ]));
    }

    /**
     * 💾 Simpan User Baru
     */
    public function storeUser(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:admin,kasir', // Owner hanya bisa tambah admin atau kasir
        ]);

        // Simpan user baru
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('owner.user')->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * ✏️ Form Edit User
     */
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        $stats = $this->getGlobalStats();
        $bukuTerlaris = $this->getBukuTerlaris(5);

        return view('owner.edit_user', array_merge($stats, [
            'user' => $user,
            'bukuTerlaris' => $bukuTerlaris,
        ]));
    }

    /**
     * 🔄 Update User
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
                'password' => 'nullable|min:6|confirmed',
            ]);
            $user->name = $request->name;
            $user->email = $request->email;
            // role tidak diubah pada diri sendiri
        } else {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
                'role' => 'required|in:admin,kasir',
                'password' => 'nullable|min:6|confirmed',
            ]);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->role = $request->role;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('owner.user')->with('success', 'User berhasil diupdate!');
    }

    /**
     * 🗑️ Hapus User
     */
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->route('owner.user')->with('error', 'Tidak bisa menghapus akun sendiri!');
        }

        if ($user->role === 'owner') {
            return redirect()->route('owner.user')->with('error', 'Tidak bisa menghapus owner lain!');
        }

        $user->delete();

        return redirect()->route('owner.user')->with('success', 'User berhasil dihapus!');
    }

    /**
     * 💰 Halaman Laporan
     */
    public function laporan(Request $request)
    {
        $stats = $this->getGlobalStats();
        $bukuTerlaris = $this->getBukuTerlaris(5);

        $filterBulan = $request->input('bulan', date('m'));
        $filterTahun = $request->input('tahun', date('Y'));

        // NOTE: use correct relasi names: 'kasir' dan 'detailTransaksi'
        $query = Transaksi::with(['kasir', 'detailTransaksi.buku']);

        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('tanggal_transaksi', $filterBulan)
                  ->whereYear('tanggal_transaksi', $filterTahun);
        }

        $transaksi = $query->orderByDesc('tanggal_transaksi')->paginate(15);

        // Statistik filtered
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $totalTransaksiFiltered = Transaksi::whereMonth('tanggal_transaksi', $filterBulan)
                                               ->whereYear('tanggal_transaksi', $filterTahun)
                                               ->count();

            $totalPendapatanFiltered = Transaksi::whereMonth('tanggal_transaksi', $filterBulan)
                                               ->whereYear('tanggal_transaksi', $filterTahun)
                                               ->sum('total');

            $totalBukuTerjualFiltered = TransaksiDetail::whereHas('transaksi', function ($q) use ($filterBulan, $filterTahun) {
                $q->whereMonth('tanggal_transaksi', $filterBulan)
                  ->whereYear('tanggal_transaksi', $filterTahun);
            })->sum('jumlah');

            $totalDiskonFiltered = TransaksiDetail::whereHas('transaksi', function ($q) use ($filterBulan, $filterTahun) {
                $q->whereMonth('tanggal_transaksi', $filterBulan)
                  ->whereYear('tanggal_transaksi', $filterTahun);
            })->sum(DB::raw('IFNULL(subtotal,0) - IFNULL(subtotal_setelah_diskon, subtotal)'));
        } else {
            $totalTransaksiFiltered = Transaksi::count();
            $totalPendapatanFiltered = Transaksi::sum('total');
            $totalBukuTerjualFiltered = TransaksiDetail::sum('jumlah');
            $totalDiskonFiltered = TransaksiDetail::sum(DB::raw('IFNULL(subtotal,0) - IFNULL(subtotal_setelah_diskon, subtotal)'));
        }

        // Buku terlaris berdasarkan filter
        $bukuTerlarisFiltered = TransaksiDetail::select(
                'buku_id',
                DB::raw('SUM(jumlah) as jumlah_terjual'),
                DB::raw('SUM(IFNULL(subtotal_setelah_diskon, subtotal)) as total_pendapatan')
            )
            ->groupBy('buku_id')
            ->orderByDesc('jumlah_terjual');

        if ($request->filled('bulan') && $request->filled('tahun')) {
            $bukuTerlarisFiltered->whereHas('transaksi', function ($q) use ($filterBulan, $filterTahun) {
                $q->whereMonth('tanggal_transaksi', $filterBulan)
                  ->whereYear('tanggal_transaksi', $filterTahun);
            });
        }

        $bukuTerlarisFiltered = $bukuTerlarisFiltered->with('buku:id,judul,kode_buku')->limit(10)->get();

        return view('owner.laporan', array_merge($stats, [
            'transaksi' => $transaksi,
            'bukuTerlaris' => $bukuTerlaris,
            'filterBulan' => $filterBulan,
            'filterTahun' => $filterTahun,
            'totalTransaksiFiltered' => $totalTransaksiFiltered,
            'totalPendapatanFiltered' => $totalPendapatanFiltered,
            'totalBukuTerjualFiltered' => $totalBukuTerjualFiltered,
            'totalDiskonFiltered' => $totalDiskonFiltered,
            'bukuTerlarisFiltered' => $bukuTerlarisFiltered,
        ]));
    }

    /**
     * 📊 Export Laporan ke Excel (CSV)
     */
    public function exportLaporan(Request $request)
    {
        $filterBulan = $request->get('bulan', date('m'));
        $filterTahun = $request->get('tahun', date('Y'));

        $query = Transaksi::with(['kasir', 'detailTransaksi.buku']);

        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('tanggal_transaksi', $filterBulan)
                  ->whereYear('tanggal_transaksi', $filterTahun);
        }

        $transaksi = $query->orderByDesc('tanggal_transaksi')->get();

        $namaBulan = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];

        $periode = ($namaBulan[$filterBulan] ?? $filterBulan) . '_' . $filterTahun;
        $filename = 'Laporan_Keuangan_' . $periode . '_' . date('YmdHis') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($transaksi, $periode) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM untuk Excel agar bisa baca karakter Indonesia
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header informasi
            fputcsv($file, ['LAPORAN KEUANGAN TOKO BUKU']);
            fputcsv($file, ['Periode: ' . $periode]);
            fputcsv($file, ['Tanggal Export: ' . date('d-m-Y H:i:s')]);
            fputcsv($file, []); // Baris kosong

            // Header kolom tabel
            fputcsv($file, [
                'No',
                'Tanggal',
                'Kode Transaksi',
                'Kasir',
                'Jumlah Item',
                'Subtotal',
                'Diskon',
                'Total'
            ]);

            // Data transaksi
            $no = 1;
            $totalSubtotal = 0;
            $totalDiskonSum = 0;
            $totalPendapatan = 0;
            $totalItem = 0;

            foreach ($transaksi as $trx) {
                // gunakan relasi detailTransaksi (nama relasi di model Transaksi)
                $subtotal = $trx->detailTransaksi->sum('subtotal');
                // jika subtotal_setelah_diskon tersedia di detail, total diskon dihitung sebagai selisih subtotal - subtotal_setelah_diskon
                $diskon = $trx->detailTransaksi->sum(function ($d) {
                    return (float)($d->subtotal ?? 0) - (float)($d->subtotal_setelah_diskon ?? $d->subtotal ?? 0);
                });
                $jumlahItem = $trx->detailTransaksi->sum('jumlah');

                // Akumulasi total
                $totalSubtotal += $subtotal;
                $totalDiskonSum += $diskon;
                $totalPendapatan += $trx->total;
                $totalItem += $jumlahItem;

                fputcsv($file, [
                    $no++,
                    \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d-m-Y H:i'),
                    $trx->kode_transaksi,
                    $trx->kasir->name ?? '-',
                    $jumlahItem,
                    $subtotal,
                    $diskon,
                    $trx->total
                ]);
            }

            // Baris total
            fputcsv($file, []); // Baris kosong
            fputcsv($file, [
                '',
                '',
                '',
                'TOTAL',
                $totalItem,
                $totalSubtotal,
                $totalDiskonSum,
                $totalPendapatan
            ]);

            // Ringkasan
            fputcsv($file, []); // Baris kosong
            fputcsv($file, ['RINGKASAN']);
            fputcsv($file, ['Total Transaksi', $no - 1]);
            fputcsv($file, ['Total Item Terjual', $totalItem]);
            fputcsv($file, ['Total Subtotal', $totalSubtotal]);
            fputcsv($file, ['Total Diskon', $totalDiskonSum]);
            fputcsv($file, ['Total Pendapatan', $totalPendapatan]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * 📊 Halaman Detail Transaksi (opsional)
     */
    public function transaksi(Request $request)
    {
        $stats = $this->getGlobalStats();
        $bukuTerlaris = $this->getBukuTerlaris(5);

        $transaksi = Transaksi::with(['kasir', 'detailTransaksi.buku'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('owner.transaksi', array_merge($stats, [
            'transaksi' => $transaksi,
            'bukuTerlaris' => $bukuTerlaris,
        ]));
    }

    /**
     * Helper: statistik global (digunakan di banyak view)
     */
    private function getGlobalStats()
    {
        return [
            'totalBuku' => Buku::count(),
            'totalKategori' => Kategori::count(),
            'totalDetailBuku' => DetailBuku::count(),
            'totalUser' => User::count(),
            'totalTransaksi' => Transaksi::count(),
            'totalPendapatan' => Transaksi::sum('total'),
            'totalBukuTerjual' => TransaksiDetail::sum('jumlah'),
            'totalDiskon' => TransaksiDetail::sum(DB::raw('IFNULL(subtotal,0) - IFNULL(subtotal_setelah_diskon, subtotal)')),
        ];
    }

    /**
     * Helper: buku terlaris (global atau per bulan/tahun)
     */
    private function getBukuTerlaris($limit = 5, $bulan = null, $tahun = null)
    {
        $query = TransaksiDetail::select(
                'buku_id',
                DB::raw('SUM(jumlah) as jumlah_terjual'),
                DB::raw('SUM(IFNULL(subtotal_setelah_diskon, subtotal)) as total_pendapatan')
            )
            ->groupBy('buku_id')
            ->orderByDesc('jumlah_terjual');

        if ($bulan && $tahun) {
            $query->whereHas('transaksi', function ($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal_transaksi', $bulan)
                  ->whereYear('tanggal_transaksi', $tahun);
            });
        }

        return $query->with('buku:id,judul,kode_buku')->limit($limit)->get();
    }
}
