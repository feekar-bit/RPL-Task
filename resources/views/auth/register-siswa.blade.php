<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Siswa</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="icon" href="{{ asset('images/RPL Task Manager Icon Only.png') }}" type="image/png" sizes="16x16">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,700;12..96,800&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --white:        #ffffff;
            --slate:        #676f9d;
            --mid:          #424769;
            --deep:         #2d3250;
            --accent:       #f9b17a;
            --accent-dim:   rgba(249,177,122,0.14);
            --glass-bg:     rgba(45,50,80,0.44);
            --glass-border: rgba(103,111,157,0.24);
        }

        *, *::before, *::after { box-sizing: border-box; }
        html, body { height: 100%; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--deep);
            color: var(--white);
            min-height: 100vh;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .bg-canvas {
            position: fixed; inset: 0; z-index: 0;
            background:
                radial-gradient(ellipse 80% 60% at 15% 10%, rgba(66,71,105,0.70) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 85% 80%, rgba(103,111,157,0.32) 0%, transparent 55%),
                radial-gradient(ellipse 50% 40% at 50% 50%, rgba(249,177,122,0.05) 0%, transparent 60%),
                var(--deep);
        }
        .bg-grid {
            position: fixed; inset: 0; z-index: 0;
            background-image:
                linear-gradient(rgba(103,111,157,0.07) 1px, transparent 1px),
                linear-gradient(90deg, rgba(103,111,157,0.07) 1px, transparent 1px);
            background-size: 60px 60px;
        }
        .orb { position: fixed; border-radius: 50%; filter: blur(80px); pointer-events: none; z-index: 0; animation: drift 14s ease-in-out infinite alternate; }
        .orb-1 { width: 420px; height: 420px; background: rgba(249,177,122,0.08); top: -80px; right: -100px; animation-duration: 16s; }
        .orb-2 { width: 320px; height: 320px; background: rgba(103,111,157,0.18); bottom: 10%; left: -80px; animation-duration: 12s; animation-delay: -4s; }
        @keyframes drift { from { transform: translate(0,0) scale(1); } to { transform: translate(30px,20px) scale(1.08); } }

        .anim-fade-left {
            opacity: 0; transform: translateX(-24px);
            transition: opacity 0.65s cubic-bezier(.22,.68,0,1.1), transform 0.65s cubic-bezier(.22,.68,0,1.1);
        }
        .anim-fade-right {
            opacity: 0; transform: translateX(24px);
            transition: opacity 0.65s cubic-bezier(.22,.68,0,1.1), transform 0.65s cubic-bezier(.22,.68,0,1.1);
        }
        .anim-fade-left.is-visible,
        .anim-fade-right.is-visible { opacity: 1; transform: translate(0); }

        /* ── Page wrapper ── */
        .auth-page {
            position: relative; z-index: 1;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 2rem 1.25rem;
        }

        /* ── Auth card ── */
        .auth-card {
            width: 100%; max-width: 960px;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 28px;
            backdrop-filter: blur(18px); -webkit-backdrop-filter: blur(18px);
            overflow: hidden;
            display: flex;
            box-shadow: 0 32px 80px rgba(0,0,0,0.35);
        }

        /* ── LEFT PANEL ── */
        .auth-left {
            flex: 1;
            padding: 3rem 2.5rem;
            display: flex; flex-direction: column; justify-content: space-between;
            background: linear-gradient(145deg, rgba(66,71,105,0.45) 0%, rgba(45,50,80,0.2) 100%);
            border-right: 1px solid var(--glass-border);
            position: relative; overflow: hidden;
        }
        .auth-left::after {
            content: ''; position: absolute; bottom: -80px; left: -60px;
            width: 280px; height: 280px;
            background: radial-gradient(circle, rgba(249,177,122,0.1) 0%, transparent 70%);
            pointer-events: none;
        }

        .left-brand { display: flex; align-items: center; gap: 0.65rem; text-decoration: none; }
        .left-logo {
            width: 48px; height: 48px;
            background: #2d3250;
            border-radius: 12px; display: grid; place-items: center;
            font-size: 1.1rem; box-shadow: 0 0 18px rgba(45, 50, 80, 1);
        }
        .left-brand-text {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-weight: 700; font-size: 1.08rem; color: var(--white);
        }
        .left-brand-text span { color: var(--accent); }

        .left-body { flex: 1; display: flex; flex-direction: column; justify-content: center; padding: 2rem 0; }

        .left-badge {
            display: inline-flex; align-items: center; gap: 0.45rem;
            background: rgba(249,177,122,0.10); border: 1px solid rgba(249,177,122,0.24);
            color: var(--accent); padding: 0.38rem 0.9rem; border-radius: 100px;
            font-size: 0.68rem; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase;
            margin-bottom: 1.4rem; width: fit-content;
        }
        .left-badge .dot {
            width: 5px; height: 5px; background: var(--accent);
            border-radius: 50%; box-shadow: 0 0 5px var(--accent);
            animation: pulse-dot 2.2s ease infinite;
        }
        @keyframes pulse-dot { 0%,100%{opacity:1;transform:scale(1);} 50%{opacity:.4;transform:scale(.72);} }

        .left-title {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-size: clamp(1.5rem, 2.6vw, 2.1rem);
            font-weight: 800; line-height: 1.15; letter-spacing: -0.02em;
            margin-bottom: 1rem; color: var(--white);
        }
        .left-title .hl {
            background: linear-gradient(130deg, var(--accent) 0%, #f7c59f 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .left-desc {
            font-size: 0.875rem; color: rgba(255,255,255,0.46);
            line-height: 1.78; font-weight: 400; margin-bottom: 2rem;
        }

        /* Step indicator */
        .step-indicator {
            display: flex; flex-direction: column; gap: 0.75rem;
        }
        .step-item {
            display: flex; align-items: center; gap: 0.75rem;
            font-size: 0.82rem; color: rgba(255,255,255,0.4); font-weight: 400;
        }
        .step-item.active { color: rgba(255,255,255,0.75); }
        .step-num {
            width: 26px; height: 26px; border-radius: 50%; flex-shrink: 0;
            display: grid; place-items: center;
            font-size: 0.7rem; font-weight: 700;
            background: rgba(103,111,157,0.2); border: 1.5px solid rgba(103,111,157,0.3);
            color: rgba(255,255,255,0.3);
        }
        .step-item.active .step-num {
            background: rgba(249,177,122,0.18); border-color: rgba(249,177,122,0.4);
            color: var(--accent);
        }
        .step-item.done .step-num {
            background: rgba(249,177,122,0.22); border-color: var(--accent);
            color: var(--accent);
        }

        .left-footer { font-size: 0.75rem; color: rgba(255,255,255,0.22); }

        /* ── RIGHT PANEL ── */
        .auth-right {
            width: 400px; flex-shrink: 0;
            padding: 3rem 2.5rem;
            display: flex; flex-direction: column; justify-content: center;
            overflow-y: auto;
        }

        .form-heading {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-size: 1.5rem; font-weight: 800; letter-spacing: -0.018em;
            color: var(--white); margin-bottom: 0.4rem;
        }
        .form-subheading {
            font-size: 0.845rem; color: rgba(255,255,255,0.4);
            font-weight: 400; margin-bottom: 1.8rem;
        }

        /* Alert */
        .alert-rpl-danger {
            background: rgba(220,80,80,0.12); border: 1px solid rgba(220,80,80,0.28);
            border-radius: 12px; padding: 0.8rem 1rem;
            font-size: 0.825rem; color: #f87171; margin-bottom: 1.4rem;
        }
        .alert-rpl-danger ul { margin: 0; padding-left: 1.1rem; }
        .alert-rpl-danger li { margin-top: 0.2rem; }

        /* 2-col grid for form fields */
        .fields-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0 1rem;
        }
        .field-full { grid-column: 1 / -1; }

        .field-group { margin-bottom: 1.1rem; }
        .field-label {
            display: block; font-size: 0.78rem; font-weight: 600;
            color: rgba(255,255,255,0.62); margin-bottom: 0.42rem; letter-spacing: 0.01em;
        }
        .field-input {
            width: 100%;
            background: rgba(255,255,255,0.05);
            border: 1.5px solid rgba(103,111,157,0.3);
            border-radius: 11px; padding: 0.68rem 0.9rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.865rem; font-weight: 400;
            color: var(--white); outline: none;
            transition: border-color 0.22s ease, background 0.22s ease, box-shadow 0.22s ease;
            -webkit-appearance: none;
        }
        .field-input::placeholder { color: rgba(255,255,255,0.2); }
        .field-input:focus {
            border-color: var(--accent);
            background: rgba(249,177,122,0.05);
            box-shadow: 0 0 0 3px rgba(249,177,122,0.12);
        }
        .field-input:-webkit-autofill,
        .field-input:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 1000px rgba(45,50,80,0.95) inset;
            -webkit-text-fill-color: var(--white); caret-color: var(--white);
        }
        .password-field { position: relative; }
        .password-field .field-input { padding-right: 3.2rem; }
        .toggle-password {
            position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%);
            background: transparent; border: none; color: rgba(255,255,255,0.46);
            width: 1.5rem; height: 1.5rem; display: grid; place-items: center;
            cursor: pointer; padding: 0.15rem;
            transition: color 0.2s ease;
        }
        .toggle-password svg { width: 1.05rem; height: 1.05rem; }
        .toggle-password:hover { color: var(--accent); }

        .btn-submit {
            width: 100%; background: var(--accent); color: var(--deep);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700; font-size: 0.9rem;
            padding: 0.8rem; border-radius: 11px; border: none; cursor: pointer;
            letter-spacing: 0.01em; box-shadow: 0 4px 24px rgba(249,177,122,0.32);
            transition: all 0.22s ease; margin-top: 0.3rem;
        }
        .btn-submit:hover { background: #fbc08e; box-shadow: 0 6px 32px rgba(249,177,122,0.50); transform: translateY(-2px); }
        .btn-submit:active { transform: translateY(0); }

        .form-footer-link {
            text-align: center; margin-top: 1.4rem;
            font-size: 0.82rem; color: rgba(255,255,255,0.36); font-weight: 400;
        }
        .form-footer-link a { color: var(--accent); font-weight: 600; text-decoration: none; transition: opacity .2s; }
        .form-footer-link a:hover { opacity: 0.8; }

        /* Divider */
        .field-divider {
            height: 1px; background: rgba(103,111,157,0.2);
            margin: 1.2rem 0;
        }

        /* ── Responsive ── */
        @media (max-width: 767px) {
            .auth-card { flex-direction: column; }
            .auth-left { border-right: none; border-bottom: 1px solid var(--glass-border); padding: 2rem 1.75rem; }
            .step-indicator { display: none; }
            .auth-right { width: 100%; padding: 2rem 1.75rem; }
            .fields-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body class="bg-light">

    <div class="bg-canvas"></div>
    <div class="bg-grid"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="auth-page">
        <div class="auth-card">

            <!-- ══ LEFT ══ -->
            <div class="auth-left anim-fade-left">
                <a href="/" class="left-brand">
                    <div class="left-logo">
                        <img src="{{ asset('images/RPL Task Manager Icon Only.png') }}" alt="RPL Task Manager Logo" style="width:32px;height:32px;">
                    </div>
                    <span class="left-brand-text">RPL<span>Tasks</span></span>
                </a>

                <div class="left-body">
                    <div class="left-badge"><span class="dot"></span> Daftar Akun Siswa</div>
                    <h2 class="left-title">
                        Bergabung sebagai<br><span class="hl">Siswa RPL</span>
                    </h2>
                    <p class="left-desc">
                        Daftarkan akunmu untuk mulai mengakses tugas, mengumpulkan
                        proyek, dan memantau progress belajar secara realtime.
                    </p>
                    <div class="step-indicator">
                        <div class="step-item done">
                            <div class="step-num">✓</div>
                            Pilih peran (Siswa / Guru)
                        </div>
                        <div class="step-item active">
                            <div class="step-num">2</div>
                            Isi data diri & buat akun
                        </div>
                        <div class="step-item">
                            <div class="step-num">3</div>
                            Akun siap digunakan
                        </div>
                    </div>
                </div>

                <div class="left-footer">&copy; 2025 RPL Task Management</div>
            </div>

            <!-- ══ RIGHT ══ -->
            <div class="auth-right anim-fade-right">
                <div class="form-heading">Buat Akun Siswa</div>
                <p class="form-subheading">Lengkapi data diri kamu di bawah ini</p>

                {{-- ERROR VALIDATION --}}
                @if($errors->any())
                    <div class="alert-rpl-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register.siswa.process') }}" method="POST">
                    @csrf

                    <div class="fields-grid">

                        {{-- NAMA --}}
                        <div class="field-group field-full">
                            <label class="field-label">Nama Lengkap</label>
                            <input type="text" name="name" class="field-input"
                                placeholder="Nama lengkap kamu"
                                value="{{ old('name') }}">
                        </div>

                        {{-- EMAIL --}}
                        <div class="field-group field-full">
                            <label class="field-label">Email</label>
                            <input type="email" name="email" class="field-input"
                                placeholder="nama@email.com"
                                value="{{ old('email') }}">
                        </div>

                        {{-- KELAS --}}
                        <div class="field-group">
                            <label class="field-label">Kelas</label>
                            <input type="text" name="class" class="field-input"
                                placeholder="Contoh: XI RPL 1"
                                value="{{ old('class') }}">
                        </div>

                        {{-- ABSEN --}}
                        <div class="field-group">
                            <label class="field-label">Nomor Absen</label>
                            <input type="text" name="attendance_number" class="field-input"
                                placeholder="Contoh: 15"
                                value="{{ old('attendance_number') }}">
                        </div>

                        {{-- PASSWORD --}}
                        <div class="field-group field-full">
                            <label class="field-label">Password</label>
                            <div class="password-field">
                                <input type="password" name="password" class="field-input"
                                    placeholder="Minimal 8 karakter">
                                <button type="button" class="toggle-password" data-toggle-password aria-label="Lihat password"></button>
                            </div>
                        </div>

                    </div>

                    <button type="submit" class="btn-submit">Daftar sebagai Siswa</button>

                    <div class="form-footer-link">
                        Sudah punya akun? <a href="/login">Masuk di sini</a>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        setTimeout(() => {
            document.querySelectorAll('.anim-fade-left, .anim-fade-right').forEach(el => {
                el.classList.add('is-visible');
            });
        }, 60);

        document.querySelectorAll('[data-toggle-password]').forEach(button => {
            const eyeOpen = `
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>`;
            const eyeClosed = `
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M10.7 5.1A10.9 10.9 0 0 1 12 5c6.5 0 10 7 10 7a18.5 18.5 0 0 1-2.1 3.1"></path>
                    <path d="M6.6 6.6C3.6 8.5 2 12 2 12s3.5 7 10 7a9.7 9.7 0 0 0 5.4-1.6"></path>
                    <path d="M14.1 14.1A3 3 0 0 1 9.9 9.9"></path>
                    <path d="M3 3l18 18"></path>
                </svg>`;

            button.innerHTML = eyeOpen;

            button.addEventListener('click', () => {
                const input = button.previousElementSibling;
                const isHidden = input.type === 'password';

                input.type = isHidden ? 'text' : 'password';
                button.innerHTML = isHidden ? eyeClosed : eyeOpen;
                button.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Lihat password');
            });
        });
    </script>

</body>
</html>
