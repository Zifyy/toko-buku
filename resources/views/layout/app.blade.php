<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NesMedia - @yield('title', 'Dashboard')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts (Poppins) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-weight: 400; /* pastikan default normal */
            background: #f8f9fa; /* cerah */
            color: #333;
            margin: 0;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 240px;
            height: 100%;
            background: #ffffff;
            border-right: 1px solid #e0e0e0;
            padding-top: 20px;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.05);
            overflow-y: auto;
        }

        .sidebar .logo {
            text-align: center;
            padding: 20px 10px;
            border-bottom: 1px solid #e9ecef;
        }

        .sidebar .logo h5 {
            margin-top: 10px;
            font-weight: 700;
            color: #0077b6;
        }

        .sidebar a {
            color: #555;
            text-decoration: none;
            display: block;
            padding: 12px 18px;
            border-radius: 8px;
            margin: 5px 10px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .sidebar a:hover {
            background: #e9f5ff;
            color: #0077b6;
        }

        .sidebar .active {
            background: linear-gradient(90deg, #00d4ff, #0077b6);
            color: #fff !important;
            font-weight: 600;
        }

        /* Content */
        .content {
            margin-left: 240px; /* sesuai lebar sidebar */
            padding: 30px;
            min-height: 100vh;
        }

        .card {
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            border-radius: 12px 12px 0 0 !important;
        }

        /* Table */
        .table {
            color: #333;
        }

        .table thead {
            background: #f1f9ff;
            color: #0077b6;
        }

        .table thead th {
            font-weight: 600; /* judul kolom tetap bold */
        }

        .table tbody td {
            font-weight: 400 !important; /* isi tabel normal */
        }

        .table tbody tr:hover {
            background: #f9f9f9;
        }

        .btn-sm {
            border-radius: 8px;
            padding: 4px 10px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="logo">
            <i class="bi bi-journal-bookmark-fill" style="font-size: 2rem; color:#0077b6;"></i>
            <h5>NesMedia</h5>
        </div>
        <div class="p-2">
            <a href="{{ route('admin.buku.index') }}" class="{{ request()->is('admin') ? 'active' : '' }}">
                <i class="bi bi-book me-2"></i> Data Buku
            </a>
            <a href="{{ route('kategori.index') }}" class="{{ request()->is('admin/kategori*') ? 'active' : '' }}">
                <i class="bi bi-tags me-2"></i> Kategori
            </a>
            <a href="{{ route('admin.laporan') }}" class="{{ request()->is('admin/laporan') ? 'active' : '' }}">
                <i class="bi bi-graph-up me-2"></i> Laporan
            </a>
            <form action="{{ route('logout') }}" method="POST" class="mt-3 px-2">
                @csrf
                <button type="submit" class="btn btn-danger w-100">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </button>
            </form>
        </div>
    </nav>

    <!-- Content -->
    <main class="content">
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
