@extends('owner.layout.owner_layout')

@section('title', 'Dashboard Owner')

@section('content')
<h1 class="mb-4">Dashboard Owner</h1>
<p class="mb-4">Halo Pak Dzikri 👋</p>

<!-- Statistik -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center text-white" style="background: linear-gradient(45deg,#00c6ff,#0077b6);">
            <div class="card-body">
                <i class="bi bi-book" style="font-size: 2rem;"></i>
                <h6 class="mt-2">Total Buku</h6>
                <h3 class="fw-bold">{{ $totalBuku }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center text-white" style="background: linear-gradient(45deg,#6a11cb,#2575fc);">
            <div class="card-body">
                <i class="bi bi-tags" style="font-size: 2rem;"></i>
                <h6 class="mt-2">Total Kategori</h6>
                <h3 class="fw-bold">{{ $totalKategori }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center text-white" style="background: linear-gradient(45deg,#f7971e,#ffd200);">
            <div class="card-body">
                <i class="bi bi-card-list" style="font-size: 2rem;"></i>
                <h6 class="mt-2">Total Detail Buku</h6>
                <h3 class="fw-bold">{{ $totalDetailBuku }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center text-white" style="background: linear-gradient(45deg,#11998e,#38ef7d);">
            <div class="card-body">
                <i class="bi bi-people" style="font-size: 2rem;"></i>
                <h6 class="mt-2">Total User</h6>
                <h3 class="fw-bold">{{ $totalUser }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Keuangan -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 text-center text-white" style="background: linear-gradient(45deg,#00b09b,#96c93d);">
            <div class="card-body">
                <i class="bi bi-cash-coin" style="font-size: 2rem;"></i>
                <h6 class="mt-2">Total Pendapatan</h6>
                <h3 class="fw-bold">Rp{{ number_format($totalPendapatan,0,',','.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 text-center text-white" style="background: linear-gradient(45deg,#fc5c7d,#6a82fb);">
            <div class="card-body">
                <i class="bi bi-cart-check" style="font-size: 2rem;"></i>
                <h6 class="mt-2">Total Transaksi</h6>
                <h3 class="fw-bold">{{ $totalTransaksi }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 text-center text-white" style="background: linear-gradient(45deg,#f7971e,#ffd200);">
            <div class="card-body">
                <i class="bi bi-bookmark-star" style="font-size: 2rem;"></i>
                <h6 class="mt-2">Total Buku Terjual</h6>
                <h3 class="fw-bold">{{ $totalBukuTerjual }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Buku Terlaris -->
<div class="card shadow mb-4">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">Buku Terlaris</h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Judul Buku</th>
                    <th>Jumlah Terjual</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bukuTerlaris as $buku)
                    <tr>
                        <td>{{ $buku->buku->judul ?? '-' }}</td>
                        <td>{{ $buku->jumlah_terjual }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
