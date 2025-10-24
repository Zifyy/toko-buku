@extends('admin.layout.admin_layout')

@section('title', 'Dashboard Admin')

@section('content')
<style>
    /* Header Dashboard */
    .dashboard-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 25px 30px;
        color: white;
        margin-bottom: 25px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }
    
    /* Stat Cards */
    .stat-card {
        border-radius: 16px;
        border: none;
        transition: all 0.3s ease;
        height: 100%;
        overflow: hidden;
        position: relative;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        margin-bottom: 12px;
    }
    
    .stat-value {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 5px;
    }
    
    .stat-label {
        font-size: 13px;
        opacity: 0.9;
        font-weight: 500;
    }
    
    /* Chart Cards */
    .chart-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .chart-card:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }
    
    .chart-card .card-header {
        background: white;
        border-bottom: 2px solid #f0f0f0;
        padding: 18px 20px;
        font-weight: 600;
        font-size: 16px;
        color: #2d3748;
    }
    
    .chart-container {
        position: relative;
        height: 280px;
        padding: 20px;
    }
    
    /* Quick Actions */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 12px;
        margin-bottom: 25px;
    }
    
    .quick-action-btn {
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 18px;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        color: #2d3748;
    }
    
    .quick-action-btn:hover {
        border-color: #667eea;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3);
    }
    
    .quick-action-btn i {
        font-size: 24px;
        display: block;
        margin-bottom: 8px;
    }
    
    .quick-action-btn span {
        font-size: 13px;
        font-weight: 600;
    }
    
    /* Filter Bar */
    .filter-bar {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }
    
    .filter-group {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .filter-select {
        min-width: 180px;
        border-radius: 8px;
        border: 2px solid #e2e8f0;
        padding: 8px 12px;
        font-size: 14px;
    }
    
    .view-toggle {
        display: flex;
        gap: 8px;
        margin-left: auto;
    }
    
    .view-btn {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        border: 2px solid #e2e8f0;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .view-btn.active {
        background: #667eea;
        border-color: #667eea;
        color: white;
    }
    
    /* Search Bar */
    .search-container {
        position: relative;
        flex: 1;
        max-width: 400px;
    }

    .search-input {
        width: 100%;
        padding: 10px 40px 10px 15px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    .search-icon {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #667eea;
        pointer-events: none;
    }
    
    /* Book Cards */
    .book-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .book-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }
    
    .book-cover {
        height: 200px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px 12px 0 0;
        overflow: hidden;
        padding: 10px;
    }
    
    .book-cover img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: transform 0.3s ease;
    }
    
    .book-card:hover .book-cover img {
        transform: scale(1.05);
    }
    
    .badge-custom {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .stock-available { background: #d1fae5; color: #065f46; }
    .stock-low { background: #fed7aa; color: #92400e; }
    .stock-out { background: #fee2e2; color: #991b1b; }
    
    /* Alert Cards */
    .alert-card {
        border-radius: 12px;
        border-left: 4px solid;
        padding: 15px;
        margin-bottom: 12px;
        background: white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }
    
    .alert-warning { border-color: #f59e0b; }
    .alert-danger { border-color: #ef4444; }
    .alert-info { border-color: #3b82f6; }
    
    /* Modal Enhancements */
    .modal-content {
        border: none;
        border-radius: 16px;
    }
    
    .modal-header {
        border-bottom: 2px solid #f0f0f0;
        padding: 20px;
    }
    
    .detail-section {
        background: #f8fafc;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 15px;
    }
    
    .detail-label {
        color: #64748b;
        font-weight: 600;
        font-size: 13px;
        margin-bottom: 5px;
    }
    
    .detail-value {
        color: #1e293b;
        font-size: 15px;
        margin-bottom: 12px;
    }
</style>

<!-- Header Welcome -->
<div class="dashboard-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-2 fw-bold">Selamat Datang, Admin!👋</h2>
            <p class="mb-0 opacity-90">Kelola dan pantau inventori buku toko Anda</p>
        </div>
        <div class="text-end">
            <div style="font-size: 13px; opacity: 0.9;">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}</div>
            <div style="font-size: 22px; font-weight: 700;">{{ \Carbon\Carbon::now()->format('H:i') }}</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions">
    <a href="{{ route('admin.buku.create') }}" class="quick-action-btn">
        <i class="bi bi-plus-circle-fill"></i>
        <span>Tambah Buku</span>
    </a>
    <a href="{{ route('admin.kategori.index') }}" class="quick-action-btn">
        <i class="bi bi-tags-fill"></i>
        <span>Kelola Kategori</span>
    </a>
    <a href="{{ route('admin.buku.index') }}" class="quick-action-btn">
        <i class="bi bi-list-ul"></i>
        <span>Semua Buku</span>
    </a>
    <a href="{{ route('admin.laporan') }}" class="quick-action-btn">
        <i class="bi bi-graph-up-arrow"></i>
        <span>Laporan</span>
    </a>
</div>

<!-- Statistik Utama -->
<div class="row mb-4 g-3">
    <div class="col-lg-3 col-md-6">
        <div class="card stat-card text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body">
                <div class="stat-icon">
                    <i class="bi bi-book-fill"></i>
                </div>
                <div class="stat-value">{{ number_format($totalBuku) }}</div>
                <div class="stat-label">Total Buku</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card stat-card text-white" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="card-body">
                <div class="stat-icon">
                    <i class="bi bi-tags-fill"></i>
                </div>
                <div class="stat-value">{{ number_format($totalKategori) }}</div>
                <div class="stat-label">Kategori Buku</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card stat-card text-white" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <div class="card-body">
                <div class="stat-icon">
                    <i class="bi bi-cash-coin"></i>
                </div>
                @php
                    $totalPendapatan = \App\Models\Transaksi::sum('total') ?? 0;
                @endphp
                <div class="stat-value" style="font-size: 20px;">Rp{{ number_format($totalPendapatan / 1000000, 1) }}Jt</div>
                <div class="stat-label">Total Pendapatan</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card stat-card text-white" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <div class="card-body">
                <div class="stat-icon">
                    <i class="bi bi-box-seam-fill"></i>
                </div>
                <div class="stat-value">{{ number_format($totalStok) }}</div>
                <div class="stat-label">Total Stok</div>
            </div>
        </div>
    </div>
</div>

<!-- Row: Charts & Alerts -->
<div class="row mb-4 g-3">
    <!-- Chart: Distribusi Kategori -->
    <div class="col-lg-4">
        <div class="card chart-card">
            <div class="card-header">
                <i class="bi bi-pie-chart-fill text-primary"></i> Distribusi Kategori
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Chart: Status Stok -->
    <div class="col-lg-4">
        <div class="card chart-card">
            <div class="card-header">
                <i class="bi bi-bar-chart-fill text-success"></i> Status Stok Buku
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="stockChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Alerts & Notifications -->
    <div class="col-lg-4">
        <div class="card chart-card">
            <div class="card-header">
                <i class="bi bi-exclamation-triangle-fill text-warning"></i> Peringatan
            </div>
            <div class="card-body" style="max-height: 280px; overflow-y: auto; padding: 15px;">
                @if($bukuStokMenipis->count() > 0)
                    <div class="alert-card alert-warning">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-circle fs-4 me-3"></i>
                            <div>
                                <strong>{{ $bukuStokMenipis->count() }} Buku Stok Menipis</strong>
                                <div style="font-size: 12px; color: #92400e;">Stok ≤ 5 unit</div>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if($bukuStokHabis->count() > 0)
                    <div class="alert-card alert-danger">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-x-circle fs-4 me-3"></i>
                            <div>
                                <strong>{{ $bukuStokHabis->count() }} Buku Stok Habis</strong>
                                <div style="font-size: 12px; color: #991b1b;">Perlu restok segera</div>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if($bukuTanpaDetail->count() > 0)
                    <div class="alert-card alert-info">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle fs-4 me-3"></i>
                            <div>
                                <strong>{{ $bukuTanpaDetail->count() }} Buku Tanpa Detail</strong>
                                <div style="font-size: 12px; color: #1e40af;">Belum ada harga/stok</div>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if($bukuStokMenipis->count() == 0 && $bukuStokHabis->count() == 0 && $bukuTanpaDetail->count() == 0)
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-check-circle fs-1"></i>
                        <p class="mt-2 mb-0">Tidak ada peringatan</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Row: Genre & Jenis Charts -->
<div class="row mb-4 g-3">
    <!-- Chart: Distribusi Genre -->
    <div class="col-lg-6">
        <div class="card chart-card">
            <div class="card-header">
                <i class="bi bi-bookmark-fill text-info"></i> Distribusi Genre Buku
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="genreChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Chart: Distribusi Jenis -->
    <div class="col-lg-6">
        <div class="card chart-card">
            <div class="card-header">
                <i class="bi bi-collection-fill text-danger"></i> Distribusi Jenis Buku
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="jenisChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Row: Top Categories -->
<div class="row mb-4 g-3">
    <div class="col-12">
        <div class="card chart-card">
            <div class="card-header">
                <i class="bi bi-trophy-fill text-warning"></i> Top 5 Kategori dengan Stok Terbanyak
            </div>
            <div class="card-body">
                <div class="chart-container" style="height: 250px;">
                    <canvas id="topCategoriesChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter & Search Bar -->
<div class="filter-bar">
    <form action="{{ route('admin.dashboard') }}" method="GET" class="filter-group">
        <div class="search-container">
            <input class="search-input" type="text" name="search" placeholder="Cari buku, kode, pengarang..." value="{{ request('search') }}">
            <i class="bi bi-search search-icon"></i>
        </div>
        
        <select name="kategori" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            @foreach($allKategori as $kat)
                <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                    {{ $kat->nama_kategori }} 
                    @if($kat->genre) - {{ $kat->genre }} @endif
                    @if($kat->jenis) - {{ $kat->jenis }} @endif
                </option>
            @endforeach
        </select>
        
        <select name="stok_status" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="tersedia" {{ request('stok_status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
            <option value="menipis" {{ request('stok_status') == 'menipis' ? 'selected' : '' }}>Stok Menipis</option>
            <option value="habis" {{ request('stok_status') == 'habis' ? 'selected' : '' }}>Habis</option>
        </select>
        
        <button type="submit" class="btn btn-primary" style="border-radius: 8px;">
            <i class="bi bi-funnel-fill"></i> Filter
        </button>
        
        @if(request('search') || request('kategori') || request('stok_status'))
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary" style="border-radius: 8px;">
                <i class="bi bi-x-circle"></i> Reset
            </a>
        @endif
        
        <div class="view-toggle">
            <button type="button" class="view-btn active" onclick="switchView('grid')" id="gridViewBtn">
                <i class="bi bi-grid-3x3-gap-fill"></i>
            </button>
            <button type="button" class="view-btn" onclick="switchView('list')" id="listViewBtn">
                <i class="bi bi-list"></i>
            </button>
        </div>
    </form>
</div>

<!-- Daftar Buku (Card View) -->
<div id="cardView" class="row g-3">
    @forelse ($buku as $item)
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card book-card">
                <div class="book-cover">
                    @if ($item->cover)
                        <img src="{{ asset('cover/' . $item->cover) }}" alt="{{ $item->judul }}">
                    @else
                        <div class="text-muted">
                            <i class="bi bi-image" style="font-size: 48px;"></i>
                        </div>
                    @endif
                </div>
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title mb-2" style="min-height: 40px; font-size: 14px; font-weight: 600;">
                        {{ Str::limit($item->judul, 50) }}
                    </h6>
                    
                    <p class="mb-1" style="font-size: 12px; color: #64748b;">
                        <strong>Kode:</strong> {{ $item->kode_buku }}
                    </p>
                    
                    <p class="mb-1" style="font-size: 12px; color: #64748b;">
                        <strong>Pengarang:</strong> {{ Str::limit($item->pengarang, 30) }}
                    </p>
                    
                    <p class="mb-2" style="font-size: 12px; color: #64748b;">
                        <strong>Kategori:</strong> {{ $item->kategori->nama_kategori ?? '-' }}
                        @if($item->kategori && $item->kategori->jenis)
                            <span class="badge bg-info" style="font-size: 10px;">{{ $item->kategori->jenis }}</span>
                        @endif
                    </p>
                    
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span style="font-size: 14px; font-weight: 700; color: #0d6efd;">
                            Rp{{ number_format(optional($item->detailBuku)->harga ?? 0, 0, ',', '.') }}
                        </span>
                        @php 
                            $stok = optional($item->detailBuku)->stok ?? 0;
                            $statusClass = $stok > 5 ? 'stock-available' : ($stok > 0 ? 'stock-low' : 'stock-out');
                        @endphp
                        <span class="badge-custom {{ $statusClass }}">
                            @if($stok > 5)
                                <i class="bi bi-check-circle"></i> {{ $stok }}
                            @elseif($stok > 0)
                                <i class="bi bi-exclamation-circle"></i> {{ $stok }}
                            @else
                                <i class="bi bi-x-circle"></i> Habis
                            @endif
                        </span>
                    </div>
                    
                    <button class="btn btn-outline-primary btn-sm w-100 mt-auto" 
                            data-bs-toggle="modal" 
                            data-bs-target="#modalBuku{{ $item->id }}"
                            style="border-radius: 8px; font-size: 13px;">
                        <i class="bi bi-eye"></i> Detail
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Detail -->
        <div class="modal fade" id="modalBuku{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header" style="background: linear-gradient(90deg, #667eea, #764ba2); color: white;">
                        <h5 class="modal-title">
                            <i class="bi bi-book-half me-2"></i>Detail Buku
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Cover -->
                            <div class="col-md-4 text-center mb-3">
                                @if ($item->cover)
                                    <img src="{{ asset('cover/' . $item->cover) }}" alt="Cover" 
                                         class="img-fluid rounded shadow" style="max-height: 350px;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 300px;">
                                        <span class="text-muted">
                                            <i class="bi bi-image fs-1"></i><br>Tidak ada cover
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Info -->
                            <div class="col-md-8">
                                <div class="detail-section">
                                    <h6 class="fw-bold mb-3"><i class="bi bi-info-circle text-primary"></i> Informasi Buku</h6>
                                    <p class="detail-label">Judul</p>
                                    <p class="detail-value fw-bold">{{ $item->judul }}</p>
                                    
                                    <div class="row">
                                        <div class="col-6">
                                            <p class="detail-label">Kode</p>
                                            <p class="detail-value">{{ $item->kode_buku }}</p>
                                        </div>
                                        <div class="col-6">
                                            <p class="detail-label">Tahun</p>
                                            <p class="detail-value">{{ $item->tahun_terbit }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-6">
                                            <p class="detail-label">Pengarang</p>
                                            <p class="detail-value">{{ $item->pengarang }}</p>
                                        </div>
                                        <div class="col-6">
                                            <p class="detail-label">Penerbit</p>
                                            <p class="detail-value">{{ $item->penerbit }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="detail-section">
                                    <h6 class="fw-bold mb-3"><i class="bi bi-tag text-success"></i> Kategori</h6>
                                    <div class="row">
                                        <div class="col-6">
                                            <p class="detail-label">Kategori</p>
                                            <p class="detail-value">
                                                <span class="badge bg-info">{{ $item->kategori->nama_kategori ?? '-' }}</span>
                                            </p>
                                        </div>
                                        <div class="col-6">
                                            <p class="detail-label">Jenis</p>
                                            <p class="detail-value">
                                                @if($item->kategori && $item->kategori->jenis)
                                                    <span class="badge bg-success">{{ $item->kategori->jenis }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                @if($item->detailBuku)
                                <div class="detail-section">
                                    <h6 class="fw-bold mb-3"><i class="bi bi-cash-coin text-warning"></i> Harga & Stok</h6>
                                    <div class="row">
                                        <div class="col-6">
                                            <p class="detail-label">Harga</p>
                                            <p class="detail-value">
                                                <span class="fs-5 fw-bold text-success">
                                                    Rp{{ number_format($item->detailBuku->harga, 0, ',', '.') }}
                                                </span>
                                            </p>
                                        </div>
                                        <div class="col-6">
                                            <p class="detail-label">Stok</p>
                                            <p class="detail-value">
                                                @php $stok = $item->detailBuku->stok; @endphp
                                                @if($stok > 5)
                                                    <span class="badge bg-success fs-6">{{ $stok }} Unit</span>
                                                @elseif($stok > 0)
                                                    <span class="badge bg-warning fs-6">{{ $stok }} Unit</span>
                                                @else
                                                    <span class="badge bg-danger fs-6">Habis</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="alert alert-warning" role="alert">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    Belum ada detail harga dan stok
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="background: #f8fafc;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i> Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 64px; color: #cbd5e1;"></i>
                <h5 class="mt-3 text-muted">Tidak ada buku ditemukan</h5>
                <p class="text-muted">Coba ubah filter atau kata kunci pencarian Anda</p>
            </div>
        </div>
    @endforelse
</div>

<!-- List View (Hidden by default) -->
<div id="listView" style="display: none;">
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background: #f8fafc;">
                        <tr>
                            <th style="padding: 15px;">Cover</th>
                            <th>Judul Buku</th>
                            <th>Kode</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($buku as $item)
                            <tr>
                                <td style="padding: 12px;">
                                    @if ($item->cover)
                                        <img src="{{ asset('cover/' . $item->cover) }}" alt="{{ $item->judul }}" 
                                             style="width: 50px; height: 70px; object-fit: cover; border-radius: 6px;">
                                    @else
                                        <div style="width: 50px; height: 70px; background: #f1f5f9; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-weight: 600; font-size: 14px; margin-bottom: 4px;">{{ Str::limit($item->judul, 40) }}</div>
                                    <div style="font-size: 12px; color: #64748b;">{{ $item->pengarang }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $item->kode_buku }}</span>
                                </td>
                                <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                                <td>
                                    <span style="font-weight: 600; color: #0d6efd;">
                                        Rp{{ number_format(optional($item->detailBuku)->harga ?? 0, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    @php $stok = optional($item->detailBuku)->stok ?? 0; @endphp
                                    @if($stok > 5)
                                        <span class="badge-custom stock-available">{{ $stok }}</span>
                                    @elseif($stok > 0)
                                        <span class="badge-custom stock-low">{{ $stok }}</span>
                                    @else
                                        <span class="badge-custom stock-out">Habis</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalBuku{{ $item->id }}"
                                            style="border-radius: 6px;">
                                        <i class="bi bi-eye"></i> Detail
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Pagination -->
@if(method_exists($buku, 'links'))
    <div class="d-flex justify-content-center mt-4">
        {{ $buku->appends(request()->query())->links() }}
    </div>
@endif

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // ✅ DATA CHART SUDAH DIKIRIM DARI CONTROLLER (KONSISTEN, TIDAK BERUBAH SAAT GANTI PAGE)
    
    // 🔍 DEBUG LENGKAP: Log semua data untuk analisis
    console.log('=== DEBUG DATA KATEGORI ===');
    console.log('Data Kategori Full:', {!! $kategoriData->toJson() !!});
    console.log('Unique Kategori di DB:', {!! $uniqueKategoriNames->toJson() !!});
    
    const kategoriDataDebug = {!! $kategoriData->toJson() !!};
    kategoriDataDebug.forEach(item => {
        console.log(`${item.nama}: ${item.jumlah} buku`);
    });
    
    // Chart 1: Distribusi Kategori (Fiksi / Non-Fiksi) - 🔥 PERBAIKAN
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const kategoriLabels = {!! $kategoriData->pluck('nama')->toJson() !!};
    const kategoriValues = {!! $kategoriData->pluck('jumlah')->toJson() !!};
    
    // Pastikan data tidak kosong semua
    const hasKategoriData = kategoriValues.some(val => val > 0);
    
    if (hasKategoriData) {
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: kategoriLabels,
                datasets: [{
                    data: kategoriValues,
                    backgroundColor: [
                        '#667eea', // Fiksi - Ungu
                        '#f093fb'  // Non-Fiksi - Pink
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: { size: 13, weight: '600' },
                            usePointStyle: true,
                            generateLabels: function(chart) {
                                const data = chart.data;
                                if (data.labels.length && data.datasets.length) {
                                    return data.labels.map((label, i) => {
                                        const value = data.datasets[0].data[i];
                                        return {
                                            text: `${label}: ${value} buku`,
                                            fillStyle: data.datasets[0].backgroundColor[i],
                                            hidden: false,
                                            index: i
                                        };
                                    });
                                }
                                return [];
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.85)',
                        padding: 15,
                        cornerRadius: 8,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                                return context.label + ': ' + context.parsed + ' buku (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    } else {
        // Jika tidak ada data, tampilkan pesan
        categoryCtx.canvas.parentNode.innerHTML = '<div style="display: flex; align-items: center; justify-content: center; height: 280px; color: #94a3b8;">Tidak ada data kategori</div>';
    }

    // Chart 2: Status Stok (Doughnut)
    const stockCtx = document.getElementById('stockChart').getContext('2d');
    new Chart(stockCtx, {
        type: 'doughnut',
        data: {
            labels: ['Tersedia (>5)', 'Menipis (1-5)', 'Habis'],
            datasets: [{
                data: [{{ $stokTersedia }}, {{ $stokMenipis }}, {{ $stokHabis }}],
                backgroundColor: ['#43e97b', '#feca57', '#f5576c'],
                borderWidth: 0,
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 12,
                        font: { size: 11 },
                        usePointStyle: true
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8
                }
            }
        }
    });

    // Chart 3: Distribusi Genre (Bar Chart)
    const genreCtx = document.getElementById('genreChart').getContext('2d');
    new Chart(genreCtx, {
        type: 'bar',
        data: {
            labels: {!! $genreData->pluck('nama')->toJson() !!},
            datasets: [{
                label: 'Jumlah Buku',
                data: {!! $genreData->pluck('jumlah')->toJson() !!},
                backgroundColor: [
                    '#667eea',
                    '#f093fb',
                    '#4facfe',
                    '#43e97b',
                    '#feca57',
                    '#fa709a',
                    '#ff6b6b',
                    '#a29bfe'
                ],
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.05)' },
                    ticks: { font: { size: 11 } }
                },
                x: {
                    grid: { display: false },
                    ticks: { 
                        font: { size: 11 },
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            }
        }
    });

    // Chart 4: Distribusi Jenis (Doughnut) - 🔥 PERBAIKAN: Warna yang lebih kontras
    const jenisCtx = document.getElementById('jenisChart').getContext('2d');
    
    // 🎨 Palet warna yang sangat berbeda dan mudah dibedakan
    const jenisColors = [
        '#FF6384', // Merah Pink terang
        '#36A2EB', // Biru cerah
        '#FFCE56', // Kuning cerah
        '#4BC0C0', // Cyan/Tosca
        '#9966FF', // Ungu
        '#FF9F40', // Orange
        '#FF6384', // Pink lagi (cadangan)
        '#C9CBCF', // Abu-abu
        '#7C4DFF', // Ungu tua
        '#00E676', // Hijau terang
        '#FF1744', // Merah terang
        '#2196F3'  // Biru medium
    ];
    
    new Chart(jenisCtx, {
        type: 'doughnut',
        data: {
            labels: {!! $jenisData->pluck('nama')->toJson() !!},
            datasets: [{
                data: {!! $jenisData->pluck('jumlah')->toJson() !!},
                backgroundColor: jenisColors,
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 10,
                hoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: { size: 12, weight: '600' },
                        usePointStyle: true,
                        pointStyle: 'circle',
                        boxWidth: 12,
                        boxHeight: 12
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.85)',
                    padding: 15,
                    cornerRadius: 8,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                            return context.label + ': ' + context.parsed + ' buku (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // Chart 5: Top 5 Kategori (Bar)
    const topCategoriesCtx = document.getElementById('topCategoriesChart').getContext('2d');
    new Chart(topCategoriesCtx, {
        type: 'bar',
        data: {
            labels: {!! $topKategori->pluck('nama')->toJson() !!},
            datasets: [{
                label: 'Total Stok',
                data: {!! $topKategori->pluck('stok')->toJson() !!},
                backgroundColor: [
                    '#667eea',
                    '#f093fb',
                    '#4facfe',
                    '#43e97b',
                    '#feca57'
                ],
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.05)' },
                    ticks: { font: { size: 11 } }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 } }
                }
            }
        }
    });

    // View Toggle Function
    function switchView(view) {
        const cardView = document.getElementById('cardView');
        const listView = document.getElementById('listView');
        const gridBtn = document.getElementById('gridViewBtn');
        const listBtn = document.getElementById('listViewBtn');

        if (view === 'grid') {
            cardView.style.display = 'flex';
            listView.style.display = 'none';
            gridBtn.classList.add('active');
            listBtn.classList.remove('active');
        } else {
            cardView.style.display = 'none';
            listView.style.display = 'block';
            gridBtn.classList.remove('active');
            listBtn.classList.add('active');
        }
    }
</script>
@endsection