<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'NesMedia - Admin')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            color: #333;
            transition: margin-left 0.3s ease;
        }

        /* Sidebar default */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 240px;
            background: #ffffff;
            border-right: 1px solid #e6e6e6;
            box-shadow: 2px 0 6px rgba(0, 0, 0, 0.05);
            overflow-y: auto;
            z-index: 1000;
            transition: width 0.3s ease;
        }

        .sidebar.collapsed {
            width: 120px;
        }

        .sidebar .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 25px 10px;
            border-bottom: 1px solid #eaeaea;
            transition: all 0.3s ease;
        }

        .sidebar .logo i {
            font-size: 2rem;
            color: #0077b6;
            flex-shrink: 0;
        }

        .sidebar .logo h5 {
            margin: 0 0 0 8px;
            font-weight: 700;
            color: #0077b6;
            transition: opacity 0.3s ease, transform 0.3s ease;
            white-space: nowrap;
        }

        .sidebar.collapsed .logo h5 {
            opacity: 0;
            transform: translateX(-20px);
            visibility: hidden;
            width: 0;
            margin: 0;
        }

        .toggle-btn {
            position: fixed;
            top: 20px;
            left: 230px;
            background: #0077b6;
            color: #fff;
            border-radius: 50%;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 1200;
        }

        .sidebar.collapsed ~ .toggle-btn {
            left: 110px;
        }

        .toggle-btn:hover {
            background: #005f8d;
        }

        .sidebar .p-2 {
            margin-top: 20px;
        }

        .sidebar a {
            color: #555;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 18px;
            border-radius: 8px;
            margin: 6px 10px;
            font-weight: 500;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .sidebar a i {
            min-width: 28px;
            text-align: center;
            font-size: 1.3rem;
        }

        .sidebar.collapsed a {
            justify-content: center;
        }

        .sidebar a:hover {
            background: #e9f5ff;
            color: #0077b6;
        }

        .sidebar .active {
            background: linear-gradient(90deg, #00c6ff, #0077b6);
            color: #fff !important;
            font-weight: 600;
        }

        .sidebar .active:hover {
            background: linear-gradient(90deg, #00c6ff, #0077b6);
            color: #fff !important;
        }

        .sidebar.collapsed a span,
        .sidebar.collapsed button span {
            display: none;
        }

        /* Konten utama */
        .content {
            margin-left: 240px;
            padding: 30px;
            transition: margin-left 0.3s ease;
        }

        body.sidebar-collapsed .content {
            margin-left: 120px !important;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar col-md-2 d-none d-md-block">
            <div class="logo">
                <i class="bi bi-journal-bookmark-fill"></i>
                <h5>NesMedia</h5>
            </div>
            <div class="p-2">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->is('admin') ? 'active' : '' }}" data-bs-toggle="tooltip" title="Dashboard">
                    <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
                </a>
                <a href="{{ route('buku.index') }}" class="{{ request()->is('admin/buku*') ? 'active' : '' }}" data-bs-toggle="tooltip" title="Data Buku">
                    <i class="bi bi-book"></i> <span>Data Buku</span>
                </a>
                <a href="{{ route('kategori.index') }}" class="{{ request()->is('admin/kategori*') ? 'active' : '' }}" data-bs-toggle="tooltip" title="Kategori">
                    <i class="bi bi-tags"></i> <span>Kategori</span>
                </a>
                <a href="{{ route('detail-buku.index') }}" class="{{ request()->is('admin/detail-buku*') ? 'active' : '' }}" data-bs-toggle="tooltip" title="Detail Buku">
                    <i class="bi bi-card-list"></i> <span>Detail Buku</span>
                </a>
                <a href="{{ route('user.index') }}" class="{{ request()->is('admin/user*') ? 'active' : '' }}" data-bs-toggle="tooltip" title="Kelola User">
                    <i class="bi bi-people"></i> <span>Kelola User</span>
                </a>
                <a href="{{ route('admin.laporan') }}" class="{{ request()->is('admin/laporan') ? 'active' : '' }}" data-bs-toggle="tooltip" title="Laporan">
                    <i class="bi bi-graph-up"></i> <span>Laporan</span>
                </a>
                <form action="/logout" method="POST" class="mt-3 px-2">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100 d-flex align-items-center justify-content-center gap-2" data-bs-toggle="tooltip" title="Logout">
                        <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
                    </button>
                </form>
            </div>
        </nav>

        <!-- Toggle button floating -->
        <div class="toggle-btn" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </div>

        <!-- Content -->
        <main class="content col-md-10 ms-sm-auto">
            @yield('content')
        </main>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const sidebar = document.getElementById('sidebar');

    function toggleSidebar() {
        sidebar.classList.toggle('collapsed');
        document.body.classList.toggle('sidebar-collapsed');
        localStorage.setItem('sidebar-collapsed', sidebar.classList.contains('collapsed'));
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (localStorage.getItem('sidebar-collapsed') === 'true') {
            sidebar.classList.add('collapsed');
            document.body.classList.add('sidebar-collapsed');
        }

        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (el) {
            return new bootstrap.Tooltip(el);
        });
    });
</script>
</body>
</html>