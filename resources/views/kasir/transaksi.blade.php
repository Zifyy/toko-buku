@extends('kasir.layout.kasir_layout')

@section('title', 'Checkout Pesanan')

@section('content')
<div class="container py-4">
    <h3 class="mb-4">💳 Checkout Pesanan</h3>
    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(isset($cart) && count($cart))
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th>Judul Buku</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @foreach($cart as $item)
                                @php 
                                    $subtotal = $item['price'] * $item['quantity']; 
                                    $total += $subtotal; 
                                @endphp
                                <tr>
                                    <td>{{ $item['title'] }}</td>
                                    <td class="text-end">Rp{{ number_format($item['price'],0,',','.') }}</td>
                                    <td class="text-center">{{ $item['quantity'] }}</td>
                                    <td class="text-end">Rp{{ number_format($subtotal,0,',','.') }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Total</td>
                                <td class="fw-bold text-end text-success">Rp{{ number_format($total,0,',','.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('kasir.dashboard') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <form id="cartForm" action="{{ route('kasir.transaksi.finalize') }}" method="POST">
                        @csrf
                        <input type="hidden" name="cart_data" id="cartDataInput">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-cash-coin"></i> Proses Pembayaran
                        </button>
                    </form>
                </div>
            @else
                <div class="text-center py-4">
                    <p class="text-muted mb-3">Keranjang masih kosong 🛒</p>
                    <a href="{{ route('kasir.dashboard') }}" class="btn btn-primary">
                        <i class="bi bi-shop"></i> Kembali ke Dashboard
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('cartForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const cart = JSON.parse(localStorage.getItem('kasir_cart') || '{}');
            const cartArr = Object.entries(cart).map(([id, item]) => ({
                id: parseInt(id), // pastikan integer
                title: item.title,
                price: item.price,
                quantity: item.quantity
            }));
            document.getElementById('cartDataInput').value = JSON.stringify(cartArr);
        });
    }

    // Jika di halaman nota, hapus cart
    if (window.location.pathname.includes('/kasir/transaksi/nota')) {
        localStorage.removeItem('kasir_cart');
    }
});
</script>
@endsection
