@extends('kasir.layout.kasir_layout')

@section('title', 'Riwayat Transaksi - Kasir NesMedia')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary mb-0">
            <i class="bi bi-clock-history me-2"></i> Riwayat Transaksi
        </h3>
        <a href="{{ route('kasir.transaksi') }}" class="btn btn-sm btn-success">
            <i class="bi bi-plus-circle"></i> Buat Transaksi Baru
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if($transaksi->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-4 text-secondary"></i>
                    <h5 class="mt-3 text-muted">Belum ada transaksi yang tercatat.</h5>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Kode Transaksi</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Total</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaksi as $index => $item)
                                <tr>
                                    <td>{{ $transaksi->firstItem() + $index }}</td>
                                    <td>
                                        <span class="fw-semibold text-dark">{{ $item->kode_transaksi }}</span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d M Y - H:i') }}</td>
                                    <td>Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ route('kasir.transaksi.nota', $item->id) }}" class="btn btn-sm btn-success">Lihat Nota</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $transaksi->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
