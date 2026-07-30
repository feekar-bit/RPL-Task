@extends('layouts.app')

@section('title', 'Submit Tugas')

@section('content')

<style>
    :root {
        --white:#ffffff; --slate:#676f9d; --mid:#424769;
        --deep:#2d3250; --accent:#f9b17a;
        --glass-bg:rgba(45,50,80,0.48); --glass-border:rgba(103,111,157,0.22);
    }
    .anim-fade-up{opacity:0;transform:translateY(18px);transition:opacity .55s cubic-bezier(.22,.68,0,1.1),transform .55s cubic-bezier(.22,.68,0,1.1);}
    .anim-fade-left{opacity:0;transform:translateX(-18px);transition:opacity .6s cubic-bezier(.22,.68,0,1.1),transform .6s cubic-bezier(.22,.68,0,1.1);}
    .anim-fade-right{opacity:0;transform:translateX(18px);transition:opacity .6s cubic-bezier(.22,.68,0,1.1),transform .6s cubic-bezier(.22,.68,0,1.1);}
    .anim-fade-up.is-visible,.anim-fade-left.is-visible,.anim-fade-right.is-visible{opacity:1;transform:translate(0);}
    .delay-1{transition-delay:.06s;} .delay-2{transition-delay:.14s;} .delay-3{transition-delay:.22s;}

    /* Page header */
    .dash-eyebrow{font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--accent);margin-bottom:.28rem;}
    .dash-title{font-family:'Bricolage Grotesque',sans-serif;font-size:clamp(1.4rem,2.8vw,1.85rem);font-weight:800;letter-spacing:-.02em;color:var(--white);margin-bottom:.22rem;}
    .dash-subtitle{font-size:.855rem;color:rgba(255,255,255,.38);font-weight:400;}

    /* Back link */
    .back-link{display:inline-flex;align-items:center;gap:.45rem;color:rgba(255,255,255,.35);font-size:.82rem;font-weight:500;text-decoration:none;margin-bottom:1.5rem;transition:color .2s;}
    .back-link svg{width:14px;height:14px;}
    .back-link:hover{color:rgba(255,255,255,.65);}

    /* Split layout */
    .submit-layout{display:grid;grid-template-columns:1fr 1.5fr;gap:1.25rem;align-items:start;}

    /* Glass card */
    .glass-card{background:var(--glass-bg);border:1px solid var(--glass-border);border-radius:20px;backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);overflow:hidden;}

    /* ── LEFT — Task detail ── */
    .task-detail-card{background:var(--glass-bg);border:1px solid var(--glass-border);border-radius:20px;backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);overflow:hidden;}

    .task-detail-topbar{padding:1.35rem 1.6rem;border-bottom:1px solid var(--glass-border);}
    .task-eyebrow{font-size:.65rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:rgba(255,255,255,.28);margin-bottom:.5rem;}
    .task-main-title{font-family:'Bricolage Grotesque',sans-serif;font-size:1.15rem;font-weight:800;letter-spacing:-.015em;color:var(--white);line-height:1.2;}

    .task-detail-body{padding:1.35rem 1.6rem;display:flex;flex-direction:column;gap:1rem;}

    /* Meta pills */
    .meta-stack{display:flex;flex-direction:column;gap:.55rem;}
    .meta-item{display:flex;align-items:center;gap:.6rem;font-size:.8rem;color:rgba(255,255,255,.5);font-weight:400;}
    .meta-item svg{width:14px;height:14px;flex-shrink:0;opacity:.55;}
    .meta-item strong{color:rgba(255,255,255,.75);font-weight:600;}

    /* Deadline highlight */
    .deadline-highlight{
        background:rgba(249,177,122,.1);border:1px solid rgba(249,177,122,.22);
        border-radius:12px;padding:.75rem 1rem;
        display:flex;align-items:center;gap:.65rem;
    }
    .deadline-highlight svg{width:16px;height:16px;color:var(--accent);flex-shrink:0;}
    .deadline-highlight-text{font-size:.8rem;font-weight:400;color:rgba(255,255,255,.55);}
    .deadline-highlight-date{font-family:'Bricolage Grotesque',sans-serif;font-size:.95rem;font-weight:800;color:var(--accent);}

    /* Desc section */
    .detail-sep{font-size:.63rem;font-weight:700;letter-spacing:.13em;text-transform:uppercase;color:rgba(255,255,255,.22);display:flex;align-items:center;gap:.5rem;}
    .detail-sep::after{content:'';flex:1;height:1px;background:var(--glass-border);}
    .task-desc-txt{font-size:.855rem;color:rgba(255,255,255,.5);line-height:1.75;font-weight:400;white-space:pre-wrap;}

    /* Download lampiran */
    .btn-download-lampiran{display:flex;align-items:center;gap:.55rem;background:rgba(103,111,157,.18);border:1px solid rgba(103,111,157,.28);color:rgba(255,255,255,.65);font-family:'Plus Jakarta Sans',sans-serif;font-size:.8rem;font-weight:600;padding:.65rem 1rem;border-radius:11px;text-decoration:none;transition:all .22s;}
    .btn-download-lampiran svg{width:15px;height:15px;flex-shrink:0;}
    .btn-download-lampiran:hover{background:rgba(103,111,157,.32);border-color:var(--slate);color:var(--white);}

    /* ── RIGHT — Form + Feedback ── */
    .form-topbar{padding:1.35rem 2rem;border-bottom:1px solid var(--glass-border);}
    .form-topbar-title{font-family:'Bricolage Grotesque',sans-serif;font-size:1rem;font-weight:800;letter-spacing:-.01em;color:var(--white);}
    .form-topbar-sub{font-size:.775rem;color:rgba(255,255,255,.32);font-weight:400;margin-top:.18rem;}
    .form-body{padding:1.75rem 2rem 2rem;}

    /* Alerts */
    .alert-rpl-success{background:rgba(52,211,153,.1);border:1px solid rgba(52,211,153,.24);border-radius:11px;padding:.8rem 1rem;font-size:.835rem;color:#34d399;margin-bottom:1.4rem;display:flex;align-items:center;gap:.6rem;}
    .alert-rpl-danger{background:rgba(220,80,80,.11);border:1px solid rgba(220,80,80,.26);border-radius:11px;padding:.8rem 1rem;font-size:.835rem;color:#f87171;margin-bottom:1.4rem;}
    .alert-rpl-danger ul{margin:0;padding-left:1.1rem;}
    .alert-rpl-danger li{margin-top:.18rem;}

    /* Form separator */
    .form-sep{font-size:.65rem;font-weight:700;letter-spacing:.13em;text-transform:uppercase;color:rgba(255,255,255,.25);display:flex;align-items:center;gap:.6rem;margin:0 0 1.1rem;}
    .form-sep::after{content:'';flex:1;height:1px;background:var(--glass-border);}

    /* Fields */
    .field-group{margin-bottom:1.15rem;}
    .field-label{display:block;font-size:.775rem;font-weight:600;color:rgba(255,255,255,.55);margin-bottom:.4rem;letter-spacing:.01em;}
    .field-input,.field-textarea{
        width:100%;background:rgba(255,255,255,.05);
        border:1.5px solid rgba(103,111,157,.28);
        border-radius:11px;padding:.7rem .95rem;
        font-family:'Plus Jakarta Sans',sans-serif;font-size:.875rem;font-weight:400;
        color:var(--white);outline:none;
        transition:border-color .22s,background .22s,box-shadow .22s;-webkit-appearance:none;
    }
    .field-textarea{resize:vertical;min-height:100px;line-height:1.65;}
    .field-input::placeholder,.field-textarea::placeholder{color:rgba(255,255,255,.18);}
    .field-input:focus,.field-textarea:focus{border-color:var(--accent);background:rgba(249,177,122,.05);box-shadow:0 0 0 3px rgba(249,177,122,.11);}
    .field-hint{font-size:.7rem;color:rgba(255,255,255,.24);margin-top:.3rem;}

    /* ── Progress Range Slider ── */
    .progress-field{margin-bottom:1.15rem;}
    .progress-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:.65rem;}
    .progress-label-txt{font-size:.775rem;font-weight:600;color:rgba(255,255,255,.55);letter-spacing:.01em;}
    .progress-pct-badge{
        font-family:'Bricolage Grotesque',sans-serif;
        font-size:1.1rem;font-weight:800;color:var(--accent);
        background:rgba(249,177,122,.12);border:1px solid rgba(249,177,122,.22);
        padding:.18rem .7rem;border-radius:8px;line-height:1;
        min-width:62px;text-align:center;
        transition:color .2s;
    }

    /* Custom range */
    input[type="range"].rpl-range{
        width:100%;-webkit-appearance:none;appearance:none;
        height:6px;border-radius:100px;outline:none;cursor:pointer;
        background:rgba(103,111,157,.25);
    }
    input[type="range"].rpl-range::-webkit-slider-thumb{
        -webkit-appearance:none;appearance:none;
        width:20px;height:20px;border-radius:50%;
        background:var(--accent);
        border:3px solid rgba(28,32,56,.9);
        box-shadow:0 0 0 2px rgba(249,177,122,.4),0 2px 8px rgba(249,177,122,.3);
        transition:transform .18s,box-shadow .18s;
        cursor:pointer;
    }
    input[type="range"].rpl-range::-webkit-slider-thumb:hover{transform:scale(1.15);box-shadow:0 0 0 3px rgba(249,177,122,.5),0 4px 14px rgba(249,177,122,.4);}
    input[type="range"].rpl-range::-moz-range-thumb{width:20px;height:20px;border-radius:50%;background:var(--accent);border:3px solid rgba(28,32,56,.9);cursor:pointer;}

    /* Track fill visual */
    .range-track-wrap{position:relative;margin-top:.55rem;}
    .range-labels{display:flex;justify-content:space-between;margin-top:.45rem;}
    .range-label-txt{font-size:.68rem;color:rgba(255,255,255,.22);font-weight:500;}

    /* File input */
    .file-wrap{position:relative;}
    .file-hidden{position:absolute;inset:0;width:100%;opacity:0;cursor:pointer;z-index:2;}
    .file-display{width:100%;background:rgba(255,255,255,.04);border:1.5px dashed rgba(103,111,157,.32);border-radius:11px;padding:.7rem .95rem;font-size:.845rem;color:rgba(255,255,255,.32);display:flex;align-items:center;gap:.6rem;cursor:pointer;transition:border-color .22s,background .22s,color .22s;}
    .file-wrap:hover .file-display{border-color:rgba(249,177,122,.36);background:rgba(249,177,122,.04);color:rgba(255,255,255,.6);}
    .file-display svg{width:16px;height:16px;flex-shrink:0;opacity:.5;}

    /* View prev file */
    .prev-file-link{display:inline-flex;align-items:center;gap:.45rem;font-size:.78rem;font-weight:600;color:var(--slate);text-decoration:none;margin-top:.55rem;transition:color .2s;}
    .prev-file-link svg{width:13px;height:13px;}
    .prev-file-link:hover{color:var(--white);}

    /* Submit button */
    .form-actions{display:flex;align-items:center;gap:.85rem;flex-wrap:wrap;padding-top:.75rem;border-top:1px solid var(--glass-border);margin-top:1.5rem;}
    .btn-save{background:var(--accent);color:var(--deep);font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:.9rem;padding:.75rem 2.2rem;border-radius:11px;border:none;cursor:pointer;box-shadow:0 4px 22px rgba(249,177,122,.28);transition:all .22s ease;display:inline-flex;align-items:center;gap:.5rem;}
    .btn-save svg{width:16px;height:16px;}
    .btn-save:hover{background:#fbc08e;box-shadow:0 6px 30px rgba(249,177,122,.46);transform:translateY(-2px);}
    .btn-save:active{transform:translateY(0);}

    /* ── FEEDBACK SECTION ── */
    .feedback-section{margin-top:1.25rem;}
    .feedback-card{background:var(--glass-bg);border:1px solid var(--glass-border);border-radius:20px;backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);overflow:hidden;}
    .feedback-topbar{padding:1.1rem 1.6rem;border-bottom:1px solid var(--glass-border);display:flex;align-items:center;justify-content:space-between;gap:.75rem;flex-wrap:wrap;}
    .feedback-topbar-title{font-family:'Bricolage Grotesque',sans-serif;font-size:.95rem;font-weight:800;letter-spacing:-.01em;color:var(--white);}

    /* Grade display */
    .grade-display{display:inline-flex;align-items:center;font-family:'Bricolage Grotesque',sans-serif;font-size:.9rem;font-weight:800;padding:.28rem .8rem;border-radius:9px;}
    .grade-has{background:rgba(52,211,153,.14);border:1px solid rgba(52,211,153,.24);color:#34d399;}
    .grade-none{background:rgba(103,111,157,.16);border:1px solid rgba(103,111,157,.24);color:rgba(255,255,255,.35);font-size:.72rem;font-family:'Plus Jakarta Sans',sans-serif;font-weight:600;}

    .feedback-body{padding:1.25rem 1.6rem;}
    .feedback-content{font-size:.875rem;color:rgba(255,255,255,.52);line-height:1.75;font-weight:400;font-style:italic;}
    .feedback-content.has-feedback{color:rgba(255,255,255,.68);font-style:normal;}

    /* Responsive */
    @media(max-width:900px){.submit-layout{grid-template-columns:1fr;}}
    @media(max-width:575px){.form-body{padding:1.35rem 1.25rem 1.5rem;}.form-topbar{padding:1.1rem 1.25rem;}.task-detail-topbar,.task-detail-body{padding:1.1rem 1.25rem;}}
</style>

{{-- Back link --}}
<a href="/siswa/tasks/{{ $task->id }}" class="back-link anim-fade-up">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
    Kembali ke Detail Tugas
</a>

@php
    $deadlineDate = \Carbon\Carbon::parse($task->deadline);
    $diffDays = now()->diffInDays($deadlineDate, false);
    if ($deadlineDate->isPast()) {
        $deadlineBadge = 'badge-overdue'; $deadlineText = 'Terlambat';
    } elseif ($diffDays <= 3) {
        $deadlineBadge = 'badge-soon'; $deadlineText = (int)$diffDays . ' hari lagi';
    } else {
        $deadlineBadge = 'badge-ok'; $deadlineText = (int)$diffDays . ' hari lagi';
    }
@endphp

<div class="submit-layout">

    {{-- ══ LEFT — Task detail ══ --}}
    <div class="anim-fade-left delay-1">
        <div class="task-detail-card">

            <div class="task-detail-topbar">
                <div class="task-eyebrow">Detail Tugas</div>
                <div class="task-main-title">{{ $task->title }}</div>
            </div>

            <div class="task-detail-body">

                {{-- Meta --}}
                <div class="meta-stack">
                    <div class="meta-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                        Guru: <strong>{{ $task->teacher->name }}</strong>
                    </div>
                    <div class="meta-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                        </svg>
                        Kelas: <strong>{{ $task->class_target }}</strong>
                    </div>
                </div>

                {{-- Deadline highlight --}}
                <div class="deadline-highlight">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    <div>
                        <div class="deadline-highlight-date">{{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }}</div>
                        <div class="deadline-highlight-text">Deadline · {{ $deadlineText }}</div>
                    </div>
                </div>

                {{-- Description --}}
                <div class="detail-sep">Deskripsi</div>
                <div class="task-desc-txt">{{ $task->description }}</div>

                {{-- Lampiran --}}
                @if($task->attachment)
                    <a href="{{ asset('storage/' . $task->attachment) }}" target="_blank" class="btn-download-lampiran">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                            <polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                        Download Lampiran Tugas
                    </a>
                @endif

            </div>
        </div>
    </div>

    {{-- ══ RIGHT — Form + Feedback ══ --}}
    <div class="anim-fade-right delay-1">

        {{-- Form card --}}
        <div class="glass-card">

            <div class="form-topbar">
                <div class="form-topbar-title">Progress & Submission</div>
                <div class="form-topbar-sub">Update progress dan kumpulkan tugasmu di sini.</div>
            </div>

            <div class="form-body">

                {{-- Alerts --}}
                @if(session('success'))
                    <div class="alert-rpl-success">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert-rpl-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Progress slider --}}
                    <div class="form-sep">Progress Pengerjaan</div>
                    <div class="progress-field">
                        <div class="progress-header">
                            <span class="progress-label-txt">Seberapa jauh kamu sudah mengerjakan?</span>
                            <span class="progress-pct-badge" id="progressValue">{{ $submission->progress ?? 0 }}%</span>
                        </div>
                        <div class="range-track-wrap">
                            <input type="range" name="progress" min="0" max="100"
                                   class="rpl-range"
                                   value="{{ $submission->progress ?? 0 }}"
                                   id="progressRange"
                                   oninput="updateProgress(this.value)">
                            <div class="range-labels">
                                <span class="range-label-txt">0%</span>
                                <span class="range-label-txt">25%</span>
                                <span class="range-label-txt">50%</span>
                                <span class="range-label-txt">75%</span>
                                <span class="range-label-txt">100%</span>
                            </div>
                        </div>
                    </div>

                    {{-- Catatan --}}
                    <div class="form-sep" style="margin-top:.5rem;">Catatan & Lampiran</div>

                    <div class="field-group">
                        <label class="field-label">Catatan Progress</label>
                        <textarea name="submission_note" rows="4" class="field-textarea"
                                  placeholder="Jelaskan apa yang sudah kamu kerjakan…">{{ $submission->submission_note ?? '' }}</textarea>
                    </div>

                    {{-- Link --}}
                    <div class="field-group">
                        <label class="field-label">Link Tugas</label>
                        <input type="url" name="submission_link" class="field-input"
                               placeholder="https://github.com/... atau link lainnya"
                               value="{{ $submission->submission_link ?? '' }}">
                        <div class="field-hint">GitHub, Google Drive, Figma, atau link tugas lainnya.</div>
                    </div>

                    {{-- File --}}
                    <div class="field-group">
                        <label class="field-label">Upload File / Foto</label>
                        <div class="file-wrap">
                            <input type="file" name="submission_file"
                                   class="file-hidden" id="subFileInput"
                                   onchange="handleSubFile(this)">
                            <div class="file-display">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                                    <polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                                </svg>
                                <span id="subFileLabel">Klik untuk pilih file…</span>
                            </div>
                        </div>

                        {{-- File sebelumnya --}}
                        @if(!empty($submission?->submission_file))
                            <a href="{{ asset('storage/' . $submission->submission_file) }}"
                               target="_blank" class="prev-file-link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                                    <polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                                </svg>
                                Lihat File Submission Sebelumnya
                            </a>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="form-actions">
                        <button type="submit" class="btn-save">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 2L11 13"/><path d="M22 2L15 22 11 13 2 9l20-7z"/>
                            </svg>
                            Simpan Submission
                        </button>
                    </div>

                </form>
            </div>
        </div>

        {{-- ── Feedback Guru section ── --}}
        @if($submission)
            <div class="feedback-section anim-fade-up delay-3">
                <div class="feedback-card">

                    <div class="feedback-topbar">
                        <div class="feedback-topbar-title">Feedback Guru</div>
                        @if($submission->grade)
                            <span class="grade-display grade-has">Nilai: {{ $submission->grade }}</span>
                        @else
                            <span class="grade-display grade-none">Belum Dinilai</span>
                        @endif
                    </div>

                    <div class="feedback-body">
                        @if($submission->teacher_feedback)
                            <div class="feedback-content has-feedback">{{ $submission->teacher_feedback }}</div>
                        @else
                            <div class="feedback-content">Guru belum memberikan feedback untuk submission ini.</div>
                        @endif
                    </div>

                </div>
            </div>
        @endif

    </div>{{-- /right --}}

</div>{{-- /layout --}}

<script>
    setTimeout(() => {
        document.querySelectorAll('.anim-fade-up,.anim-fade-left,.anim-fade-right')
            .forEach(el => el.classList.add('is-visible'));
    }, 60);

    function updateProgress(val) {
        const badge = document.getElementById('progressValue');
        badge.textContent = val + '%';
        // Dynamic color
        const v = parseInt(val);
        if (v >= 70)      badge.style.color = '#34d399';
        else if (v >= 40) badge.style.color = '#fbbf24';
        else              badge.style.color = 'var(--accent)';
    }
    // Init color on load
    updateProgress(document.getElementById('progressRange')?.value ?? 0);

    function handleSubFile(input) {
        const label = document.getElementById('subFileLabel');
        if (input.files && input.files[0]) {
            label.textContent = input.files[0].name;
            label.style.color = 'rgba(255,255,255,.72)';
        } else {
            label.textContent = 'Klik untuk pilih file…';
            label.style.color = '';
        }
    }
</script>

@endsection