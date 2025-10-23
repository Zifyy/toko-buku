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
    .book-card.disabled {
        opacity: 0.6;
        pointer-events: none;
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
    .book-meta.out-of-stock {
        color: #dc3545;
        font-weight: 600;
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
    .price.unavailable {
        color: #6c757d;
    }
    .add-btn {
        width: 100%;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    .add-btn:disabled {
        background-color: #6c757d;
        border-color: #6c757d;
        cursor: not-allowed;
        opacity: 0.65;
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
    .cart-header-actions {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    .select-all-container {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
    }
    .select-all-container input[type="checkbox"] {
        width: 16px;
        height: 16px;
        cursor: pointer;
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
    .cart-item-checkbox {
        display: flex;
        align-items: center;
    }
    .cart-item-checkbox input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }
    .cart-item-title {
        flex: 1;
    }
    .cart-item-controls {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .cart-item-controls button {
        width: 25px;
        height: 25px;
        border: none;
        border-radius: 5px;
        background: #0d6efd;
        color: #fff;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
    .cart-item-controls button:disabled {
        background: #ccc;
        cursor: not-allowed;
    }
    .cart-item-controls .qty-display {
        min-width: 30px;
        text-align: center;
        font-weight: 600;
    }
    .btn-delete-item {
        background: #dc3545;
        width: 25px;
        height: 25px;
        border: none;
        border-radius: 5px;
        color: #fff;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .btn-delete-selected {
        background: #dc3545;
        border: none;
        color: #fff;
        padding: 4px 10px;
        border-radius: 5px;
        font-size: 12px;
        cursor: pointer;
    }
    .btn-delete-selected:disabled {
        background: #ccc;
        cursor: not-allowed;
    }
    .cart-footer {
        padding: 15px;
        border-top: 1px solid #eee;
    }
    .stock-warning {
        color: #dc3545;
        font-size: 11px;
        margin-top: 2px;
    }
    .empty-cart-message {
        text-align: center;
        padding: 40px 20px;
        color: #999;
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
        @php
            $stok = optional($item->detailBuku)->stok ?? 0;
            $harga = optional($item->detailBuku)->harga ?? 0;
            $isAvailable = $stok > 0 && $harga > 0;
        @endphp
        <div class="book-card {{ !$isAvailable ? 'disabled' : '' }}">
            <img src="{{ $item->cover ? asset('cover/'.$item->cover) : asset('images/no-cover.png') }}" alt="{{ $item->judul }}">
            <div class="book-info">
                <h6 class="book-title">{{ $item->judul }}</h6>

                <div class="book-info-content">
                    <p class="book-meta"><strong>Kode:</strong> {{ $item->kode_buku }}</p>
                    <p class="book-meta"><strong>Kategori:</strong> {{ $item->kategori->nama_kategori ?? '-' }}</p>
                    <p class="book-meta"><strong>Penerbit:</strong> {{ $item->penerbit ?? '-' }}</p>
                    <p class="book-meta"><strong>Pengarang:</strong> {{ $item->pengarang }}</p>
                    <p class="book-meta {{ $stok <= 0 ? 'out-of-stock' : '' }}">
                        <strong>Stok:</strong> {{ $stok }}
                        @if($stok <= 0)
                            <span style="color: #dc3545;">(Habis)</span>
                        @endif
                    </p>
                </div>

                <div class="book-bottom">
                    <span class="price {{ !$isAvailable ? 'unavailable' : '' }}">
                        @if($harga > 0)
                            Rp{{ number_format($harga, 0, ',', '.') }}
                        @else
                            <span style="color: #dc3545;">Harga belum tersedia</span>
                        @endif
                    </span>
                    <button
                        class="btn btn-primary add-btn"
                        data-bs-toggle="tooltip" 
                        data-bs-placement="top" 
                        title="{{ !$isAvailable ? 'Stok habis atau harga belum tersedia' : 'Tambah ke Keranjang' }}"
                        onclick="addToCart('{{ $item->id }}', '{{ addslashes($item->judul) }}', {{ $harga }}, {{ $stok }})"
                        {{ !$isAvailable ? 'disabled' : '' }}>
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
        <h6 class="m-0">🛒 Keranjang</h6>
        <div class="cart-header-actions">
            <div class="select-all-container">
                <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll()">
                <label for="selectAllCheckbox" style="margin: 0; cursor: pointer;">Pilih Semua</label>
            </div>
            <button class="btn-delete-selected" id="deleteSelectedBtn" onclick="deleteSelected()" disabled>
                🗑️ Hapus
            </button>
            <button class="btn btn-sm btn-light" onclick="toggleCart()">✕</button>
        </div>
    </div>
    <div class="floating-cart-body" id="cartItems">
        <div class="empty-cart-message">
            <i class="bi bi-cart-x" style="font-size: 48px; color: #ccc;"></i>
            <p style="margin-top: 10px;">Keranjang masih kosong</p>
        </div>
    </div>
    <div class="cart-footer">
        <h6 class="mb-2">Total: <span id="cartTotal">Rp0</span></h6>

        <form id="cartForm" action="{{ route('kasir.transaksi.store') }}" method="POST">
            @csrf
            <input type="hidden" name="cart_data" id="cartDataInput">
            <button type="submit" class="btn btn-success w-100" id="confirmOrderBtn" disabled>Konfirmasi Pesanan</button>
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
    let selectedItems = new Set();

    document.addEventListener('DOMContentLoaded', function () {
        const savedCart = localStorage.getItem('kasir_cart');
        if (savedCart) {
            cart = JSON.parse(savedCart);
            updateCart();
        }

        document.getElementById('cartForm').addEventListener('submit', function(e) {
            if (Object.keys(cart).length === 0) {
                e.preventDefault();
                alert('Keranjang masih kosong!');
                return;
            }

            const cartArr = Object.entries(cart).map(([id, item]) => ({
                id: parseInt(id),
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
        const confirmBtn = document.getElementById('confirmOrderBtn');
        let total = 0;
        let count = 0;

        cartContainer.innerHTML = '';
        
        if (Object.keys(cart).length === 0) {
            cartContainer.innerHTML = `
                <div class="empty-cart-message">
                    <i class="bi bi-cart-x" style="font-size: 48px; color: #ccc;"></i>
                    <p style="margin-top: 10px;">Keranjang masih kosong</p>
                </div>
            `;
            confirmBtn.disabled = true;
        } else {
            for (let id in cart) {
                const item = cart[id];
                const itemTotal = item.price * item.quantity;
                total += itemTotal;
                count += item.quantity;

                const isMaxStock = item.quantity >= item.maxStock;
                const stockWarning = isMaxStock ? `<div class="stock-warning">Stok maksimal: ${item.maxStock}</div>` : '';

                cartContainer.innerHTML += `
                    <div class="cart-item">
                        <div class="cart-item-checkbox">
                            <input type="checkbox" class="item-checkbox" data-id="${id}" onchange="updateSelectedItems()">
                        </div>
                        <div class="cart-item-title">
                            ${item.title}<br>
                            <small>Rp${(item.price).toLocaleString()} × ${item.quantity}</small>
                            ${stockWarning}
                        </div>
                        <div class="cart-item-controls">
                            <button onclick="changeQty('${id}', -1)">−</button>
                            <span class="qty-display">${item.quantity}</span>
                            <button onclick="changeQty('${id}', 1)" ${isMaxStock ? 'disabled' : ''}>+</button>
                            <button class="btn-delete-item" onclick="deleteItem('${id}')" title="Hapus item">
                                <i class="bi bi-trash" style="font-size: 12px;"></i>
                            </button>
                        </div>
                    </div>
                `;
            }
            confirmBtn.disabled = false;
        }

        cartCount = count;
        document.getElementById('cartTotal').innerText = 'Rp' + total.toLocaleString();
        cartCountEl.innerText = cartCount;
        cartCountEl.classList.toggle('show', cartCount > 0);

        document.getElementById('cartDataInput').value = JSON.stringify(cart);
        localStorage.setItem('kasir_cart', JSON.stringify(cart));

        updateSelectAllCheckbox();
        updateDeleteButton();
    }

    function toggleCart() {
        const panel = document.getElementById('floatingCart');
        const bubble = document.getElementById('cartBubble');
        panel.classList.toggle('open');
        bubble.classList.toggle('hidden');
    }

    function addToCart(id, title, price, maxStock) {
        if (maxStock <= 0) {
            alert('Stok buku habis!');
            return;
        }

        if (price <= 0) {
            alert('Harga buku belum tersedia!');
            return;
        }

        if (!cart[id]) {
            cart[id] = { 
                id: id, 
                title: title, 
                price: Number(price) || 0, 
                quantity: 1,
                maxStock: Number(maxStock) || 0
            };
        } else {
            if (cart[id].quantity >= maxStock) {
                alert(`Stok buku hanya tersedia ${maxStock} item!`);
                return;
            }
            cart[id].quantity++;
        }
        updateCart();
        animateBadge();
    }

    function changeQty(id, delta) {
        if (!cart[id]) return;

        const newQty = cart[id].quantity + delta;

        if (newQty > cart[id].maxStock) {
            alert(`Stok maksimal hanya ${cart[id].maxStock} item!`);
            return;
        }

        if (newQty <= 0) {
            if (confirm('Hapus item ini dari keranjang?')) {
                delete cart[id];
                selectedItems.delete(id);
            }
        } else {
            cart[id].quantity = newQty;
        }

        updateCart();
        animateBadge();
    }

    function deleteItem(id) {
        if (confirm('Hapus item ini dari keranjang?')) {
            delete cart[id];
            selectedItems.delete(id);
            updateCart();
        }
    }

    function toggleSelectAll() {
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        const checkboxes = document.querySelectorAll('.item-checkbox');

        if (selectAllCheckbox.checked) {
            checkboxes.forEach(cb => {
                cb.checked = true;
                selectedItems.add(cb.dataset.id);
            });
        } else {
            checkboxes.forEach(cb => {
                cb.checked = false;
            });
            selectedItems.clear();
        }

        updateDeleteButton();
    }

    function updateSelectedItems() {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        selectedItems.clear();

        checkboxes.forEach(cb => {
            if (cb.checked) {
                selectedItems.add(cb.dataset.id);
            }
        });

        updateSelectAllCheckbox();
        updateDeleteButton();
    }

    function updateSelectAllCheckbox() {
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const totalItems = checkboxes.length;
        const checkedItems = Array.from(checkboxes).filter(cb => cb.checked).length;

        if (totalItems === 0) {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        } else if (checkedItems === totalItems) {
            selectAllCheckbox.checked = true;
            selectAllCheckbox.indeterminate = false;
        } else if (checkedItems > 0) {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = true;
        } else {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        }
    }

    function updateDeleteButton() {
        const deleteBtn = document.getElementById('deleteSelectedBtn');
        deleteBtn.disabled = selectedItems.size === 0;
    }

    function deleteSelected() {
        if (selectedItems.size === 0) return;

        if (confirm(`Hapus ${selectedItems.size} item yang dipilih dari keranjang?`)) {
            selectedItems.forEach(id => {
                delete cart[id];
            });
            selectedItems.clear();
            updateCart();
        }
    }

    function animateBadge() {
        const badge = document.getElementById('cartCount');
        badge.classList.remove('bump');
        void badge.offsetWidth;
        badge.classList.add('bump');
    }
</script>
@endsection