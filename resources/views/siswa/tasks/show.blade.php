@extends('layouts.app')

@section('title', 'Detail Tugas')

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

    .anim-fade-up   { opacity:0; transform:translateY(18px); transition:opacity .55s cubic-bezier(.22,.68,0,1.1),transform .55s cubic-bezier(.22,.68,0,1.1); }
    .anim-fade-left { opacity:0; transform:translateX(-18px); transition:opacity .6s cubic-bezier(.22,.68,0,1.1),transform .6s cubic-bezier(.22,.68,0,1.1); }
    .anim-fade-right{ opacity:0; transform:translateX(18px); transition:opacity .6s cubic-bezier(.22,.68,0,1.1),transform .6s cubic-bezier(.22,.68,0,1.1); }
    .anim-fade-up.is-visible,.anim-fade-left.is-visible,.anim-fade-right.is-visible { opacity:1; transform:translate(0); }
    .delay-1 { transition-delay:.06s; } .delay-2 { transition-delay:.14s; } .delay-3 { transition-delay:.22s; }

    /* Page header */
    .dash-eyebrow { font-size:.7rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:var(--accent); margin-bottom:.28rem; }
    .dash-title { font-family:'Bricolage Grotesque',sans-serif; font-size:clamp(1.4rem,2.8vw,1.85rem); font-weight:800; letter-spacing:-.02em; color:var(--white); margin-bottom:.22rem; }
    .dash-subtitle { font-size:.855rem; color:rgba(255,255,255,.38); font-weight:400; }

    /* Back link */
    .back-link {
        display:inline-flex; align-items:center; gap:.45rem;
        color:rgba(255,255,255,.35); font-size:.82rem; font-weight:500;
        text-decoration:none; margin-bottom:1.5rem;
        transition:color .2s;
    }
    .back-link svg { width:14px; height:14px; }
    .back-link:hover { color:rgba(255,255,255,.65); }

    /* Layout */
    .detail-layout {
        display:grid;
        grid-template-columns:1fr 300px;
        gap:1.25rem;
        align-items:start;
    }

    /* Glass card */
    .glass-card {
        background:var(--glass-bg);
        border:1px solid var(--glass-border);
        border-radius:20px;
        backdrop-filter:blur(14px); -webkit-backdrop-filter:blur(14px);
        overflow:hidden;
    }

    /* Card top bar */
    .card-topbar {
        padding:1.4rem 1.75rem;
        border-bottom:1px solid var(--glass-border);
        display:flex; align-items:center; justify-content:space-between; gap:.75rem; flex-wrap:wrap;
    }
    .card-topbar-title {
        font-family:'Bricolage Grotesque',sans-serif;
        font-size:1rem; font-weight:800; letter-spacing:-.01em; color:var(--white);
    }
    .card-topbar-sub { font-size:.775rem; color:rgba(255,255,255,.32); font-weight:400; margin-top:.15rem; }

    /* Deadline badge */
    .deadline-badge {
        font-size:.63rem; font-weight:700;
        letter-spacing:.07em; text-transform:uppercase;
        padding:.24rem .7rem; border-radius:100px;
    }
    .badge-soon    { background:rgba(251,191,36,.14); color:#fbbf24; border:1px solid rgba(251,191,36,.22); }
    .badge-ok      { background:rgba(52,211,153,.12);  color:#34d399; border:1px solid rgba(52,211,153,.2); }
    .badge-overdue { background:rgba(248,113,113,.12); color:#f87171; border:1px solid rgba(248,113,113,.2); }

    /* Card body */
    .card-body-pad { padding:1.75rem; }

    /* Section separator */
    .form-sep {
        font-size:.65rem; font-weight:700;
        letter-spacing:.13em; text-transform:uppercase;
        color:rgba(255,255,255,.25);
        display:flex; align-items:center; gap:.6rem;
        margin:0 0 1rem;
    }
    .form-sep::after { content:''; flex:1; height:1px; background:var(--glass-border); }

    /* Judul tugas */
    .task-main-title {
        font-family:'Bricolage Grotesque',sans-serif;
        font-size:clamp(1.4rem,3vw,2rem);
        font-weight:800; letter-spacing:-.02em; color:var(--white);
        margin-bottom:.5rem; line-height:1.15;
    }

    /* Meta row */
    .meta-row {
        display:flex; align-items:center; gap:.5rem;
        font-size:.8rem; color:rgba(255,255,255,.42); font-weight:500;
        margin-bottom:1.5rem;
    }
    .meta-row svg { width:14px; height:14px; flex-shrink:0; opacity:.5; }
    .meta-sep { width:3px; height:3px; border-radius:50%; background:rgba(255,255,255,.2); }

    /* Description */
    .task-desc {
        font-size:.9rem; color:rgba(255,255,255,.58);
        line-height:1.78; font-weight:400;
        white-space:pre-wrap;
    }

    /* Divider */
    .card-divider { height:1px; background:var(--glass-border); margin:1.5rem 0; }

    /* Action buttons */
    .action-group { display:flex; gap:.75rem; flex-wrap:wrap; align-items:center; }

    .btn-download {
        display:inline-flex; align-items:center; gap:.5rem;
        background:rgba(103,111,157,.2);
        border:1px solid rgba(103,111,157,.32);
        color:var(--white);
        font-family:'Plus Jakarta Sans',sans-serif;
        font-weight:600; font-size:.875rem;
        padding:.72rem 1.5rem; border-radius:11px;
        text-decoration:none;
        transition:all .22s ease;
    }
    .btn-download svg { width:16px; height:16px; }
    .btn-download:hover { background:rgba(103,111,157,.35); border-color:var(--slate); color:var(--white); transform:translateY(-2px); }

    .btn-submit {
        display:inline-flex; align-items:center; gap:.5rem;
        background:var(--accent); color:var(--deep);
        font-family:'Plus Jakarta Sans',sans-serif;
        font-weight:700; font-size:.875rem;
        padding:.72rem 1.75rem; border-radius:11px;
        text-decoration:none; border:none;
        box-shadow:0 4px 20px rgba(249,177,122,.28);
        transition:all .22s ease;
    }
    .btn-submit svg { width:16px; height:16px; }
    .btn-submit:hover { background:#fbc08e; color:var(--deep); box-shadow:0 6px 28px rgba(249,177,122,.45); transform:translateY(-2px); }

    /* ── RIGHT PANEL — info sidebar ── */
    .info-stack { display:flex; flex-direction:column; gap:1rem; }

    .info-card {
        background:var(--glass-bg);
        border:1px solid var(--glass-border);
        border-radius:18px; padding:1.3rem 1.4rem;
        backdrop-filter:blur(14px); -webkit-backdrop-filter:blur(14px);
    }
    .info-card-label {
        font-size:.65rem; font-weight:700;
        letter-spacing:.12em; text-transform:uppercase;
        color:rgba(255,255,255,.25); margin-bottom:.85rem;
        display:flex; align-items:center; gap:.5rem;
    }
    .info-card-label::after { content:''; flex:1; height:1px; background:var(--glass-border); }

    .info-row {
        display:flex; align-items:flex-start; justify-content:space-between;
        padding:.5rem 0;
        border-bottom:1px solid rgba(103,111,157,.1);
        gap:.5rem;
    }
    .info-row:last-child { border-bottom:none; padding-bottom:0; }
    .info-row-key { font-size:.75rem; font-weight:600; color:rgba(255,255,255,.32); flex-shrink:0; }
    .info-row-val { font-size:.78rem; font-weight:500; color:rgba(255,255,255,.65); text-align:right; word-break:break-word; }
    .info-row-val.accent { color:var(--accent); }
    .info-row-val.overdue { color:#f87171; }
    .info-row-val.soon { color:#fbbf24; }

    /* No attachment notice */
    .no-attachment {
        font-size:.8rem; color:rgba(255,255,255,.25);
        font-style:italic; text-align:center;
        padding:.5rem 0;
    }

    /* Responsive */
    @media (max-width:900px) {
        .detail-layout { grid-template-columns:1fr; }
    }
    @media (max-width:575px) {
        .card-body-pad { padding:1.35rem 1.25rem; }
        .card-topbar { padding:1.1rem 1.25rem; }
    }
</style>

{{-- Back link --}}
<a href="/siswa/tasks" class="back-link anim-fade-up">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
         stroke-linecap="round" stroke-linejoin="round">
        <path d="M19 12H5M12 5l-7 7 7 7"/>
    </svg>
    Kembali ke Tugas Saya
</a>

@php
    $deadlineDate = \Carbon\Carbon::parse($task->deadline);
    $diffDays = now()->diffInDays($deadlineDate, false);
    if ($deadlineDate->isPast()) {
        $badgeClass = 'badge-overdue'; $badgeText = 'Terlambat';
        $valClass = 'overdue';
    } elseif ($diffDays <= 3) {
        $badgeClass = 'badge-soon'; $badgeText = 'Segera — ' . (int)$diffDays . ' hari lagi';
        $valClass = 'soon';
    } else {
        $badgeClass = 'badge-ok'; $badgeText = (int)$diffDays . ' hari lagi';
        $valClass = 'accent';
    }
@endphp

<div class="detail-layout">

    {{-- ══ LEFT — main content ══ --}}
    <div class="anim-fade-left delay-1">
        <div class="glass-card">

            {{-- Topbar --}}
            <div class="card-topbar">
                <div>
                    <div class="card-topbar-title">Detail Tugas</div>
                    <div class="card-topbar-sub">Baca instruksi dengan teliti sebelum mengumpulkan.</div>
                </div>
                <span class="deadline-badge {{ $badgeClass }}">{{ $badgeText }}</span>
            </div>

            <div class="card-body-pad">

                {{-- Judul --}}
                <h2 class="task-main-title">{{ $task->title }}</h2>

                {{-- Meta --}}
                <div class="meta-row">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    {{ $task->teacher->name }}
                    <span class="meta-sep"></span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    Deadline: {{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }}
                </div>

                {{-- Deskripsi --}}
                <div class="form-sep">Deskripsi Tugas</div>
                <div class="task-desc">{{ $task->description }}</div>

                <div class="card-divider"></div>

                {{-- Actions --}}
                <div class="action-group">

                    @if($task->attachment)
                        <a href="{{ asset('storage/' . $task->attachment) }}"
                           target="_blank" class="btn-download">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                                <polyline points="7 10 12 15 17 10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            Download Lampiran
                        </a>
                    @endif

                    <a href="/siswa/tasks/{{ $task->id }}/submit" class="btn-submit">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 2L11 13"/>
                            <path d="M22 2L15 22 11 13 2 9l20-7z"/>
                        </svg>
                        Progress & Submit Tugas
                    </a>

                </div>

            </div>
        </div>
    </div>

    {{-- ══ RIGHT — info sidebar ══ --}}
    <div class="info-stack anim-fade-right delay-2">

        {{-- Ringkasan tugas --}}
        <div class="info-card">
            <div class="info-card-label">Ringkasan</div>
            <div class="info-row">
                <span class="info-row-key">Guru</span>
                <span class="info-row-val">{{ $task->teacher->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-row-key">Target Kelas</span>
                <span class="info-row-val">{{ $task->class_target }}</span>
            </div>
            <div class="info-row">
                <span class="info-row-key">Deadline</span>
                <span class="info-row-val {{ $valClass }}">
                    {{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }}
                </span>
            </div>
            <div class="info-row">
                <span class="info-row-key">Sisa Waktu</span>
                <span class="info-row-val {{ $valClass }}">{{ $badgeText }}</span>
            </div>
        </div>

        {{-- Lampiran --}}
        <div class="info-card">
            <div class="info-card-label">Lampiran</div>
            @if($task->attachment)
                <a href="{{ asset('storage/' . $task->attachment) }}"
                   target="_blank" class="btn-download" style="width:100%;justify-content:center;font-size:.8rem;padding:.6rem 1rem;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                        <polyline points="7 10 12 15 17 10"/>
                        <line x1="12" y1="15" x2="12" y2="3"/>
                    </svg>
                    Download File
                </a>
            @else
                <p class="no-attachment">Tidak ada lampiran untuk tugas ini.</p>
            @endif
        </div>

        {{-- Submit shortcut --}}
        <div class="info-card" style="text-align:center;">
            <div class="info-card-label">Pengumpulan</div>
            <p style="font-size:.8rem;color:rgba(255,255,255,.36);margin-bottom:1rem;line-height:1.6;">
                Kumpulkan tugasmu sebelum deadline habis.
            </p>
            <a href="/siswa/tasks/{{ $task->id }}/submit" class="btn-submit" style="width:100%;justify-content:center;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 2L11 13"/>
                    <path d="M22 2L15 22 11 13 2 9l20-7z"/>
                </svg>
                Submit Sekarang
            </a>
        </div>

    </div>

</div>

<script>
    setTimeout(() => {
        document.querySelectorAll('.anim-fade-up, .anim-fade-left, .anim-fade-right')
            .forEach(el => el.classList.add('is-visible'));
    }, 60);
</script>

@endsection