<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — RPL Tasks</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="icon" href="{{ asset('images/RPL Task Manager Icon Only.png') }}" type="image/png" sizes="16x16">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,700;12..96,800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* ════════════════════════════════════
           DESIGN TOKENS — konsisten welcome
        ════════════════════════════════════ */
        :root {
            --white:        #ffffff;
            --slate:        #676f9d;
            --mid:          #424769;
            --deep:         #2d3250;
            --deeper:       #252842;
            --accent:       #f9b17a;
            --glass-border: rgba(103,111,157,0.22);

            --sb-w:         248px;
            --sb-w-mini:    70px;
            --sb-transition: 0.36s cubic-bezier(0.65,0,0.35,1);

            --topbar-h:     64px;
        }

        *, *::before, *::after { box-sizing: border-box; }
        html, body { height: 100%; margin: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--deep);
            color: var(--white);
            -webkit-font-smoothing: antialiased;
        }

        /* Anti-FOUC */
        .sb-no-transition,
        .sb-no-transition * { transition: none !important; }

        /* ════════════════════════════════════
           SIDEBAR
        ════════════════════════════════════ */
        #rplSidebar {
            width: var(--sb-w);
            min-height: 100vh;
            height: 100%;
            background: var(--deeper);
            border-right: 1px solid var(--glass-border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            z-index: 300;
            transition: width var(--sb-transition);
            overflow: hidden;
            will-change: width;
            box-shadow: 4px 0 28px rgba(10,14,35,0.28);
        }

        #rplSidebar.collapsed,
        html.sb-collapsed #rplSidebar { width: var(--sb-w-mini); }

        /* ── Brand ── */
        .sb-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 18px;
            height: var(--topbar-h);
            border-bottom: 1px solid var(--glass-border);
            text-decoration: none;
            flex-shrink: 0;
            white-space: nowrap;
            overflow: hidden;
            transition: padding var(--sb-transition), gap var(--sb-transition);
        }
        #rplSidebar.collapsed .sb-brand,
        html.sb-collapsed #rplSidebar .sb-brand { padding: 0; justify-content: center; gap: 0; }

        .sb-brand-icon {
            width: 40px; height: 40px;
            background: #2d3250;
            border-radius: 10px;
            display: grid; place-items: center;
            font-size: 1rem;
            box-shadow: 0 0 14px rgba(45, 50, 80, 1);
            flex-shrink: 0;
        }
        .sb-brand-text {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-size: 1.05rem; font-weight: 700;
            color: var(--white); letter-spacing: 0.01em;
            opacity: 1;
            max-width: 160px;
            transition: opacity var(--sb-transition), max-width var(--sb-transition);
            overflow: hidden;
        }
        .sb-brand-text span { color: var(--accent); }
        #rplSidebar.collapsed .sb-brand-text,
        html.sb-collapsed #rplSidebar .sb-brand-text { opacity: 0; max-width: 0; }

        /* ── Nav list ── */
        .sb-nav {
            flex: 1;
            list-style: none;
            margin: 0;
            padding: 12px 10px;
            overflow-y: auto;
            overflow-x: hidden;
        }
        .sb-nav::-webkit-scrollbar { width: 3px; }
        .sb-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 3px; }

        .sb-section-label {
            font-size: 10px; font-weight: 700;
            letter-spacing: 1.4px; text-transform: uppercase;
            color: rgba(255,255,255,0.25);
            padding: 10px 10px 5px;
            white-space: nowrap; overflow: hidden;
            opacity: 1; max-height: 40px;
            transition: opacity var(--sb-transition), max-height var(--sb-transition), padding var(--sb-transition);
            margin: 0;
        }
        #rplSidebar.collapsed .sb-section-label,
        html.sb-collapsed #rplSidebar .sb-section-label {
            opacity: 0; max-height: 0; padding-top: 0; padding-bottom: 0; pointer-events: none;
        }

        .sb-divider {
            height: 1px; background: var(--glass-border);
            margin: 6px 10px;
        }

        .sb-nav-item { margin-bottom: 2px; }

        .sb-nav-link {
            display: flex; align-items: center; gap: 11px;
            padding: 10px 12px;
            border-radius: 12px;
            text-decoration: none;
            color: rgba(255,255,255,0.55);
            font-size: 0.875rem; font-weight: 600;
            white-space: nowrap; overflow: hidden;
            transition: background 0.2s, color 0.2s, padding var(--sb-transition), justify-content var(--sb-transition), gap var(--sb-transition);
            position: relative;
        }
        .sb-nav-link:hover { background: rgba(255,255,255,0.07); color: var(--white); }
        .sb-nav-link.active {
            background: rgba(249,177,122,0.14);
            color: var(--accent);
            box-shadow: inset 0 0 0 1px rgba(249,177,122,0.2);
        }

        /* Collapsed: center icon */
        #rplSidebar.collapsed .sb-nav-link,
        html.sb-collapsed #rplSidebar .sb-nav-link {
            padding: 10px; justify-content: center; gap: 0;
        }

        /* Tooltip on collapsed */
        #rplSidebar.collapsed .sb-nav-link::after,
        html.sb-collapsed #rplSidebar .sb-nav-link::after {
            content: attr(data-tooltip);
            position: fixed;
            left: calc(var(--sb-w-mini) + 10px);
            background: var(--mid);
            color: var(--white);
            font-size: 12px; font-weight: 600;
            padding: 5px 11px;
            border-radius: 8px;
            border: 1px solid var(--glass-border);
            white-space: nowrap;
            opacity: 0; pointer-events: none;
            transition: opacity 0.15s;
            z-index: 400;
        }
        #rplSidebar.collapsed .sb-nav-link:hover::after,
        html.sb-collapsed #rplSidebar .sb-nav-link:hover::after { opacity: 1; }

        .sb-nav-icon { width: 18px; height: 18px; flex-shrink: 0; }

        .sb-nav-label {
            opacity: 1; max-width: 150px;
            transition: opacity var(--sb-transition), max-width var(--sb-transition);
            overflow: hidden; flex-shrink: 0;
        }
        #rplSidebar.collapsed .sb-nav-label,
        html.sb-collapsed #rplSidebar .sb-nav-label { opacity: 0; max-width: 0; pointer-events: none; }

        /* ── Bottom: profile + logout ── */
        .sb-bottom {
            padding: 10px;
            border-top: 1px solid var(--glass-border);
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        /* Profile row inside sidebar */
        .sb-user-card {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px;
            border-radius: 12px;
            text-decoration: none;
            color: rgba(255,255,255,0.65);
            font-size: 0.82rem; font-weight: 500;
            white-space: nowrap; overflow: hidden;
            transition: background 0.2s, color 0.2s, padding var(--sb-transition), justify-content var(--sb-transition), gap var(--sb-transition);
        }
        .sb-user-card:hover { background: rgba(255,255,255,0.07); color: var(--white); }
        .sb-user-card.active { background: rgba(249,177,122,0.14); color: var(--accent); box-shadow: inset 0 0 0 1px rgba(249,177,122,0.2); }

        #rplSidebar.collapsed .sb-user-card,
        html.sb-collapsed #rplSidebar .sb-user-card { padding: 10px; justify-content: center; gap: 0; }

        .sb-avatar-mini {
            width: 28px; height: 28px; border-radius: 8px; flex-shrink: 0;
            background: linear-gradient(135deg, var(--mid), var(--slate));
            display: grid; place-items: center;
            font-size: 0.7rem; font-weight: 700; color: var(--white);
            letter-spacing: 0.02em;
        }
        .sb-user-label {
            opacity: 1; max-width: 150px;
            transition: opacity var(--sb-transition), max-width var(--sb-transition);
            overflow: hidden; flex-shrink: 0;
        }
        #rplSidebar.collapsed .sb-user-label,
        html.sb-collapsed #rplSidebar .sb-user-label { opacity: 0; max-width: 0; }

        /* Logout button */
        .sb-logout-btn {
            display: flex; align-items: center; gap: 11px;
            width: 100%; padding: 10px 12px;
            border-radius: 12px; border: none; background: transparent;
            color: rgba(255,255,255,0.45);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.875rem; font-weight: 600;
            white-space: nowrap; overflow: hidden;
            cursor: pointer; text-align: left;
            transition: background 0.2s, color 0.2s, padding var(--sb-transition), justify-content var(--sb-transition), gap var(--sb-transition);
        }
        .sb-logout-btn:hover { background: rgba(248,113,113,0.1); color: #f87171; }
        #rplSidebar.collapsed .sb-logout-btn,
        html.sb-collapsed #rplSidebar .sb-logout-btn { padding: 10px; justify-content: center; gap: 0; }

        /* ── Collapse toggle button ── */
        #sbToggleBtn {
            display: flex; align-items: center; gap: 8px;
            padding: 10px 12px; border: none;
            background: transparent;
            color: rgba(255,255,255,0.3);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.78rem; font-weight: 600;
            cursor: pointer; white-space: nowrap; overflow: hidden;
            width: 100%;
            border-top: 1px solid var(--glass-border);
            transition: color 0.2s, padding var(--sb-transition), justify-content var(--sb-transition), gap var(--sb-transition);
        }
        #sbToggleBtn:hover { color: rgba(255,255,255,0.65); }
        #rplSidebar.collapsed #sbToggleBtn,
        html.sb-collapsed #rplSidebar #sbToggleBtn { padding: 10px; justify-content: center; gap: 0; }

        .sb-toggle-icon {
            width: 18px; height: 18px; flex-shrink: 0;
            transition: transform var(--sb-transition);
        }
        #rplSidebar.collapsed .sb-toggle-icon,
        html.sb-collapsed #rplSidebar .sb-toggle-icon { transform: rotate(180deg); }

        .sb-toggle-label {
            opacity: 1; max-width: 120px;
            transition: opacity var(--sb-transition), max-width var(--sb-transition);
            overflow: hidden;
        }
        #rplSidebar.collapsed .sb-toggle-label,
        html.sb-collapsed #rplSidebar .sb-toggle-label { opacity: 0; max-width: 0; }

        /* ── Overlay mobile ── */
        #sbOverlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.55); z-index: 299;
            backdrop-filter: blur(2px);
        }
        #sbOverlay.visible { display: block; }

        /* ════════════════════════════════════
           MAIN CONTENT AREA
        ════════════════════════════════════ */
        .rpl-main {
            margin-left: var(--sb-w);
            transition: margin-left var(--sb-transition);
            min-height: 100vh;
            display: flex; flex-direction: column;
            background: var(--deep);
        }
        html.sb-collapsed .rpl-main { margin-left: var(--sb-w-mini); }

        /* ── Topbar ── */
        .rpl-topbar {
            height: var(--topbar-h);
            background: rgba(37,40,66,0.85);
            backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--glass-border);
            position: sticky; top: 0; z-index: 200;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 1.75rem;
            flex-shrink: 0;
        }

        /* Hamburger (mobile) + page title */
        .topbar-left { display: flex; align-items: center; gap: 0.9rem; }

        .hamburger-btn {
            display: none;
            width: 36px; height: 36px; border-radius: 9px;
            border: 1px solid var(--glass-border);
            background: transparent; cursor: pointer;
            color: rgba(255,255,255,0.6);
            align-items: center; justify-content: center;
        }
        @media (max-width: 768px) {
            .hamburger-btn { display: flex; }
        }

        .topbar-page-title {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-size: 1.05rem; font-weight: 700;
            color: var(--white); letter-spacing: -0.01em;
        }

        /* Right: user info */
        .topbar-right { display: flex; align-items: center; gap: 0.85rem; }

        .topbar-user-name {
            font-size: 0.85rem; font-weight: 600;
            color: rgba(255,255,255,0.72);
            line-height: 1;
        }
        .topbar-user-role {
            font-size: 0.7rem; font-weight: 500;
            color: rgba(255,255,255,0.35);
            margin-top: 2px; text-transform: capitalize;
        }

        .topbar-avatar {
            width: 36px; height: 36px; border-radius: 10px;
            background: linear-gradient(135deg, var(--mid) 0%, var(--slate) 100%);
            border: 2px solid rgba(249,177,122,0.35);
            display: grid; place-items: center;
            font-size: 0.8rem; font-weight: 700;
            color: var(--white); flex-shrink: 0;
            box-shadow: 0 0 12px rgba(249,177,122,0.15);
        }

        /* Role badge on topbar */
        .topbar-role-badge {
            font-size: 0.65rem; font-weight: 700;
            letter-spacing: 0.08em; text-transform: uppercase;
            padding: 0.28rem 0.65rem; border-radius: 100px;
        }
        .badge-guru  { background: rgba(103,111,157,0.2); color: var(--slate); border: 1px solid rgba(103,111,157,0.3); }
        .badge-siswa { background: rgba(249,177,122,0.15); color: var(--accent); border: 1px solid rgba(249,177,122,0.25); }
        .badge-admin { background: rgba(248,113,113,0.12); color: #f87171; border: 1px solid rgba(248,113,113,0.22); }

        /* ── Page content ── */
        .rpl-content {
            flex: 1;
            padding: 2rem 1.75rem;
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            #rplSidebar { transform: translateX(-100%); width: var(--sb-w) !important; transition: transform var(--sb-transition); }
            #rplSidebar.mobile-open { transform: translateX(0); }
            .rpl-main { margin-left: 0 !important; }
        }
    </style>
</head>
<body>

{{-- Anti-FOUC --}}
<script>
(function() {
    if (window.innerWidth > 768 && localStorage.getItem('rpl_sb_collapsed') === '1') {
        document.documentElement.classList.add('sb-collapsed');
    }
})();
</script>

{{-- Overlay mobile --}}
<div id="sbOverlay"></div>

<!-- ══════════════════════════════
     SIDEBAR
══════════════════════════════ -->
<div id="rplSidebar">

    {{-- Brand --}}
    <a href="/" class="sb-brand">
        <div class="sb-brand-icon">
            <img src="{{ asset('images/RPL Task Manager Icon Only.png') }}" alt="RPL Task Manager Logo" style="width:30px;height:30px;">
        </div>
        <span class="sb-brand-text">RPL<span>Tasks</span></span>
    </a>

    {{-- Nav --}}
    <ul class="sb-nav">

        {{-- ── ADMIN ── --}}
        @if(auth()->user()->role == 'admin')

            <li><p class="sb-section-label">Menu Utama</p></li>

            <li class="sb-nav-item">
                <a href="/admin/dashboard"
                   class="sb-nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}"
                   data-tooltip="Dashboard">
                    <svg class="sb-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                        <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                    </svg>
                    <span class="sb-nav-label">Dashboard</span>
                </a>
            </li>

            <li><div class="sb-divider"></div></li>
            <li><p class="sb-section-label">Manajemen</p></li>

            <li class="sb-nav-item">
                <a href="/admin/guru/pending"
                   class="sb-nav-link {{ request()->is('admin/guru/pending') ? 'active' : '' }}"
                   data-tooltip="Approval Guru">
                    <svg class="sb-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <polyline points="16 11 18 13 22 9"/>
                    </svg>
                    <span class="sb-nav-label">Approval Guru</span>
                </a>

                
            </li>
            <li class="sb-nav-item">
                <a href="/admin/teachers"
                   class="sb-nav-link {{ request()->is('admin/teachers*') ? 'active' : '' }}"
                   data-tooltip="Data Guru">
                    <svg class="sb-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 00-3-3.87"/>
                        <path d="M16 3.13a4 4 0 010 7.75"/>
                    </svg>
                    <span class="sb-nav-label">Data Guru</span>
                </a>
            </li>

        @endif

        {{-- ── GURU ── --}}
        @if(auth()->user()->role == 'guru')

            <li><p class="sb-section-label">Menu Utama</p></li>

            <li class="sb-nav-item">
                <a href="/guru/dashboard"
                   class="sb-nav-link {{ request()->is('guru/dashboard') ? 'active' : '' }}"
                   data-tooltip="Dashboard">
                    <svg class="sb-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                        <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                    </svg>
                    <span class="sb-nav-label">Dashboard</span>
                </a>
            </li>

            <li><div class="sb-divider"></div></li>
            <li><p class="sb-section-label">Manajemen</p></li>

            <li class="sb-nav-item">
                <a href="/guru/tasks"
                   class="sb-nav-link {{ request()->is('guru/tasks*') && !request()->is('guru/tasks/history*') ? 'active' : '' }}"
                   data-tooltip="Tugas">
                    <svg class="sb-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 11l3 3L22 4"/>
                        <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
                    </svg>
                    <span class="sb-nav-label">Tugas</span>
                </a>
            </li>

            <li class="sb-nav-item">
                <a href="/guru/students"
                   class="sb-nav-link {{ request()->is('guru/students*') ? 'active' : '' }}"
                   data-tooltip="Data Siswa">
                    <svg class="sb-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 00-3-3.87"/>
                        <path d="M16 3.13a4 4 0 010 7.75"/>
                    </svg>
                    <span class="sb-nav-label">Data Siswa</span>
                </a>
            </li>

            <li class="sb-nav-item">
                <a href="/guru/tasks/history"
                   class="sb-nav-link {{ request()->is('guru/tasks/history*') ? 'active' : '' }}"
                   data-tooltip="History Tugas">
                    <svg class="sb-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 8v4l3 3"/>
                        <path d="M3.05 11a9 9 0 1 0 .5-4.5"/>
                        <polyline points="3 3 3 7 7 7"/>
                    </svg>
                    <span class="sb-nav-label">Riwayat Tugas</span>
                </a>
            </li>

        @endif

        {{-- ── SISWA ── --}}
        @if(auth()->user()->role == 'siswa')

            <li><p class="sb-section-label">Menu Utama</p></li>

            <li class="sb-nav-item">
                <a href="/siswa/dashboard"
                   class="sb-nav-link {{ request()->is('siswa/dashboard') ? 'active' : '' }}"
                   data-tooltip="Dashboard">
                    <svg class="sb-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                        <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                    </svg>
                    <span class="sb-nav-label">Dashboard</span>
                </a>
            </li>

            <li><div class="sb-divider"></div></li>
            <li><p class="sb-section-label">Akademik</p></li>

            <li class="sb-nav-item">
                <a href="/siswa/tasks"
                   class="sb-nav-link {{ request()->is('siswa/tasks*') ? 'active' : '' }}"
                   data-tooltip="Tugas Saya">
                    <svg class="sb-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                        <polyline points="10 9 9 9 8 9"/>
                    </svg>
                    <span class="sb-nav-label">Tugas Saya</span>
                </a>
            </li>

        @endif

    </ul>

    {{-- ── Bottom: Profile + Logout ── --}}
    <div class="sb-bottom">

        {{-- Profile link --}}
        @if(auth()->user()->role == 'admin')
            @php $profileUrl = '/admin/profile'; @endphp
        @elseif(auth()->user()->role == 'guru')
            @php $profileUrl = '/guru/profile'; @endphp
        @else
            @php $profileUrl = '/siswa/profile'; @endphp
        @endif

        <a href="{{ $profileUrl }}"
           class="sb-user-card {{ request()->is('*/profile') ? 'active' : '' }}"
           data-tooltip="Profile">
            <div class="sb-avatar-mini">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <span class="sb-user-label">Profile</span>
        </a>

        {{-- Logout --}}
        <form action="{{ route('logout') }}" method="POST" style="margin:0;">
            @csrf
            <button type="submit" class="sb-logout-btn" data-tooltip="Logout">
                <svg class="sb-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                <span class="sb-nav-label">Logout</span>
            </button>
        </form>

        {{-- Collapse toggle --}}
        <button id="sbToggleBtn" aria-label="Toggle sidebar">
            <svg class="sb-toggle-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"/>
            </svg>
            <span class="sb-toggle-label">Sembunyikan</span>
        </button>

    </div>

</div>

<!-- ══════════════════════════════
     MAIN CONTENT
══════════════════════════════ -->
<div class="rpl-main">

    <!-- TOPBAR -->
    <div class="rpl-topbar">

        <div class="topbar-left">
            <!-- Mobile hamburger -->
            <button class="hamburger-btn" onclick="window.openRplSidebar()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <line x1="3" y1="12" x2="21" y2="12"/>
                    <line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>

            <span class="topbar-page-title">@yield('title')</span>
        </div>

        <div class="topbar-right">

            {{-- Role badge --}}
            {{-- @if(auth()->user()->role == 'guru')
                <span class="topbar-role-badge badge-guru">Guru</span>
            @elseif(auth()->user()->role == 'siswa')
                <span class="topbar-role-badge badge-siswa">Siswa</span>
            @elseif(auth()->user()->role == 'admin')
                <span class="topbar-role-badge badge-admin">Admin</span>
            @endif --}}

            {{-- User info --}}
            <div style="text-align:right;">
                <div class="topbar-user-name">{{ auth()->user()->name }}</div>
                <div class="topbar-user-role">{{ auth()->user()->role }}</div>
            </div>

            {{-- Avatar with initials --}}
            <div class="topbar-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>

        </div>

    </div>

    <!-- PAGE CONTENT -->
    <div class="rpl-content">
        @yield('content')
    </div>

</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
(function() {
    const sidebar   = document.getElementById('rplSidebar');
    const toggleBtn = document.getElementById('sbToggleBtn');
    const overlay   = document.getElementById('sbOverlay');
    const STORAGE   = 'rpl_sb_collapsed';
    const isMobile  = () => window.innerWidth <= 768;

    if (!sidebar || !toggleBtn) return;

    // Anti-FOUC: apply state without animation on load
    (function applyInitial() {
        if (!isMobile() && localStorage.getItem(STORAGE) === '1') {
            sidebar.classList.add('sb-no-transition', 'collapsed');
            sidebar.getBoundingClientRect();
            requestAnimationFrame(() => requestAnimationFrame(() => sidebar.classList.remove('sb-no-transition')));
        }
    })();

    function syncMain(collapsed) {
        document.documentElement.classList.toggle('sb-collapsed', collapsed);
    }
    syncMain(sidebar.classList.contains('collapsed'));

    // Toggle collapse
    toggleBtn.addEventListener('click', () => {
        if (isMobile()) { closeMobile(); return; }
        const collapsed = sidebar.classList.toggle('collapsed');
        localStorage.setItem(STORAGE, collapsed ? '1' : '0');
        syncMain(collapsed);
    });

    overlay.addEventListener('click', closeMobile);

    function closeMobile() {
        sidebar.classList.remove('mobile-open');
        overlay.classList.remove('visible');
        document.body.style.overflow = '';
    }

    window.openRplSidebar = function() {
        sidebar.classList.add('mobile-open');
        overlay.classList.add('visible');
        document.body.style.overflow = 'hidden';
    };

    window.addEventListener('resize', () => {
        if (!isMobile()) {
            closeMobile();
            const saved = localStorage.getItem(STORAGE) === '1';
            sidebar.classList.toggle('collapsed', saved);
            syncMain(saved);
        } else {
            syncMain(false);
        }
    });
})();
</script>

</body>
</html>