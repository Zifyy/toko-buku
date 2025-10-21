<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'NesMedia - Kasir')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css" rel="stylesheet">

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
            width: 260px;
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
            left: 250px;
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
            box-shadow: 0 2px 8px rgba(0, 119, 182, 0.3);
        }

        .sidebar.collapsed ~ .toggle-btn {
            left: 110px;
        }

        .toggle-btn:hover {
            background: #005f8d;
            transform: scale(1.05);
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
            position: relative;
            overflow: hidden;
        }

        .sidebar a i {
            min-width: 28px;
            text-align: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .sidebar a span {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            flex: 1;
        }

        .sidebar.collapsed a {
            justify-content: center;
            padding: 12px 18px;
        }

        .sidebar a:hover {
            background: #e9f5ff;
            color: #0077b6;
            transform: translateX(3px);
        }

        .sidebar .active {
            background: linear-gradient(90deg, #00c6ff, #0077b6);
            color: #fff !important;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0, 119, 182, 0.3);
        }

        .sidebar .active:hover {
            background: linear-gradient(90deg, #00c6ff, #0077b6);
            color: #fff !important;
            transform: translateX(3px);
        }

        .sidebar .active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 70%;
            background: #fff;
            border-radius: 0 4px 4px 0;
        }

        .sidebar.collapsed a span,
        .sidebar.collapsed button span {
            display: none;
        }

        /* Konten utama */
        .content {
            margin-left: 260px;
            padding: 30px;
            transition: margin-left 0.3s ease;
        }

        body.sidebar-collapsed .content {
            margin-left: 120px !important;
        }

        /* Logout button styling */
        .sidebar form button {
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .sidebar form button:hover {
            background: #dc3545 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }

        .sidebar.collapsed form button {
            padding: 10px;
        }

        .sidebar.collapsed form button i {
            margin: 0;
        }

        /* Scrollbar styling */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #0077b6;
            border-radius: 10px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #005f8d;
        }

        /* ========== LIQUID GLASS MORPHISM ALERT STYLE ========== */
        
        /* Backdrop with blur */
        .swal2-container.swal2-backdrop-show {
            background: rgba(0, 0, 0, 0.6) !important;
            backdrop-filter: blur(10px) !important;
            -webkit-backdrop-filter: blur(10px) !important;
        }

        /* Glass morphism popup */
        .swal2-popup {
            font-family: 'Poppins', sans-serif !important;
            background: rgba(255, 255, 255, 0.85) !important;
            backdrop-filter: blur(20px) saturate(180%) !important;
            -webkit-backdrop-filter: blur(20px) saturate(180%) !important;
            border-radius: 28px !important;
            padding: 3rem 2.5rem !important;
            box-shadow: 
                0 8px 32px rgba(0, 119, 182, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.4) inset,
                0 0 80px rgba(0, 198, 255, 0.1) !important;
            border: 1px solid rgba(255, 255, 255, 0.5) !important;
            position: relative !important;
            overflow: hidden !important;
        }

        /* Liquid effect background */
        .swal2-popup::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 30% 50%, rgba(0, 198, 255, 0.15) 0%, transparent 50%),
                        radial-gradient(circle at 70% 50%, rgba(0, 119, 182, 0.1) 0%, transparent 50%);
            animation: liquidMove 8s ease-in-out infinite;
            pointer-events: none;
            z-index: -1;
        }

        @keyframes liquidMove {
            0%, 100% {
                transform: translate(0, 0) rotate(0deg);
            }
            33% {
                transform: translate(10%, 10%) rotate(120deg);
            }
            66% {
                transform: translate(-5%, 5%) rotate(240deg);
            }
        }

        /* Glass overlay effect */
        .swal2-popup::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.4) 0%, 
                rgba(255, 255, 255, 0.1) 50%, 
                rgba(255, 255, 255, 0.2) 100%);
            pointer-events: none;
            z-index: -1;
        }

        /* Title styling */
        .swal2-title {
            font-size: 2rem !important;
            font-weight: 700 !important;
            color: #1a1a1a !important;
            margin: 0.5rem 0 1rem 0 !important;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: relative;
            z-index: 1;
        }

        /* HTML content styling */
        .swal2-html-container {
            font-size: 1.05rem !important;
            color: #555 !important;
            margin: 1.5rem 0 2rem 0 !important;
            line-height: 1.7 !important;
            position: relative;
            z-index: 1;
        }

        /* Custom icon container */
        .custom-icon-container {
            width: 90px;
            height: 90px;
            margin: 0 auto 1.5rem auto;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Glass circle background for icon */
        .icon-glass-bg {
            position: absolute;
            width: 100%;
            height: 100%;
            background: rgba(255, 152, 0, 0.1);
            border-radius: 50%;
            border: 2px solid rgba(255, 152, 0, 0.3);
            backdrop-filter: blur(10px);
            animation: pulseGlow 2s ease-in-out infinite;
        }

        @keyframes pulseGlow {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 20px rgba(255, 152, 0, 0.3);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 0 30px rgba(255, 152, 0, 0.5);
            }
        }

        /* Icon styling */
        .custom-alert-icon {
            position: relative;
            z-index: 2;
            font-size: 3rem;
            color: #ff9800;
            animation: iconFloat 3s ease-in-out infinite;
            filter: drop-shadow(0 4px 10px rgba(255, 152, 0, 0.3));
        }

        @keyframes iconFloat {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            25% {
                transform: translateY(-5px) rotate(-3deg);
            }
            75% {
                transform: translateY(-3px) rotate(3deg);
            }
        }

        /* Particles around icon */
        .icon-particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 152, 0, 0.6);
            border-radius: 50%;
            animation: particleFloat 3s ease-in-out infinite;
        }

        .icon-particle:nth-child(1) {
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .icon-particle:nth-child(2) {
            top: 20%;
            right: 10%;
            animation-delay: 0.5s;
        }

        .icon-particle:nth-child(3) {
            bottom: 15%;
            left: 15%;
            animation-delay: 1s;
        }

        .icon-particle:nth-child(4) {
            bottom: 10%;
            right: 20%;
            animation-delay: 1.5s;
        }

        @keyframes particleFloat {
            0%, 100% {
                transform: translateY(0) scale(1);
                opacity: 0.3;
            }
            50% {
                transform: translateY(-15px) scale(1.2);
                opacity: 0.8;
            }
        }

        /* Button actions */
        .swal2-actions {
            gap: 1rem !important;
            margin-top: 2.5rem !important;
            position: relative;
            z-index: 1;
        }

        /* Glass morphism buttons */
        .swal2-confirm, .swal2-cancel {
            border: none !important;
            border-radius: 16px !important;
            padding: 14px 36px !important;
            font-weight: 600 !important;
            font-size: 1rem !important;
            position: relative !important;
            overflow: hidden !important;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
            backdrop-filter: blur(10px) !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1) !important;
        }

        /* Confirm button - Danger glass */
        .swal2-confirm {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.9), rgba(200, 35, 51, 0.9)) !important;
            color: #fff !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
        }

        .swal2-confirm::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .swal2-confirm:hover::before {
            left: 100%;
        }

        .swal2-confirm:hover {
            transform: translateY(-3px) scale(1.02) !important;
            box-shadow: 0 8px 30px rgba(220, 53, 69, 0.4) !important;
        }

        .swal2-confirm:active {
            transform: translateY(-1px) scale(0.98) !important;
        }

        /* Cancel button - Neutral glass */
        .swal2-cancel {
            background: linear-gradient(135deg, rgba(108, 117, 125, 0.8), rgba(90, 98, 104, 0.8)) !important;
            color: #fff !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
        }

        .swal2-cancel::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .swal2-cancel:hover::before {
            left: 100%;
        }

        .swal2-cancel:hover {
            transform: translateY(-3px) scale(1.02) !important;
            box-shadow: 0 8px 30px rgba(108, 117, 125, 0.3) !important;
        }

        .swal2-cancel:active {
            transform: translateY(-1px) scale(0.98) !important;
        }

        /* Button icons animation */
        .btn-icon {
            display: inline-block;
            transition: transform 0.3s ease;
        }

        .swal2-confirm:hover .btn-icon {
            transform: translateX(4px);
        }

        .swal2-cancel:hover .btn-icon {
            transform: rotate(90deg);
        }

        /* Loading state - Glass loader */
        .loading-glass-container {
            position: relative;
            width: 80px;
            height: 80px;
            margin: 0 auto;
        }

        .loading-glass-bg {
            position: absolute;
            width: 100%;
            height: 100%;
            background: rgba(0, 119, 182, 0.1);
            border-radius: 50%;
            border: 2px solid rgba(0, 119, 182, 0.3);
            backdrop-filter: blur(10px);
        }

        .loading-spinner {
            position: relative;
            z-index: 2;
            font-size: 2.5rem;
            color: #0077b6;
            animation: smoothRotate 1.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) infinite;
            filter: drop-shadow(0 4px 10px rgba(0, 119, 182, 0.3));
        }

        @keyframes smoothRotate {
            0% {
                transform: rotate(0deg) scale(1);
            }
            50% {
                transform: rotate(180deg) scale(1.1);
            }
            100% {
                transform: rotate(360deg) scale(1);
            }
        }

        /* Popup animation */
        @keyframes glassSlideIn {
            0% {
                opacity: 0;
                transform: scale(0.7) translateY(-30px);
                filter: blur(10px);
            }
            60% {
                transform: scale(1.03) translateY(0);
            }
            100% {
                opacity: 1;
                transform: scale(1) translateY(0);
                filter: blur(0);
            }
        }

        @keyframes glassSlideOut {
            0% {
                opacity: 1;
                transform: scale(1) translateY(0);
                filter: blur(0);
            }
            100% {
                opacity: 0;
                transform: scale(0.7) translateY(30px);
                filter: blur(10px);
            }
        }

        .swal2-show {
            animation: glassSlideIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards !important;
        }

        .swal2-hide {
            animation: glassSlideOut 0.3s cubic-bezier(0.4, 0, 1, 1) forwards !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 120px;
            }
            
            .sidebar .logo h5,
            .sidebar a span,
            .sidebar button span {
                display: none;
            }
            
            .content {
                margin-left: 120px;
            }
            
            .toggle-btn {
                left: 110px;
            }
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar col-md-2 d-none d-md-block">
            <div class="logo">
                <i class="bi bi-cash-stack"></i>
                <h5>Kasir NesMedia</h5>
            </div>
            <div class="p-2">
                <a href="{{ route('kasir.dashboard') }}"
                   class="{{ request()->routeIs('kasir.dashboard') ? 'active' : '' }}"
                   data-bs-toggle="tooltip"
                   data-bs-placement="right"
                   title="Dashboard">
                    <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
                </a>

                <a href="{{ route('kasir.transaksi') }}"
                   class="{{ request()->routeIs('kasir.transaksi*') ? 'active' : '' }}"
                   data-bs-toggle="tooltip"
                   data-bs-placement="right"
                   title="Transaksi">
                    <i class="bi bi-receipt-cutoff"></i> <span>Transaksi</span>
                </a>

                <a href="{{ route('kasir.riwayat') }}"
                   class="{{ request()->routeIs('kasir.riwayat*') ? 'active' : '' }}"
                   data-bs-toggle="tooltip"
                   data-bs-placement="right"
                   title="Riwayat Transaksi">
                    <i class="bi bi-clock-history"></i> <span>Riwayat Transaksi</span>
                </a>

                <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="mt-3 px-2">
                    @csrf
                    <button type="button"
                            id="logoutBtn"
                            class="btn btn-danger w-100 d-flex align-items-center justify-content-center gap-2" 
                            data-bs-toggle="tooltip"
                            data-bs-placement="right"
                            title="Logout">
                        <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
                    </button>
                </form>
            </div>
        </nav>

        <!-- Toggle button -->
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
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>

<script>
    const sidebar = document.getElementById('sidebar');

    function toggleSidebar() {
        sidebar.classList.toggle('collapsed');
        document.body.classList.toggle('sidebar-collapsed');
        localStorage.setItem('sidebar-collapsed-kasir', sidebar.classList.contains('collapsed'));
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Restore sidebar state
        if (localStorage.getItem('sidebar-collapsed-kasir') === 'true') {
            sidebar.classList.add('collapsed');
            document.body.classList.add('sidebar-collapsed');
        }

        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (el) {
            return new bootstrap.Tooltip(el, {
                trigger: 'hover'
            });
        });

        // Logout confirmation with Liquid Glass effect
        document.getElementById('logoutBtn').addEventListener('click', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Konfirmasi Logout',
                html: `
                    <div class="custom-icon-container">
                        <div class="icon-glass-bg"></div>
                        <div class="icon-particle"></div>
                        <div class="icon-particle"></div>
                        <div class="icon-particle"></div>
                        <div class="icon-particle"></div>
                        <i class="bi bi-exclamation-triangle-fill custom-alert-icon"></i>
                    </div>
                    <p style="font-size: 1.1rem; margin-top: 1rem; color: #333; font-weight: 500;">
                        Apakah Anda yakin ingin keluar dari sistem kasir?
                    </p>
                    <p style="color: #888; font-size: 0.95rem; margin-top: 0.5rem;">
                        Anda perlu login kembali untuk melakukan transaksi
                    </p>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-box-arrow-right btn-icon"></i> Ya, Logout Sekarang',
                cancelButtonText: '<i class="bi bi-x-circle btn-icon"></i> Tidak, Tetap Disini',
                reverseButtons: true,
                allowOutsideClick: false,
                allowEscapeKey: true,
                focusCancel: true,
                showClass: {
                    popup: 'swal2-show',
                    backdrop: 'swal2-backdrop-show'
                },
                hideClass: {
                    popup: 'swal2-hide'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show glass loading state
                    Swal.fire({
                        title: 'Sedang Logout',
                        html: `
                            <div class="loading-glass-container">
                                <div class="loading-glass-bg"></div>
                                <i class="bi bi-arrow-clockwise loading-spinner"></i>
                            </div>
                            <p style="color: #666; margin-top: 1.5rem; font-size: 1rem;">
                                Mohon tunggu sebentar...
                            </p>
                        `,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false,
                        showConfirmButton: false,
                        showClass: {
                            popup: 'swal2-show'
                        }
                    });
                    
                    // Smooth delay for better UX
                    setTimeout(() => {
                        document.getElementById('logoutForm').submit();
                    }, 1000);
                }
            });
        });
    });
</script>
</body>
</html>