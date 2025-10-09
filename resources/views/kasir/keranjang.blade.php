@extends('kasir.layout.kasir_layout')

@section('title', 'Keranjang Transaksi')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold text-primary mb-4">🛒 Keranjang Transaksi</h3>

    @if(empty($keranjang))
        <div class="alert alert-info">Keranjang masih kosong.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Judul Buku</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($keranjang as $id => $item)
                        @php $subtotal = $item['harga'] * $item['jumlah']; $total += $subtotal; @endphp
                        <tr>
                            <td>{{ $item['judul'] }}</td>
                            <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                            <td>{{ $item['jumlah'] }}</td>
                            <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                            <td>
                                <form action="{{ route('kasir.keranjang.hapus', $id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="text-end mt-3">
            <h5 class="fw-bold">Total: Rp {{ number_format($total, 0, ',', '.') }}</h5>
            <button class="btn btn-success mt-2">
                <i class="bi bi-check-circle"></i> Proses Pembayaran
            </button>
        </div>
    @endif
</div>
@endsection
