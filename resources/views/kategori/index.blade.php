@extends('admin.layout.admin_layout')

@section('title', 'Kategori')

@section('content')
    <h1 class="mb-4">Daftar Kategori</h1>
    <p>Kelola kategori buku yang tersedia 📚</p>

    <!-- Data Kategori -->
    <div class="card shadow">
        <div class="card-header text-white d-flex justify-content-between align-items-center"
             style="background: linear-gradient(90deg,#00c6ff,#0077b6);">
            <h5 class="mb-0">Data Kategori</h5>
            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addKategoriModal">
                <i class="bi bi-plus-circle me-1"></i> Tambah Kategori
            </button>
        </div>
        <div class="card-body">

            <div class="table-responsive">
                <table class="table align-middle" id="kategoriTable">
                    <thead class="table-light">
                        <tr>
                            <th>No</th> {{-- sebelumnya ID --}}
                            <th>Nama Kategori</th>
                            <th>Genre</th>
                            <th>Jenis</th>
                            <th style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kategori as $kat)
                            <tr>
                                <td>{{ $loop->iteration }}</td> {{-- ganti dari $kat->id --}}
                                <td>{{ $kat->nama_kategori }}</td>
                                <td>{{ $kat->genre }}</td>
                                <td>{{ $kat->jenis }}</td>
                                <td>
                                    <!-- Tombol Edit -->
                                    <button class="btn btn-warning btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editKategoriModal{{ $kat->id }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('kategori.destroy', $kat->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin ingin menghapus kategori ini?')">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="editKategoriModal{{ $kat->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header text-white"
                                             style="background: linear-gradient(90deg,#f1c40f,#f39c12);">
                                            <h5 class="modal-title">Edit Kategori</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('kategori.update', $kat->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <!-- Nama Kategori -->
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Kategori</label>
                                                    <select name="nama_kategori" class="form-select" required>
                                                        <option value="Fiksi" {{ $kat->nama_kategori === 'Fiksi' ? 'selected' : '' }}>Fiksi</option>
                                                        <option value="Non Fiksi" {{ $kat->nama_kategori === 'Non Fiksi' ? 'selected' : '' }}>Non Fiksi</option>
                                                    </select>
                                                </div>
                                                <!-- Genre -->
                                                <div class="mb-3">
                                                    <label class="form-label">Genre</label>
                                                    <select name="genre" class="form-select genre-select" data-target="editGenreInput{{ $kat->id }}">
                                                        @foreach($genres as $g)
                                                            <option value="{{ $g }}" {{ $kat->genre === $g ? 'selected' : '' }}>{{ $g }}</option>
                                                        @endforeach
                                                        <option value="__new">+ Tambah Baru</option>
                                                    </select>
                                                    <input type="text" id="editGenreInput{{ $kat->id }}" name="genre_manual"
                                                           class="form-control mt-2 d-none" placeholder="Masukkan genre baru">
                                                </div>
                                                <!-- Jenis -->
                                                <div class="mb-3">
                                                    <label class="form-label">Jenis</label>
                                                    <select name="jenis" class="form-select jenis-select" data-target="editJenisInput{{ $kat->id }}">
                                                        @foreach($jenisList as $j)
                                                            <option value="{{ $j }}" {{ $kat->jenis === $j ? 'selected' : '' }}>{{ $j }}</option>
                                                        @endforeach
                                                        <option value="__new">+ Tambah Baru</option>
                                                    </select>
                                                    <input type="text" id="editJenisInput{{ $kat->id }}" name="jenis_manual"
                                                           class="form-control mt-2 d-none" placeholder="Masukkan jenis baru">
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
                                <td colspan="5" class="text-center text-muted">Belum ada data kategori</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="addKategoriModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-white"
                     style="background: linear-gradient(90deg,#00c851,#007e33);">
                    <h5 class="modal-title">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('kategori.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <!-- Nama Kategori -->
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori</label>
                            <select name="nama_kategori" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Fiksi">Fiksi</option>
                                <option value="Non Fiksi">Non Fiksi</option>
                            </select>
                        </div>
                        <!-- Genre -->
                        <div class="mb-3">
                            <label class="form-label">Genre</label>
                            <select name="genre" class="form-select genre-select" data-target="addGenreInput">
                                @foreach($genres as $g)
                                    <option value="{{ $g }}">{{ $g }}</option>
                                @endforeach
                                <option value="__new">+ Tambah Baru</option>
                            </select>
                            <input type="text" id="addGenreInput" name="genre_manual" class="form-control mt-2 d-none" placeholder="Masukkan genre baru">
                        </div>
                        <!-- Jenis -->
                        <div class="mb-3">
                            <label class="form-label">Jenis</label>
                            <select name="jenis" class="form-select jenis-select" data-target="addJenisInput">
                                @foreach($jenisList as $j)
                                    <option value="{{ $j }}">{{ $j }}</option>
                                @endforeach
                                <option value="__new">+ Tambah Baru</option>
                            </select>
                            <input type="text" id="addJenisInput" name="jenis_manual" class="form-control mt-2 d-none" placeholder="Masukkan jenis baru">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Tambah</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            function toggleManual(selectEl) {
                const targetId = selectEl.getAttribute('data-target');
                const input = document.getElementById(targetId);
                if (selectEl.value === '__new') {
                    input.classList.remove('d-none');
                    input.required = true;
                } else {
                    input.classList.add('d-none');
                    input.required = false;
                    input.value = '';
                }
            }

            document.querySelectorAll('.genre-select, .jenis-select').forEach(select => {
                toggleManual(select); // inisialisasi
                select.addEventListener('change', function () {
                    toggleManual(this);
                });
            });
        });
    </script>
@endsection
