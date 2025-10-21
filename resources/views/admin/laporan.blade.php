@extends('admin.layout.admin_layout')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="container py-4">
    <h3 class="mb-4">Laporan Keuangan Toko Buku</h3>

    <!-- Ringkasan -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-bg-primary mb-3">
                <div class="card-body">
                    <h6 class="card-title mb-1">Total Transaksi</h6>
                    <h4 class="card-text">{{ $totalTransaksi }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-success mb-3">
                <div class="card-body">
                    <h6 class="card-title mb-1">Total Buku Terjual</h6>
                    <h4 class="card-text">{{ $totalBukuTerjual }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-warning mb-3">
                <div class="card-body">
                    <h6 class="card-title mb-1">Total Pendapatan</h6>
                    <h4 class="card-text">Rp{{ number_format($totalPendapatan,0,',','.') }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-info mb-3">
                <div class="card-body">
                    <h6 class="card-title mb-1">Total Diskon</h6>
                    <h4 class="card-text">Rp{{ number_format($totalDiskon,0,',','.') }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Detail Transaksi -->
    <div class="card shadow mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Detail Transaksi</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kode Transaksi</th>
                        <th>Kasir</th>
                        <th>Jumlah Item</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaksi as $trx)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d M Y H:i') }}</td>
                            <td>{{ $trx->kode_transaksi }}</td>
                            <td>{{ $trx->kasir->name ?? '-' }}</td>
                            <td>{{ $trx->detailTransaksi->sum('jumlah') }}</td>
                            <td>Rp{{ number_format($trx->total,0,',','.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Buku Terlaris -->
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Buku Terlaris</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Judul Buku</th>
                        <th>Jumlah Terjual</th>
                        <th>Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bukuTerlaris as $buku)
                        <tr>
                            <td>{{ $buku->judul }}</td>
                            <td>{{ $buku->jumlah_terjual }}</td>
                            <td>Rp{{ number_format($buku->total_pendapatan,0,',','.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
