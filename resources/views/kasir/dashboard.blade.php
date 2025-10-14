@extends('kasir.layout.kasir_layout')

@section('title', 'Dashboard Kasir')

@section('content')
<style>
    /* Grid layout: konsisten */
    .books-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(234px, 1fr));
        gap: 16px;
        align-items: stretch;
    }

    .book-card {
        display: flex;
        flex-direction: column;
        height: 428px;
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .book-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.15);
    }

    .book-card img {
        width: 100%;
        height: 180px;
        object-fit: contain;
        background: #f5f6fa;
        border-bottom: 1px solid #eee;
        padding: 10px 0;
    }

    .book-info {
        display: grid;
        grid-template-rows: auto 1fr auto;
        gap: 8px;
        padding: 12px 14px;
        flex: 1;
    }

    .book-title {
        font-weight: 600;
        font-size: 15px;
        line-height: 1.3;
        color: #222;
        margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 40px;
    }

    .book-info-content {
        overflow: hidden;
        max-height: 96px;
    }
    .book-meta {
        margin: 0;
        color: #666;
        font-size: 13px;
        line-height: 1.4;
    }

    .book-bottom {
        display: grid;
        grid-template-rows: auto auto;
        gap: 8px;
    }
    .price {
        font-weight: 700;
        color: #0d6efd;
        font-size: 14px;
    }
    .add-btn {
        width: 100%;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .cart-bubble {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #0d6efd;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        box-shadow: 0 6px 16px rgba(0,0,0,0.25);
        cursor: pointer;
        z-index: 1100;
        transition: opacity 0.2s ease, transform 0.2s ease;
    }
    .cart-bubble.hidden {
        opacity: 0;
        pointer-events: none;
        transform: scale(0.9);
    }
    .cart-badge {
        position: absolute;
        top: 6px;
        right: 6px;
        background: #dc3545;
        color: #fff;
        border-radius: 999px;
        min-width: 20px;
        height: 20px;
        padding: 0 6px;
        font-size: 12px;
        display: none;
        align-items: center;
        justify-content: center;
    }
    .cart-badge.show { display: flex; }
    .cart-badge.bump { animation: badge-bump 260ms ease, badge-glow 480ms ease; }
    @keyframes badge-bump { 0%{transform:scale(1)} 50%{transform:scale(1.25)} 100%{transform:scale(1)} }
    @keyframes badge-glow { 0%{box-shadow:0 0 0 rgba(220,53,69,0)} 50%{box-shadow:0 0 14px rgba(220,53,69,0.7)} 100%{box-shadow:0 0 0 rgba(220,53,69,0)} }

    .floating-cart {
        position: fixed;
        top: 0;
        right: 0;
        width: 360px;
        height: 100vh;
        background: #fff;
        box-shadow: -2px 0 10px rgba(0,0,0,0.1);
        transform: translateX(100%);
        transition: transform 0.3s ease-in-out;
        z-index: 1200;
        display: flex;
        flex-direction: column;
    }
    .floating-cart.open { transform: translateX(0); }
    .floating-cart-header {
        padding: 15px;
        background: #0d6efd;
        color: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .floating-cart-body {
        flex: 1;
        overflow-y: auto;
        padding: 15px;
        max-height: calc(100vh - 140px);
    }
    .cart-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
        font-size: 13px;
        border-bottom: 1px solid #eee;
        padding-bottom: 8px;
    }
    .cart-item-controls button {
        width: 25px;
        height: 25px;
        border: none;
        border-radius: 5px;
        background: #0d6efd;
        color: #fff;
    }
    .cart-footer {
        padding: 15px;
        border-top: 1px solid #eee;
    }
</style>

<div class="container-fluid py-4">
    <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center mb-4">
        <h3 class="m-0">📚 Daftar Buku</h3>

        <form method="GET" action="{{ route('kasir.dashboard') }}" class="d-flex" style="gap: 10px;">
            <input type="text" name="search" class="form-control" placeholder="Cari buku, kode, pengarang, kategori..." value="{{ request('search') }}">
            <button class="btn btn-primary">Cari</button>
        </form>
    </div>

    <div class="books-grid">
        @foreach($buku as $item)
        <div class="book-card">
            <img src="{{ $item->cover ? asset('cover/'.$item->cover) : asset('images/no-cover.png') }}" alt="{{ $item->judul }}">
            <div class="book-info">
                <h6 class="book-title">{{ $item->judul }}</h6>

                <div class="book-info-content">
                    <p class="book-meta"><strong>Kode:</strong> {{ $item->kode_buku }}</p>
                    <p class="book-meta"><strong>Kategori:</strong> {{ $item->kategori->nama_kategori ?? '-' }}</p>
                    <p class="book-meta"><strong>Penerbit:</strong> {{ $item->penerbit ?? '-' }}</p>
                    <p class="book-meta"><strong>Pengarang:</strong> {{ $item->pengarang }}</p>
                    <p class="book-meta"><strong>Stok:</strong> {{ optional($item->detailBuku)->stok ?? 0 }}</p>
                </div>

                <div class="book-bottom">
                    <span class="price">Rp{{ number_format(optional($item->detailBuku)->harga ?? 0,0,',','.') }}</span>
                    <button
                        class="btn btn-primary add-btn"
                        data-bs-toggle="tooltip" data-bs-placement="top" title="Tambah ke Keranjang"
                        onclick="addToCart('{{ $item->id }}', '{{ addslashes($item->judul) }}', {{ optional($item->detailBuku)->harga ?? 0 }})">
                        <i class="bi bi-cart-plus"></i>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $buku->links() }}
    </div>
</div>

<div id="cartBubble" class="cart-bubble" onclick="toggleCart()" aria-label="Buka keranjang">
    <i class="bi bi-cart-fill"></i>
    <span id="cartCount" class="cart-badge">0</span>
</div>

<div id="floatingCart" class="floating-cart" aria-live="polite">
    <div class="floating-cart-header">
        <h6 class="m-0">🛍️ Keranjang</h6>
        <button class="btn btn-sm btn-light" onclick="toggleCart()">✕</button>
    </div>
    <div class="floating-cart-body" id="cartItems"></div>
    <div class="cart-footer">
        <h6 class="mb-2">Total: <span id="cartTotal">Rp0</span></h6>

        <!-- ✅ FIXED: arahkan ke route yang benar -->
        <form id="cartForm" action="{{ route('kasir.transaksi.store') }}" method="POST">
            @csrf
            <input type="hidden" name="cart_data" id="cartDataInput">
            <button type="submit" class="btn btn-success w-100">Konfirmasi Pesanan</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));
    });

    let cart = {};
    let cartCount = 0;

    document.addEventListener('DOMContentLoaded', function () {
        const savedCart = localStorage.getItem('kasir_cart');
        if (savedCart) {
            cart = JSON.parse(savedCart);
            updateCart();
        }

        document.getElementById('cartForm').addEventListener('submit', function(e) {
            // Ubah object cart menjadi array of object dengan id bertipe integer
            const cartArr = Object.entries(cart).map(([id, item]) => ({
                id: parseInt(id), // pastikan integer
                title: item.title,
                price: item.price,
                quantity: item.quantity
            }));
            document.getElementById('cartDataInput').value = JSON.stringify(cartArr);
        });
    });

    function updateCart() {
        const cartContainer = document.getElementById('cartItems');
        const cartCountEl = document.getElementById('cartCount');
        let total = 0;
        let count = 0;

        cartContainer.innerHTML = '';
        for (let id in cart) {
            const item = cart[id];
            const itemTotal = item.price * item.quantity;
            total += itemTotal;
            count += item.quantity;

            cartContainer.innerHTML += `
                <div class="cart-item">
                    <div class="cart-item-title">
                        ${item.title}<br>
                        <small>Rp${(item.price).toLocaleString()}</small>
                    </div>
                    <div class="cart-item-controls">
                        <button onclick="changeQty('${id}', -1)">-</button>
                        <span>${item.quantity}</span>
                        <button onclick="changeQty('${id}', 1)">+</button>
                    </div>
                </div>
            `;
        }

        cartCount = count;
        document.getElementById('cartTotal').innerText = 'Rp' + total.toLocaleString();
        cartCountEl.innerText = cartCount;
        cartCountEl.classList.toggle('show', cartCount > 0);

        document.getElementById('cartDataInput').value = JSON.stringify(cart);
        localStorage.setItem('kasir_cart', JSON.stringify(cart));
    }

    function toggleCart() {
        const panel = document.getElementById('floatingCart');
        const bubble = document.getElementById('cartBubble');
        panel.classList.toggle('open');
        bubble.classList.toggle('hidden');
    }

    function addToCart(id, title, price) {
        if (!cart[id]) {
        cart[id] = { id: id, title: title, price: Number(price) || 0, quantity: 1 };
    } else {
        cart[id].quantity++;
    }
    updateCart();
    animateBadge();
    }

    function changeQty(id, delta) {
        if (!cart[id]) return;
        cart[id].quantity += delta;
        if (cart[id].quantity <= 0) delete cart[id];
        updateCart();
        animateBadge();
    }

    function animateBadge() {
        const badge = document.getElementById('cartCount');
        badge.classList.remove('bump');
        void badge.offsetWidth;
        badge.classList.add('bump');
    }
</script>
@endsection
