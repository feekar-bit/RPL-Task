@extends('layouts.app')

@section('title', 'Dashboard Admin')

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
        transition: opacity .55s cubic-bezier(.22,.68,0,1.1),
                    transform .55s cubic-bezier(.22,.68,0,1.1);
    }
    .anim-fade-up.is-visible { opacity: 1; transform: translateY(0); }
    .delay-1 { transition-delay: .06s; }
    .delay-2 { transition-delay: .14s; }
    .delay-3 { transition-delay: .22s; }
    .delay-4 { transition-delay: .32s; }
    .delay-5 { transition-delay: .42s; }

    /* Page header */
    .dash-eyebrow { font-size:.7rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:var(--accent); margin-bottom:.28rem; }
    .dash-title { font-family:'Bricolage Grotesque',sans-serif; font-size:clamp(1.4rem,2.8vw,1.85rem); font-weight:800; letter-spacing:-.02em; color:var(--white); margin-bottom:.22rem; }
    .dash-subtitle { font-size:.855rem; color:rgba(255,255,255,.38); font-weight:400; }

    /* Stat cards */
    .stat-card {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 20px; padding: 1.5rem 1.6rem;
        backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
        position: relative; overflow: hidden; height: 100%;
        transition: transform .28s ease, border-color .28s ease, box-shadow .28s ease;
    }
    .stat-card::before {
        content: ''; position: absolute; inset: 0; opacity: 0;
        transition: opacity .3s;
        background: linear-gradient(135deg, rgba(249,177,122,.04) 0%, transparent 55%);
    }
    .stat-card:hover { transform: translateY(-4px); box-shadow: 0 18px 48px rgba(0,0,0,.24); border-color: rgba(249,177,122,.25); }
    .stat-card:hover::before { opacity: 1; }

    .stat-icon-wrap { width: 44px; height: 44px; border-radius: 12px; display: grid; place-items: center; margin-bottom: 1.2rem; }
    .icon-accent { background: rgba(249,177,122,.14); border: 1px solid rgba(249,177,122,.22); }
    .icon-slate  { background: rgba(103,111,157,.18); border: 1px solid rgba(103,111,157,.28); }
    .icon-red    { background: rgba(248,113,113,.12); border: 1px solid rgba(248,113,113,.2); }
    .stat-icon-wrap svg { width: 20px; height: 20px; }
    .color-accent { color: var(--accent); }
    .color-slate  { color: var(--slate); }
    .color-red    { color: #f87171; }

    .stat-label { font-size:.72rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:rgba(255,255,255,.38); margin-bottom:.45rem; }
    .stat-value { font-family:'Bricolage Grotesque',sans-serif; font-size:2.2rem; font-weight:800; color:var(--white); line-height:1; letter-spacing:-.02em; }
    .stat-desc  { font-size:.75rem; color:rgba(255,255,255,.3); font-weight:400; margin-top:.4rem; }

    /* Divider line accent bottom of card */
    .stat-accent-bar { height: 3px; border-radius: 100px; margin-top: 1.25rem; }
    .bar-accent { background: linear-gradient(90deg, var(--accent), rgba(249,177,122,.3)); }
    .bar-slate  { background: linear-gradient(90deg, var(--slate), rgba(103,111,157,.3)); }
    .bar-red    { background: linear-gradient(90deg, #f87171, rgba(248,113,113,.3)); }

    /* Quick links */
    .quick-card {
        background: var(--glass-bg); border: 1px solid var(--glass-border);
        border-radius: 20px; padding: 1.5rem 1.6rem;
        backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
        height: 100%; display: flex; flex-direction: column; justify-content: space-between;
    }
    .quick-card-label { font-size:.65rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:rgba(255,255,255,.25); margin-bottom:.75rem; display:flex; align-items:center; gap:.5rem; }
    .quick-card-label::after { content:''; flex:1; height:1px; background:var(--glass-border); }
    .quick-card-title { font-family:'Bricolage Grotesque',sans-serif; font-size:1rem; font-weight:800; color:var(--white); margin-bottom:.4rem; letter-spacing:-.01em; }
    .quick-card-sub   { font-size:.8rem; color:rgba(255,255,255,.38); font-weight:400; margin-bottom:1.25rem; line-height:1.6; }
    .btn-quick {
        display:inline-flex; align-items:center; gap:.5rem;
        background:var(--accent); color:var(--deep);
        font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; font-size:.82rem;
        padding:.6rem 1.2rem; border-radius:10px; text-decoration:none; border:none;
        box-shadow:0 4px 16px rgba(249,177,122,.25); transition:all .22s ease; width:fit-content;
    }
    .btn-quick svg { width:14px; height:14px; }
    .btn-quick:hover { background:#fbc08e; color:var(--deep); box-shadow:0 6px 24px rgba(249,177,122,.42); transform:translateY(-2px); }
</style>

{{-- Page header --}}
<div class="anim-fade-up" style="margin-bottom:1.75rem;">
    <div class="dash-eyebrow">Selamat datang kembali 👋</div>
    <h1 class="dash-title">{{ auth()->user()->name }}</h1>
    <p class="dash-subtitle">Ringkasan keseluruhan sistem RPL Task Management.</p>
</div>

{{-- Stat cards --}}
<div class="row g-3 mb-3">

    {{-- Total Guru --}}
    <div class="col-12 col-md-4 anim-fade-up delay-1">
        <div class="stat-card">
            <div class="stat-icon-wrap icon-slate">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round" class="color-slate">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                </svg>
            </div>
            <div class="stat-label">Total Guru</div>
            <div class="stat-value">{{ $totalGuru }}</div>
            <div class="stat-desc">Guru yang sudah disetujui</div>
            <div class="stat-accent-bar bar-slate"></div>
        </div>
    </div>

    {{-- Total Siswa --}}
    <div class="col-12 col-md-4 anim-fade-up delay-2">
        <div class="stat-card">
            <div class="stat-icon-wrap icon-accent">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round" class="color-accent">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M16 11l2 2 4-4"/>
                </svg>
            </div>
            <div class="stat-label">Total Siswa</div>
            <div class="stat-value">{{ $totalSiswa }}</div>
            <div class="stat-desc">Siswa yang terdaftar aktif</div>
            <div class="stat-accent-bar bar-accent"></div>
        </div>
    </div>

    {{-- Total Tugas --}}
    <div class="col-12 col-md-4 anim-fade-up delay-3">
        <div class="stat-card">
            <div class="stat-icon-wrap icon-red">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round" class="color-red">
                    <path d="M9 11l3 3L22 4"/>
                    <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
                </svg>
            </div>
            <div class="stat-label">Total Tugas</div>
            <div class="stat-value">{{ $totalTask }}</div>
            <div class="stat-desc">Tugas yang dibuat seluruh guru</div>
            <div class="stat-accent-bar bar-red"></div>
        </div>
    </div>

</div>

{{-- Quick actions --}}
<div class="row g-3">
    <div class="col-12 col-md-6 anim-fade-up delay-4">
        <div class="quick-card">
            <div>
                <div class="quick-card-label">Manajemen</div>
                <div class="quick-card-title">Approval Guru Pending</div>
                <div class="quick-card-sub">Tinjau dan setujui pendaftaran guru baru yang sedang menunggu persetujuan.</div>
            </div>
            <a href="/admin/guru/pending" class="btn-quick">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
                Lihat Approval
            </a>
        </div>
    </div>
    <div class="col-12 col-md-6 anim-fade-up delay-5">
        <div class="quick-card">
            <div>
                <div class="quick-card-label">Akun</div>
                <div class="quick-card-title">Kelola Profile Admin</div>
                <div class="quick-card-sub">Perbarui nama dan foto profil akun administrator kamu.</div>
            </div>
            <a href="/admin/profile" class="btn-quick">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
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
</script>

@endsection