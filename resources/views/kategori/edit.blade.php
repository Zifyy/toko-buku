<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>NesMedia - Kategori</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; background: #f8f9fa; color: #333;}
        .sidebar { min-height: 100vh; background: #ffffff; border-right: 1px solid #e6e6e6; padding-top: 20px; box-shadow: 2px 0 6px rgba(0, 0, 0, 0.05);}
        .sidebar .logo { text-align: center; padding: 20px 10px; border-bottom: 1px solid #eaeaea; }
        .sidebar .logo h5 { margin-top: 10px; font-weight: 700; color: #0077b6; }
        .sidebar a { color: #555; text-decoration: none; display: block; padding: 12px 18px; border-radius: 8px; margin: 5px 10px; font-weight: 500; transition: 0.2s;}
        .sidebar a:hover { background: #e9f5ff; color: #0077b6; }
        .sidebar .active { background: linear-gradient(90deg, #00c6ff, #0077b6); color: #fff !important; font-weight: 600;}
        .content { padding: 30px;}
        .card { background: #ffffff; border: none; border-radius: 12px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);}
        .card-header { border-radius: 12px 12px 0 0 !important;}
        .table thead { background: #e9f5ff; color: #0077b6;}
        .table tbody tr:hover { background: rgba(0, 119, 182, 0.05);}
        .btn-sm { border-radius: 8px; padding: 4px 10px;}
    </style>
</head>

<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-2 d-none d-md-block sidebar">
            <div class="logo">
                <i class="bi bi-journal-bookmark-fill" style="font-size: 2rem; color:#0077b6;"></i>
                <h5>NesMedia</h5>
            </div>
            <div class="p-2">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->is('admin') ? 'active' : '' }}">
                    <i class="bi bi-book me-2"></i> Data Buku
                </a>
                <a href="{{ route('kategori.index') }}" class="{{ request()->is('admin/kategori*') ? 'active' : '' }}">
                    <i class="bi bi-tags me-2"></i> Kategori
                </a>
                <a href="{{ route('admin.laporan') }}" class="{{ request()->is('admin/laporan') ? 'active' : '' }}">
                    <i class="bi bi-graph-up me-2"></i> Laporan
                </a>
                <form action="/logout" method="POST" class="mt-3 px-2">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </button>
                </form>
            </div>
        </nav>

        <!-- Content -->
        <main class="col-md-10 ms-sm-auto content">
            <h1 class="mb-4">Daftar Kategori</h1>
            <p>Kelola kategori buku yang tersedia 📚</p>

            <!-- Data Kategori -->
            <div class="card shadow">
                <div class="card-header text-white d-flex justify-content-between align-items-center"
                     style="background: linear-gradient(90deg,#00c6ff,#0077b6);">
                    <h5 class="mb-0">Data Kategori</h5>
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahKategori">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Kategori
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Kategori</th>
                                <th>Genre</th>
                                <th>Jenis</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($kategori as $kat)
                                <tr>
                                    <td>{{ $kat->id }}</td>
                                    <td>{{ $kat->nama_kategori }}</td>
                                    <td>{{ $kat->genre }}</td>
                                    <td>{{ $kat->jenis }}</td>
                                    <td>
                                        <!-- Tombol Edit -->
                                        <button class="btn btn-warning btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalEditKategori{{ $kat->id }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <!-- Modal Edit -->
                                        <div class="modal fade" id="modalEditKategori{{ $kat->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-warning text-dark">
                                                        <h5 class="modal-title">Edit Kategori</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('kategori.update', $kat->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Nama Kategori</label>
                                                                <select name="nama_kategori" class="form-select" required>
                                                                    <option value="Fiksi" {{ $kat->nama_kategori === 'Fiksi' ? 'selected' : '' }}>Fiksi</option>
                                                                    <option value="Non Fiksi" {{ $kat->nama_kategori === 'Non Fiksi' ? 'selected' : '' }}>Non Fiksi</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Genre</label>
                                                                <select name="genre" class="form-select genre-select" data-target="#genreInputEdit{{ $kat->id }}">
                                                                    <option value="{{ $kat->genre }}" selected>{{ $kat->genre }}</option>
                                                                    @foreach ($kategori->unique('genre') as $g)
                                                                        @if($g->genre !== $kat->genre)
                                                                            <option value="{{ $g->genre }}">{{ $g->genre }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                    <option value="__new">+ Tambah Baru</option>
                                                                </select>
                                                                <input type="text" name="genre_new" id="genreInputEdit{{ $kat->id }}" class="form-control mt-2 d-none" placeholder="Masukkan genre baru">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Jenis</label>
                                                                <select name="jenis" class="form-select jenis-select" data-target="#jenisInputEdit{{ $kat->id }}">
                                                                    <option value="{{ $kat->jenis }}" selected>{{ $kat->jenis }}</option>
                                                                    @foreach ($kategori->unique('jenis') as $j)
                                                                        @if($j->jenis !== $kat->jenis)
                                                                            <option value="{{ $j->jenis }}">{{ $j->jenis }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                    <option value="__new">+ Tambah Baru</option>
                                                                </select>
                                                                <input type="text" name="jenis_new" id="jenisInputEdit{{ $kat->id }}" class="form-control mt-2 d-none" placeholder="Masukkan jenis baru">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-warning">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

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
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambahKategori" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('kategori.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori</label>
                        <select name="nama_kategori" class="form-select" required>
                            <option value="Fiksi">Fiksi</option>
                            <option value="Non Fiksi">Non Fiksi</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Genre</label>
                        <select name="genre" class="form-select genre-select" data-target="#genreInputTambah">
                            @foreach ($kategori->unique('genre') as $g)
                                <option value="{{ $g->genre }}">{{ $g->genre }}</option>
                            @endforeach
                            <option value="__new">+ Tambah Baru</option>
                        </select>
                        <input type="text" name="genre_new" id="genreInputTambah" class="form-control mt-2 d-none" placeholder="Masukkan genre baru">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis</label>
                        <select name="jenis" class="form-select jenis-select" data-target="#jenisInputTambah">
                            @foreach ($kategori->unique('jenis') as $j)
                                <option value="{{ $j->jenis }}">{{ $j->jenis }}</option>
                            @endforeach
                            <option value="__new">+ Tambah Baru</option>
                        </select>
                        <input type="text" name="jenis_new" id="jenisInputTambah" class="form-control mt-2 d-none" placeholder="Masukkan jenis baru">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // toggle input baru jika pilih "__new"
    document.querySelectorAll('.genre-select, .jenis-select').forEach(select => {
        select.addEventListener('change', function() {
            let targetInput = document.querySelector(this.dataset.target);
            if (this.value === '__new') {
                targetInput.classList.remove('d-none');
                targetInput.required = true;
            } else {
                targetInput.classList.add('d-none');
                targetInput.required = false;
            }
        });
    });
</script>
</body>
</html>
