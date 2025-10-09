@extends('admin.layout.admin_layout')

@section('title', 'Dashboard Admin')

@section('content')
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
            <form action="{{ route('admin.dashboard') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2"
                       placeholder="Cari judul, kode, atau pengarang..."
                       value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i>
                </button>
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
                        <h6 class="card-title">{{ $item->judul }}</h6>
                        <p class="mb-1"><strong>Kode:</strong> {{ $item->kode_buku }}</p>
                        <p class="mb-1"><strong>Pengarang:</strong> {{ $item->pengarang }}</p>
                        <p class="mb-1"><strong>Kategori:</strong> {{ $item->kategori->nama_kategori ?? '-' }}</p>

                        {{-- Card Detail Buku --}}
                        <p class="mb-1">
                            <strong>Harga:</strong>
                            Rp{{ number_format(optional($item->detailBuku)->harga ?? 0, 0, ',', '.') }}
                        </p>
                        <p class="mb-2">
                            <strong>Stok:</strong>
                            @php $stok = optional($item->detailBuku)->stok ?? 0; @endphp
                            @if ($stok > 0)
                                <span class="badge bg-success">{{ $stok }}</span>
                            @else
                                <span class="badge bg-danger">Habis</span>
                            @endif
                        </p>

                        <!-- Tombol selalu di bawah -->
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
                            <h5 class="modal-title">Detail Buku</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4 text-center mb-3 mb-md-0">
                                    @if ($item->cover)
                                        <img src="{{ asset('cover/' . $item->cover) }}" alt="Cover Buku"
                                             class="img-fluid rounded shadow">
                                    @else
                                        <span class="text-muted">Tidak ada cover</span>
                                    @endif
                                </div>
                                <div class="col-md-8">
                                    <p><strong>Kode Buku:</strong> {{ $item->kode_buku }}</p>
                                    <p><strong>Judul:</strong> {{ $item->judul }}</p>
                                    <p><strong>Kategori:</strong> {{ $item->kategori->nama_kategori ?? '-' }}</p>
                                    <p><strong>Penerbit:</strong> {{ $item->penerbit }}</p>
                                    <p><strong>Pengarang:</strong> {{ $item->pengarang }}</p>
                                    <p><strong>Tahun Terbit:</strong> {{ $item->tahun_terbit }}</p>
                                    <hr>
                                    <h6 class="mb-2"><strong>Detail Buku</strong></h6>

                                    {{-- Modal Detail Buku --}}
                                    @if ($item->detailBuku)
                                        <p><strong>Harga:</strong>
                                            Rp{{ number_format($item->detailBuku->harga, 0, ',', '.') }}</p>
                                        <p><strong>Stok:</strong> {{ $item->detailBuku->stok }}</p>
                                    @else
                                        <p class="text-muted mb-0">Belum ada detail buku</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Tutup</button>
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