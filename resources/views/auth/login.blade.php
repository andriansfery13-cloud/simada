<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMADA</title>

    <!-- Google Fonts: Outfit for a highly elegant, clean, and modern look -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #4F46E5;
            --primary-light: #818CF8;
            --primary-dark: #3730A3;
            --secondary: #0EA5E9;
            --accent: #F43F5E;
            --glass-bg: rgba(255, 255, 255, 0.9);
            --glass-border: rgba(255, 255, 255, 0.6);
            --glass-shadow: rgba(0, 0, 0, 0.05);
        }

        body,
        html {
            margin: 0;
            padding: 0;
            font-family: 'Outfit', sans-serif;
            height: 100%;
            overflow: hidden;
            background-color: #F8FAFC;
            /* Bright background */
        }

        /* Animated Mesh Gradient Background (Bright/Pastel Version) */
        .bg-mesh {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 15% 50%, rgba(99, 102, 241, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 85% 30%, rgba(56, 189, 248, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 50% 80%, rgba(244, 63, 94, 0.1) 0%, transparent 50%);
            background-size: 200% 200%;
            animation: meshAnimation 15s ease infinite alternate;
            z-index: -2;
        }

        .bg-shapes {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            overflow: hidden;
            z-index: -1;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            animation: float 20s infinite ease-in-out alternate;
        }

        .shape-1 {
            width: 500px;
            height: 500px;
            background: rgba(99, 102, 241, 0.15);
            top: -200px;
            left: -100px;
            animation-delay: 0s;
        }

        .shape-2 {
            width: 600px;
            height: 600px;
            background: rgba(56, 189, 248, 0.15);
            bottom: -200px;
            right: -100px;
            animation-delay: -5s;
        }

        .shape-3 {
            width: 400px;
            height: 400px;
            background: rgba(167, 139, 250, 0.15);
            top: 40%;
            left: 60%;
            animation-delay: -10s;
        }

        @keyframes meshAnimation {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 100%;
            }

            100% {
                background-position: 50% 0%;
            }
        }

        @keyframes float {
            0% {
                transform: translate(0, 0) rotate(0deg) scale(1);
            }

            50% {
                transform: translate(50px, 30px) rotate(10deg) scale(1.1);
            }

            100% {
                transform: translate(-30px, 60px) rotate(-5deg) scale(0.9);
            }
        }

        /* Glassmorphism Container */
        .login-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            padding: 20px;
        }

        .login-glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid var(--glass-border);
            border-radius: 32px;
            box-shadow: 0 30px 60px -12px rgba(15, 23, 42, 0.08), 0 0 0 1px rgba(255, 255, 255, 0.5) inset;
            width: 100%;
            max-width: 1100px;
            display: flex;
            overflow: hidden;
            position: relative;
            transform: translateY(0);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .login-glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 40px 70px -15px rgba(15, 23, 42, 0.12), 0 0 0 1px rgba(255, 255, 255, 0.6) inset;
        }

        /* Left Branding Side */
        .login-info {
            padding: 70px 60px;
            flex: 1.2;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.6) 0%, rgba(255, 255, 255, 0.2) 100%);
            border-right: 1px solid rgba(255, 255, 255, 0.5);
        }

        /* Right Form Side */
        .login-form-container {
            padding: 60px 50px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: rgba(255, 255, 255, 0.4);
        }

        /* Custom Logo SVG Styling */
        .logo-container {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .logo-svg {
            width: 58px;
            height: 58px;
            filter: drop-shadow(0 8px 12px rgba(79, 70, 229, 0.2));
        }

        .logo-text {
            font-size: 2.5rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 0;
            line-height: 1;
        }

        /* Form Elements - Elegant Styling */
        .form-floating {
            margin-bottom: 22px;
        }

        .form-control {
            border: 1.5px solid #E2E8F0;
            background: #FFFFFF;
            border-radius: 16px;
            padding: 1.1rem 1.25rem;
            height: auto;
            font-size: 16px;
            color: #1E293B;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
        }

        .form-control:focus {
            background: #FFFFFF;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1), 0 4px 6px rgba(0, 0, 0, 0.02);
        }

        .form-floating>label {
            padding: 1.1rem 1.25rem;
            color: #64748B;
            font-weight: 400;
            font-size: 15px;
        }

        .input-icon-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            right: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            font-size: 1.25rem;
            pointer-events: none;
            transition: color 0.3s ease;
        }

        .form-control:focus+.form-floating>label,
        .form-control:focus~.input-icon {
            color: var(--primary);
            font-weight: 500;
        }

        /* Premium Button */
        .btn-login {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            border-radius: 16px;
            padding: 18px;
            font-weight: 600;
            font-size: 17px;
            letter-spacing: 0.5px;
            width: 100%;
            margin-top: 15px;
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.3);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%);
            opacity: 0;
            z-index: -1;
            transition: opacity 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-3px) scale(1.01);
            box-shadow: 0 15px 30px rgba(79, 70, 229, 0.4);
            color: white;
        }

        .btn-login:hover::before {
            opacity: 1;
        }

        /* Typography & Utilities */
        .tagline {
            font-size: 1.15rem;
            line-height: 1.7;
            color: #475569;
            font-weight: 400;
            margin-bottom: 2.5rem;
            max-width: 420px;
        }

        .title-elegant {
            color: #0F172A;
            font-weight: 700;
            margin-bottom: 0.75rem;
            line-height: 1.2;
        }

        .custom-checkbox .form-check-input {
            width: 1.3em;
            height: 1.3em;
            border-radius: 6px;
            border: 2px solid #CBD5E1;
            cursor: pointer;
            margin-top: 0.15em;
        }

        .custom-checkbox .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .mobile-logo {
            display: none;
        }

        /* Form Titles */
        .form-title {
            font-weight: 700;
            color: #0F172A;
            margin-bottom: 0.5rem;
        }

        .form-subtitle {
            color: #64748B;
            font-weight: 400;
            font-size: 1.05rem;
        }

        /* Responsive Design */
        @media (max-width: 991px) {
            .login-glass-card {
                flex-direction: column;
                max-width: 480px;
                border-radius: 28px;
            }

            .login-info {
                display: none;
                /* Hide branding text on mobile to save space */
            }

            .mobile-logo {
                display: flex;
                flex-direction: column;
                align-items: center;
                margin-bottom: 35px;
                text-align: center;
            }

            .login-form-container {
                padding: 45px 35px;
                background: var(--glass-bg);
            }
        }
    </style>
</head>

<body>
    <!-- Abstract Animated Background (Bright Theme) -->
    <div class="bg-mesh"></div>
    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <div class="login-wrapper">
        <div class="login-glass-card">

            <!-- Left Info Side (Desktop Only) -->
            <div class="login-info d-none d-lg-flex">
                <div class="logo-container">
                    <!-- Redesigned Modern SIMADA Logo -->
                    <svg class="logo-svg" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#4F46E5" />
                                <stop offset="100%" stop-color="#0EA5E9" />
                            </linearGradient>
                            <linearGradient id="grad2" x1="0%" y1="100%" x2="100%" y2="0%">
                                <stop offset="0%" stop-color="#8B5CF6" />
                                <stop offset="100%" stop-color="#F43F5E" />
                            </linearGradient>
                            <filter id="shadow">
                                <feDropShadow dx="0" dy="4" stdDeviation="8" flood-color="#4F46E5"
                                    flood-opacity="0.25" />
                            </filter>
                        </defs>

                        <!-- Base Calendar Shape -->
                        <rect x="15" y="25" width="70" height="60" rx="18" fill="url(#grad1)" filter="url(#shadow)" />

                        <!-- Calendar Top Bar -->
                        <path d="M15 41C15 31.0589 23.0589 23 33 23H67C76.9411 23 85 31.0589 85 41V45H15V41Z"
                            fill="white" fill-opacity="0.25" />

                        <!-- Rings -->
                        <rect x="25" y="15" width="8" height="20" rx="4" fill="white" filter="url(#shadow)" />
                        <rect x="67" y="15" width="8" height="20" rx="4" fill="white" filter="url(#shadow)" />

                        <!-- Dynamic S Shape Overlay -->
                        <path
                            d="M65 52C65 48 60 46 54 46C43 46 38 52 38 58C38 67 62 62 62 72C62 78 55 81 48 81C40 81 35 77 35 73"
                            stroke="url(#grad2)" stroke-width="8" stroke-linecap="round" stroke-linejoin="round" />
                        <circle cx="50" cy="55" r="2" fill="white" />
                    </svg>
                    <h1 class="logo-text">SIMADA</h1>
                </div>

                <h2 class="title-elegant fs-1">Sistem Informasi<br>Pemantauan Agenda</h2>
                <p class="tagline">Kelola, pantau, dan disposisikan seluruh agenda kegiatan instansi Anda dengan mudah,
                    cerdas, dan efisien dalam satu platform terpadu.</p>

                <div
                    class="mt-auto pt-4 border-top border-secondary border-opacity-25 d-flex justify-content-between align-items-center">
                    <span class="small text-muted fw-medium">&copy; {{ date('Y') }} Hak Cipta Dilindungi</span>
                    <div class="d-flex gap-2">
                        <span
                            class="badge bg-white text-primary border border-primary border-opacity-25 px-3 py-2 rounded-pill shadow-sm">v2.0
                            Premium</span>
                    </div>
                </div>
            </div>

            <!-- Right Form Side -->
            <div class="login-form-container">
                <!-- Mobile Branding -->
                <div class="mobile-logo">
                    <svg class="logo-svg mb-3" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg"
                        style="width: 72px; height: 72px;">
                        <defs>
                            <linearGradient id="grad1_mob" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#4F46E5" />
                                <stop offset="100%" stop-color="#0EA5E9" />
                            </linearGradient>
                            <linearGradient id="grad2_mob" x1="0%" y1="100%" x2="100%" y2="0%">
                                <stop offset="0%" stop-color="#8B5CF6" />
                                <stop offset="100%" stop-color="#F43F5E" />
                            </linearGradient>
                            <filter id="shadow_mob">
                                <feDropShadow dx="0" dy="4" stdDeviation="6" flood-color="#4F46E5"
                                    flood-opacity="0.2" />
                            </filter>
                        </defs>
                        <rect x="15" y="25" width="70" height="60" rx="18" fill="url(#grad1_mob)"
                            filter="url(#shadow_mob)" />
                        <path d="M15 41C15 31.0589 23.0589 23 33 23H67C76.9411 23 85 31.0589 85 41V45H15V41Z"
                            fill="white" fill-opacity="0.25" />
                        <rect x="25" y="15" width="8" height="20" rx="4" fill="white" filter="url(#shadow_mob)" />
                        <rect x="67" y="15" width="8" height="20" rx="4" fill="white" filter="url(#shadow_mob)" />
                        <path
                            d="M65 52C65 48 60 46 54 46C43 46 38 52 38 58C38 67 62 62 62 72C62 78 55 81 48 81C40 81 35 77 35 73"
                            stroke="url(#grad2_mob)" stroke-width="8" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <h2 class="logo-text fs-1 mb-1">SIMADA</h2>
                    <p class="text-secondary small fw-medium mt-1">Pemantauan Agenda Instansi</p>
                </div>

                <div class="mb-5 text-center text-lg-start">
                    <h3 class="form-title h2">Selamat Datang 👋</h3>
                    <p class="form-subtitle">Silakan masuk untuk melanjutkan ke dashboard.</p>
                </div>

                @if(session('status'))
                    <div
                        class="alert alert-success rounded-4 border border-success border-opacity-25 shadow-sm fw-medium mb-4 d-flex align-items-center bg-white">
                        <i class="bi bi-check-circle-fill fs-5 me-2 text-success"></i> {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="input-icon-wrapper mb-4">
                        <div class="form-floating">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ old('email') }}" required autofocus
                                placeholder="name@example.com">
                            <label for="email">Alamat Email</label>
                        </div>
                        <i class="bi bi-envelope input-icon"></i>
                        @error('email')
                            <div class="text-danger small mt-2 fw-medium ms-2"><i
                                    class="bi bi-exclamation-triangle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-icon-wrapper mb-4">
                        <div class="form-floating">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" required placeholder="Password">
                            <label for="password">Kata Sandi</label>
                        </div>
                        <i class="bi bi-shield-lock input-icon"></i>
                        @error('password')
                            <div class="text-danger small mt-2 fw-medium ms-2"><i
                                    class="bi bi-exclamation-triangle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-5 px-2">
                        <div class="form-check custom-checkbox d-flex align-items-center">
                            <input type="checkbox" class="form-check-input me-2" id="remember_me" name="remember">
                            <label class="form-check-label text-secondary fw-medium small" for="remember_me">Ingat
                                Saya</label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="small text-primary text-decoration-none fw-semibold"
                                style="transition: color 0.2s;">Lupa Sandi?</a>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-login">
                        <span class="d-flex align-items-center justify-content-center">
                            Masuk Sekarang <i class="bi bi-arrow-right-short fs-4 ms-1"></i>
                        </span>
                    </button>
                </form>

                <!-- Demo Accounts Section -->

            </div>
        </div>
    </div>
</body>

</html>