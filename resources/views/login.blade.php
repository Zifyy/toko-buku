<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NesMedia - Login</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #e8edf3;
            overflow: hidden;
            position: relative;
        }

        /* Animated Blobs Background */
        .blob-container {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.3;
            will-change: transform;
        }

        .blob:nth-child(1) {
            width: 600px;
            height: 600px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            top: -15%;
            left: -10%;
            animation: float1 15s infinite ease-in-out alternate;
        }

        .blob:nth-child(2) {
            width: 500px;
            height: 500px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            top: 20%;
            right: -8%;
            animation: float2 18s infinite ease-in-out alternate;
        }

        .blob:nth-child(3) {
            width: 550px;
            height: 550px;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            bottom: -12%;
            left: 25%;
            animation: float3 20s infinite ease-in-out alternate;
        }

        .blob:nth-child(4) {
            width: 450px;
            height: 450px;
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            top: 30%;
            right: 25%;
            animation: float4 12s infinite ease-in-out alternate;
        }

        .blob:nth-child(5) {
            width: 520px;
            height: 520px;
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            bottom: 15%;
            right: -5%;
            animation: float5 16s infinite ease-in-out alternate;
        }

        @keyframes float1 {
            0% {
                transform: translate(0, 0) rotate(0deg) scale(1);
            }
            33% {
                transform: translate(120px, -80px) rotate(120deg) scale(1.1);
            }
            66% {
                transform: translate(-60px, 120px) rotate(240deg) scale(0.95);
            }
            100% {
                transform: translate(80px, -40px) rotate(360deg) scale(1.05);
            }
        }

        @keyframes float2 {
            0% {
                transform: translate(0, 0) rotate(0deg) scale(1);
            }
            33% {
                transform: translate(-100px, 90px) rotate(-100deg) scale(1.08);
            }
            66% {
                transform: translate(70px, -110px) rotate(-200deg) scale(0.92);
            }
            100% {
                transform: translate(-50px, 60px) rotate(-360deg) scale(1.03);
            }
        }

        @keyframes float3 {
            0% {
                transform: translate(0, 0) rotate(0deg) scale(1);
            }
            33% {
                transform: translate(-90px, -100px) rotate(90deg) scale(1.12);
            }
            66% {
                transform: translate(110px, 80px) rotate(180deg) scale(0.9);
            }
            100% {
                transform: translate(-70px, -50px) rotate(270deg) scale(1.06);
            }
        }

        @keyframes float4 {
            0% {
                transform: translate(0, 0) rotate(0deg) scale(1);
            }
            33% {
                transform: translate(95px, 105px) rotate(-90deg) scale(1.15);
            }
            66% {
                transform: translate(-115px, -75px) rotate(-180deg) scale(0.88);
            }
            100% {
                transform: translate(65px, 85px) rotate(-270deg) scale(1.08);
            }
        }

        @keyframes float5 {
            0% {
                transform: translate(0, 0) rotate(0deg) scale(1);
            }
            33% {
                transform: translate(-105px, -95px) rotate(150deg) scale(1.1);
            }
            66% {
                transform: translate(85px, 115px) rotate(300deg) scale(0.93);
            }
            100% {
                transform: translate(-55px, -65px) rotate(450deg) scale(1.04);
            }
        }

        .login-wrapper {
            width: 100%;
            max-width: 460px;
            padding: 20px;
            position: relative;
            z-index: 10;
        }

        /* Enhanced Liquid Glass Card */
        .login-card {
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(60px) saturate(200%);
            -webkit-backdrop-filter: blur(60px) saturate(200%);
            border: 2px solid rgba(255, 255, 255, 0.6);
            border-radius: 40px;
            padding: 3.5rem 3rem;
            box-shadow: 
                0 8px 32px 0 rgba(15, 23, 42, 0.08),
                0 2px 8px 0 rgba(15, 23, 42, 0.04),
                inset 0 2px 4px 0 rgba(255, 255, 255, 0.95),
                inset 0 -2px 4px 0 rgba(255, 255, 255, 0.6);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .login-card:hover {
            transform: translateY(-10px);
            box-shadow: 
                0 20px 60px 0 rgba(15, 23, 42, 0.12),
                0 4px 16px 0 rgba(15, 23, 42, 0.08),
                inset 0 2px 4px 0 rgba(255, 255, 255, 1),
                inset 0 -2px 4px 0 rgba(255, 255, 255, 0.7);
            border-color: rgba(255, 255, 255, 0.8);
        }

        /* Glossy edge highlight */
        .login-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, 
                transparent, 
                rgba(255, 255, 255, 0.95), 
                transparent);
            border-radius: 40px 40px 0 0;
        }

        .icon-box {
            width: 90px;
            height: 90px;
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(30px);
            border: 2px solid rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 1.5rem auto;
            box-shadow: 
                0 8px 24px rgba(15, 23, 42, 0.08),
                inset 0 2px 4px rgba(255, 255, 255, 0.95),
                inset 0 -2px 4px rgba(255, 255, 255, 0.6);
            transition: all 0.3s ease;
        }

        .icon-box:hover {
            transform: scale(1.05);
            background: rgba(255, 255, 255, 0.55);
        }

        .icon-box i {
            font-size: 40px;
            color: #3b82f6;
            filter: drop-shadow(0 2px 4px rgba(59, 130, 246, 0.3));
        }

        .brand-title {
            font-size: 2.8rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 0.5rem;
            color: #3b82f6;
            letter-spacing: -1px;
            filter: drop-shadow(0 2px 4px rgba(59, 130, 246, 0.2));
        }

        .subtitle {
            text-align: center;
            font-size: 1rem;
            color: #475569;
            margin-bottom: 2.5rem;
            font-weight: 500;
        }

        /* Animated Input Container */
        .input-container {
            position: relative;
            margin-bottom: 1.8rem;
        }

        .input-container:last-of-type {
            margin-bottom: 2.5rem;
        }

        /* Liquid Glass Input */
        .form-control {
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(30px);
            border: 2px solid rgba(255, 255, 255, 0.6);
            border-radius: 20px;
            color: #1e293b;
            padding: 1.1rem 1.3rem;
            font-size: 1rem;
            width: 100%;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 
                inset 0 2px 4px rgba(15, 23, 42, 0.04),
                0 1px 2px rgba(255, 255, 255, 0.95);
            font-weight: 500;
        }

        .form-control::placeholder {
            color: transparent;
        }

        .form-control:focus,
        .form-control:valid {
            background: rgba(255, 255, 255, 0.55);
            border-color: rgba(255, 255, 255, 0.8);
            box-shadow: 
                0 0 0 4px rgba(255, 255, 255, 0.25),
                inset 0 2px 4px rgba(15, 23, 42, 0.04),
                0 2px 4px rgba(255, 255, 255, 0.95);
            outline: none;
        }

        .form-control:hover {
            border-color: rgba(255, 255, 255, 0.75);
            background: rgba(255, 255, 255, 0.5);
        }

        /* Animated Floating Label */
        .floating-label {
            position: absolute;
            left: 1.3rem;
            top: 1.1rem;
            pointer-events: none;
            transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            font-weight: 600;
            color: #64748b;
            display: flex;
            font-size: 1rem;
        }

        .floating-label span {
            display: inline-block;
            min-width: 5px;
            transition: 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .form-control:focus + .floating-label span,
        .form-control:valid + .floating-label span {
            color: #3b82f6;
            transform: translateY(-46px);
        }

        /* Stagger animation for each letter */
        .floating-label span:nth-child(1) { transition-delay: 0s; }
        .floating-label span:nth-child(2) { transition-delay: 0.02s; }
        .floating-label span:nth-child(3) { transition-delay: 0.04s; }
        .floating-label span:nth-child(4) { transition-delay: 0.06s; }
        .floating-label span:nth-child(5) { transition-delay: 0.08s; }
        .floating-label span:nth-child(6) { transition-delay: 0.1s; }
        .floating-label span:nth-child(7) { transition-delay: 0.12s; }
        .floating-label span:nth-child(8) { transition-delay: 0.14s; }
        .floating-label span:nth-child(9) { transition-delay: 0.16s; }
        .floating-label span:nth-child(10) { transition-delay: 0.18s; }
        .floating-label span:nth-child(11) { transition-delay: 0.2s; }
        .floating-label span:nth-child(12) { transition-delay: 0.22s; }
        .floating-label span:nth-child(13) { transition-delay: 0.24s; }
        .floating-label span:nth-child(14) { transition-delay: 0.26s; }

        .form-control:focus + .floating-label,
        .form-control:valid + .floating-label {
            top: 1.1rem;
            left: 1.3rem;
            font-size: 0.75rem;
            background: transparent;
            backdrop-filter: none;
            border-radius: 0;
            box-shadow: none;
        }

        /* Enhanced Liquid Glass Button */
        .btn-login {
            width: 100%;
            border: none;
            border-radius: 20px;
            padding: 1.1rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            font-size: 1.05rem;
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(30px);
            color: #1e293b;
            border: 2px solid rgba(255, 255, 255, 0.7);
            box-shadow: 
                0 4px 16px rgba(15, 23, 42, 0.08),
                0 2px 4px rgba(15, 23, 42, 0.04),
                inset 0 2px 4px rgba(255, 255, 255, 0.95),
                inset 0 -2px 4px rgba(255, 255, 255, 0.6);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            background: rgba(255, 255, 255, 0.6);
            transform: translateY(-3px);
            box-shadow: 
                0 12px 32px rgba(15, 23, 42, 0.12),
                0 4px 8px rgba(15, 23, 42, 0.08),
                inset 0 2px 4px rgba(255, 255, 255, 1),
                inset 0 -2px 4px rgba(255, 255, 255, 0.7);
            border-color: rgba(255, 255, 255, 0.85);
        }

        .btn-login:active {
            transform: translateY(-1px);
            box-shadow: 
                0 4px 16px rgba(15, 23, 42, 0.08),
                inset 0 2px 4px rgba(15, 23, 42, 0.06);
        }

        /* Alert Styling */
        .alert {
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(30px);
            border: 2px solid rgba(255, 255, 255, 0.7);
            color: #1e293b;
            animation: slideDown 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 
                0 4px 16px rgba(15, 23, 42, 0.08),
                inset 0 2px 4px rgba(255, 255, 255, 0.95);
            margin-bottom: 1.5rem;
        }

        .alert-danger {
            background: rgba(254, 242, 242, 0.65);
            border-color: rgba(239, 68, 68, 0.4);
            color: #991b1b;
        }

        .alert-success {
            background: rgba(240, 253, 244, 0.65);
            border-color: rgba(34, 197, 94, 0.4);
            color: #14532d;
        }

        .alert strong {
            font-weight: 700;
        }

        .btn-close {
            opacity: 0.5;
        }

        .btn-close:hover {
            opacity: 0.8;
        }

        .invalid-feedback {
            color: #dc2626;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            font-weight: 600;
            display: block;
        }

        .is-invalid {
            border-color: rgba(239, 68, 68, 0.6) !important;
            background: rgba(254, 242, 242, 0.5) !important;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 576px) {
            .login-card {
                padding: 2.5rem 2rem;
                border-radius: 32px;
            }

            .brand-title {
                font-size: 2.2rem;
            }

            .icon-box {
                width: 75px;
                height: 75px;
            }
        }
    </style>
</head>

<body>
    <!-- Animated Blobs Background -->
    <div class="blob-container">
        <div class="blob"></div>
        <div class="blob"></div>
        <div class="blob"></div>
        <div class="blob"></div>
        <div class="blob"></div>
    </div>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="icon-box">
                <i class="bi bi-journal-bookmark-fill"></i>
            </div>
            <div class="brand-title">NesMedia</div>
            <div class="subtitle">Silakan login untuk melanjutkan</div>

            <!-- Alert Error -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Login Gagal!</strong>
                    <ul class="mb-0 mt-2 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Alert untuk pesan error dari session -->
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Error!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Alert untuk pesan sukses (opsional) -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <strong>Berhasil!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="input-container">
                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           placeholder=" " value="{{ old('email') }}" required autofocus>
                    <label for="email" class="floating-label">
                        <span>A</span><span>l</span><span>a</span><span>m</span><span>a</span><span>t</span><span> </span><span>E</span><span>m</span><span>a</span><span>i</span><span>l</span>
                    </label>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-container">
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror"
                           placeholder=" " required>
                    <label for="password" class="floating-label">
                        <span>K</span><span>a</span><span>t</span><span>a</span><span> </span><span>S</span><span>a</span><span>n</span><span>d</span><span>i</span>
                    </label>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-login">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Login
                </button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>