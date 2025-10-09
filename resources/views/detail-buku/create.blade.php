<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Detail Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Tambah Detail Buku</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('detail-buku.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Judul Buku</label>
                    <select name="buku_id" class="form-select" required>
                        <option value="">-- Pilih Buku --</option>
                        @foreach($availableBooks as $buku)
                            <option value="{{ $buku->id }}">{{ $buku->judul }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Harga</label>
                    <input type="text" name="harga" id="harga" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Stok</label>
                    <input type="number" name="stok" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="{{ route('detail-buku.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>

<script>
    // Format harga input dengan titik ribuan
    const hargaInput = document.getElementById('harga');
    hargaInput.addEventListener('input', function (e) {
        let value = this.value.replace(/\./g, '');
        if (!isNaN(value) && value.length > 0) {
            this.value = new Intl.NumberFormat('id-ID').format(value);
        } else {
            this.value = '';
        }
    });
</script>
</body>
</html>
