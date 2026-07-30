<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Register</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="icon" href="{{ asset('images/RPL Task Manager Icon Only.png') }}" type="image/png" sizes="16x16">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,700;12..96,800&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        /* ── Variables (konsisten dengan welcome.blade) ── */
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
        html { scroll-behavior: smooth; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--deep);
            color: var(--white);
            min-height: 100vh;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        /* ── Background layers ── */
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

        /* ── Floating orbs ── */
        .orb { position: fixed; border-radius: 50%; filter: blur(80px); pointer-events: none; z-index: 0; animation: drift 14s ease-in-out infinite alternate; }
        .orb-1 { width: 420px; height: 420px; background: rgba(249,177,122,0.08); top: -80px; right: -100px; animation-duration: 16s; }
        .orb-2 { width: 320px; height: 320px; background: rgba(103,111,157,0.18); bottom: 10%; left: -80px; animation-duration: 12s; animation-delay: -4s; }
        .orb-3 { width: 200px; height: 200px; background: rgba(249,177,122,0.06); top: 55%; right: 20%; animation-duration: 18s; animation-delay: -8s; }
        @keyframes drift {
            from { transform: translate(0,0) scale(1); }
            to   { transform: translate(30px,20px) scale(1.08); }
        }

        /* ── Page content ── */
        .page-content {
            position: relative; z-index: 1;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 3rem 1.25rem;
        }

        /* ── Entrance animations ── */
        .anim-fade-up {
            opacity: 0; transform: translateY(24px);
            transition: opacity 0.65s cubic-bezier(.22,.68,0,1.1),
                        transform 0.65s cubic-bezier(.22,.68,0,1.1);
        }
        .anim-fade-up.is-visible { opacity: 1; transform: translateY(0); }
        .delay-1 { transition-delay: 0.06s; }
        .delay-2 { transition-delay: 0.16s; }
        .delay-3 { transition-delay: 0.28s; }
        .delay-4 { transition-delay: 0.42s; }
        .delay-5 { transition-delay: 0.56s; }

        /* ─────────────────────────
           TOP BRAND (no navbar)
        ───────────────────────── */
        .top-brand {
            display: flex; align-items: center; gap: 0.65rem;
            text-decoration: none; margin-bottom: 2.8rem;
        }
        .top-logo-icon {
            width: 48px; height: 48px;
            background: #2d3250;
            border-radius: 12px; display: grid; place-items: center;
            font-size: 1.1rem; box-shadow: 0 0 18px rgba(45, 50, 80, 1);
        }
        .top-brand-text {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-weight: 700; font-size: 1.1rem;
            color: var(--white); letter-spacing: 0.01em;
        }
        .top-brand-text span { color: var(--accent); }

        /* ─────────────────────────
           HEADING AREA
        ───────────────────────── */
        .role-wrap { width: 100%; max-width: 600px; text-align: center; }

        .role-badge {
            display: inline-flex; align-items: center; gap: 0.48rem;
            background: rgba(249,177,122,0.10);
            border: 1px solid rgba(249,177,122,0.26);
            color: var(--accent);
            padding: 0.4rem 1rem; border-radius: 100px;
            font-size: 0.7rem; font-weight: 600;
            letter-spacing: 0.11em; text-transform: uppercase;
            margin-bottom: 1.4rem;
        }
        .role-badge .dot {
            width: 6px; height: 6px; background: var(--accent);
            border-radius: 50%; box-shadow: 0 0 6px var(--accent);
            animation: pulse-dot 2.2s ease infinite;
        }
        @keyframes pulse-dot {
            0%,100% { opacity: 1; transform: scale(1); }
            50%      { opacity: 0.4; transform: scale(0.72); }
        }

        .role-title {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-size: clamp(1.85rem, 4.5vw, 2.8rem);
            font-weight: 800; line-height: 1.12; letter-spacing: -0.022em;
            margin-bottom: 0.65rem; color: var(--white);
        }
        .role-title .highlight {
            background: linear-gradient(130deg, var(--accent) 0%, #f7c59f 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .role-desc {
            font-size: 0.95rem; color: rgba(255,255,255,0.48);
            line-height: 1.78; font-weight: 400; margin-bottom: 2.5rem;
        }

        /* ─────────────────────────
           LANDSCAPE ROLE CARDS
        ───────────────────────── */
        .role-cards {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            width: 100%;
        }

        .role-card {
            display: flex;
            align-items: center;
            gap: 1.6rem;
            background: var(--glass-bg);
            border: 1.5px solid var(--glass-border);
            border-radius: 20px;
            padding: 1.6rem 1.8rem;
            text-decoration: none;
            color: var(--white);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            transition: transform 0.28s ease, border-color 0.28s ease,
                        box-shadow 0.28s ease;
            position: relative;
            overflow: hidden;
            text-align: left;
        }
        /* Subtle glow layer on hover */
        .role-card::before {
            content: ''; position: absolute; inset: 0;
            opacity: 0; transition: opacity 0.3s;
            pointer-events: none;
        }
        .card-siswa::before {
            background: linear-gradient(120deg, rgba(249,177,122,0.06) 0%, transparent 55%);
        }
        .card-guru::before {
            background: linear-gradient(120deg, rgba(103,111,157,0.10) 0%, transparent 55%);
        }
        .role-card:hover { transform: translateY(-4px); color: var(--white); }
        .role-card:hover::before { opacity: 1; }

        .card-siswa:hover {
            border-color: rgba(249,177,122,0.42);
            box-shadow: 0 20px 56px rgba(249,177,122,0.10);
        }
        .card-guru:hover {
            border-color: rgba(103,111,157,0.55);
            box-shadow: 0 20px 56px rgba(103,111,157,0.14);
        }

        /* ── Icon box ── */
        .card-icon-box {
            flex-shrink: 0;
            width: 64px; height: 64px;
            border-radius: 18px;
            display: grid; place-items: center;
        }
        .card-siswa .card-icon-box {
            background: rgba(249,177,122,0.12);
            border: 1px solid rgba(249,177,122,0.22);
        }
        .card-guru .card-icon-box {
            background: rgba(103,111,157,0.18);
            border: 1px solid rgba(103,111,157,0.28);
        }
        .card-icon-box svg { display: block; }

        /* ── Card body ── */
        .card-body-text { flex: 1; min-width: 0; }

        .card-role-label {
            font-size: 0.65rem; font-weight: 700;
            letter-spacing: 0.13em; text-transform: uppercase;
            margin-bottom: 0.28rem;
        }
        .card-siswa .card-role-label { color: var(--accent); }
        .card-guru  .card-role-label { color: var(--slate); }

        .card-title {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-size: 1.25rem; font-weight: 800;
            letter-spacing: -0.01em; margin-bottom: 0.38rem;
            color: var(--white);
        }
        .card-desc-text {
            font-size: 0.845rem; color: rgba(255,255,255,0.44);
            line-height: 1.68; font-weight: 400; margin: 0;
        }

        /* ── Arrow button ── */
        .card-arrow {
            flex-shrink: 0;
            width: 40px; height: 40px;
            border-radius: 12px;
            display: grid; place-items: center;
            font-size: 1rem;
            transition: transform 0.22s ease, background 0.22s ease;
        }
        .card-siswa .card-arrow {
            background: rgba(249,177,122,0.14);
            color: var(--accent);
        }
        .card-guru .card-arrow {
            background: rgba(103,111,157,0.18);
            color: var(--slate);
        }
        .role-card:hover .card-arrow { transform: translateX(4px); }
        .card-siswa:hover .card-arrow { background: rgba(249,177,122,0.24); }
        .card-guru:hover  .card-arrow { background: rgba(103,111,157,0.30); }

        /* ─────────────────────────
           BACK LINK
        ───────────────────────── */
        .back-link {
            display: inline-flex; align-items: center; gap: 0.4rem;
            color: rgba(255,255,255,0.32); font-size: 0.82rem; font-weight: 500;
            text-decoration: none; margin-top: 2rem;
            transition: color 0.2s;
        }
        .back-link:hover { color: rgba(255,255,255,0.62); }

        /* ── Responsive ── */
        @media (max-width: 480px) {
            .card-desc-text { display: none; }
            .role-card { gap: 1.1rem; padding: 1.35rem 1.4rem; }
            .card-icon-box { width: 52px; height: 52px; border-radius: 14px; }
        }
    </style>
</head>
<body>

    <!-- ── Background layers ── -->
    <div class="bg-canvas"></div>
    <div class="bg-grid"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <!-- ══════════════════
         PAGE CONTENT
    ══════════════════ -->
    <div class="page-content">

        <!-- Top brand mark (no navbar) -->
        <a href="/" class="top-brand anim-fade-up delay-1">
            <div class="top-logo-icon">
                <img src="{{ asset('images/RPL Task Manager Icon Only.png') }}" alt="RPL Task Manager Logo" style="width:28px;height:28px;">
            </div>
            <span class="top-brand-text">RPL<span>Tasks</span></span>
        </a>

        <div class="role-wrap">

            <!-- Badge -->
            <div class="role-badge anim-fade-up delay-2">
                <span class="dot"></span>
                Pilih Peran
            </div>

            <!-- Heading -->
            <h2 class="role-title anim-fade-up delay-2">
                Register Sebagai <span class="highlight">Siapa?</span>
            </h2>
            <p class="role-desc anim-fade-up delay-3">
                Pilih peranmu untuk mendapatkan akses dan fitur yang sesuai.
            </p>

            <!-- ── LANDSCAPE ROLE CARDS ── -->
            <div class="role-cards">

                <!-- Siswa -->
                <a href="/register/siswa" class="role-card card-siswa anim-fade-up delay-3">
                    <div class="card-icon-box">
                        <!-- SVG: student / graduation cap -->
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16 4L2 11L16 18L30 11L16 4Z" stroke="#f9b17a" stroke-width="2" stroke-linejoin="round" fill="rgba(249,177,122,0.12)"/>
                            <path d="M7 14V21C7 21 10.5 25 16 25C21.5 25 25 21 25 21V14" stroke="#f9b17a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M30 11V18" stroke="#f9b17a" stroke-width="2" stroke-linecap="round"/>
                            <circle cx="30" cy="19.5" r="1.5" fill="#f9b17a"/>
                        </svg>
                    </div>

                    <div class="card-body-text">
                        <div class="card-role-label">Pelajar</div>
                        <div class="card-title">Siswa</div>
                        <p class="card-desc-text">Akses tugas, kumpulkan proyek, dan pantau progress belajarmu secara realtime.</p>
                    </div>

                    <div class="card-arrow">→</div>
                </a>

                <!-- Guru -->
                <a href="/register/guru" class="role-card card-guru anim-fade-up delay-4">
                    <div class="card-icon-box">
                        <!-- SVG: teacher / person at board -->
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="3" y="5" width="22" height="15" rx="2" stroke="#676f9d" stroke-width="2" fill="rgba(103,111,157,0.12)"/>
                            <path d="M8 27H24" stroke="#676f9d" stroke-width="2" stroke-linecap="round"/>
                            <path d="M14 20V27" stroke="#676f9d" stroke-width="2" stroke-linecap="round"/>
                            <path d="M7 12H10M7 9H15M7 15H12" stroke="#676f9d" stroke-width="1.75" stroke-linecap="round"/>
                            <circle cx="25" cy="22" r="4" fill="rgba(103,111,157,0.18)" stroke="#676f9d" stroke-width="1.75"/>
                            <path d="M23.5 22L24.5 23L26.5 21" stroke="#676f9d" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>

                    <div class="card-body-text">
                        <div class="card-role-label">Pengajar</div>
                        <div class="card-title">Guru</div>
                        <p class="card-desc-text">Buat dan distribusikan tugas, nilai pekerjaan siswa, dan kelola kelas dengan mudah.</p>
                    </div>

                    <div class="card-arrow">→</div>
                </a>

            </div><!-- /role-cards -->

            <!-- Back link -->
            <a href="/" class="back-link anim-fade-up delay-5">
                ← Kembali ke halaman utama
            </a>

        </div><!-- /role-wrap -->

    </div><!-- /page-content -->

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ── Entrance animations ──
        setTimeout(() => {
            document.querySelectorAll('.anim-fade-up').forEach(el => {
                el.classList.add('is-visible');
            });
        }, 60);
    </script>

</body>
</html>