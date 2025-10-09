@extends('admin.layout.admin_layout')

@section('title', 'Tambah Buku')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header text-white fw-bold"
                     style="background: linear-gradient(90deg,#00c851,#007e33);">
                    <i class="bi bi-plus-circle me-2"></i> Tambah Buku
                </div>
                <div class="card-body">
                    <form action="{{ route('buku.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Kode Buku (otomatis) --}}
                        <div class="mb-3">
                            <label for="kode_buku" class="form-label">Kode Buku (Otomatis)</label>
                            <input type="text" id="kode_buku" class="form-control" value="-- pilih kategori dulu --" readonly>
                            <small class="text-muted">Kode akan dibuat otomatis berdasarkan kategori yang dipilih</small>
                        </div>

                        {{-- Judul --}}
                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul</label>
                            <input type="text" name="judul" id="judul"
                                   class="form-control @error('judul') is-invalid @enderror"
                                   value="{{ old('judul') }}" required>
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Penerbit --}}
                        <div class="mb-3">
                            <label for="penerbit" class="form-label">Penerbit</label>
                            <input type="text" name="penerbit" id="penerbit"
                                   class="form-control @error('penerbit') is-invalid @enderror"
                                   value="{{ old('penerbit') }}" required>
                            @error('penerbit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Pengarang --}}
                        <div class="mb-3">
                            <label for="pengarang" class="form-label">Pengarang</label>
                            <input type="text" name="pengarang" id="pengarang"
                                   class="form-control @error('pengarang') is-invalid @enderror"
                                   value="{{ old('pengarang') }}" required>
                            @error('pengarang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tahun Terbit --}}
                        <div class="mb-3">
                            <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                            <input type="number" name="tahun_terbit" id="tahun_terbit"
                                   class="form-control @error('tahun_terbit') is-invalid @enderror"
                                   value="{{ old('tahun_terbit') }}" required>
                            @error('tahun_terbit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kategori --}}
                        <div class="mb-3">
                            <label for="kategori_id" class="form-label">Kategori</label>
                            <select name="kategori_id" id="kategori_id"
                                    class="form-select @error('kategori_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategori as $kat)
                                    <option value="{{ $kat->id }}"
                                        data-nama="{{ $kat->nama_kategori }}">
                                        {{ $kat->nama_kategori }} - {{ $kat->genre }} - {{ $kat->jenis }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Cover --}}
                        <div class="mb-3">
                            <label for="cover" class="form-label">Cover Buku</label>
                            <input type="file" name="cover" id="cover"
                                   class="form-control @error('cover') is-invalid @enderror">
                            @error('cover')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left-circle me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save me-1"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Script preview kode otomatis --}}
    <script>
        document.getElementById('kategori_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const kategori = selectedOption.getAttribute('data-nama');
            const kodeInput = document.getElementById('kode_buku');

            if (kategori === 'Non Fiksi') {
                kodeInput.value = "BKNxxx (otomatis)";
            } else if (kategori === 'Fiksi') {
                kodeInput.value = "BKFxxx (otomatis)";
            } else {
                kodeInput.value = "-- pilih kategori dulu --";
            }
        });
    </script>
@endsection
