@extends('layouts.app')

@section('title', 'Tugas Saya')

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
    .delay-4 { transition-delay: 0.30s; }
    .delay-5 { transition-delay: 0.38s; }
    .delay-6 { transition-delay: 0.46s; }

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

    /* Task card */
    .task-card {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        padding: 1.6rem;
        backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);
        display: flex; flex-direction: column;
        height: 100%;
        position: relative; overflow: hidden;
        transition: transform 0.28s ease, border-color 0.28s ease, box-shadow 0.28s ease;
    }
    .task-card::before {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(135deg, rgba(249,177,122,0.04) 0%, transparent 55%);
        opacity: 0; transition: opacity 0.3s; pointer-events: none;
    }
    .task-card:hover { transform: translateY(-5px); border-color: rgba(249,177,122,0.28); box-shadow: 0 20px 52px rgba(0,0,0,0.26); }
    .task-card:hover::before { opacity: 1; }

    /* Card top: deadline badge */
    .card-top {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 1rem;
    }
    .deadline-badge {
        font-size: 0.63rem; font-weight: 700;
        letter-spacing: 0.07em; text-transform: uppercase;
        padding: 0.22rem 0.65rem; border-radius: 100px;
    }
    .badge-soon    { background: rgba(251,191,36,0.14); color: #fbbf24; border: 1px solid rgba(251,191,36,0.22); }
    .badge-ok      { background: rgba(52,211,153,0.12);  color: #34d399; border: 1px solid rgba(52,211,153,0.2); }
    .badge-overdue { background: rgba(248,113,113,0.12); color: #f87171; border: 1px solid rgba(248,113,113,0.2); }

    /* Teacher pill */
    .teacher-pill {
        display: inline-flex; align-items: center; gap: 0.38rem;
        background: rgba(103,111,157,0.16);
        border: 1px solid rgba(103,111,157,0.26);
        padding: 0.2rem 0.65rem; border-radius: 100px;
        font-size: 0.65rem; font-weight: 600;
        color: var(--slate); letter-spacing: 0.02em;
    }
    .teacher-pill svg { width: 10px; height: 10px; }

    /* Card body */
    .task-card-title {
        font-family: 'Bricolage Grotesque', sans-serif;
        font-size: 1.05rem; font-weight: 800;
        letter-spacing: -0.015em; color: var(--white);
        margin-bottom: 0.5rem; line-height: 1.25;
    }
    .task-card-deadline {
        display: flex; align-items: center; gap: 0.45rem;
        font-size: 0.78rem; color: rgba(255,255,255,0.42);
        font-weight: 500; margin-bottom: 1.4rem;
    }
    .task-card-deadline svg { width: 13px; height: 13px; flex-shrink: 0; opacity: 0.5; }

    /* Card footer */
    .task-card-footer {
        margin-top: auto;
        padding-top: 1rem;
        border-top: 1px solid rgba(103,111,157,0.15);
    }
    .btn-detail {
        display: inline-flex; align-items: center; gap: 0.45rem;
        background: var(--accent); color: var(--deep);
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700; font-size: 0.82rem;
        padding: 0.6rem 1.2rem; border-radius: 10px;
        text-decoration: none;
        box-shadow: 0 3px 14px rgba(249,177,122,0.25);
        transition: all 0.22s ease;
    }
    .btn-detail svg { width: 13px; height: 13px; }
    .btn-detail:hover {
        background: #fbc08e; color: var(--deep);
        box-shadow: 0 5px 22px rgba(249,177,122,0.42);
        transform: translateY(-1px);
    }

    /* Empty state */
    .empty-wrap {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);
        padding: 4rem 1.5rem;
        text-align: center;
    }
    .empty-icon {
        width: 60px; height: 60px; border-radius: 18px;
        background: rgba(103,111,157,0.14);
        border: 1px solid rgba(103,111,157,0.22);
        display: grid; place-items: center;
        margin: 0 auto 1.1rem;
    }
    .empty-icon svg { width: 26px; height: 26px; color: rgba(255,255,255,0.25); }
    .empty-title {
        font-family: 'Bricolage Grotesque', sans-serif;
        font-size: 1.05rem; font-weight: 800;
        color: rgba(255,255,255,0.38); margin-bottom: 0.4rem;
    }
    .empty-sub { font-size: 0.82rem; color: rgba(255,255,255,0.22); }
</style>

{{-- Page header --}}
<div class="anim-fade-up" style="margin-bottom:1.75rem;">
    <div class="dash-eyebrow">Akademik</div>
    <h1 class="dash-title">Tugas Saya</h1>
    <p class="dash-subtitle">Semua tugas yang diberikan untuk kelasmu.</p>
</div>

{{-- Task cards grid --}}
<div class="row g-3">

    @forelse($tasks as $i => $task)
        @php
            $deadlineDate = \Carbon\Carbon::parse($task->deadline);
            $diffDays = now()->diffInDays($deadlineDate, false);
            if ($deadlineDate->isPast()) {
                $badgeClass = 'badge-overdue'; $badgeText = 'Terlambat';
            } elseif ($diffDays <= 3) {
                $badgeClass = 'badge-soon'; $badgeText = 'Segera';
            } else {
                $badgeClass = 'badge-ok'; $badgeText = $diffDays . ' hari lagi';
            }
            $delays = ['delay-1','delay-2','delay-3','delay-4','delay-5','delay-6'];
            $delayClass = $delays[$i % 6];
        @endphp

        <div class="col-12 col-md-6 col-lg-4 anim-fade-up {{ $delayClass }}">
            <div class="task-card">

                {{-- Top row: badge + teacher --}}
                <div class="card-top">
                    <span class="deadline-badge {{ $badgeClass }}">{{ $badgeText }}</span>
                    <span class="teacher-pill">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        {{ $task->teacher->name }}
                    </span>
                </div>

                {{-- Title --}}
                <div class="task-card-title">{{ $task->title }}</div>

                {{-- Deadline --}}
                <div class="task-card-deadline">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    Deadline: {{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }}
                </div>

                {{-- Footer --}}
                <div class="task-card-footer">
                    <a href="/siswa/tasks/{{ $task->id }}" class="btn-detail">
                        Lihat Detail
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

            </div>
        </div>

    @empty
        <div class="col-12 anim-fade-up delay-1">
            <div class="empty-wrap">
                <div class="empty-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                </div>
                <div class="empty-title">Belum ada tugas</div>
                <p class="empty-sub">Belum ada tugas untuk kelas kamu saat ini.</p>
            </div>
        </div>
    @endforelse

</div>

<script>
    setTimeout(() => {
        document.querySelectorAll('.anim-fade-up').forEach(el => el.classList.add('is-visible'));
    }, 60);
</script>

@endsection