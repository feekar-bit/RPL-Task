@extends('layouts.app')

@section('title', 'Profile Guru')

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

    /* ── Entrance ── */
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
    .dash-header { margin-bottom: 1.75rem; }
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

    /* ══════════════════════════════════
       FULL-SCREEN PROFILE LAYOUT
    ══════════════════════════════════ */
    .profile-layout {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 1.25rem;
        align-items: start;
    }

    /* ── LEFT PANEL — avatar + identity ── */
    .profile-left {
        display: flex; flex-direction: column; gap: 1rem;
    }

    /* Identity card */
    .identity-card {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        padding: 2rem 1.5rem;
        text-align: center;
        backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);
        position: relative; overflow: hidden;
    }
    /* Subtle glow */
    .identity-card::before {
        content: ''; position: absolute;
        top: -60px; left: 50%; transform: translateX(-50%);
        width: 180px; height: 180px;
        background: radial-gradient(circle, rgba(249,177,122,0.08) 0%, transparent 70%);
        pointer-events: none;
    }

    .avatar-wrap { position: relative; display: inline-block; margin-bottom: 1.25rem; }

    .avatar-img {
        width: 100px; height: 100px; border-radius: 22px;
        object-fit: cover;
        border: 2.5px solid rgba(249,177,122,0.38);
        box-shadow: 0 0 24px rgba(249,177,122,0.14);
        display: block;
    }
    .avatar-initials {
        width: 100px; height: 100px; border-radius: 22px;
        background: linear-gradient(135deg, var(--mid) 0%, var(--slate) 100%);
        border: 2.5px solid rgba(249,177,122,0.38);
        box-shadow: 0 0 24px rgba(249,177,122,0.14);
        display: grid; place-items: center;
        font-family: 'Bricolage Grotesque', sans-serif;
        font-size: 2rem; font-weight: 800; color: var(--white);
        letter-spacing: -0.02em;
    }

    /* Camera badge */
    .avatar-cam {
        position: absolute; bottom: -5px; right: -5px;
        width: 30px; height: 30px; border-radius: 9px;
        background: var(--accent);
        display: grid; place-items: center;
        box-shadow: 0 2px 12px rgba(249,177,122,0.45);
        cursor: pointer; transition: transform 0.2s;
    }
    .avatar-cam:hover { transform: scale(1.1); }
    .avatar-cam svg { width: 14px; height: 14px; color: var(--deep); }

    .identity-name {
        font-family: 'Bricolage Grotesque', sans-serif;
        font-size: 1.1rem; font-weight: 800;
        letter-spacing: -0.015em; color: var(--white);
        margin-bottom: 0.3rem;
    }
    .identity-email {
        font-size: 0.78rem; color: rgba(255,255,255,0.38);
        font-weight: 400; margin-bottom: 0.85rem;
        word-break: break-all;
    }
    .identity-role {
        display: inline-flex; align-items: center; gap: 0.4rem;
        font-size: 0.63rem; font-weight: 700;
        letter-spacing: 0.1em; text-transform: uppercase;
        padding: 0.28rem 0.8rem; border-radius: 100px;
        background: rgba(103,111,157,0.18);
        border: 1px solid rgba(103,111,157,0.3);
        color: var(--slate);
    }
    .identity-role .dot {
        width: 5px; height: 5px; border-radius: 50%;
        background: var(--slate);
        animation: pulse-dot 2.2s ease infinite;
    }
    @keyframes pulse-dot {
        0%,100% { opacity:1; transform:scale(1); }
        50%      { opacity:.4; transform:scale(.72); }
    }

    /* Info pills below identity */
    .info-card {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 18px; padding: 1.25rem 1.35rem;
        backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);
    }
    .info-card-label {
        font-size: 0.65rem; font-weight: 700;
        letter-spacing: 0.12em; text-transform: uppercase;
        color: rgba(255,255,255,0.25); margin-bottom: 0.85rem;
        display: flex; align-items: center; gap: 0.5rem;
    }
    .info-card-label::after {
        content: ''; flex: 1; height: 1px; background: var(--glass-border);
    }
    .info-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.55rem 0;
        border-bottom: 1px solid rgba(103,111,157,0.1);
        gap: 0.5rem;
    }
    .info-row:last-child { border-bottom: none; padding-bottom: 0; }
    .info-row-key {
        font-size: 0.75rem; font-weight: 600;
        color: rgba(255,255,255,0.35);
        flex-shrink: 0;
    }
    .info-row-val {
        font-size: 0.78rem; font-weight: 500;
        color: rgba(255,255,255,0.65);
        text-align: right; word-break: break-all;
    }
    .info-row-val.accent { color: var(--accent); }

    /* ── RIGHT PANEL — form ── */
    .profile-right {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);
        overflow: hidden;
    }

    /* Form top bar */
    .form-topbar {
        padding: 1.35rem 2rem;
        border-bottom: 1px solid var(--glass-border);
        display: flex; align-items: center; justify-content: space-between;
    }
    .form-topbar-title {
        font-family: 'Bricolage Grotesque', sans-serif;
        font-size: 1rem; font-weight: 800;
        letter-spacing: -0.01em; color: var(--white);
    }
    .form-topbar-sub {
        font-size: 0.775rem; color: rgba(255,255,255,0.35); font-weight: 400;
        margin-top: 0.18rem;
    }

    /* Form body */
    .form-body { padding: 1.75rem 2rem 2rem; }

    /* Alerts */
    .alert-rpl-success {
        background: rgba(52,211,153,0.1); border: 1px solid rgba(52,211,153,0.24);
        border-radius: 11px; padding: 0.8rem 1rem;
        font-size: 0.835rem; color: #34d399;
        margin-bottom: 1.5rem;
        display: flex; align-items: center; gap: 0.6rem;
    }
    .alert-rpl-danger {
        background: rgba(220,80,80,0.11); border: 1px solid rgba(220,80,80,0.26);
        border-radius: 11px; padding: 0.8rem 1rem;
        font-size: 0.835rem; color: #f87171;
        margin-bottom: 1.5rem;
    }
    .alert-rpl-danger ul { margin: 0; padding-left: 1.1rem; }
    .alert-rpl-danger li { margin-top: 0.18rem; }

    /* Section separator */
    .form-sep {
        font-size: 0.65rem; font-weight: 700;
        letter-spacing: 0.13em; text-transform: uppercase;
        color: rgba(255,255,255,0.25);
        display: flex; align-items: center; gap: 0.6rem;
        margin: 1.5rem 0 1.1rem;
    }
    .form-sep::before,
    .form-sep::after { content: ''; flex: 1; height: 1px; background: var(--glass-border); }

    /* Fields grid */
    .fields-grid-2 {
        display: grid; grid-template-columns: 1fr 1fr; gap: 0 1.25rem;
    }
    .field-full { grid-column: 1 / -1; }

    .field-group { margin-bottom: 1.1rem; }
    .field-label {
        display: block; font-size: 0.775rem; font-weight: 600;
        color: rgba(255,255,255,0.55); margin-bottom: 0.4rem; letter-spacing: 0.01em;
    }
    .field-input {
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
    .field-input::placeholder { color: rgba(255,255,255,0.18); }
    .field-input:focus {
        border-color: var(--accent);
        background: rgba(249,177,122,0.05);
        box-shadow: 0 0 0 3px rgba(249,177,122,0.11);
    }
    .field-input:-webkit-autofill,
    .field-input:-webkit-autofill:focus {
        -webkit-box-shadow: 0 0 0 1000px rgba(37,40,66,0.98) inset;
        -webkit-text-fill-color: var(--white); caret-color: var(--white);
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
        color: rgba(255,255,255,0.58);
    }
    .field-hint {
        font-size: 0.7rem; color: rgba(255,255,255,0.24);
        margin-top: 0.3rem;
    }

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
    }
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
        .profile-layout { grid-template-columns: 1fr; }
        .profile-left { flex-direction: row; flex-wrap: wrap; }
        .identity-card { flex: 1 1 220px; }
        .info-card     { flex: 1 1 220px; }
    }
    @media (max-width: 575px) {
        .profile-left  { flex-direction: column; }
        .fields-grid-2 { grid-template-columns: 1fr; }
        .form-body     { padding: 1.35rem 1.25rem 1.5rem; }
        .form-topbar   { padding: 1.1rem 1.25rem; }
    }
</style>

{{-- ── Page header ── --}}
<div class="dash-header anim-fade-up">
    <div class="dash-eyebrow">Akun Saya</div>
    <h1 class="dash-title">Profile Guru</h1>
    <p class="dash-subtitle">Kelola informasi akun dan foto profil kamu.</p>
</div>

<div class="profile-layout">

    {{-- ══════════════════
         LEFT — Identity
    ══════════════════ --}}
    <div class="profile-left">

        {{-- Identity card --}}
        <div class="identity-card anim-fade-left delay-1">

            <div class="avatar-wrap">
                @if(Auth::user()->photo)
                    <img src="{{ asset('storage/' . Auth::user()->photo) }}"
                         class="avatar-img" id="avatarPreview" alt="Foto profil">
                @else
                    <div class="avatar-initials" id="avatarInitials">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                @endif

                <label for="photoInput" class="avatar-cam" title="Ganti foto" style="z-index:2;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/>
                        <circle cx="12" cy="13" r="4"/>
                    </svg>
                </label>
                <input type="file" name="photo" id="photoInput"
                       accept="image/*"
                       onchange="handleFileChange(this)"
                       style="position:absolute;bottom:-5px;right:-5px;width:30px;height:30px;opacity:0;cursor:pointer;z-index:3;border-radius:9px;">
            </div>

            <div class="identity-name">{{ Auth::user()->name }}</div>
            <div class="identity-email">{{ Auth::user()->email }}</div>
            <div class="identity-role">
                <span class="dot"></span> Guru Pengajar
            </div>

        </div>

        {{-- Info detail --}}
        <div class="info-card anim-fade-left delay-2">
            <div class="info-card-label">Detail Akun</div>

            <div class="info-row">
                <span class="info-row-key">Role</span>
                <span class="info-row-val accent">Guru</span>
            </div>
            <div class="info-row">
                <span class="info-row-key">Status</span>
                <span class="info-row-val accent">Aktif</span>
            </div>
            <div class="info-row">
                <span class="info-row-key">Platform</span>
                <span class="info-row-val">RPL Tasks</span>
            </div>
        </div>

    </div>

    {{-- ══════════════════
         RIGHT — Form
    ══════════════════ --}}
    <div class="profile-right anim-fade-right delay-1">

        <div class="form-topbar">
            <div>
                <div class="form-topbar-title">Edit Profile</div>
                <div class="form-topbar-sub">Perubahan akan disimpan setelah kamu klik Simpan.</div>
            </div>
        </div>

        <div class="form-body">

            {{-- Alerts --}}
            @if(session('success'))
                <div class="alert-rpl-success">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2.5"
                         stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
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

            <form action="/guru/profile/update" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- ── Informasi Pribadi ── --}}
                <div class="form-sep">Informasi Pribadi</div>

                <div class="fields-grid-2">

                    {{-- NAMA --}}
                    <div class="field-group field-full">
                        <label class="field-label">Nama Lengkap</label>
                        <input type="text" name="name" class="field-input"
                               placeholder="Nama lengkap kamu"
                               value="{{ Auth::user()->name }}">
                    </div>

                    {{-- EMAIL --}}
                    <div class="field-group field-full">
                        <label class="field-label">Email</label>
                        <input type="email" name="email" class="field-input"
                               placeholder="nama@email.com"
                               value="{{ Auth::user()->email }}">
                    </div>

                </div>

                {{-- ── Ubah Password ── --}}
                <div class="form-sep">Ubah Password</div>

                <div class="fields-grid-2">

                    {{-- PASSWORD BARU --}}
                    <div class="field-group">
                        <label class="field-label">Password Baru</label>
                        <input type="password" name="password" class="field-input"
                               placeholder="Kosongkan jika tidak diubah">
                    </div>

                    {{-- KONFIRMASI PASSWORD --}}
                    <div class="field-group">
                        <label class="field-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="field-input"
                               placeholder="Ulangi password baru">
                    </div>

                </div>

                {{-- ── Actions ── --}}
                <div class="form-actions">
                    <button type="submit" class="btn-save">Simpan Perubahan</button>
                    <a href="javascript:history.back()" class="btn-back">Batal</a>
                </div>

            </form>
        </div>{{-- /form-body --}}

    </div>{{-- /profile-right --}}

</div>{{-- /profile-layout --}}

<script>
    // ── Entrance animations ──
    setTimeout(() => {
        document.querySelectorAll('.anim-fade-up, .anim-fade-left, .anim-fade-right')
            .forEach(el => el.classList.add('is-visible'));
    }, 60);

    // ── File input: live avatar preview ──
    function handleFileChange(input) {
        if (!input.files || !input.files[0]) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            const existing = document.getElementById('avatarPreview');
            const initials = document.getElementById('avatarInitials');
            if (existing) {
                existing.src = e.target.result;
            } else if (initials) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'avatar-img';
                img.id = 'avatarPreview';
                img.alt = 'Preview foto';
                initials.replaceWith(img);
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
</script>

@endsection