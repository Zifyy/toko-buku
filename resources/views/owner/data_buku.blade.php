@extends('owner.layout.owner_layout')

@section('title', 'Data Buku - Owner')

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
</style>

<div class="container py-4">
    <h2 class="mb-4">📚 Data Buku</h2>
    <p class="mb-4 text-muted">Lihat semua data buku yang tersedia di sistem toko Anda.</p>

    <!-- Search Bar -->
    <div class="row mb-4">
        <div class="col-md-6">
            <form action="{{ route('owner.data_buku') }}" method="GET" class="d-flex align-items-center">
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

    <!-- Daftar Buku (Card Style) -->
    <div class="row">
        @forelse ($buku as $item)
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
                        <p class="mb-1"><strong>Penerbit:</strong> {{ $item->penerbit }}</p>

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
        @empty
            <div class="text-center py-5">
                <p class="text-muted mb-0">Belum ada data buku.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(method_exists($buku, 'links'))
        <div class="d-flex justify-content-center mt-3">
            {{ $buku->links() }}
        </div>
    @endif
</div>
@endsection
