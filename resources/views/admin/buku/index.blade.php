@extends('admin.layout.admin_layout')

@section('title', 'Dashboard Admin')

@section('content')

    <style>
        /* Style khusus tabel buku */
        #bukuTable {
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            border-collapse: collapse;
            width: 100%;
        }

        #bukuTable thead th {
            font-weight: 700; /* header tebal */
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background-color: #f8f9fa;
            color: #333;
        }

        #bukuTable tbody td {
            font-weight: 400 !important; /* isi normal */
            color: #212529;
        }

        #bukuTable tbody tr:hover {
            background-color: #f1f1f1;
        }

        #bukuTable td:last-child {
            white-space: nowrap;
        }

        /* Style modal detail */
        .modal-header {
            background: linear-gradient(90deg,#00c6ff,#0077b6);
            color: #fff;
        }
        .modal-body p {
            margin-bottom: 8px;
            font-size: 14px;
        }
        .modal-body span.fw-bold {
            color: #0077b6;
        }
    </style>

    <!-- Judul halaman -->
    <h1 class="mb-4 fw-bold">Data Buku</h1>
    <p class="text-muted">Kelola Data Buku</p>

    <!-- Data Buku -->
    <div class="card shadow">
        <div class="card-header text-white d-flex justify-content-between align-items-center"
             style="background: linear-gradient(90deg,#00c6ff,#0077b6);">
            <h5 class="mb-0 fw-bold">Data Buku</h5>
            <a href="{{ route('buku.create') }}" class="btn btn-success btn-sm">
                <i class="bi bi-plus-circle me-1"></i> Tambah Buku
            </a>
        </div>
        <div class="card-body">

            <!-- Search & Filter -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <input type="text" id="searchInput" class="form-control"
                           placeholder="Cari buku berdasarkan judul, pengarang, penerbit...">
                </div>
                <div class="col-md-4">
                    <select id="filterPenerbit" class="form-select">
                        <option value="">-- Filter Penerbit --</option>
                        @php
                            $penerbitList = $buku->pluck('penerbit')->unique();
                        @endphp
                        @foreach ($penerbitList as $penerbit)
                            <option value="{{ $penerbit }}">{{ $penerbit }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Tabel Data Buku -->
            <div class="table-responsive">
                <table class="table align-middle" id="bukuTable">
                    <thead class="table-light">
                        <tr>
                            <th>Kode Buku</th>
                            <th>Judul</th>
                            <th>Penerbit</th>
                            <th>Pengarang</th>
                            <th>Cover</th>
                            <th style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($buku as $item)
                            <tr>
                                <td>{{ $item->kode_buku }}</td>
                                <td>{{ $item->judul }}</td>
                                <td>{{ $item->penerbit }}</td>
                                <td>{{ $item->pengarang }}</td>
                                <td>
                                    @if ($item->cover)
                                        <img src="{{ asset('cover/' . $item->cover) }}" alt="Cover Buku"
                                             width="60" class="rounded">
                                    @else
                                        <span class="text-muted">Tidak ada</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#viewModal{{ $item->id }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <a href="{{ route('buku.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('buku.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin ingin menghapus buku ini?')">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Detail -->
                            <div class="modal fade" id="viewModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">Detail Buku</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-4 text-center">
                                                    @if ($item->cover)
                                                        <img src="{{ asset('cover/' . $item->cover) }}" alt="Cover Buku"
                                                             class="img-fluid rounded shadow">
                                                    @else
                                                        <span class="text-muted">Tidak ada cover</span>
                                                    @endif
                                                </div>
                                                <div class="col-md-8">
                                                    <p><span class="fw-bold">Kode Buku:</span> {{ $item->kode_buku }}</p>
                                                    <p><span class="fw-bold">Judul:</span> {{ $item->judul }}</p>
                                                    <p><span class="fw-bold">Penerbit:</span> {{ $item->penerbit }}</p>
                                                    <p><span class="fw-bold">Pengarang:</span> {{ $item->pengarang }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-3">
                    {{ $buku->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter Script -->
    <script>
        const searchInput = document.getElementById('searchInput');
        const filterPenerbit = document.getElementById('filterPenerbit');
        const rows = document.querySelectorAll('#bukuTable tbody tr');

        function filterTable() {
            const searchText = searchInput.value.toLowerCase();
            const filterValue = filterPenerbit.value.toLowerCase();

            rows.forEach(row => {
                const judul = row.cells[1].textContent.toLowerCase();
                const penerbit = row.cells[2].textContent.toLowerCase();
                const pengarang = row.cells[3].textContent.toLowerCase();

                const matchSearch = judul.includes(searchText) || penerbit.includes(searchText) || pengarang.includes(searchText);
                const matchFilter = filterValue === "" || penerbit === filterValue;

                row.style.display = (matchSearch && matchFilter) ? "" : "none";
            });
        }

        searchInput.addEventListener('keyup', filterTable);
        filterPenerbit.addEventListener('change', filterTable);
    </script>
@endsection
