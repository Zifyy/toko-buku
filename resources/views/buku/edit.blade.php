@extends('admin.layout.admin_layout')

@section('title', 'Edit Buku')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Content -->
        <main class="col-md-10 ms-sm-auto content py-4">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="card shadow-sm">
                        <div class="card-header text-white fw-bold"
                             style="background: linear-gradient(90deg,#f1c40f,#f39c12);">
                            <i class="bi bi-pencil-square me-2"></i> Edit Buku
                        </div>
                        <div class="card-body">
                            <form action="{{ route('buku.update', $buku->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                {{-- Kode Buku (Hanya Ditampilkan, Tidak Bisa Diedit) --}}
                                <div class="mb-3">
                                    <label class="form-label">Kode Buku</label>
                                    <input type="text" class="form-control" value="{{ $buku->kode_buku }}" disabled>
                                    <small class="text-muted">Kode buku akan berubah otomatis jika kategori diganti.</small>
                                </div>

                                {{-- Judul --}}
                                <div class="mb-3">
                                    <label for="judul" class="form-label">Judul</label>
                                    <input type="text" name="judul" id="judul"
                                           class="form-control @error('judul') is-invalid @enderror"
                                           value="{{ old('judul', $buku->judul) }}" required>
                                    @error('judul')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Penerbit --}}
                                <div class="mb-3">
                                    <label for="penerbit" class="form-label">Penerbit</label>
                                    <input type="text" name="penerbit" id="penerbit"
                                           class="form-control @error('penerbit') is-invalid @enderror"
                                           value="{{ old('penerbit', $buku->penerbit) }}" required>
                                    @error('penerbit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Pengarang --}}
                                <div class="mb-3">
                                    <label for="pengarang" class="form-label">Pengarang</label>
                                    <input type="text" name="pengarang" id="pengarang"
                                           class="form-control @error('pengarang') is-invalid @enderror"
                                           value="{{ old('pengarang', $buku->pengarang) }}" required>
                                    @error('pengarang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Tahun Terbit --}}
                                <div class="mb-3">
                                    <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                                    <input type="number" name="tahun_terbit" id="tahun_terbit"
                                           class="form-control @error('tahun_terbit') is-invalid @enderror"
                                           value="{{ old('tahun_terbit', $buku->tahun_terbit) }}" required>
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
                                                {{ old('kategori_id', $buku->kategori_id) == $kat->id ? 'selected' : '' }}>
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
                                    @if($buku->cover)
                                        <div class="mt-2">
                                            <img src="{{ asset('cover/' . $buku->cover) }}" 
                                                 alt="Cover Buku" width="120" class="img-thumbnail">
                                        </div>
                                    @endif
                                    @error('cover')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Tombol Aksi --}}
                                <div class="d-flex justify-content-between mt-4">
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left-circle me-1"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-warning">
                                        <i class="bi bi-save me-1"></i> Update
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
