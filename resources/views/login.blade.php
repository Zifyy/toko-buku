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

    <style>
        body {
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;

            /* AI Gradient vibes */
            background: linear-gradient(-45deg,
                #1e3c72,   /* deep blue */
                #2a5298,   /* royal blue */
                #6a89cc,   /* soft pastel blue */
                #9b59b6,   /* elegant purple */
                #16a085,   /* teal green */
                #2980b9,   /* bright blue */
                #8e44ad,   /* violet */
                #2c3e50    /* dark grey-blue */
            );
            background-size: 600% 600%;
            animation: gradientMove 18s ease infinite;

            font-family: 'Poppins', sans-serif;
        }

        @keyframes gradientMove {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 18px;
            padding: 2.5rem;
            color: #333;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.25);
        }

        .brand-title {
            font-size: 2rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 0.5rem;
            color: #2a5298;
        }

        .subtitle {
            text-align: center;
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 2rem;
        }

        .form-label {
            font-weight: 600;
            color: #333;
        }

        .form-control {
            background: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 12px;
            color: #333;
            padding: 0.8rem;
        }

        .form-control:focus {
            background: #fff;
            color: #333;
            border-color: #6a89cc;
            box-shadow: 0 0 0 3px rgba(106, 137, 204, 0.25);
        }

        .btn-login {
            width: 100%;
            border: none;
            border-radius: 12px;
            padding: 0.9rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            background: linear-gradient(90deg, #2a5298, #9b59b6);
            color: #fff;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: linear-gradient(90deg, #9b59b6, #2a5298);
            transform: scale(1.03);
        }

        .icon-box {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #eef3fb;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 1rem auto;
            box-shadow: 0 4px 10px rgba(106, 137, 204, 0.3);
        }

        .icon-box i {
            font-size: 28px;
            color: #2a5298;
        }

        .alert {
            border-radius: 12px;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
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

                <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           placeholder="Masukkan email anda" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror"
                           placeholder="Masukkan password anda" required>
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