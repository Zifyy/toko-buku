@extends('kasir.layout.kasir_layout')

@section('title', 'Detail Transaksi - Kasir NesMedia')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary mb-0">
            <i class="bi bi-receipt me-2"></i> Detail Transaksi
        </h3>
        <a href="{{ route('kasir.riwayat') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-circle"></i> Kembali ke Riwayat
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="fw-bold text-dark">Informasi Transaksi</h5>
                    <table class="table table-borderless mt-2">
                        <tr>
                            <th>Kode Transaksi:</th>
                            <td>{{ $transaksi->kode_transaksi }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal:</th>
                            <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d M Y - H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Kasir:</th>
                            <td>{{ $transaksi->user->name ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6 text-md-end">
                    <h5 class="fw-bold text-dark">Total Pembayaran</h5>
                    <h3 class="text-success fw-bold mt-2">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</h3>
                </div>
            </div>

            <hr>

            <h5 class="fw-bold text-dark mb-3">Detail Barang</h5>

            @if ($transaksi->detailTransaksi->isEmpty())
                <div class="alert alert-warning">Belum ada detail barang untuk transaksi ini.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>#</th>
                                <th>Nama Buku</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaksi->detailTransaksi as $index => $detail)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $detail->buku->judul ?? '-' }}</td>
                                    <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                    <td>{{ $detail->jumlah }}</td>
                                    <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="fw-bold table-light">
                                <td colspan="4" class="text-end">Total</td>
                                <td>Rp {{ number_format($transaksi->total, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif

            <div class="mt-4 d-flex justify-content-end gap-2">
                <button class="btn btn-primary" onclick="window.print()">
                    <i class="bi bi-printer"></i> Cetak Nota
                </button>
                <a href="{{ route('kasir.transaksi') }}" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Transaksi Baru
                </a>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .card, .card * {
        visibility: visible;
    }
    .card {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
    }
}
</style>
@endsection
