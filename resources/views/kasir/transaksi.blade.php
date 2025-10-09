@extends('kasir.layout.kasir_layout')

@section('title', 'Checkout Pesanan')

@section('content')
<div class="container py-4">
    <h3 class="mb-4">Checkout Pesanan</h3>
    <div class="card shadow-sm">
        <div class="card-body">
            @if($cart && count($cart))
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Judul Buku</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach($cart as $item)
                            @php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; @endphp
                            <tr>
                                <td>{{ $item['title'] }}</td>
                                <td>Rp{{ number_format($item['price'],0,',','.') }}</td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>Rp{{ number_format($subtotal,0,',','.') }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Total</td>
                            <td class="fw-bold">Rp{{ number_format($total,0,',','.') }}</td>
                        </tr>
                    </tbody>
                </table>
                <a href="{{ route('kasir.dashboard') }}" class="btn btn-secondary">Kembali</a>
                <form action="{{ route('kasir.transaksi.finalize') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">Proses Pembayaran</button>
                </form>
            @else
                <p class="text-muted">Keranjang kosong.</p>
                <a href="{{ route('kasir.dashboard') }}" class="btn btn-primary">Kembali ke Dashboard</a>
            @endif
        </div>
    </div>
</div>
@endsection