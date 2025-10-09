@extends('layout.app')

@section('title', 'Tambah Kategori')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header text-white" style="background: linear-gradient(90deg,#00c6ff,#0077b6);">
            <h5 class="mb-0">Tambah Kategori</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('kategori.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="nama_kategori" class="form-label">Nama Kategori</label>
                    <input type="text" name="nama_kategori" id="nama_kategori" 
                           class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="genre" class="form-label">Genre</label>
                    <input type="text" name="genre" id="genre" 
                           class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="jenis" class="form-label">Jenis</label>
                    <input type="text" name="jenis" id="jenis" 
                           class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
