@extends('owner.layout.owner_layout')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Laporan Keuangan Toko Buku</h1>
            <p class="text-muted">Laporan transaksi dan penjualan toko buku</p>
        </div>
    </div>

    <!-- Filter Periode -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('owner.laporan') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Bulan</label>
                    <select name="bulan" class="form-select">
                        <option value="01" {{ $filterBulan == '01' ? 'selected' : '' }}>Januari</option>
                        <option value="02" {{ $filterBulan == '02' ? 'selected' : '' }}>Februari</option>
                        <option value="03" {{ $filterBulan == '03' ? 'selected' : '' }}>Maret</option>
                        <option value="04" {{ $filterBulan == '04' ? 'selected' : '' }}>April</option>
                        <option value="05" {{ $filterBulan == '05' ? 'selected' : '' }}>Mei</option>
                        <option value="06" {{ $filterBulan == '06' ? 'selected' : '' }}>Juni</option>
                        <option value="07" {{ $filterBulan == '07' ? 'selected' : '' }}>Juli</option>
                        <option value="08" {{ $filterBulan == '08' ? 'selected' : '' }}>Agustus</option>
                        <option value="09" {{ $filterBulan == '09' ? 'selected' : '' }}>September</option>
                        <option value="10" {{ $filterBulan == '10' ? 'selected' : '' }}>Oktober</option>
                        <option value="11" {{ $filterBulan == '11' ? 'selected' : '' }}>November</option>
                        <option value="12" {{ $filterBulan == '12' ? 'selected' : '' }}>Desember</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Tahun</label>
                    <select name="tahun" class="form-select">
                        @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                            <option value="{{ $i }}" {{ $filterTahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <a href="{{ route('owner.laporan') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                    <a href="{{ route('owner.laporan.export', ['bulan' => $filterBulan, 'tahun' => $filterTahun]) }}" 
                       class="btn btn-success">
                        <i class="bi bi-file-earmark-excel"></i> Export Excel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Ringkasan Statistik (3 Cards) -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card text-white shadow h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="card-title mb-1 fw-bold">Total Transaksi</h6>
                            <small class="opacity-75">Transaksi Terjadi</small>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-receipt fs-2"></i>
                        </div>
                    </div>
                    <h2 class="card-text mb-0 fw-bold display-5">{{ number_format($totalTransaksiFiltered, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card text-white shadow h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="card-title mb-1 fw-bold">Total Buku Terjual</h6>
                            <small class="opacity-75">Buku Terjual</small>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-book fs-2"></i>
                        </div>
                    </div>
                    <h2 class="card-text mb-0 fw-bold display-5">{{ number_format($totalBukuTerjualFiltered, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card text-white shadow h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="card-title mb-1 fw-bold">Total Pendapatan</h6>
                            <small class="opacity-75">Total Pemasukan</small>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-cash-stack fs-2"></i>
                        </div>
                    </div>
                    <h2 class="card-text mb-0 fw-bold" style="font-size: 1.75rem;">Rp{{ number_format($totalPendapatanFiltered, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Detail Transaksi -->
    <div class="card shadow-sm mb-4">
        <div class="card-header text-white d-flex justify-content-between align-items-center"
             style="background: linear-gradient(90deg, #00c6ff, #0077b6);">
            <h5 class="mb-0">
                <i class="bi bi-list-check me-2"></i>Detail Transaksi
            </h5>
            <span class="badge bg-white text-primary">{{ $transaksi->total() }} transaksi</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="50">No</th>
                            <th>Tanggal</th>
                            <th>Kode Transaksi</th>
                            <th>Kasir</th>
                            <th class="text-center">Jumlah Item</th>
                            <th class="text-end">Total Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transaksi as $index => $trx)
                            <tr>
                                <td>{{ $index + $transaksi->firstItem() }}</td>
                                <td>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d M Y') }}
                                    </small><br>
                                    <small class="text-primary fw-bold">
                                        {{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('H:i') }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $trx->kode_transaksi }}</span>
                                </td>
                                <td>
                                    <i class="bi bi-person-circle me-1"></i>
                                    {{ $trx->user->name ?? '-' }}
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info">{{ $trx->transaksiDetail->sum('jumlah') }} item</span>
                                </td>
                                <td class="text-end">
                                    <strong class="text-success fs-5">Rp{{ number_format($trx->total, 0, ',', '.') }}</strong>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Tidak ada data transaksi untuk periode ini
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($transaksi->count() > 0)
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="5" class="text-end fs-5">TOTAL PENDAPATAN:</th>
                                <th class="text-end text-success">
                                    <strong class="fs-4">Rp{{ number_format($totalPendapatanFiltered, 0, ',', '.') }}</strong>
                                </th>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>

            <!-- Pagination -->
            @if($transaksi->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $transaksi->appends(['bulan' => $filterBulan, 'tahun' => $filterTahun])->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Buku Terlaris -->
    <div class="card shadow-sm">
        <div class="card-header text-white"
             style="background: linear-gradient(90deg, #f093fb, #f5576c);">
            <h5 class="mb-0">
                <i class="bi bi-trophy me-2"></i>Top 10 Buku Terlaris
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="50">Rank</th>
                            <th>Judul Buku</th>
                            <th class="text-center">Jumlah Terjual</th>
                            <th class="text-end">Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bukuTerlarisFiltered as $index => $item)
                            <tr>
                                <td>
                                    @if($index == 0)
                                        <span class="badge bg-warning text-dark fs-6">🥇 #1</span>
                                    @elseif($index == 1)
                                        <span class="badge bg-secondary fs-6">🥈 #2</span>
                                    @elseif($index == 2)
                                        <span class="badge bg-danger fs-6">🥉 #3</span>
                                    @else
                                        <span class="badge bg-light text-dark">#{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $item->buku->judul ?? '-' }}</strong><br>
                                    <small class="text-muted">{{ $item->buku->kode_buku ?? '-' }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success fs-6">{{ $item->jumlah_terjual }} buku</span>
                                </td>
                                <td class="text-end">
                                    <strong class="text-primary">Rp{{ number_format($item->total_pendapatan, 0, ',', '.') }}</strong>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Belum ada data penjualan buku
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* Enhanced Card Styling */
    .card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
    }

    /* Stats Card Animation */
    .card-body {
        position: relative;
        overflow: hidden;
    }

    .card-body::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .card:hover .card-body::before {
        left: 100%;
    }

    /* Table Enhancements */
    .table thead th {
        background-color: #f8f9fa;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #dee2e6;
        white-space: nowrap;
    }

    .table tbody tr {
        transition: all 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.01);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .table tfoot tr {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-top: 3px solid #10b981;
    }

    .table tfoot th {
        padding: 1rem;
        font-weight: 700;
    }

    /* Badge Styling */
    .badge {
        padding: 0.5rem 0.75rem;
        font-weight: 500;
        border-radius: 8px;
    }

    /* Button Enhancements */
    .btn {
        border-radius: 8px;
        padding: 0.5rem 1.25rem;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .btn-secondary {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    }

    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    /* Form Select Styling */
    .form-select {
        border-radius: 8px;
        border: 1px solid #dee2e6;
        padding: 0.6rem 1rem;
        transition: all 0.3s ease;
    }

    .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    /* Card Header Gradient */
    .card-header {
        border-radius: 15px 15px 0 0 !important;
        border: none;
        padding: 1.25rem 1.5rem;
    }

    /* Icon Circle Background */
    .bg-white.bg-opacity-25 {
        backdrop-filter: blur(10px);
    }

    /* Number Animation */
    .display-5, h2 {
        animation: countUp 0.5s ease-out;
    }

    @keyframes countUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive Badge Medals */
    .badge.fs-6 {
        animation: bounceIn 0.5s ease-out;
    }

    @keyframes bounceIn {
        0% {
            transform: scale(0);
        }
        50% {
            transform: scale(1.2);
        }
        100% {
            transform: scale(1);
        }
    }

    /* Empty State Icon */
    .bi-inbox {
        opacity: 0.3;
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
    }

    /* Enhanced Total Payment Column */
    .text-success.fs-5 {
        text-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);
        font-weight: 600;
    }

    /* Footer Total Styling */
    .table tfoot .fs-4 {
        color: #10b981 !important;
        text-shadow: 0 2px 6px rgba(16, 185, 129, 0.3);
        letter-spacing: 0.5px;
    }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
<script>
    // Alert konfirmasi sebelum export dengan Liquid Glass theme
    document.querySelectorAll('a[href*="export"]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const exportUrl = this.href;
            
            Swal.fire({
                title: 'Export Laporan',
                html: `
                    <div class="custom-icon-container">
                        <div class="icon-glass-bg" style="background: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.3);"></div>
                        <div class="icon-particle"></div>
                        <div class="icon-particle"></div>
                        <div class="icon-particle"></div>
                        <div class="icon-particle"></div>
                        <i class="bi bi-file-earmark-excel custom-alert-icon" style="color: #10b981;"></i>
                    </div>
                    <p style="font-size: 1.1rem; margin-top: 1rem; color: #333; font-weight: 500;">
                        Apakah Anda ingin mengexport laporan ke Excel?
                    </p>
                    <p style="color: #888; font-size: 0.95rem; margin-top: 0.5rem;">
                        File akan diunduh dalam format .xlsx
                    </p>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-download btn-icon"></i> Ya, Export Sekarang',
                cancelButtonText: '<i class="bi bi-x-circle btn-icon"></i> Batal',
                reverseButtons: true,
                allowOutsideClick: false,
                allowEscapeKey: true,
                focusCancel: true,
                showClass: {
                    popup: 'swal2-show',
                    backdrop: 'swal2-backdrop-show'
                },
                hideClass: {
                    popup: 'swal2-hide'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Sedang Memproses',
                        html: `
                            <div class="loading-glass-container">
                                <div class="loading-glass-bg"></div>
                                <i class="bi bi-arrow-clockwise loading-spinner"></i>
                            </div>
                            <p style="color: #666; margin-top: 1.5rem; font-size: 1rem;">
                                Mohon tunggu, sedang mengexport data...
                            </p>
                        `,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false,
                        showConfirmButton: false,
                        showClass: {
                            popup: 'swal2-show'
                        }
                    });
                    
                    // Redirect to export
                    setTimeout(() => {
                        window.location.href = exportUrl;
                        
                        // Show success after redirect
                        setTimeout(() => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Laporan berhasil diexport',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }, 500);
                    }, 1000);
                }
            });
        });
    });
</script>
@endpush