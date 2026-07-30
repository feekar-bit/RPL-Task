@extends('layouts.app')

@section('title', 'Feedback Submission')

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

    .dash-eyebrow{font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--accent);margin-bottom:.28rem;}
    .dash-title{font-family:'Bricolage Grotesque',sans-serif;font-size:clamp(1.4rem,2.8vw,1.85rem);font-weight:800;letter-spacing:-.02em;color:var(--white);margin-bottom:.22rem;}
    .dash-subtitle{font-size:.855rem;color:rgba(255,255,255,.38);font-weight:400;}

    /* Back link */
    .back-link{display:inline-flex;align-items:center;gap:.45rem;color:rgba(255,255,255,.35);font-size:.82rem;font-weight:500;text-decoration:none;margin-bottom:1.5rem;transition:color .2s;}
    .back-link svg{width:14px;height:14px;}
    .back-link:hover{color:rgba(255,255,255,.65);}

    /* Split layout */
    .feedback-layout{display:grid;grid-template-columns:280px 1fr;gap:1.25rem;align-items:start;}

    /* Glass card */
    .glass-card{background:var(--glass-bg);border:1px solid var(--glass-border);border-radius:20px;backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);overflow:hidden;}

    /* LEFT — student info */
    .student-card{background:var(--glass-bg);border:1px solid var(--glass-border);border-radius:20px;padding:1.75rem 1.5rem;text-align:center;backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);position:relative;overflow:hidden;}
    .student-card::before{content:'';position:absolute;top:-50px;left:50%;transform:translateX(-50%);width:160px;height:160px;background:radial-gradient(circle,rgba(249,177,122,.07) 0%,transparent 70%);pointer-events:none;}

    .stu-avatar{width:80px;height:80px;border-radius:18px;background:linear-gradient(135deg,var(--mid),var(--slate));border:2.5px solid rgba(249,177,122,.35);box-shadow:0 0 20px rgba(249,177,122,.12);display:grid;place-items:center;font-family:'Bricolage Grotesque',sans-serif;font-size:1.8rem;font-weight:800;color:var(--white);margin:0 auto 1rem;}
    .stu-name{font-family:'Bricolage Grotesque',sans-serif;font-size:1.05rem;font-weight:800;letter-spacing:-.015em;color:var(--white);margin-bottom:.28rem;}
    .stu-class{font-size:.78rem;color:rgba(255,255,255,.38);font-weight:400;margin-bottom:.8rem;}
    .stu-role{display:inline-flex;align-items:center;gap:.35rem;font-size:.62rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;padding:.24rem .7rem;border-radius:100px;background:rgba(249,177,122,.12);border:1px solid rgba(249,177,122,.22);color:var(--accent);}
    .stu-role .dot{width:5px;height:5px;border-radius:50%;background:var(--accent);animation:pulse-dot 2.2s ease infinite;}
    @keyframes pulse-dot{0%,100%{opacity:1;transform:scale(1);}50%{opacity:.4;transform:scale(.72);}}

    /* Info rows in sidebar */
    .side-divider{height:1px;background:var(--glass-border);margin:1.2rem 0;}
    .side-label{font-size:.65rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:rgba(255,255,255,.25);margin-bottom:.75rem;display:flex;align-items:center;gap:.5rem;}
    .side-label::after{content:'';flex:1;height:1px;background:var(--glass-border);}
    .side-row{display:flex;align-items:center;justify-content:space-between;padding:.48rem 0;border-bottom:1px solid rgba(103,111,157,.1);gap:.5rem;}
    .side-row:last-child{border-bottom:none;padding-bottom:0;}
    .side-key{font-size:.74rem;font-weight:600;color:rgba(255,255,255,.3);flex-shrink:0;}
    .side-val{font-size:.78rem;font-weight:500;color:rgba(255,255,255,.65);text-align:right;}
    .side-val.accent{color:var(--accent);}
    .side-val.green{color:#34d399;}

    /* Progress in sidebar */
    .progress-track{height:5px;background:rgba(103,111,157,.2);border-radius:100px;overflow:hidden;margin-top:.55rem;}
    .progress-fill{height:100%;border-radius:100px;background:linear-gradient(90deg,var(--accent),#f7c59f);transition:width 1.2s cubic-bezier(.22,.68,0,1);}
    .progress-fill.fill-low{background:linear-gradient(90deg,#f87171,#fca5a5);}
    .progress-fill.fill-mid{background:linear-gradient(90deg,#fbbf24,#fde68a);}

    /* Submission links in sidebar */
    .btn-side-link{display:flex;align-items:center;gap:.5rem;background:rgba(103,111,157,.18);border:1px solid rgba(103,111,157,.28);color:rgba(255,255,255,.65);font-size:.78rem;font-weight:600;padding:.55rem .85rem;border-radius:10px;text-decoration:none;transition:all .2s;margin-top:.65rem;}
    .btn-side-link svg{width:14px;height:14px;flex-shrink:0;}
    .btn-side-link:hover{background:rgba(103,111,157,.3);border-color:var(--slate);color:var(--white);}

    /* RIGHT — form */
    .form-topbar{padding:1.35rem 2rem;border-bottom:1px solid var(--glass-border);}
    .form-topbar-title{font-family:'Bricolage Grotesque',sans-serif;font-size:1rem;font-weight:800;letter-spacing:-.01em;color:var(--white);}
    .form-topbar-sub{font-size:.775rem;color:rgba(255,255,255,.32);font-weight:400;margin-top:.18rem;}
    .form-body{padding:1.75rem 2rem 2rem;}

    /* Alert success */
    .alert-rpl-success{background:rgba(52,211,153,.1);border:1px solid rgba(52,211,153,.24);border-radius:11px;padding:.8rem 1rem;font-size:.835rem;color:#34d399;margin-bottom:1.5rem;display:flex;align-items:center;gap:.6rem;}

    /* Form section separator */
    .form-sep{font-size:.65rem;font-weight:700;letter-spacing:.13em;text-transform:uppercase;color:rgba(255,255,255,.25);display:flex;align-items:center;gap:.6rem;margin:0 0 1.1rem;}
    .form-sep::after{content:'';flex:1;height:1px;background:var(--glass-border);}

    /* Grade input row */
    .grade-row{display:grid;grid-template-columns:1fr 1fr;gap:0 1.25rem;}

    .field-group{margin-bottom:1.15rem;}
    .field-label{display:block;font-size:.775rem;font-weight:600;color:rgba(255,255,255,.55);margin-bottom:.4rem;letter-spacing:.01em;}
    .field-hint{font-size:.7rem;color:rgba(255,255,255,.24);margin-top:.3rem;}

    .field-input,.field-textarea{
        width:100%;background:rgba(255,255,255,.05);
        border:1.5px solid rgba(103,111,157,.28);
        border-radius:11px;padding:.7rem .95rem;
        font-family:'Plus Jakarta Sans',sans-serif;font-size:.875rem;font-weight:400;
        color:var(--white);outline:none;
        transition:border-color .22s,background .22s,box-shadow .22s;-webkit-appearance:none;
    }
    .field-input[type="number"]{-moz-appearance:textfield;}
    .field-input[type="number"]::-webkit-inner-spin-button,.field-input[type="number"]::-webkit-outer-spin-button{-webkit-appearance:none;}
    .field-textarea{resize:vertical;min-height:130px;line-height:1.65;}
    .field-input::placeholder,.field-textarea::placeholder{color:rgba(255,255,255,.18);}
    .field-input:focus,.field-textarea:focus{border-color:var(--accent);background:rgba(249,177,122,.05);box-shadow:0 0 0 3px rgba(249,177,122,.11);}
    .field-input:-webkit-autofill,.field-input:-webkit-autofill:focus{-webkit-box-shadow:0 0 0 1000px rgba(37,40,66,.98) inset;-webkit-text-fill-color:var(--white);caret-color:var(--white);}

    /* Grade preview badge */
    .grade-preview-wrap{display:flex;align-items:center;gap:.6rem;margin-top:.75rem;}
    .grade-preview-label{font-size:.72rem;color:rgba(255,255,255,.3);font-weight:500;}
    .grade-preview-val{font-family:'Bricolage Grotesque',sans-serif;font-size:1.1rem;font-weight:800;color:var(--accent);}

    /* Actions */
    .form-actions{display:flex;align-items:center;gap:.85rem;flex-wrap:wrap;padding-top:.75rem;border-top:1px solid var(--glass-border);margin-top:1.5rem;}
    .btn-save{background:var(--accent);color:var(--deep);font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:.9rem;padding:.75rem 2.2rem;border-radius:11px;border:none;cursor:pointer;box-shadow:0 4px 22px rgba(249,177,122,.28);transition:all .22s ease;}
    .btn-save:hover{background:#fbc08e;box-shadow:0 6px 30px rgba(249,177,122,.46);transform:translateY(-2px);}
    .btn-save:active{transform:translateY(0);}
    .btn-back-link{background:transparent;color:rgba(255,255,255,.38);font-family:'Plus Jakarta Sans',sans-serif;font-weight:500;font-size:.875rem;padding:.75rem 1.4rem;border-radius:11px;border:1.5px solid rgba(103,111,157,.26);cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;transition:all .22s;}
    .btn-back-link:hover{border-color:rgba(103,111,157,.5);color:rgba(255,255,255,.68);background:rgba(103,111,157,.1);}

    /* Responsive */
    @media(max-width:900px){.feedback-layout{grid-template-columns:1fr;}}
    @media(max-width:575px){.form-body{padding:1.35rem 1.25rem 1.5rem;}.form-topbar{padding:1.1rem 1.25rem;}.grade-row{grid-template-columns:1fr;}}
</style>

{{-- Back link --}}
<a href="javascript:history.back()" class="back-link anim-fade-up">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
    Kembali ke Submission
</a>

@php
    $pct = $submission->progress;
    $fillClass = $pct >= 70 ? '' : ($pct >= 40 ? 'fill-mid' : 'fill-low');
@endphp

<div class="feedback-layout">

    {{-- ══ LEFT — Student info ══ --}}
    <div class="anim-fade-left delay-1">
        <div class="student-card">

            {{-- Avatar initials --}}
            <div class="stu-avatar">{{ strtoupper(substr($submission->student->name,0,2)) }}</div>
            <div class="stu-name">{{ $submission->student->name }}</div>
            <div class="stu-class">{{ $submission->student->class }}</div>
            <div class="stu-role"><span class="dot"></span> Siswa</div>

            <div class="side-divider"></div>

            {{-- Progress --}}
            <div class="side-label">Progress</div>
            <div class="side-row" style="border-bottom:none;padding-bottom:0;">
                <span class="side-key">Pengerjaan</span>
                <span class="side-val accent">{{ $submission->progress }}%</span>
            </div>
            <div class="progress-track">
                <div class="progress-fill {{ $fillClass }}" id="sideProgress" style="width:0%;"></div>
            </div>

            <div class="side-divider"></div>

            {{-- Submission links --}}
            <div class="side-label">Submission</div>

            @if($submission->submission_link)
                <a href="{{ $submission->submission_link }}" target="_blank" class="btn-side-link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/>
                        <polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>
                    </svg>
                    Buka Link Submission
                </a>
            @endif

            @if($submission->submission_file)
                <a href="{{ asset('storage/' . $submission->submission_file) }}" target="_blank" class="btn-side-link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                        <polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                    </svg>
                    Download File Submission
                </a>
            @endif

            @if(!$submission->submission_link && !$submission->submission_file)
                <div style="font-size:.78rem;color:rgba(255,255,255,.22);text-align:center;font-style:italic;margin-top:.5rem;">Belum ada file atau link.</div>
            @endif

            @if($submission->grade)
                <div class="side-divider"></div>
                <div class="side-label">Nilai Saat Ini</div>
                <div class="side-row" style="border-bottom:none;padding-bottom:0;">
                    <span class="side-key">Nilai</span>
                    <span class="side-val green" style="font-family:'Bricolage Grotesque',sans-serif;font-size:1.1rem;font-weight:800;">{{ $submission->grade }}</span>
                </div>
            @endif

        </div>
    </div>

    {{-- ══ RIGHT — Form ══ --}}
    <div class="glass-card anim-fade-right delay-1">

        <div class="form-topbar">
            <div class="form-topbar-title">Feedback Submission</div>
            <div class="form-topbar-sub">Berikan nilai dan catatan feedback untuk siswa ini.</div>
        </div>

        <div class="form-body">

            @if(session('success'))
                <div class="alert-rpl-success">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            <form action="/guru/submissions/{{ $submission->id }}/update" method="POST">
                @csrf
                @method('PUT')

                {{-- Nilai --}}
                <div class="form-sep">Penilaian</div>
                <div class="grade-row">
                    <div class="field-group">
                        <label class="field-label">Nilai (0–100)</label>
                        <input type="number" name="grade" class="field-input"
                               min="0" max="100"
                               value="{{ $submission->grade }}"
                               placeholder="Contoh: 85"
                               id="gradeInput"
                               oninput="updateGradePreview(this.value)">
                        <div class="field-hint">Masukkan angka antara 0 hingga 100.</div>
                    </div>
                    <div class="field-group" style="display:flex;flex-direction:column;justify-content:flex-end;padding-bottom:1.1rem;">
                        <div class="grade-preview-wrap">
                            <span class="grade-preview-label">Preview:</span>
                            <span class="grade-preview-val" id="gradePreview">{{ $submission->grade ?? '—' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Feedback --}}
                <div class="form-sep">Catatan Feedback</div>
                <div class="field-group">
                    <label class="field-label">Feedback Guru</label>
                    <textarea name="teacher_feedback" rows="6" class="field-textarea"
                              placeholder="Tuliskan catatan, koreksi, atau apresiasi untuk siswa…">{{ $submission->teacher_feedback }}</textarea>
                    <div class="field-hint">Feedback yang baik membantu siswa memahami kekurangan dan kelebihan mereka.</div>
                </div>

                {{-- Actions --}}
                <div class="form-actions">
                    <button type="submit" class="btn-save">Simpan Feedback</button>
                    <a href="javascript:history.back()" class="btn-back-link">Batal</a>
                </div>

            </form>
        </div>
    </div>

</div>

<script>
    setTimeout(() => {
        document.querySelectorAll('.anim-fade-up,.anim-fade-left,.anim-fade-right')
            .forEach(el => el.classList.add('is-visible'));
    }, 60);

    // Animate sidebar progress
    setTimeout(() => {
        const fill = document.getElementById('sideProgress');
        if (fill) fill.style.width = '{{ $submission->progress }}%';
    }, 400);

    // Grade preview
    function updateGradePreview(val) {
        const el = document.getElementById('gradePreview');
        el.textContent = val !== '' ? val : '—';
    }
</script>

@endsection