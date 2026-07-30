@extends('layouts.app')

@section('title', 'Submission Siswa')

@section('content')

<style>
    :root {
        --white:#ffffff; --slate:#676f9d; --mid:#424769;
        --deep:#2d3250; --accent:#f9b17a;
        --glass-bg:rgba(45,50,80,0.48); --glass-border:rgba(103,111,157,0.22);
    }
    .anim-fade-up{opacity:0;transform:translateY(18px);transition:opacity .55s cubic-bezier(.22,.68,0,1.1),transform .55s cubic-bezier(.22,.68,0,1.1);}
    .anim-fade-up.is-visible{opacity:1;transform:translateY(0);}
    .delay-1{transition-delay:.06s;} .delay-2{transition-delay:.14s;}

    .dash-eyebrow{font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--accent);margin-bottom:.28rem;}
    .dash-title{font-family:'Bricolage Grotesque',sans-serif;font-size:clamp(1.4rem,2.8vw,1.85rem);font-weight:800;letter-spacing:-.02em;color:var(--white);margin-bottom:.22rem;}
    .dash-subtitle{font-size:.855rem;color:rgba(255,255,255,.38);font-weight:400;}

    /* Task badge */
    .task-label-wrap{display:flex;align-items:center;gap:.65rem;margin-bottom:1.4rem;flex-wrap:wrap;}
    .task-name-badge{display:inline-flex;align-items:center;gap:.5rem;background:rgba(249,177,122,.12);border:1px solid rgba(249,177,122,.24);color:var(--accent);font-size:.78rem;font-weight:700;padding:.38rem .9rem;border-radius:100px;}
    .task-name-badge svg{width:13px;height:13px;}

    /* Glass card */
    .glass-card{background:var(--glass-bg);border:1px solid var(--glass-border);border-radius:20px;backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);overflow:hidden;}

    /* Table */
    .rpl-table{width:100%;border-collapse:separate;border-spacing:0;}
    .rpl-table thead tr{background:rgba(37,40,66,.7);}
    .rpl-table thead th{padding:.9rem 1.1rem;font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.38);border-bottom:1px solid var(--glass-border);white-space:nowrap;}
    .rpl-table thead th:first-child{padding-left:1.5rem;}
    .rpl-table thead th:last-child{padding-right:1.5rem;}
    .rpl-table tbody tr{border-bottom:1px solid rgba(103,111,157,.1);transition:background .18s;}
    .rpl-table tbody tr:last-child{border-bottom:none;}
    .rpl-table tbody tr:hover{background:rgba(103,111,157,.08);}
    .rpl-table tbody td{padding:.95rem 1.1rem;font-size:.875rem;font-weight:400;color:rgba(255,255,255,.72);vertical-align:middle;}
    .rpl-table tbody td:first-child{padding-left:1.5rem;}
    .rpl-table tbody td:last-child{padding-right:1.5rem;}

    .row-num{font-size:.75rem;font-weight:600;color:rgba(255,255,255,.28);font-family:'Bricolage Grotesque',sans-serif;}

    /* Student cell */
    .student-row{display:flex;align-items:center;gap:.7rem;}
    .student-avatar{width:32px;height:32px;border-radius:9px;flex-shrink:0;background:linear-gradient(135deg,var(--mid),var(--slate));display:grid;place-items:center;font-family:'Bricolage Grotesque',sans-serif;font-size:.68rem;font-weight:800;color:var(--white);}
    .student-name-txt{font-weight:600;color:var(--white);}
    .student-class-txt{font-size:.72rem;color:rgba(255,255,255,.35);margin-top:.1rem;}

    /* Progress bar */
    .progress-wrap{min-width:140px;}
    .progress-track{height:6px;background:rgba(103,111,157,.2);border-radius:100px;overflow:hidden;margin-bottom:.35rem;}
    .progress-fill{height:100%;border-radius:100px;background:linear-gradient(90deg,var(--accent),#f7c59f);transition:width 1s cubic-bezier(.22,.68,0,1);}
    .progress-fill.fill-low{background:linear-gradient(90deg,#f87171,#fca5a5);}
    .progress-fill.fill-mid{background:linear-gradient(90deg,#fbbf24,#fde68a);}
    .progress-pct{font-size:.7rem;font-weight:700;color:rgba(255,255,255,.5);}

    /* Grade badge */
    .grade-badge{display:inline-flex;align-items:center;font-family:'Bricolage Grotesque',sans-serif;font-size:.85rem;font-weight:800;padding:.28rem .75rem;border-radius:9px;}
    .grade-has{background:rgba(52,211,153,.14);border:1px solid rgba(52,211,153,.24);color:#34d399;}
    .grade-none{background:rgba(103,111,157,.16);border:1px solid rgba(103,111,157,.25);color:rgba(255,255,255,.35);font-size:.72rem;font-weight:600;font-family:'Plus Jakarta Sans',sans-serif;}

    /* Links */
    .btn-link-sm{display:inline-flex;align-items:center;gap:.3rem;font-size:.75rem;font-weight:600;color:var(--slate);text-decoration:none;transition:color .2s;}
    .btn-link-sm svg{width:12px;height:12px;}
    .btn-link-sm:hover{color:var(--white);}
    .no-data{font-size:.75rem;color:rgba(255,255,255,.22);font-style:italic;}

    /* Action buttons */
    .btn-feedback{display:inline-flex;align-items:center;gap:.35rem;font-family:'Plus Jakarta Sans',sans-serif;font-size:.75rem;font-weight:700;padding:.38rem .9rem;border-radius:9px;border:none;cursor:pointer;white-space:nowrap;transition:all .2s;text-decoration:none;}
    .btn-feedback svg{width:13px;height:13px;}
    .btn-fb-new{background:rgba(249,177,122,.14);border:1px solid rgba(249,177,122,.24);color:var(--accent);}
    .btn-fb-new:hover{background:rgba(249,177,122,.26);border-color:var(--accent);color:var(--accent);}
    .btn-fb-edit{background:rgba(251,191,36,.12);border:1px solid rgba(251,191,36,.22);color:#fbbf24;}
    .btn-fb-edit:hover{background:rgba(251,191,36,.24);border-color:#fbbf24;color:#fbbf24;}

    /* Empty state */
    .empty-state{text-align:center;padding:4rem 1.5rem;color:rgba(255,255,255,.28);}
    .empty-icon{width:56px;height:56px;border-radius:16px;background:rgba(103,111,157,.14);border:1px solid rgba(103,111,157,.22);display:grid;place-items:center;margin:0 auto 1rem;}
    .empty-icon svg{width:24px;height:24px;opacity:.45;}
    .empty-title{font-family:'Bricolage Grotesque',sans-serif;font-size:1rem;font-weight:800;color:rgba(255,255,255,.4);margin-bottom:.4rem;}
    .empty-sub{font-size:.82rem;}

    .table-scroll{overflow-x:auto;}
    .table-scroll::-webkit-scrollbar{height:4px;}
    .table-scroll::-webkit-scrollbar-thumb{background:rgba(103,111,157,.3);border-radius:4px;}
</style>

{{-- Page header --}}
<div class="anim-fade-up" style="margin-bottom:1.4rem;">
    <div class="dash-eyebrow">Penilaian</div>
    <h1 class="dash-title">Submission Siswa</h1>
    <p class="dash-subtitle">Tinjau dan berikan feedback untuk setiap submission.</p>
</div>

{{-- Task name badge --}}
<div class="task-label-wrap anim-fade-up delay-1">
    <span class="task-name-badge">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
        </svg>
        {{ $task->title }}
    </span>
</div>

{{-- Table --}}
<div class="glass-card anim-fade-up delay-2">
    <div class="table-scroll">
        <table class="rpl-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Siswa</th>
                    <th>Progress</th>
                    <th>Link</th>
                    <th>File</th>
                    <th>Nilai</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>

                @forelse($submissions as $submission)
                    @php
                        $pct = $submission->progress;
                        $fillClass = $pct >= 70 ? '' : ($pct >= 40 ? 'fill-mid' : 'fill-low');
                    @endphp
                    <tr>

                        {{-- No --}}
                        <td><span class="row-num">{{ $loop->iteration }}</span></td>

                        {{-- Siswa --}}
                        <td>
                            <div class="student-row">
                                <div class="student-avatar">{{ strtoupper(substr($submission->student->name,0,2)) }}</div>
                                <div>
                                    <div class="student-name-txt">{{ $submission->student->name }}</div>
                                    <div class="student-class-txt">{{ $submission->student->class }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- Progress --}}
                        <td>
                            <div class="progress-wrap">
                                <div class="progress-track">
                                    <div class="progress-fill {{ $fillClass }}"
                                         style="width:{{ $submission->progress }}%;"></div>
                                </div>
                                <div class="progress-pct">{{ $submission->progress }}%</div>
                            </div>
                        </td>

                        {{-- Link --}}
                        <td>
                            @if($submission->submission_link)
                                <a href="{{ $submission->submission_link }}" target="_blank" class="btn-link-sm">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/>
                                        <polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>
                                    </svg>
                                    Buka Link
                                </a>
                            @else
                                <span class="no-data">—</span>
                            @endif
                        </td>

                        {{-- File --}}
                        <td>
                            @if($submission->submission_file)
                                <a href="{{ asset('storage/' . $submission->submission_file) }}" target="_blank" class="btn-link-sm">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                                        <polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                                    </svg>
                                    Download
                                </a>
                            @else
                                <span class="no-data">—</span>
                            @endif
                        </td>

                        {{-- Nilai --}}
                        <td>
                            @if($submission->grade)
                                <span class="grade-badge grade-has">{{ $submission->grade }}</span>
                            @else
                                <span class="grade-badge grade-none">Belum Dinilai</span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td>
                            @if($submission->grade)
                                <a href="/guru/submissions/{{ $submission->id }}/edit" class="btn-feedback btn-fb-edit">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                    Edit Feedback
                                </a>
                            @else
                                <a href="/guru/submissions/{{ $submission->id }}/edit" class="btn-feedback btn-fb-new">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                                    </svg>
                                    Feedback
                                </a>
                            @endif
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/>
                                    </svg>
                                </div>
                                <div class="empty-title">Belum ada submission</div>
                                <p class="empty-sub">Belum ada siswa yang mengumpulkan tugas ini.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>
</div>

<script>
    // Animate progress bars after page load
    setTimeout(() => {
        document.querySelectorAll('.anim-fade-up').forEach(el => el.classList.add('is-visible'));
    }, 60);
</script>

@endsection