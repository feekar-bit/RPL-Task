<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>RPL Task Manager v2- Manajemen Tugas & Proyek RPL</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="icon" href="{{ asset('images/RPL Task Manager Icon Only.png') }}" type="image/png" sizes="16x16">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,700;12..96,800&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        /* ── Variables ── */
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

        /* ── Base ── */
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

        .page-content { position: relative; z-index: 1; }

        /* ─────────────────────────────
           ENTRANCE ANIMATIONS
        ───────────────────────────── */
        .anim-fade-down {
            opacity: 0;
            transform: translateY(-16px);
            transition: opacity 0.55s cubic-bezier(.22,.68,0,1.2), transform 0.55s cubic-bezier(.22,.68,0,1.2);
        }
        .anim-fade-up {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity 0.65s cubic-bezier(.22,.68,0,1.1), transform 0.65s cubic-bezier(.22,.68,0,1.1);
        }
        .anim-fade-in {
            opacity: 0;
            transition: opacity 0.7s ease;
        }
        .anim-fade-down.is-visible,
        .anim-fade-up.is-visible,
        .anim-fade-in.is-visible { opacity: 1; transform: translateY(0); }

        /* Stagger delays */
        .delay-1 { transition-delay: 0.08s; }
        .delay-2 { transition-delay: 0.18s; }
        .delay-3 { transition-delay: 0.30s; }
        .delay-4 { transition-delay: 0.44s; }
        .delay-5 { transition-delay: 0.58s; }
        .delay-6 { transition-delay: 0.72s; }

        /* ─────────────────────────────
           NAVBAR
        ───────────────────────────── */
        .navbar-custom {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 200;
            padding: 1.3rem 0;
            transition: background 0.42s ease, backdrop-filter 0.42s ease,
                        -webkit-backdrop-filter 0.42s ease, border-bottom 0.42s ease, padding 0.3s ease;
        }
        .navbar-custom.scrolled {
            background: rgba(28,32,55,0.62);
            backdrop-filter: blur(22px) saturate(160%);
            -webkit-backdrop-filter: blur(22px) saturate(160%);
            border-bottom: 1px solid var(--glass-border);
            padding: 0.85rem 0;
        }

        .nav-brand { display: flex; align-items: center; gap: 0.65rem; text-decoration: none; }
        .nav-logo-icon {
            width: 48px; height: 48px;
            background: #2d3250;
            border-radius: 11px;
            display: grid; place-items: center;
            font-size: 1.05rem;
            box-shadow: 0 0 18px rgba(45, 50, 80, 1);
            flex-shrink: 0;
        }
        .nav-brand-text {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-weight: 700; font-size: 1.08rem;
            color: var(--white); letter-spacing: 0.01em;
        }
        .nav-brand-text span { color: var(--accent); }
        .nav-actions { display: flex; gap: 0.65rem; align-items: center; }

        /* ─────────────────────────────
           BUTTONS
        ───────────────────────────── */
        .btn-rpl {
            display: inline-flex; align-items: center; gap: 0.45rem;
            text-decoration: none;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 600; font-size: 0.875rem;
            border-radius: 10px; padding: 0.58rem 1.35rem;
            transition: all 0.25s ease; cursor: pointer;
            border: none; outline: none; letter-spacing: 0.01em; line-height: 1;
        }
        .btn-ghost {
            background: transparent; color: rgba(255,255,255,0.75);
            border: 1px solid rgba(103,111,157,0.42);
        }
        .btn-ghost:hover { background: rgba(103,111,157,0.14); border-color: var(--slate); color: var(--white); }
        .btn-accent {
            background: var(--accent); color: var(--deep); font-weight: 700;
            box-shadow: 0 0 20px rgba(249,177,122,0.32);
        }
        .btn-accent:hover { background: #fbc08e; box-shadow: 0 0 30px rgba(249,177,122,0.52); transform: translateY(-1px); }

        .btn-hero-primary {
            background: var(--accent); color: var(--deep);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700; font-size: 0.95rem;
            padding: 0.92rem 2.4rem; border-radius: 13px; border: none;
            text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;
            box-shadow: 0 4px 28px rgba(249,177,122,0.38);
            transition: all 0.25s ease; letter-spacing: 0.01em;
        }
        .btn-hero-primary:hover { background: #fbc08e; box-shadow: 0 6px 42px rgba(249,177,122,0.58); transform: translateY(-3px); color: var(--deep); }

        .btn-hero-outline {
            background: transparent; color: rgba(255,255,255,0.8);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 500; font-size: 0.9rem;
            padding: 0.9rem 2.1rem; border-radius: 13px;
            border: 1.5px solid rgba(255,255,255,0.18);
            text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;
            transition: all 0.25s ease; letter-spacing: 0.01em;
        }
        .btn-hero-outline:hover { border-color: var(--slate); background: rgba(103,111,157,0.1); color: var(--white); transform: translateY(-2px); }

        /* ─────────────────────────────
           HERO
        ───────────────────────────── */
        .hero-section {
            min-height: 100vh;
            display: flex; align-items: center;
            padding-top: 9rem; padding-bottom: 4rem;
        }

        .hero-badge {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: rgba(249,177,122,0.10);
            border: 1px solid rgba(249,177,122,0.26);
            color: var(--accent);
            padding: 0.42rem 1.05rem; border-radius: 100px;
            font-size: 0.72rem; font-weight: 600;
            letter-spacing: 0.11em; text-transform: uppercase;
            margin-bottom: 1.9rem;
        }
        .hero-badge .dot {
            width: 6px; height: 6px;
            background: var(--accent); border-radius: 50%;
            box-shadow: 0 0 6px var(--accent); flex-shrink: 0;
            animation: pulse-dot 2.2s ease infinite;
        }
        @keyframes pulse-dot {
            0%,100% { opacity: 1; transform: scale(1); }
            50%      { opacity: 0.45; transform: scale(0.75); }
        }

        .hero-title {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-size: clamp(2.4rem, 5.5vw, 4.6rem);
            font-weight: 800; line-height: 1.1; letter-spacing: -0.025em;
            margin-bottom: 1.55rem; color: var(--white);
        }
        .hero-title .highlight {
            background: linear-gradient(130deg, var(--accent) 0%, #f7c59f 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .hero-desc {
            font-size: 1.05rem; color: rgba(255,255,255,0.55);
            line-height: 1.82; font-weight: 400;
            margin-bottom: 2.5rem; max-width: 460px;
        }
        .hero-cta { display: flex; gap: 0.9rem; flex-wrap: wrap; align-items: center; }

        /* Stats */
        .stats-bar {
            display: flex; gap: 0;
            margin-top: 0; padding: 2.6rem 0;
            border-top: 1px solid rgba(103,111,157,0.2);
            border-bottom: 1px solid rgba(103,111,157,0.2);
        }
        .stat-item { flex: 1; text-align: center; padding: 0 0.75rem; position: relative; }
        .stat-item + .stat-item::before {
            content: ''; position: absolute; left: 0; top: 15%; bottom: 15%;
            width: 1px; background: rgba(103,111,157,0.22);
        }
        .stat-num {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-size: 1.9rem; font-weight: 800; color: var(--white); line-height: 1; display: block;
        }
        .stat-num em { color: var(--accent); font-style: normal; }
        .stat-label {
            display: block; font-size: 0.7rem; font-weight: 600;
            color: rgba(255,255,255,0.35); letter-spacing: 0.08em;
            text-transform: uppercase; margin-top: 0.4rem;
        }

        /* ── Hero visual / mock card ── */
        .hero-visual { position: relative; margin-top: -6rem; }

        .hero-card-mock {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 20px; padding: 1.6rem;
            backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);
            position: relative; z-index: 1;
        }
        .mock-header { display: flex; align-items: center; gap: 0.55rem; margin-bottom: 1.35rem; }
        .mock-dot { width: 10px; height: 10px; border-radius: 50%; }
        .mock-title {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-size: 0.82rem; font-weight: 700; color: rgba(255,255,255,0.65); margin-left: 0.25rem;
        }

        .mock-task {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(103,111,157,0.2);
            border-radius: 12px; padding: 0.8rem 0.95rem;
            margin-bottom: 0.65rem; display: flex; align-items: center; gap: 0.75rem;
        }
        .mock-task:last-of-type { margin-bottom: 0; }
        .mock-check {
            width: 20px; height: 20px; border-radius: 6px;
            display: grid; place-items: center; font-size: 0.62rem; flex-shrink: 0;
        }
        .check-done { background: rgba(249,177,122,0.2); border: 1.5px solid var(--accent); color: var(--accent); }
        .check-pend { background: rgba(103,111,157,0.15); border: 1.5px solid rgba(103,111,157,0.4); }
        .mock-task-info { flex: 1; min-width: 0; }
        .mock-task-name {
            font-size: 0.78rem; font-weight: 600; color: rgba(255,255,255,0.8);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .mock-task-name.done { color: rgba(255,255,255,0.35); text-decoration: line-through; }
        .mock-task-meta { font-size: 0.67rem; color: rgba(255,255,255,0.3); margin-top: 0.15rem; }
        .mock-badge {
            font-size: 0.6rem; font-weight: 700; padding: 0.2rem 0.55rem;
            border-radius: 100px; letter-spacing: 0.05em; text-transform: uppercase; flex-shrink: 0;
        }
        .badge-done    { background: rgba(249,177,122,0.15); color: var(--accent); }
        .badge-process { background: rgba(103,111,157,0.22); color: var(--slate); }
        .badge-late    { background: rgba(220,80,80,0.14);   color: #f87171; }

        .mock-progress-wrap { margin-top: 1.35rem; }
        .mock-progress-label {
            display: flex; justify-content: space-between;
            font-size: 0.7rem; color: rgba(255,255,255,0.4); margin-bottom: 0.48rem;
        }
        .mock-progress-bar { height: 6px; background: rgba(103,111,157,0.2); border-radius: 100px; overflow: hidden; }
        .mock-progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--accent), #f7c59f);
            border-radius: 100px; width: 72%;
            animation: grow-bar 1.8s 0.9s cubic-bezier(.22,.68,0,1) both;
        }
        @keyframes grow-bar { from { width: 0; } to { width: 72%; } }

        /* Floating mini cards */
        .float-card {
            position: absolute;
            background: rgba(32,36,60,0.92);
            border: 1px solid rgba(103,111,157,0.35);
            border-radius: 14px; padding: 0.7rem 1rem;
            backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
            animation: float-y 4s ease-in-out infinite alternate;
            z-index: 2;
            box-shadow: 0 8px 32px rgba(0,0,0,0.28);
        }
        .float-card-1 { top: -1.8rem; right: -2rem; animation-duration: 5s; }
        .float-card-2 { bottom: -1.8rem; left: -2rem; animation-duration: 4.2s; animation-delay: -2s; }
        @keyframes float-y { from { transform: translateY(0); } to { transform: translateY(-10px); } }
        .float-label { font-size: 0.62rem; font-weight: 600; color: rgba(255,255,255,0.32); letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 0.18rem; }
        .float-value { font-family: 'Bricolage Grotesque', sans-serif; font-size: 1.3rem; font-weight: 800; color: var(--accent); line-height: 1; }

        /* ─────────────────────────────
           FEATURES
        ───────────────────────────── */
        .features-section { padding: 5.5rem 0; }

        .section-label {
            font-size: 0.7rem; font-weight: 700;
            letter-spacing: 0.16em; text-transform: uppercase;
            color: var(--accent); margin-bottom: 0.8rem;
        }
        .section-title {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-size: clamp(1.7rem, 3.6vw, 2.55rem);
            font-weight: 800; line-height: 1.18; letter-spacing: -0.02em;
            margin-bottom: 0.85rem; color: var(--white);
        }
        .section-subtitle {
            color: rgba(255,255,255,0.46); font-size: 0.965rem;
            line-height: 1.76; font-weight: 400;
        }

        .feature-card {
            background: var(--glass-bg); border: 1px solid var(--glass-border);
            border-radius: 20px; padding: 1.9rem 1.7rem;
            backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
            height: 100%; position: relative; overflow: hidden;
            transition: transform 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .feature-card::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(249,177,122,0.04) 0%, transparent 55%);
            opacity: 0; transition: opacity 0.3s;
        }
        .feature-card:hover { transform: translateY(-6px); border-color: rgba(249,177,122,0.28); box-shadow: 0 18px 55px rgba(0,0,0,0.28); }
        .feature-card:hover::before { opacity: 1; }

        .feature-icon {
            width: 48px; height: 48px; background: var(--accent-dim);
            border: 1px solid rgba(249,177,122,0.2); border-radius: 13px;
            display: grid; place-items: center; font-size: 1.35rem; margin-bottom: 1.3rem;
        }
        .feature-card h3 {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-size: 1rem; font-weight: 700; letter-spacing: -0.01em;
            margin-bottom: 0.55rem; color: var(--white);
        }
        .feature-card p {
            font-size: 0.875rem; color: rgba(255,255,255,0.45);
            line-height: 1.74; font-weight: 400; margin-bottom: 0;
        }

        /* ─────────────────────────────
           CTA
        ───────────────────────────── */
        .cta-section { padding: 0 0 5.5rem; }
        .cta-card {
            background: linear-gradient(135deg, var(--mid) 0%, var(--deep) 100%);
            border: 1px solid rgba(249,177,122,0.18);
            border-radius: 28px; padding: 4rem 2rem;
            text-align: center; position: relative; overflow: hidden;
        }
        .cta-card::after {
            content: ''; position: absolute; top: -60px; right: -60px;
            width: 260px; height: 260px;
            background: radial-gradient(circle, rgba(249,177,122,0.12) 0%, transparent 70%);
            pointer-events: none;
        }
        .cta-card h2 {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-size: clamp(1.6rem, 3.5vw, 2.4rem); font-weight: 800;
            letter-spacing: -0.02em; margin-bottom: 0.85rem; color: var(--white);
        }
        .cta-card p {
            color: rgba(255,255,255,0.46); font-size: 0.975rem;
            line-height: 1.75; margin-bottom: 2.2rem; font-weight: 400;
        }

        /* ─────────────────────────────
           FOOTER
        ───────────────────────────── */
        .site-footer { border-top: 1px solid rgba(103,111,157,0.18); padding: 2rem 0; }
        .footer-copy { font-size: 0.82rem; font-weight: 400; color: rgba(255,255,255,0.3); }
        .footer-tag  { font-size: 0.82rem; font-weight: 400; color: rgba(255,255,255,0.26); }
        .footer-tag span { color: var(--accent); }

        /* ─────────────────────────────
           RESPONSIVE
        ───────────────────────────── */
        @media (max-width: 991px) {
            .hero-visual { margin-top: 3rem; }
            .float-card-1, .float-card-2 { display: none; }
        }
        @media (max-width: 767px) {
            .hero-section { padding-top: 6rem; }
            .hero-title { font-size: clamp(2rem, 8vw, 2.8rem); }
            .hero-desc { max-width: 100%; }
            .stats-bar { flex-wrap: wrap; }
            .stat-item { flex: 1 1 40%; }
            .stat-item + .stat-item::before { display: none; }
            .cta-card { padding: 2.5rem 1.25rem; }
            .footer-copy, .footer-tag { text-align: center; }
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
         NAVBAR
    ══════════════════ -->
    <nav class="navbar-custom anim-fade-down" id="mainNav">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between w-100">

                <a href="/" class="nav-brand">
                    <div class="nav-logo-icon">
                        <img src="{{ asset('images/RPL Task Manager Icon Only.png') }}" alt="RPL Task Manager Logo" style="width:35px;height:35px;">
                    </div>
                    <span class="nav-brand-text">RPL<span>Tasks</span></span>
                </a>

                <div class="nav-actions">
                    <a href="/login"    class="btn-rpl btn-ghost">Masuk</a>
                    <a href="/register" class="btn-rpl btn-accent">Daftar Sekarang</a>
                </div>

            </div>
        </div>
    </nav>

    <!-- ══════════════════
         PAGE CONTENT
    ══════════════════ -->
    <div class="page-content">

        <!-- HERO -->
        <section class="hero-section">
            <div class="container">
                <div class="row align-items-center g-5">

                    <!-- Copy -->
                    <div class="col-lg-6">
                        <div class="hero-badge anim-fade-up delay-1">
                            <span class="dot"></span>
                            Platform Manajemen Tugas Siswa
                        </div>

                        <h1 class="hero-title anim-fade-up delay-2">
                            Kelola Tugas &amp; Proyek<br>
                            dengan <span class="highlight">Lebih Cerdas</span>
                        </h1>

                        <p class="hero-desc anim-fade-up delay-3">
                            Sistem manajemen tugas dan proyek siswa RPL dengan progress tracker
                            realtime. Pantau, kumpulkan, dan evaluasi tugas dalam satu platform.
                        </p>

                        <div class="hero-cta anim-fade-up delay-4">
                            <a href="/register" class="btn-hero-primary">Mulai Sekarang &rarr;</a>
                            <a href="/login"    class="btn-hero-outline">Sudah punya akun? Login</a>
                        </div>

                    </div>

                    <!-- Mock dashboard -->
                    <div class="col-lg-6 anim-fade-in delay-4">
                        <div class="hero-visual">
                            <div class="float-card float-card-1">
                                <div class="float-label">Tugas Selesai</div>
                                <div class="float-value">24</div>
                            </div>
                            <div class="float-card float-card-2">
                                <div class="float-label">Rata-rata Nilai</div>
                                <div class="float-value">87<span style="font-size:.85rem;color:rgba(255,255,255,.35);">/100</span></div>
                            </div>

                            <div class="hero-card-mock">
                                <div class="mock-header">
                                    <div class="mock-dot" style="background:#f87171;"></div>
                                    <div class="mock-dot" style="background:#fbbf24;"></div>
                                    <div class="mock-dot" style="background:#34d399;"></div>
                                    <span class="mock-title">Dashboard — Kelas XI RPL 3</span>
                                </div>

                                <div class="mock-task">
                                    <div class="mock-check check-done">✓</div>
                                    <div class="mock-task-info">
                                        <div class="mock-task-name done">UTS Pemrograman Web</div>
                                        <div class="mock-task-meta">Dikumpul 3 hari lalu · 32 siswa</div>
                                    </div>
                                    <span class="mock-badge badge-done">Selesai</span>
                                </div>

                                <div class="mock-task">
                                    <div class="mock-check check-pend"></div>
                                    <div class="mock-task-info">
                                        <div class="mock-task-name">Proyek Akhir — Aplikasi Laravel</div>
                                        <div class="mock-task-meta">Deadline 5 hari lagi · 30 siswa</div>
                                    </div>
                                    <span class="mock-badge badge-process">Proses</span>
                                </div>

                                <div class="mock-task">
                                    <div class="mock-check check-pend"></div>
                                    <div class="mock-task-info">
                                        <div class="mock-task-name">Laporan Praktikum Database</div>
                                        <div class="mock-task-meta">Deadline kemarin · 28 siswa</div>
                                    </div>
                                    <span class="mock-badge badge-late">Terlambat</span>
                                </div>

                                <div class="mock-progress-wrap">
                                    <div class="mock-progress-label">
                                        <span>Progress pengumpulan minggu ini</span>
                                        <span style="color:var(--accent);font-weight:600;">72%</span>
                                    </div>
                                    <div class="mock-progress-bar">
                                        <div class="mock-progress-fill"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- STATS BAR (full width) -->
        <div class="container">
            <div class="stats-bar anim-fade-up delay-5">
                <div class="stat-item">
                    <span class="stat-num">200<em>+</em></span>
                    <span class="stat-label">Siswa Aktif</span>
                </div>
                <div class="stat-item">
                    <span class="stat-num">1.2<em>K</em></span>
                    <span class="stat-label">Tugas Terkelola</span>
                </div>
                <div class="stat-item">
                    <span class="stat-num">98<em>%</em></span>
                    <span class="stat-label">Kepuasan</span>
                </div>
                <div class="stat-item">
                    <span class="stat-num">12<em>+</em></span>
                    <span class="stat-label">Mapel</span>
                </div>
            </div>
        </div>

        <!-- FEATURES -->
        <section class="features-section" id="features">
            <div class="container">
                <div class="row mb-5">
                    <div class="col-lg-6 anim-fade-up">
                        <div class="section-label">Fitur Unggulan</div>
                        <h2 class="section-title">Semua yang kamu butuhkan,<br>dalam satu tempat.</h2>
                    </div>
                    <div class="col-lg-5 offset-lg-1 d-flex align-items-end anim-fade-up delay-2">
                        <p class="section-subtitle mb-0">
                            Dirancang khusus untuk ekosistem belajar RPL yang dinamis dan kolaboratif.
                        </p>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-6 col-lg-4 anim-fade-up delay-1">
                        <div class="feature-card">
                            <div class="feature-icon">📋</div>
                            <h3>Manajemen Tugas</h3>
                            <p>Buat, distribusikan, dan lacak tugas siswa dengan antarmuka yang intuitif dan terstruktur.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 anim-fade-up delay-2">
                        <div class="feature-card">
                            <div class="feature-icon">📊</div>
                            <h3>Progress Tracker Realtime</h3>
                            <p>Pantau kemajuan pengerjaan setiap siswa secara langsung tanpa perlu refresh halaman.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 anim-fade-up delay-3">
                        <div class="feature-card">
                            <div class="feature-icon">🗂️</div>
                            <h3>Manajemen Proyek</h3>
                            <p>Kelola proyek kelompok dengan fitur kolaborasi, pembagian peran, dan deadline tracking.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 anim-fade-up delay-4">
                        <div class="feature-card">
                            <div class="feature-icon">🔔</div>
                            <h3>Notifikasi Pintar</h3>
                            <p>Pengingat otomatis deadline tugas agar tidak ada yang terlewat dari siswa maupun guru.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 anim-fade-up delay-5">
                        <div class="feature-card">
                            <div class="feature-icon">📁</div>
                            <h3>Pengumpulan File</h3>
                            <p>Upload dan kumpulkan tugas dalam berbagai format langsung dari platform tanpa perlu email.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 anim-fade-up delay-6">
                        <div class="feature-card">
                            <div class="feature-icon">✅</div>
                            <h3>Penilaian &amp; Evaluasi</h3>
                            <p>Guru dapat memberikan nilai, feedback, dan catatan langsung pada setiap tugas yang dikumpulkan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="cta-section" id="about">
            <div class="container">
                <div class="cta-card anim-fade-up">
                    <h2>Siap untuk memulai?</h2>
                    <p>Bergabunglah dengan ratusan siswa dan guru yang sudah menggunakan RPL Tasks.</p>
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="/register" class="btn-hero-primary">Daftar Gratis</a>
                        <a href="/login"    class="btn-hero-outline">Login ke Akun</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- FOOTER -->
        <footer class="site-footer">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="footer-copy mb-0">&copy; 2026 RPL Task Management. All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="footer-tag mb-0">Dibuat dengan <span>♥</span> untuk siswa RPL</p>
                    </div>
                </div>
            </div>
        </footer>

    </div><!-- /page-content -->

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ── Navbar glass on scroll ──
        const nav = document.getElementById('mainNav');
        window.addEventListener('scroll', () => {
            nav.classList.toggle('scrolled', window.scrollY > 30);
        }, { passive: true });

        // ── Entrance animations ──
        // Navbar: trigger immediately
        nav.classList.add('is-visible');

        // Hero elements: trigger quickly for above-the-fold feel
        const heroEls = document.querySelectorAll(
            '.hero-section .anim-fade-up, .hero-section .anim-fade-in'
        );
        setTimeout(() => {
            heroEls.forEach(el => el.classList.add('is-visible'));
        }, 60);

        // Below-fold elements: trigger on scroll via IntersectionObserver
        const offScreenEls = document.querySelectorAll(
            '.features-section .anim-fade-up, .cta-section .anim-fade-up, .stats-bar.anim-fade-up'
        );
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });

        offScreenEls.forEach(el => observer.observe(el));
    </script>

</body>
</html>