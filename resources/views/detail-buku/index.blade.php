@extends('admin.layout.admin_layout')

@section('title', 'Detail Buku')

@section('content')
    <h1 class="mb-4">Daftar Detail Buku</h1>
    <p>Kelola data harga dan stok buku 📚</p>

    <!-- Card Data -->
    <div class="card shadow">
        <div class="card-header text-white d-flex justify-content-between align-items-center"
             style="background: linear-gradient(90deg,#00c6ff,#0077b6);">
            <h5 class="mb-0">Data Detail Buku</h5>
            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-circle me-1"></i> Tambah Detail
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th> {{-- sebelumnya ID --}}
                            <th>Judul Buku</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($detailBuku as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td> {{-- nomor urut otomatis --}}
                                <td>{{ $item->buku->judul ?? '-' }}</td>
                                <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td>{{ $item->stok }}</td>
                                <td>
                                    <!-- Tombol Edit -->
                                    <button class="btn btn-warning btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModal{{ $item->id }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('detail-buku.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin ingin menghapus detail buku ini?')">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('detail-buku.update', $item->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <div class="modal-header text-dark"
                                                 style="background: linear-gradient(90deg,#f1c40f,#f39c12);">
                                                <h5 class="modal-title">Edit Detail Buku</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Buku</label>
                                                    <input type="text" class="form-control" value="{{ $item->buku->judul ?? '-' }}" disabled>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Harga</label>
                                                    <input type="text" name="harga" class="form-control"
                                                           value="{{ number_format($item->harga, 0, ',', '.') }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Stok</label>
                                                    <input type="number" name="stok" class="form-control" value="{{ $item->stok }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-warning">Update</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada data detail buku</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('detail-buku.store') }}" method="POST">
                    @csrf
                    <div class="modal-header text-white"
                         style="background: linear-gradient(90deg,#00c851,#007e33);">
                        <h5 class="modal-title">Tambah Detail Buku</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Buku</label>
                            <select name="buku_id" class="form-select" required>
                                <option value="">-- Pilih Buku --</option>
                                @foreach ($availableBuku ?? [] as $b)
                                    <option value="{{ $b->id }}">{{ $b->judul }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Harga</label>
                            <input type="text" name="harga" class="form-control" placeholder="contoh: 97.000" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stok</label>
                            <input type="number" name="stok" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
