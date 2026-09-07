@extends('layouts.app')

@section('title', 'Tambah Tugas')

@section('content')

<style>
    :root {
        --white:        #ffffff;
        --slate:        #676f9d;
        --mid:          #424769;
        --deep:         #2d3250;
        --deeper:       #252842;
        --accent:       #f9b17a;
        --glass-bg:     rgba(45,50,80,0.48);
        --glass-border: rgba(103,111,157,0.22);
    }

    .anim-fade-up {
        opacity: 0; transform: translateY(18px);
        transition: opacity 0.55s cubic-bezier(.22,.68,0,1.1),
                    transform 0.55s cubic-bezier(.22,.68,0,1.1);
    }
    .anim-fade-left {
        opacity: 0; transform: translateX(-18px);
        transition: opacity 0.6s cubic-bezier(.22,.68,0,1.1),
                    transform 0.6s cubic-bezier(.22,.68,0,1.1);
    }
    .anim-fade-right {
        opacity: 0; transform: translateX(18px);
        transition: opacity 0.6s cubic-bezier(.22,.68,0,1.1),
                    transform 0.6s cubic-bezier(.22,.68,0,1.1);
    }
    .anim-fade-up.is-visible,
    .anim-fade-left.is-visible,
    .anim-fade-right.is-visible { opacity: 1; transform: translate(0); }
    .delay-1 { transition-delay: 0.06s; }
    .delay-2 { transition-delay: 0.14s; }
    .delay-3 { transition-delay: 0.22s; }

    /* ── Page header ── */
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

    /* ── Create layout: kiri info tips, kanan form ── */
    .create-layout {
        display: grid;
        grid-template-columns: 260px 1fr;
        gap: 1.25rem;
        align-items: start;
    }

    /* ── LEFT — tip cards ── */
    .tips-stack { display: flex; flex-direction: column; gap: 1rem; }

    .tip-card {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 18px; padding: 1.35rem 1.4rem;
        backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);
    }
    .tip-icon {
        width: 40px; height: 40px; border-radius: 11px;
        display: grid; place-items: center;
        margin-bottom: 0.9rem;
        background: rgba(249,177,122,0.12);
        border: 1px solid rgba(249,177,122,0.2);
    }
    .tip-icon svg { width: 18px; height: 18px; color: var(--accent); }
    .tip-title {
        font-family: 'Bricolage Grotesque', sans-serif;
        font-size: 0.9rem; font-weight: 800;
        letter-spacing: -0.01em; color: var(--white);
        margin-bottom: 0.4rem;
    }
    .tip-body {
        font-size: 0.8rem; color: rgba(255,255,255,0.4);
        line-height: 1.68; font-weight: 400;
    }

    /* ── RIGHT — form card ── */
    .form-card {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);
        overflow: hidden;
    }

    .form-topbar {
        padding: 1.35rem 2rem;
        border-bottom: 1px solid var(--glass-border);
    }
    .form-topbar-title {
        font-family: 'Bricolage Grotesque', sans-serif;
        font-size: 1rem; font-weight: 800;
        letter-spacing: -0.01em; color: var(--white);
    }
    .form-topbar-sub {
        font-size: 0.775rem; color: rgba(255,255,255,0.35);
        font-weight: 400; margin-top: 0.18rem;
    }

    .form-body { padding: 1.75rem 2rem 2rem; }

    /* Section separator */
    .form-sep {
        font-size: 0.65rem; font-weight: 700;
        letter-spacing: 0.13em; text-transform: uppercase;
        color: rgba(255,255,255,0.25);
        display: flex; align-items: center; gap: 0.6rem;
        margin: 0 0 1.1rem;
    }
    .form-sep::after { content: ''; flex: 1; height: 1px; background: var(--glass-border); }

    /* Fields */
    .fields-grid-2 {
        display: grid; grid-template-columns: 1fr 1fr; gap: 0 1.25rem;
    }
    .field-full { grid-column: 1 / -1; }
    .field-group { margin-bottom: 1.15rem; }

    .field-label {
        display: block; font-size: 0.775rem; font-weight: 600;
        color: rgba(255,255,255,0.55); margin-bottom: 0.4rem; letter-spacing: 0.01em;
    }
    .field-input,
    .field-textarea {
        width: 100%;
        background: rgba(255,255,255,0.05);
        border: 1.5px solid rgba(103,111,157,0.28);
        border-radius: 11px; padding: 0.7rem 0.95rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.875rem; font-weight: 400;
        color: var(--white); outline: none;
        transition: border-color 0.22s, background 0.22s, box-shadow 0.22s;
        -webkit-appearance: none;
    }
    .field-textarea { resize: vertical; min-height: 110px; line-height: 1.65; }
    .field-input::placeholder,
    .field-textarea::placeholder { color: rgba(255,255,255,0.18); }
    .field-input:focus,
    .field-textarea:focus {
        border-color: var(--accent);
        background: rgba(249,177,122,0.05);
        box-shadow: 0 0 0 3px rgba(249,177,122,0.11);
    }

    /* Date input */
    input[type="date"].field-input::-webkit-calendar-picker-indicator {
        filter: invert(0.6) sepia(1) saturate(2) hue-rotate(5deg);
        cursor: pointer;
    }

    /* File input */
    .file-wrap { position: relative; }
    .file-hidden {
        position: absolute; inset: 0; width: 100%;
        opacity: 0; cursor: pointer; z-index: 2;
    }
    .file-display {
        width: 100%;
        background: rgba(255,255,255,0.04);
        border: 1.5px dashed rgba(103,111,157,0.32);
        border-radius: 11px; padding: 0.7rem 0.95rem;
        font-size: 0.845rem; color: rgba(255,255,255,0.32);
        display: flex; align-items: center; gap: 0.6rem;
        cursor: pointer;
        transition: border-color 0.22s, background 0.22s, color 0.22s;
    }
    .file-wrap:hover .file-display {
        border-color: rgba(249,177,122,0.36);
        background: rgba(249,177,122,0.04);
        color: rgba(255,255,255,0.6);
    }
    .file-display svg { width: 16px; height: 16px; flex-shrink: 0; opacity: 0.5; }
    .field-hint {
        font-size: 0.7rem; color: rgba(255,255,255,0.24); margin-top: 0.3rem;
    }

    /* Alert */
    .alert-rpl-danger {
        background: rgba(220,80,80,0.11); border: 1px solid rgba(220,80,80,0.26);
        border-radius: 11px; padding: 0.82rem 1rem;
        font-size: 0.835rem; color: #f87171;
        margin-bottom: 1.5rem;
    }
    .alert-rpl-danger ul { margin: 0; padding-left: 1.1rem; }
    .alert-rpl-danger li { margin-top: 0.18rem; }

    /* Actions */
    .form-actions {
        display: flex; align-items: center; gap: 0.85rem;
        flex-wrap: wrap; padding-top: 0.75rem;
        border-top: 1px solid var(--glass-border);
        margin-top: 1.5rem;
    }
    .btn-save {
        background: var(--accent); color: var(--deep);
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700; font-size: 0.9rem;
        padding: 0.75rem 2.2rem; border-radius: 11px; border: none; cursor: pointer;
        box-shadow: 0 4px 22px rgba(249,177,122,0.28);
        transition: all 0.22s ease;
        display: inline-flex; align-items: center; gap: 0.5rem;
    }
    .btn-save svg { width: 16px; height: 16px; }
    .btn-save:hover {
        background: #fbc08e;
        box-shadow: 0 6px 30px rgba(249,177,122,0.46);
        transform: translateY(-2px);
    }
    .btn-save:active { transform: translateY(0); }

    .btn-back {
        background: transparent; color: rgba(255,255,255,0.38);
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 500; font-size: 0.875rem;
        padding: 0.75rem 1.4rem; border-radius: 11px;
        border: 1.5px solid rgba(103,111,157,0.26);
        cursor: pointer; text-decoration: none;
        display: inline-flex; align-items: center;
        transition: all 0.22s;
    }
    .btn-back:hover {
        border-color: rgba(103,111,157,0.5);
        color: rgba(255,255,255,0.68);
        background: rgba(103,111,157,0.1);
    }

    /* ── Responsive ── */
    @media (max-width: 900px) {
        .create-layout { grid-template-columns: 1fr; }
        .tips-stack { flex-direction: row; flex-wrap: wrap; }
        .tip-card { flex: 1 1 200px; }
    }
    @media (max-width: 575px) {
        .fields-grid-2 { grid-template-columns: 1fr; }
        .form-body { padding: 1.35rem 1.25rem 1.5rem; }
        .form-topbar { padding: 1.1rem 1.25rem; }
        .tips-stack { flex-direction: column; }
    }
</style>

{{-- ── Page header ── --}}
<div class="anim-fade-up" style="margin-bottom:1.75rem;">
    <div class="dash-eyebrow">Manajemen Tugas</div>
    <h1 class="dash-title">Tambah Tugas</h1>
    <p class="dash-subtitle">Isi detail tugas yang akan diberikan ke siswa.</p>
</div>

<div class="create-layout">

    {{-- ══════════════════
         LEFT — Tips
    ══════════════════ --}}
    <div class="tips-stack">

        <div class="tip-card anim-fade-left delay-1">
            <div class="tip-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
            </div>
            <div class="tip-title">Judul yang Jelas</div>
            <div class="tip-body">Gunakan judul yang deskriptif agar siswa langsung memahami apa yang harus dikerjakan.</div>
        </div>

        <div class="tip-card anim-fade-left delay-2">
            <div class="tip-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
            </div>
            <div class="tip-title">Deadline Realistis</div>
            <div class="tip-body">Beri waktu yang cukup. Deadline yang terlalu singkat bisa menurunkan kualitas pekerjaan siswa.</div>
        </div>

        <div class="tip-card anim-fade-left delay-3">
            <div class="tip-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66L9.41 17.41a2 2 0 01-2.83-2.83l8.49-8.48"/>
                </svg>
            </div>
            <div class="tip-title">Lampiran Opsional</div>
            <div class="tip-body">Sertakan file panduan, template, atau materi pendukung jika diperlukan.</div>
        </div>

    </div>

    {{-- ══════════════════
         RIGHT — Form
    ══════════════════ --}}
    <div class="form-card anim-fade-right delay-1">

        <div class="form-topbar">
            <div class="form-topbar-title">Detail Tugas</div>
            <div class="form-topbar-sub">Lengkapi semua informasi yang dibutuhkan.</div>
        </div>

        <div class="form-body">

            {{-- Error --}}
            @if($errors->any())
                <div class="alert-rpl-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="/guru/tasks/store" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- ── Informasi Tugas ── --}}
                <div class="form-sep">Informasi Tugas</div>

                <div class="fields-grid-2">

                    {{-- JUDUL --}}
                    <div class="field-group field-full">
                        <label class="field-label">Judul Tugas</label>
                        <input type="text" name="title" class="field-input"
                               placeholder="Contoh: Proyek Akhir — Aplikasi Laravel"
                               value="{{ old('title') }}">
                    </div>

                    {{-- DESKRIPSI --}}
                    <div class="field-group field-full">
                        <label class="field-label">Deskripsi</label>
                        <textarea name="description" rows="5"
                                  class="field-textarea"
                                  placeholder="Jelaskan instruksi, tujuan, dan kriteria penilaian tugas…">{{ old('description') }}</textarea>
                    </div>

                </div>

                {{-- ── Target & Deadline ── --}}
                <div class="form-sep" style="margin-top:0.5rem;">Target & Deadline</div>

                <div class="fields-grid-2">

                    {{-- KELAS --}}
                    <div class="field-group">
                        <label class="field-label">Target Kelas</label>
                        <input type="text" name="class_target" class="field-input"
                               placeholder="Contoh: XI RPL 1"
                               value="{{ old('class_target') }}">
                    </div>

                    {{-- DEADLINE --}}
                    <div class="field-group">
                        <label class="field-label">Deadline</label>
                        <input type="date" name="deadline" class="field-input"
                               value="{{ old('deadline') }}">
                    </div>

                </div>

                {{-- ── Lampiran ── --}}
                <div class="form-sep" style="margin-top:0.5rem;">Lampiran</div>

                <div class="field-group">
                    <label class="field-label">File Lampiran</label>
                    <div class="file-wrap">
                        <input type="file" name="attachment"
                               class="file-hidden" id="attachInput"
                               onchange="handleAttach(this)">
                        <div class="file-display">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66L9.41 17.41a2 2 0 01-2.83-2.83l8.49-8.48"/>
                            </svg>
                            <span id="attachLabel">Klik untuk pilih file lampiran…</span>
                        </div>
                    </div>
                    <div class="field-hint">PDF, DOCX, XLSX, PNG, JPG · Maks. 10MB</div>
                </div>

                {{-- ── Actions ── --}}
                <div class="form-actions">
                    <button type="submit" class="btn-save">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Simpan Tugas
                    </button>
                    <a href="/guru/tasks" class="btn-back">Batal</a>
                </div>

            </form>
        </div>{{-- /form-body --}}

    </div>{{-- /form-card --}}

</div>{{-- /create-layout --}}

<script>
    setTimeout(() => {
        document.querySelectorAll('.anim-fade-up, .anim-fade-left, .anim-fade-right')
            .forEach(el => el.classList.add('is-visible'));
    }, 60);

    function handleAttach(input) {
        const label = document.getElementById('attachLabel');
        if (input.files && input.files[0]) {
            label.textContent = input.files[0].name;
            label.style.color = 'rgba(255,255,255,0.72)';
        } else {
            label.textContent = 'Klik untuk pilih file lampiran…';
            label.style.color = '';
        }
    }
</script>

@endsection