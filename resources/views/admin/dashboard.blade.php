@extends('admin.layout.admin_layout')

@section('title', 'Dashboard Admin')

@section('content')
<style>
    /* === Animated Search Bar (Uiverse.io by krlozCJ - themed) === */
    .search-container {
        position: relative;
        --size-button: 42px;
        color: #333;
        display: inline-block;
    }

    .search-input {
        padding-left: var(--size-button);
        height: var(--size-button);
        font-size: 15px;
        border: none;
        color: #333;
        outline: none;
        width: var(--size-button);
        transition: all ease 0.3s;
        background-color: #ffffff;
        box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.15),
                    -2px -2px 4px rgba(255, 255, 255, 0.6);
        border-radius: 50px;
        cursor: pointer;
    }

    .search-input:focus,
    .search-input:not(:invalid) {
        width: 240px;
        cursor: text;
        box-shadow: inset 2px 2px 4px rgba(0, 0, 0, 0.15),
                    inset -2px -2px 4px rgba(255, 255, 255, 0.8);
    }

    .search-input:focus + .search-icon,
    .search-input:not(:invalid) + .search-icon {
        pointer-events: all;
        cursor: pointer;
    }

    .search-icon {
        position: absolute;
        width: var(--size-button);
        height: var(--size-button);
        top: 0;
        left: 0;
        padding: 8px;
        pointer-events: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .search-icon svg {
        width: 100%;
        height: 100%;
        fill: #0077b6;
    }

    /* Modal Enhancements */
    .modal-content {
        border: none;
        border-radius: 15px;
        overflow: hidden;
    }

    .modal-header {
        border-bottom: none;
        padding: 1.5rem;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .detail-label {
        color: #666;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .detail-value {
        color: #333;
        font-size: 1rem;
        margin-bottom: 1rem;
    }

    .detail-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 1rem;
    }

    .badge-jenis {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        font-weight: 500;
        border-radius: 8px;
        display: inline-block;
    }

    .cover-container {
        position: relative;
        overflow: hidden;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .cover-container img {
        transition: transform 0.3s ease;
    }

    .cover-container:hover img {
        transform: scale(1.05);
    }

    /* Card Hover Effects */
    .card {
        transition: all 0.3s ease;
        border-radius: 12px;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
    }

    /* Card Image Top Enhancement */
    .card-img-top {
        border-radius: 12px 12px 0 0;
    }

    /* Badge Styling on Card */
    .badge-kategori {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 0.4rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .badge-jenis-card {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        padding: 0.4rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    /* Info Row on Card */
    .info-row {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        align-items: center;
    }

    .card-body p {
        line-height: 1.6;
    }
</style>

<h1 class="mb-4">Dashboard Admin</h1>
<p class="mb-4">Selamat datang kembali, Admin 👋</p>

<!-- Statistik -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center text-white"
             style="background: linear-gradient(45deg,#00c6ff,#0077b6);">
            <div class="card-body">
                <i class="bi bi-book" style="font-size: 2rem;"></i>
                <h6 class="mt-2">Total Buku</h6>
                <h3 class="fw-bold">{{ $totalBuku }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center text-white"
             style="background: linear-gradient(45deg,#6a11cb,#2575fc);">
            <div class="card-body">
                <i class="bi bi-tags" style="font-size: 2rem;"></i>
                <h6 class="mt-2">Total Kategori</h6>
                <h3 class="fw-bold">{{ $totalKategori }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center text-white"
             style="background: linear-gradient(45deg,#f7971e,#ffd200);">
            <div class="card-body">
                <i class="bi bi-card-list" style="font-size: 2rem;"></i>
                <h6 class="mt-2">Total Detail Buku</h6>
                <h3 class="fw-bold">{{ $totalDetailBuku }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center text-white"
             style="background: linear-gradient(45deg,#11998e,#38ef7d);">
            <div class="card-body">
                <i class="bi bi-people" style="font-size: 2rem;"></i>
                <h6 class="mt-2">Total User</h6>
                <h3 class="fw-bold">{{ $totalUser }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Search Bar -->
<div class="row mb-4">
    <div class="col-md-6">
        <form action="{{ route('admin.dashboard') }}" method="GET" class="d-flex align-items-center">
            <div class="search-container">
                <input class="search-input" type="text" name="search" placeholder="Cari buku..."
                       value="{{ request('search') }}" required>
                <div class="search-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path
                            d="M10 2a8 8 0 105.29 14.29l4.49 4.49a1 1 0 001.42-1.42l-4.49-4.49A8 8 0 0010 2zm0 2a6 6 0 110 12A6 6 0 0110 4z" />
                    </svg>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Daftar Buku (Card) -->
<div class="row">
    @foreach ($buku as $item)
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100 d-flex flex-column">
                <div class="card-img-top d-flex align-items-center justify-content-center bg-light"
                     style="height: 180px; overflow: hidden;">
                    @if ($item->cover)
                        <img src="{{ asset('cover/' . $item->cover) }}" alt="Cover Buku"
                             class="img-fluid h-100" style="object-fit: cover;">
                    @else
                        <span class="text-muted">Tidak ada cover</span>
                    @endif
                </div>
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title mb-3">{{ $item->judul }}</h6>
                    
                    <p class="mb-2"><strong>Kode:</strong> {{ $item->kode_buku }}</p>
                    <p class="mb-2"><strong>Pengarang:</strong> {{ $item->pengarang }}</p>
                    
                    <!-- Kategori & Jenis -->
                    <div class="mb-2">
                        <strong class="d-block mb-2">Kategori & Jenis:</strong>
                        <div class="info-row">
                            <span class="badge badge-kategori">
                                <i class="bi bi-tag-fill me-1"></i>{{ $item->kategori->nama_kategori ?? '-' }}
                            </span>
                            @if($item->kategori && $item->kategori->jenis)
                                <span class="badge badge-jenis-card">
                                    <i class="bi bi-bookmark-fill me-1"></i>{{ $item->kategori->jenis }}
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="bi bi-question-circle me-1"></i>Tidak ada jenis
                                </span>
                            @endif
                        </div>
                    </div>

                    <p class="mb-2">
                        <strong>Harga:</strong>
                        Rp{{ number_format(optional($item->detailBuku)->harga ?? 0, 0, ',', '.') }}
                    </p>
                    <p class="mb-3">
                        <strong>Stok:</strong>
                        @php $stok = optional($item->detailBuku)->stok ?? 0; @endphp
                        @if ($stok > 0)
                            <span class="badge bg-success">{{ $stok }} Unit</span>
                        @else
                            <span class="badge bg-danger">Habis</span>
                        @endif
                    </p>

                    <div class="mt-auto">
                        <button class="btn btn-outline-primary btn-sm w-100"
                                data-bs-toggle="modal"
                                data-bs-target="#modalBuku{{ $item->id }}"
                                title="Lihat Rincian">
                            <i class="bi bi-eye"></i> Lihat Rincian
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Detail Buku -->
        <div class="modal fade" id="modalBuku{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header"
                         style="background: linear-gradient(90deg,#00c6ff,#0077b6); color: #fff;">
                        <h5 class="modal-title">
                            <i class="bi bi-book-half me-2"></i>Detail Buku
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Cover Section -->
                            <div class="col-md-4 text-center mb-3 mb-md-0">
                                <div class="cover-container">
                                    @if ($item->cover)
                                        <img src="{{ asset('cover/' . $item->cover) }}" alt="Cover Buku"
                                             class="img-fluid rounded shadow" style="max-height: 350px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                             style="height: 300px;">
                                            <span class="text-muted">
                                                <i class="bi bi-image fs-1 d-block mb-2"></i>
                                                Tidak ada cover
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Info Section -->
                            <div class="col-md-8">
                                <!-- Informasi Dasar Buku -->
                                <div class="detail-section">
                                    <h6 class="fw-bold mb-3 text-primary">
                                        <i class="bi bi-info-circle me-2"></i>Informasi Buku
                                    </h6>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="detail-label">Kode Buku</p>
                                            <p class="detail-value">
                                                <span class="badge bg-primary">{{ $item->kode_buku }}</span>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="detail-label">Tahun Terbit</p>
                                            <p class="detail-value">{{ $item->tahun_terbit }}</p>
                                        </div>
                                    </div>

                                    <p class="detail-label">Judul Buku</p>
                                    <p class="detail-value fw-bold fs-5">{{ $item->judul }}</p>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="detail-label">Pengarang</p>
                                            <p class="detail-value">
                                                <i class="bi bi-pen me-1"></i>{{ $item->pengarang }}
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="detail-label">Penerbit</p>
                                            <p class="detail-value">
                                                <i class="bi bi-building me-1"></i>{{ $item->penerbit }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Kategori & Jenis -->
                                <div class="detail-section">
                                    <h6 class="fw-bold mb-3 text-success">
                                        <i class="bi bi-tag me-2"></i>Kategori & Jenis
                                    </h6>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="detail-label">Kategori</p>
                                            <p class="detail-value">
                                                <span class="badge bg-info badge-jenis">
                                                    <i class="bi bi-tag-fill me-1"></i>
                                                    {{ $item->kategori->nama_kategori ?? '-' }}
                                                </span>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="detail-label">Jenis</p>
                                            <p class="detail-value">
                                                @if($item->kategori && $item->kategori->jenis)
                                                    <span class="badge bg-success badge-jenis">
                                                        <i class="bi bi-bookmark-fill me-1"></i>
                                                        {{ $item->kategori->jenis }}
                                                    </span>
                                                @else
                                                    <span class="text-muted fst-italic">Tidak ada jenis</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Detail Stok & Harga -->
                                <div class="detail-section">
                                    <h6 class="fw-bold mb-3 text-warning">
                                        <i class="bi bi-currency-dollar me-2"></i>Harga & Stok
                                    </h6>

                                    @if ($item->detailBuku)
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="detail-label">Harga Jual</p>
                                                <p class="detail-value">
                                                    <span class="fs-5 fw-bold text-success">
                                                        Rp{{ number_format($item->detailBuku->harga, 0, ',', '.') }}
                                                    </span>
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="detail-label">Stok Tersedia</p>
                                                <p class="detail-value">
                                                    @if ($item->detailBuku->stok > 0)
                                                        <span class="badge bg-success fs-6">
                                                            <i class="bi bi-check-circle me-1"></i>
                                                            {{ $item->detailBuku->stok }} Unit
                                                        </span>
                                                    @else
                                                        <span class="badge bg-danger fs-6">
                                                            <i class="bi bi-x-circle me-1"></i>
                                                            Stok Habis
                                                        </span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-warning mb-0" role="alert">
                                            <i class="bi bi-exclamation-triangle me-2"></i>
                                            Belum ada detail harga dan stok untuk buku ini
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="background-color: #f8f9fa;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Pagination -->
@if(method_exists($buku, 'links'))
    <div class="d-flex justify-content-center mt-3">
        {{ $buku->links() }}
    </div>
@endif
@endsection