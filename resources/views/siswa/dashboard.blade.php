@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')

<style>
    :root {
        --white:        #ffffff;
        --slate:        #676f9d;
        --mid:          #424769;
        --deep:         #2d3250;
        --accent:       #f9b17a;
        --glass-bg:     rgba(45,50,80,0.48);
        --glass-border: rgba(103,111,157,0.22);
    }

    .anim-fade-up {
        opacity: 0; transform: translateY(18px);
        transition: opacity 0.55s cubic-bezier(.22,.68,0,1.1),
                    transform 0.55s cubic-bezier(.22,.68,0,1.1);
    }
    .anim-fade-up.is-visible { opacity: 1; transform: translateY(0); }
    .delay-1 { transition-delay: 0.06s; }
    .delay-2 { transition-delay: 0.14s; }
    .delay-3 { transition-delay: 0.22s; }
    .delay-4 { transition-delay: 0.32s; }

    /* Page header */
    .dash-eyebrow {
        font-size: 0.7rem; font-weight: 700;
        letter-spacing: 0.12em; text-transform: uppercase;
        color: var(--accent); margin-bottom: 0.28rem;
    }
    .dash-title {
        font-family: 'Bricolage Grotesque', sans-serif;
        font-size: clamp(1.4rem, 2.8vw, 1.85rem);
        font-weight: 800; letter-spacing: -0.02em;
        color: var(--white); margin-bottom: 0.22rem;
    }
    .dash-subtitle {
        font-size: 0.855rem; color: rgba(255,255,255,0.38); font-weight: 400;
    }

    /* Stat cards */
    .stat-card {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 20px; padding: 1.5rem 1.6rem;
        backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
        position: relative; overflow: hidden;
        transition: transform 0.28s ease, border-color 0.28s ease, box-shadow 0.28s ease;
        height: 100%;
    }
    .stat-card::before {
        content: ''; position: absolute; inset: 0; opacity: 0;
        transition: opacity 0.3s;
        background: linear-gradient(135deg, rgba(249,177,122,0.04) 0%, transparent 55%);
    }
    .stat-card:hover { transform: translateY(-4px); box-shadow: 0 18px 48px rgba(0,0,0,0.24); }
    .stat-card:hover::before { opacity: 1; }
    .stat-card:hover { border-color: rgba(249,177,122,0.25); }

    .stat-icon-wrap {
        width: 44px; height: 44px; border-radius: 12px;
        display: grid; place-items: center; margin-bottom: 1.2rem;
    }
    .icon-accent { background: rgba(249,177,122,0.14); border: 1px solid rgba(249,177,122,0.22); }
    .icon-slate  { background: rgba(103,111,157,0.18); border: 1px solid rgba(103,111,157,0.28); }
    .icon-green  { background: rgba(52,211,153,0.12);  border: 1px solid rgba(52,211,153,0.22); }
    .stat-icon-wrap svg { width: 20px; height: 20px; }
    .color-accent { color: var(--accent); }
    .color-slate  { color: var(--slate); }
    .color-green  { color: #34d399; }

    .stat-label {
        font-size: 0.72rem; font-weight: 700;
        letter-spacing: 0.1em; text-transform: uppercase;
        color: rgba(255,255,255,0.38); margin-bottom: 0.45rem;
    }
    .stat-value {
        font-family: 'Bricolage Grotesque', sans-serif;
        font-size: 2.2rem; font-weight: 800;
        color: var(--white); line-height: 1; letter-spacing: -0.02em;
    }
    .stat-value em { color: var(--accent); font-style: normal; font-size: 1.4rem; }
    .stat-desc {
        font-size: 0.75rem; color: rgba(255,255,255,0.3);
        font-weight: 400; margin-top: 0.4rem;
    }

    /* Progress bar inside stat card */
    .stat-progress-bar {
        height: 4px; background: rgba(103,111,157,0.2);
        border-radius: 100px; margin-top: 1rem; overflow: hidden;
    }
    .stat-progress-fill {
        height: 100%; border-radius: 100px;
        background: linear-gradient(90deg, var(--accent), #f7c59f);
        transition: width 1.2s cubic-bezier(.22,.68,0,1);
    }

    /* Quick action card */
    .quick-card {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 20px; padding: 1.5rem 1.6rem;
        backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
        height: 100%;
        display: flex; flex-direction: column; justify-content: space-between;
    }
    .quick-card-label {
        font-size: 0.65rem; font-weight: 700;
        letter-spacing: 0.12em; text-transform: uppercase;
        color: rgba(255,255,255,0.28); margin-bottom: 0.75rem;
        display: flex; align-items: center; gap: 0.5rem;
    }
    .quick-card-label::after { content: ''; flex: 1; height: 1px; background: var(--glass-border); }
    .quick-card-title {
        font-family: 'Bricolage Grotesque', sans-serif;
        font-size: 1rem; font-weight: 800;
        color: var(--white); margin-bottom: 0.4rem; letter-spacing: -0.01em;
    }
    .quick-card-sub {
        font-size: 0.8rem; color: rgba(255,255,255,0.38);
        font-weight: 400; margin-bottom: 1.25rem; line-height: 1.6;
    }
    .btn-quick {
        display: inline-flex; align-items: center; gap: 0.5rem;
        background: var(--accent); color: var(--deep);
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700; font-size: 0.82rem;
        padding: 0.6rem 1.2rem; border-radius: 10px;
        text-decoration: none; border: none;
        box-shadow: 0 4px 16px rgba(249,177,122,0.25);
        transition: all 0.22s ease; width: fit-content;
    }
    .btn-quick svg { width: 14px; height: 14px; }
    .btn-quick:hover {
        background: #fbc08e; color: var(--deep);
        box-shadow: 0 6px 24px rgba(249,177,122,0.42);
        transform: translateY(-2px);
    }
</style>

{{-- Page header --}}
<div class="dash-header anim-fade-up" style="margin-bottom:1.75rem;">
    <div class="dash-eyebrow">Selamat datang kembali 👋</div>
    <h1 class="dash-title">{{ auth()->user()->name }}</h1>
    <p class="dash-subtitle">Berikut ringkasan progress belajar kamu.</p>
</div>

{{-- Stat cards --}}
<div class="row g-3 mb-3">

    {{-- Total Submission --}}
    <div class="col-12 col-md-4 anim-fade-up delay-1">
        <div class="stat-card">
            <div class="stat-icon-wrap icon-slate">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round" class="color-slate">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
            </div>
            <div class="stat-label">Total Submission</div>
            <div class="stat-value">{{ $totalSubmission }}</div>
            <div class="stat-desc">Tugas yang sudah dikumpulkan</div>
        </div>
    </div>

    {{-- Rata-rata Progress --}}
    <div class="col-12 col-md-4 anim-fade-up delay-2">
        <div class="stat-card">
            <div class="stat-icon-wrap icon-accent">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round" class="color-accent">
                    <line x1="18" y1="20" x2="18" y2="10"/>
                    <line x1="12" y1="20" x2="12" y2="4"/>
                    <line x1="6" y1="20" x2="6" y2="14"/>
                </svg>
            </div>
            <div class="stat-label">Rata-rata Progress</div>
            <div class="stat-value">{{ round($averageProgress) }}<em>%</em></div>
            <div class="stat-desc">Rata-rata pengerjaan semua tugas</div>
            <div class="stat-progress-bar">
                <div class="stat-progress-fill" id="avgFill" style="width:0%;"></div>
            </div>
        </div>
    </div>

    {{-- Tugas Selesai --}}
    <div class="col-12 col-md-4 anim-fade-up delay-3">
        <div class="stat-card">
            <div class="stat-icon-wrap icon-green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round" class="color-green">
                    <path d="M9 11l3 3L22 4"/>
                    <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
                </svg>
            </div>
            <div class="stat-label">Tugas Selesai</div>
            <div class="stat-value">{{ $completedTask }}</div>
            <div class="stat-desc">Tugas dengan progress 100%</div>
        </div>
    </div>

</div>

{{-- Quick actions --}}
<div class="row g-3">
    <div class="col-12 col-md-6 anim-fade-up delay-3">
        <div class="quick-card">
            <div>
                <div class="quick-card-label">Shortcut</div>
                <div class="quick-card-title">Lihat Semua Tugas</div>
                <div class="quick-card-sub">Cek tugas-tugas yang perlu dikerjakan dan pantau status pengumpulan kamu.</div>
            </div>
            <a href="/siswa/tasks" class="btn-quick">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
                Ke Halaman Tugas
            </a>
        </div>
    </div>
    <div class="col-12 col-md-6 anim-fade-up delay-4">
        <div class="quick-card">
            <div>
                <div class="quick-card-label">Akun</div>
                <div class="quick-card-title">Kelola Profile</div>
                <div class="quick-card-sub">Perbarui foto, nama, email, atau ubah password akun kamu kapan saja.</div>
            </div>
            <a href="/siswa/profile" class="btn-quick">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
                Ke Halaman Profile
            </a>
        </div>
    </div>
</div>

<script>
    setTimeout(() => {
        document.querySelectorAll('.anim-fade-up').forEach(el => el.classList.add('is-visible'));
    }, 60);
    setTimeout(() => {
        const fill = document.getElementById('avgFill');
        if (fill) fill.style.width = '{{ round($averageProgress) }}%';
    }, 400);
</script>

@endsection