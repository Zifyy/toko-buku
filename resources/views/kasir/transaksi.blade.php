@extends('kasir.layout.kasir_layout')

@section('title', 'Checkout Pesanan')

@section('content')
<style>
    .payment-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-top: 20px;
    }
    
    .payment-input-group {
        margin-bottom: 15px;
    }
    
    .payment-input-group label {
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
    }
    
    .payment-input-group input {
        font-size: 18px;
        padding: 12px;
        text-align: right;
    }
    
    .quick-amount-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 10px;
    }
    
    .quick-amount-buttons button {
        flex: 1;
        min-width: 80px;
        padding: 8px;
        font-size: 13px;
    }
    
    .change-display {
        background: #fff;
        padding: 15px;
        border-radius: 6px;
        border: 2px solid #198754;
        margin-top: 15px;
    }
    
    .change-display.insufficient {
        border-color: #dc3545;
    }
    
    .change-amount {
        font-size: 24px;
        font-weight: bold;
        color: #198754;
    }
    
    .change-amount.insufficient {
        color: #dc3545;
    }
</style>

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
                                <td class="fw-bold text-end text-success" id="totalAmount">Rp{{ number_format($total,0,',','.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Payment Section -->
                <div class="payment-section">
                    <h5 class="mb-3">💰 Pembayaran</h5>
                    
                    <div class="payment-input-group">
                        <label for="totalBelanja">Total Belanja:</label>
                        <input type="text" class="form-control" id="totalBelanja" value="Rp{{ number_format($total,0,',','.') }}" readonly>
                    </div>

                    <div class="payment-input-group">
                        <label for="jumlahBayar">Jumlah Bayar: <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="jumlahBayar" placeholder="Masukkan jumlah bayar" min="0" step="1000">
                        
                        <div class="quick-amount-buttons">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="setAmount({{ $total }})">Uang Pas</button>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="setAmount(50000)">50k</button>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="setAmount(100000)">100k</button>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="setAmount(200000)">200k</button>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="setAmount(500000)">500k</button>
                        </div>
                    </div>

                    <div class="change-display" id="changeDisplay" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Kembalian:</span>
                            <span class="change-amount" id="changeAmount">Rp0</span>
                        </div>
                    </div>

                    <div class="alert alert-danger mt-3" id="insufficientAlert" style="display: none;">
                        <i class="bi bi-exclamation-triangle"></i> Jumlah bayar kurang dari total belanja!
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('kasir.dashboard') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <form id="cartForm" action="{{ route('kasir.transaksi.finalize') }}" method="POST">
                        @csrf
                        <input type="hidden" name="cart_data" id="cartDataInput">
                        <input type="hidden" name="jumlah_bayar" id="jumlahBayarInput">
                        <input type="hidden" name="kembalian" id="kembalianInput">
                        <button type="submit" class="btn btn-success" id="submitBtn" disabled>
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
const totalBelanja = {{ $total ?? 0 }};

document.addEventListener('DOMContentLoaded', function() {
    const jumlahBayarInput = document.getElementById('jumlahBayar');
    const form = document.getElementById('cartForm');
    
    // Calculate change when user types
    if (jumlahBayarInput) {
        jumlahBayarInput.addEventListener('input', function() {
            calculateChange();
        });
    }

    if (form) {
        form.addEventListener('submit', function(e) {
            const jumlahBayar = parseInt(document.getElementById('jumlahBayar').value) || 0;
            
            if (jumlahBayar < totalBelanja) {
                e.preventDefault();
                alert('Jumlah bayar tidak boleh kurang dari total belanja!');
                return false;
            }

            if (jumlahBayar === 0) {
                e.preventDefault();
                alert('Silakan masukkan jumlah bayar terlebih dahulu!');
                return false;
            }

            const cart = JSON.parse(localStorage.getItem('kasir_cart') || '{}');
            const cartArr = Object.entries(cart).map(([id, item]) => ({
                id: parseInt(id),
                title: item.title,
                price: item.price,
                quantity: item.quantity
            }));
            
            document.getElementById('cartDataInput').value = JSON.stringify(cartArr);
            document.getElementById('jumlahBayarInput').value = jumlahBayar;
            document.getElementById('kembalianInput').value = jumlahBayar - totalBelanja;
        });
    }

    // Clear cart if on nota page
    if (window.location.pathname.includes('/kasir/transaksi/nota')) {
        localStorage.removeItem('kasir_cart');
    }
});

function setAmount(amount) {
    document.getElementById('jumlahBayar').value = amount;
    calculateChange();
}

function calculateChange() {
    const jumlahBayar = parseInt(document.getElementById('jumlahBayar').value) || 0;
    const changeDisplay = document.getElementById('changeDisplay');
    const changeAmount = document.getElementById('changeAmount');
    const insufficientAlert = document.getElementById('insufficientAlert');
    const submitBtn = document.getElementById('submitBtn');
    
    if (jumlahBayar === 0) {
        changeDisplay.style.display = 'none';
        insufficientAlert.style.display = 'none';
        submitBtn.disabled = true;
        return;
    }
    
    const kembalian = jumlahBayar - totalBelanja;
    
    if (kembalian < 0) {
        // Insufficient payment
        changeDisplay.style.display = 'block';
        changeDisplay.classList.add('insufficient');
        changeAmount.classList.add('insufficient');
        changeAmount.textContent = 'Kurang Rp' + Math.abs(kembalian).toLocaleString('id-ID');
        insufficientAlert.style.display = 'block';
        submitBtn.disabled = true;
    } else {
        // Sufficient payment
        changeDisplay.style.display = 'block';
        changeDisplay.classList.remove('insufficient');
        changeAmount.classList.remove('insufficient');
        changeAmount.textContent = 'Rp' + kembalian.toLocaleString('id-ID');
        insufficientAlert.style.display = 'none';
        submitBtn.disabled = false;
    }
}

// Format number input
document.getElementById('jumlahBayar')?.addEventListener('blur', function() {
    if (this.value) {
        this.value = Math.round(parseFloat(this.value));
    }
});
</script>
@endsection