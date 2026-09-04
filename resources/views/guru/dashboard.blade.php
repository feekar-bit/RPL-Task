@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')

<style>
    /* ════════════════════════════════════
       DASHBOARD — RPL Design System
    ════════════════════════════════════ */
    :root {
        --white:        #ffffff;
        --slate:        #676f9d;
        --mid:          #424769;
        --deep:         #2d3250;
        --deeper:       #252842;
        --accent:       #f9b17a;
        --glass-bg:     rgba(45,50,80,0.5);
        --glass-border: rgba(103,111,157,0.22);
    }

    /* ── Entrance animations ── */
    .anim-fade-up {
        opacity: 0; transform: translateY(20px);
        transition: opacity 0.55s cubic-bezier(.22,.68,0,1.1),
                    transform 0.55s cubic-bezier(.22,.68,0,1.1);
    }
    .anim-fade-up.is-visible { opacity: 1; transform: translateY(0); }
    .delay-1 { transition-delay: 0.06s; }
    .delay-2 { transition-delay: 0.14s; }
    .delay-3 { transition-delay: 0.22s; }
    .delay-4 { transition-delay: 0.32s; }
    .delay-5 { transition-delay: 0.42s; }

    /* ── Page header ── */
    .dash-header {
        margin-bottom: 2rem;
    }
    .dash-greeting {
        font-size: 0.78rem; font-weight: 600;
        letter-spacing: 0.1em; text-transform: uppercase;
        color: var(--accent); margin-bottom: 0.35rem;
    }
    .dash-title {
        font-family: 'Bricolage Grotesque', sans-serif;
        font-size: clamp(1.5rem, 3vw, 2rem);
        font-weight: 800; letter-spacing: -0.02em;
        color: var(--white); margin-bottom: 0.3rem;
    }
    .dash-subtitle {
        font-size: 0.875rem; color: rgba(255,255,255,0.42);
        font-weight: 400;
    }

    /* ── Stat cards ── */
    .stat-card {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        padding: 1.5rem 1.6rem;
        backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
        position: relative; overflow: hidden;
        transition: transform 0.28s ease, border-color 0.28s ease, box-shadow 0.28s ease;
        height: 100%;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 48px rgba(0,0,0,0.24);
    }
    .stat-card::before {
        content: ''; position: absolute; inset: 0;
        opacity: 0; transition: opacity 0.3s;
        background: linear-gradient(135deg, rgba(249,177,122,0.04) 0%, transparent 55%);
    }
    .stat-card:hover::before { opacity: 1; }
    .stat-card:hover { border-color: rgba(249,177,122,0.25); }

    .stat-icon-wrap {
        width: 44px; height: 44px; border-radius: 12px;
        display: grid; place-items: center;
        margin-bottom: 1.2rem;
    }
    .icon-accent  { background: rgba(249,177,122,0.14); border: 1px solid rgba(249,177,122,0.22); }
    .icon-slate   { background: rgba(103,111,157,0.18); border: 1px solid rgba(103,111,157,0.28); }
    .icon-green   { background: rgba(52,211,153,0.12);  border: 1px solid rgba(52,211,153,0.22); }

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
        color: var(--white); line-height: 1;
        letter-spacing: -0.02em;
    }
    .stat-value em { color: var(--accent); font-style: normal; font-size: 1.4rem; }
    .stat-desc {
        font-size: 0.75rem; color: rgba(255,255,255,0.3);
        font-weight: 400; margin-top: 0.4rem;
    }

    /* Progress mini bar inside stat card */
    .stat-progress-bar {
        height: 4px; background: rgba(103,111,157,0.2);
        border-radius: 100px; margin-top: 1rem; overflow: hidden;
    }
    .stat-progress-fill {
        height: 100%; border-radius: 100px;
        background: linear-gradient(90deg, var(--accent), #f7c59f);
        transition: width 1.2s cubic-bezier(.22,.68,0,1);
    }

    /* ── Chart card ── */
    .chart-card {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        padding: 1.75rem 1.75rem 1.5rem;
        backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
    }

    .chart-header {
        display: flex; align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        flex-wrap: wrap; gap: 0.75rem;
    }
    .chart-title-wrap {}
    .chart-eyebrow {
        font-size: 0.68rem; font-weight: 700;
        letter-spacing: 0.12em; text-transform: uppercase;
        color: rgba(255,255,255,0.32); margin-bottom: 0.25rem;
    }
    .chart-title {
        font-family: 'Bricolage Grotesque', sans-serif;
        font-size: 1.1rem; font-weight: 800;
        letter-spacing: -0.015em; color: var(--white);
    }

    /* Chart legend pills */
    .chart-legend {
        display: flex; gap: 0.65rem; flex-wrap: wrap; align-items: center;
    }
    .legend-pill {
        display: flex; align-items: center; gap: 0.4rem;
        font-size: 0.72rem; font-weight: 600;
        color: rgba(255,255,255,0.45);
    }
    .legend-dot {
        width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0;
    }

    .chart-canvas-wrap {
        position: relative;
        /* Give canvas breathing room */
    }

    /* Empty state */
    .empty-chart {
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        padding: 3rem 1rem; text-align: center;
        color: rgba(255,255,255,0.28);
    }
    .empty-chart svg { margin-bottom: 0.75rem; opacity: 0.3; }
    .empty-chart p { font-size: 0.875rem; margin: 0; }
</style>

{{-- ════════════════════════════
     PAGE HEADER
════════════════════════════ --}}
<div class="dash-header anim-fade-up">
    <div class="dash-greeting">Selamat datang kembali 👋</div>
    <h1 class="dash-title">{{ auth()->user()->name }}</h1>
    <p class="dash-subtitle">Berikut ringkasan aktivitas kelas kamu hari ini.</p>
</div>

{{-- ════════════════════════════
     STAT CARDS
════════════════════════════ --}}
<div class="row g-3 mb-4">

    {{-- Total Tugas --}}
    <div class="col-12 col-md-4 anim-fade-up delay-1">
        <div class="stat-card">
            <div class="stat-icon-wrap icon-accent">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round" class="color-accent">
                    <path d="M9 11l3 3L22 4"/>
                    <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
                </svg>
            </div>
            <div class="stat-label">Total Tugas siswa</div>
            <div class="stat-value">{{ $totalTask }}</div>
            <div class="stat-desc">Tugas yang sudah dibuat</div>
        </div>
    </div>

    {{-- Total Submission --}}
    <div class="col-12 col-md-4 anim-fade-up delay-2">
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
    <div class="col-12 col-md-4 anim-fade-up delay-3">
        <div class="stat-card">
            <div class="stat-icon-wrap icon-green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round" class="color-green">
                    <line x1="18" y1="20" x2="18" y2="10"/>
                    <line x1="12" y1="20" x2="12" y2="4"/>
                    <line x1="6" y1="20" x2="6" y2="14"/>
                </svg>
            </div>
            <div class="stat-label">Rata-rata Progress</div>
            <div class="stat-value">
                {{ round($averageProgress) }}<em>%</em>
            </div>
            <div class="stat-desc">Progress pengerjaan siswa</div>
            <div class="stat-progress-bar">
                <div class="stat-progress-fill" id="avgProgressFill"
                     style="width: 0%;"></div>
            </div>
        </div>
    </div>

</div>

{{-- ════════════════════════════
     CHART CARD
════════════════════════════ --}}
<div class="row g-3">
    <div class="col-12 anim-fade-up delay-4">
        <div class="chart-card">

            <div class="chart-header">
                <div class="chart-title-wrap">
                    <div class="chart-eyebrow">Visualisasi</div>
                    <div class="chart-title">Grafik Progress Siswa</div>
                </div>
                <div class="chart-legend">
                    <div class="legend-pill">
                        <span class="legend-dot" style="background: var(--accent);"></span>
                        Progress (%)
                    </div>
                </div>
            </div>

            <div class="chart-canvas-wrap">
                @if($submissions->count() > 0)
                    <canvas id="progressChart" style="max-height: 340px;"></canvas>
                @else
                    <div class="empty-chart">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/>
                            <line x1="6" y1="20" x2="6" y2="14"/>
                        </svg>
                        <p>Belum ada data submission untuk ditampilkan.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // ── Animate stat cards on load ──
    setTimeout(() => {
        document.querySelectorAll('.anim-fade-up').forEach(el => el.classList.add('is-visible'));
    }, 60);

    // ── Progress bar fill ──
    setTimeout(() => {
        const fill = document.getElementById('avgProgressFill');
        if (fill) fill.style.width = '{{ round($averageProgress) }}%';
    }, 400);

    // ── Chart.js ──
    @if($submissions->count() > 0)
    const ctx = document.getElementById('progressChart');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                @foreach($submissions as $submission)
                    '{{ addslashes($submission->student->name) }}',
                @endforeach
            ],
            datasets: [{
                label: 'Progress Siswa (%)',
                data: [
                    @foreach($submissions as $submission)
                        {{ $submission->progress }},
                    @endforeach
                ],
                backgroundColor: function(context) {
                    const chart = context.chart;
                    const { ctx: c, chartArea } = chart;
                    if (!chartArea) return 'rgba(249,177,122,0.7)';
                    const gradient = c.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                    gradient.addColorStop(0, 'rgba(249,177,122,0.85)');
                    gradient.addColorStop(1, 'rgba(249,177,122,0.25)');
                    return gradient;
                },
                borderColor: 'rgba(249,177,122,0.9)',
                borderWidth: 1.5,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(37,40,66,0.95)',
                    borderColor: 'rgba(103,111,157,0.35)',
                    borderWidth: 1,
                    titleColor: 'rgba(255,255,255,0.9)',
                    bodyColor: 'rgba(255,255,255,0.6)',
                    padding: 12,
                    cornerRadius: 10,
                    callbacks: {
                        label: function(ctx) {
                            return '  Progress: ' + ctx.parsed.y + '%';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(103,111,157,0.1)', drawBorder: false },
                    ticks: {
                        color: 'rgba(255,255,255,0.38)',
                        font: { family: "'Plus Jakarta Sans'", size: 11, weight: '500' },
                        maxRotation: 30,
                    },
                    border: { display: false }
                },
                y: {
                    beginAtZero: true, max: 100,
                    grid: { color: 'rgba(103,111,157,0.12)', drawBorder: false },
                    ticks: {
                        color: 'rgba(255,255,255,0.38)',
                        font: { family: "'Plus Jakarta Sans'", size: 11 },
                        callback: val => val + '%',
                        stepSize: 20,
                    },
                    border: { display: false }
                }
            },
            animation: {
                duration: 900,
                easing: 'easeOutQuart'
            }
        }
    });
    @endif
</script>

@endsection