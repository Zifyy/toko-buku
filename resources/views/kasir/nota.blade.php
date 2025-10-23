@extends('kasir.layout.kasir_layout')

@section('title', 'Nota Transaksi')

@section('content')
<style>
    /* Style khusus untuk tampilan struk */
    .struk-container {
        width: 350px;
        margin: 0 auto;
        background: #fff;
        border: 1px dashed #000;
        padding: 20px;
        font-family: 'Courier New', monospace;
    }

    .struk-header {
        text-align: center;
        margin-bottom: 10px;
    }

    .struk-header h5 {
        margin: 0;
        font-weight: bold;
    }

    .struk-header p {
        margin: 0;
        font-size: 13px;
    }

    .struk-divider {
        border-top: 1px dashed #000;
        margin: 8px 0;
    }

    .struk-body table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .struk-body td {
        padding: 2px 0;
    }

    .struk-body td:last-child {
        text-align: right;
    }

    .struk-total {
        font-weight: bold;
        font-size: 14px;
        margin-top: 5px;
    }

    .struk-footer {
        text-align: center;
        margin-top: 15px;
        font-size: 12px;
    }

    @media print {
        .btn-print,
        .btn-back {
            display: none;
        }
        body {
            background: #fff;
        }
        .struk-container {
            box-shadow: none;
            border: none;
            width: 100%;
        }
    }
</style>

<div class="container py-4">
    <div class="struk-container">
        <div class="struk-header">
            <h5>NesMedia</h5>
            <p>Jl. Arief Rahman Hakim No. 45 - Subang</p>
            <p>Telp: (022) 555-9090</p>
        </div>

        <div class="struk-divider"></div>

        <p><strong>Kode:</strong> {{ $transaksi->kode_transaksi }}</p>
        <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d/m/Y H:i') }}</p>
        <p><strong>Kasir:</strong> {{ $transaksi->kasir->name ?? '-' }}</p>

        <div class="struk-divider"></div>

        <div class="struk-body">
            <table>
                <tbody>
                    @foreach($transaksi->detailTransaksi as $item)
                        <tr>
                            {{-- ✅ PERBAIKAN: Gunakan nama_buku dari snapshot, bukan dari relasi --}}
                            <td colspan="2">{{ $item->nama_buku }}</td>
                        </tr>
                        <tr>
                            <td>{{ $item->jumlah }} x {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->subtotal_setelah_diskon ?? $item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="struk-divider"></div>

        <p class="struk-total">TOTAL : Rp {{ number_format($transaksi->total, 0, ',', '.') }}</p>

        <div class="struk-divider"></div>

        <div class="struk-footer">
            <p>Terima kasih telah berbelanja di Toko Buku</p>
            <p><strong>NesMedia</strong></p>
            <p>Semoga hari Anda menyenangkan!</p>
        </div>
    </div>

    <div class="d-flex justify-content-between mt-3">
        <a href="{{ route('kasir.dashboard') }}" class="btn btn-secondary btn-back">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <button onclick="window.print()" class="btn btn-primary btn-print">
            <i class="bi bi-printer"></i> Cetak Struk
        </button>
    </div>
</div>
@endsection