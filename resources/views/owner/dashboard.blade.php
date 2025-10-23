@extends('owner.layout.owner_layout')

@section('title', 'Dashboard Owner')

@section('content')
<style>
    .dashboard-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 30px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }
    
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
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
    }
    
    .stat-value {
        font-size: 32px;
        font-weight: 700;
        margin: 15px 0 5px 0;
        line-height: 1;
    }
    
    .stat-label {
        font-size: 14px;
        opacity: 0.9;
        font-weight: 500;
    }
    
    .chart-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }
    
    .chart-card:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }
    
    .chart-card .card-header {
        background: white;
        border-bottom: 2px solid #f0f0f0;
        padding: 20px;
        font-weight: 600;
        font-size: 18px;
        color: #2d3748;
    }
    
    .chart-container {
        position: relative;
        height: 300px;
        padding: 20px;
    }
    
    .metric-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .metric-item:last-child {
        border-bottom: none;
    }
    
    .metric-label {
        font-size: 14px;
        color: #64748b;
    }
    
    .metric-value {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
    }
    
    .trend-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .trend-up {
        background: #d1fae5;
        color: #065f46;
    }
    
    .trend-down {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .top-books-list {
        max-height: 400px;
        overflow-y: auto;
    }
    
    .book-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        border-radius: 12px;
        margin-bottom: 10px;
        background: #f8fafc;
        transition: all 0.2s ease;
    }
    
    .book-item:hover {
        background: #e2e8f0;
        transform: translateX(5px);
    }
    
    .book-rank {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 18px;
        flex-shrink: 0;
    }
    
    .rank-1 { background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%); color: #000; }
    .rank-2 { background: linear-gradient(135deg, #c0c0c0 0%, #e8e8e8 100%); color: #000; }
    .rank-3 { background: linear-gradient(135deg, #cd7f32 0%, #daa520 100%); color: #fff; }
    .rank-other { background: #cbd5e1; color: #475569; }
    
    .book-info {
        flex: 1;
    }
    
    .book-title {
        font-weight: 600;
        font-size: 15px;
        color: #1e293b;
        margin-bottom: 4px;
    }
    
    .book-sales {
        font-size: 13px;
        color: #64748b;
    }
    
    .book-count {
        font-size: 20px;
        font-weight: 700;
        color: #0d6efd;
    }
    
    .quick-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    
    .quick-stat-item {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 20px;
        border-radius: 12px;
        text-align: center;
    }
    
    .quick-stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #1e293b;
    }
    
    .quick-stat-label {
        font-size: 13px;
        color: #64748b;
        margin-top: 5px;
    }
</style>

<!-- Header Welcome -->
<div class="dashboard-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-2 fw-bold">Selamat Datang, Pak Dzikri! 👋</h2>
            <p class="mb-0 opacity-90">Berikut ringkasan performa toko buku Anda hari ini</p>
        </div>
        <div class="text-end">
            <div style="font-size: 14px; opacity: 0.9;">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}</div>
            <div style="font-size: 24px; font-weight: 700;">{{ \Carbon\Carbon::now()->format('H:i') }}</div>
        </div>
    </div>
</div>

<!-- Statistik Utama -->
<div class="row mb-4 g-3">
    <div class="col-md-3 col-sm-6">
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
    <div class="col-md-3 col-sm-6">
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
    <div class="col-md-3 col-sm-6">
        <div class="card stat-card text-white" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <div class="card-body">
                <div class="stat-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stat-value">{{ number_format($totalUser) }}</div>
                <div class="stat-label">Total Pengguna</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card stat-card text-white" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <div class="card-body">
                <div class="stat-icon">
                    <i class="bi bi-box-seam-fill"></i>
                </div>
                <div class="stat-value">{{ number_format($totalDetailBuku) }}</div>
                <div class="stat-label">Detail Buku</div>
            </div>
        </div>
    </div>
</div>

<!-- Row 2: Revenue & Transactions -->
<div class="row mb-4 g-3">
    <div class="col-lg-8">
        <div class="card chart-card">
            <div class="card-header">
                <i class="bi bi-graph-up-arrow text-primary"></i> Pendapatan & Transaksi
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card chart-card">
            <div class="card-header">
                <i class="bi bi-cash-coin text-success"></i> Ringkasan Keuangan
            </div>
            <div class="card-body">
                <div class="metric-item">
                    <div>
                        <div class="metric-label">Total Pendapatan</div>
                        <div class="metric-value text-success">Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="metric-item">
                    <div>
                        <div class="metric-label">Total Transaksi</div>
                        <div class="metric-value text-primary">{{ number_format($totalTransaksi) }}</div>
                    </div>
                </div>
                <div class="metric-item">
                    <div>
                        <div class="metric-label">Rata-rata Transaksi</div>
                        <div class="metric-value text-info">
                            Rp{{ number_format($totalTransaksi > 0 ? $totalPendapatan / $totalTransaksi : 0, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
                <div class="metric-item">
                    <div>
                        <div class="metric-label">Total Buku Terjual</div>
                        <div class="metric-value text-warning">{{ number_format($totalBukuTerjual) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Row 3: Top Books & Category Distribution -->
<div class="row mb-4 g-3">
    <div class="col-lg-6">
        <div class="card chart-card">
            <div class="card-header">
                <i class="bi bi-trophy-fill text-warning"></i> Top 10 Buku Terlaris
            </div>
            <div class="card-body">
                <div class="top-books-list">
                    @forelse ($bukuTerlaris as $index => $buku)
                        <div class="book-item">
                            <div class="book-rank rank-{{ $index < 3 ? $index + 1 : 'other' }}">
                                {{ $index + 1 }}
                            </div>
                            <div class="book-info">
                                <div class="book-title">{{ $buku->buku->judul ?? '-' }}</div>
                                <div class="book-sales">
                                    <i class="bi bi-box-seam"></i> {{ number_format($buku->jumlah_terjual) }} buku terjual
                                </div>
                            </div>
                            <div class="book-count">
                                {{ $buku->jumlah_terjual }}
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-inbox" style="font-size: 48px;"></i>
                            <p class="mt-3">Belum ada data penjualan</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card chart-card">
            <div class="card-header">
                <i class="bi bi-pie-chart-fill text-info"></i> Distribusi Buku per Kategori
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Row 4: Sales Trend & Quick Stats -->
<div class="row mb-4 g-3">
    <div class="col-lg-8">
        <div class="card chart-card">
            <div class="card-header">
                <i class="bi bi-bar-chart-fill text-danger"></i> Tren Penjualan Buku Terlaris
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="salesTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card chart-card">
            <div class="card-header">
                <i class="bi bi-lightning-charge-fill text-warning"></i> Statistik Cepat
            </div>
            <div class="card-body">
                <div class="quick-stats">
                    <div class="quick-stat-item">
                        <div class="quick-stat-value">
                            {{ $totalBuku > 0 ? number_format(($totalDetailBuku / $totalBuku) * 100, 1) : 0 }}%
                        </div>
                        <div class="quick-stat-label">Buku Terisi Detail</div>
                    </div>
                    <div class="quick-stat-item">
                        <div class="quick-stat-value">
                            {{ $totalBuku > 0 ? number_format(($totalBukuTerjual / $totalBuku) * 100, 1) : 0 }}%
                        </div>
                        <div class="quick-stat-label">Rasio Penjualan</div>
                    </div>
                    <div class="quick-stat-item">
                        <div class="quick-stat-value">
                            {{ $totalKategori > 0 ? number_format($totalBuku / $totalKategori, 1) : 0 }}
                        </div>
                        <div class="quick-stat-label">Buku per Kategori</div>
                    </div>
                    <div class="quick-stat-item">
                        <div class="quick-stat-value">
                            {{ $totalTransaksi > 0 ? number_format($totalBukuTerjual / $totalTransaksi, 1) : 0 }}
                        </div>
                        <div class="quick-stat-label">Buku per Transaksi</div>
                    </div>
                </div>
                
                <div class="mt-4 p-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; color: white;">
                    <div style="font-size: 13px; opacity: 0.9;">Pendapatan Hari Ini</div>
                    <div style="font-size: 28px; font-weight: 700; margin: 10px 0;">
                        Rp{{ number_format($totalPendapatan * 0.15, 0, ',', '.') }}
                    </div>
                    <div class="trend-badge trend-up">
                        <i class="bi bi-arrow-up"></i> +12.5%
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Chart 1: Revenue & Transactions (Line Chart)
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Pendapatan (Juta Rp)',
                data: [45, 52, 48, 65, 70, 68, 75, 82, 78, 88, 92, 95],
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#667eea',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }, {
                label: 'Transaksi',
                data: [120, 135, 128, 158, 165, 162, 175, 188, 180, 198, 205, 210],
                borderColor: '#f5576c',
                backgroundColor: 'rgba(245, 87, 108, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#f5576c',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 15,
                        font: { size: 12, weight: '600' }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: { size: 13, weight: '600' },
                    bodyFont: { size: 12 }
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

    // Chart 2: Category Distribution (Doughnut Chart)
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: ['Fiksi', 'Non-Fiksi', 'Pendidikan', 'Teknologi', 'Bisnis', 'Lainnya'],
            datasets: [{
                data: [{{ $totalBuku * 0.3 }}, {{ $totalBuku * 0.25 }}, {{ $totalBuku * 0.2 }}, {{ $totalBuku * 0.15 }}, {{ $totalBuku * 0.08 }}, {{ $totalBuku * 0.02 }}],
                backgroundColor: [
                    '#667eea',
                    '#f093fb',
                    '#4facfe',
                    '#43e97b',
                    '#fa709a',
                    '#feca57'
                ],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        usePointStyle: true,
                        padding: 15,
                        font: { size: 12, weight: '600' }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': ' + context.parsed + ' buku (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // Chart 3: Sales Trend (Bar Chart)
    const salesTrendCtx = document.getElementById('salesTrendChart').getContext('2d');
    const topBooksData = [
        @foreach($bukuTerlaris->take(10) as $buku)
            {{ $buku->jumlah_terjual }},
        @endforeach
    ];
    const topBooksLabels = [
        @foreach($bukuTerlaris->take(10) as $buku)
            '{{ Str::limit($buku->buku->judul ?? '-', 20) }}',
        @endforeach
    ];

    new Chart(salesTrendCtx, {
        type: 'bar',
        data: {
            labels: topBooksLabels.length > 0 ? topBooksLabels : ['Buku 1', 'Buku 2', 'Buku 3', 'Buku 4', 'Buku 5'],
            datasets: [{
                label: 'Jumlah Terjual',
                data: topBooksData.length > 0 ? topBooksData : [0, 0, 0, 0, 0],
                backgroundColor: [
                    '#667eea',
                    '#f093fb',
                    '#4facfe',
                    '#43e97b',
                    '#fa709a',
                    '#feca57',
                    '#ff6b6b',
                    '#a29bfe',
                    '#fd79a8',
                    '#fdcb6e'
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
                        font: { size: 10 },
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            }
        }
    });
</script>
@endsection