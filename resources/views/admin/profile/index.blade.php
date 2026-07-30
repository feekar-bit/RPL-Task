@extends('layouts.app')

@section('title', 'Profile Admin')

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
    .delay-1 { transition-delay:.06s; } .delay-2 { transition-delay:.14s; }

    /* Page header */
    .dash-eyebrow { font-size:.7rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:var(--accent); margin-bottom:.28rem; }
    .dash-title { font-family:'Bricolage Grotesque',sans-serif; font-size:clamp(1.4rem,2.8vw,1.85rem); font-weight:800; letter-spacing:-.02em; color:var(--white); margin-bottom:.22rem; }
    .dash-subtitle { font-size:.855rem; color:rgba(255,255,255,.38); font-weight:400; }

    /* Full-width layout */
    .profile-layout { display:grid; grid-template-columns:280px 1fr; gap:1.25rem; align-items:start; }

    /* LEFT */
    .profile-left { display:flex; flex-direction:column; gap:1rem; }

    .identity-card {
        background:var(--glass-bg); border:1px solid var(--glass-border);
        border-radius:20px; padding:2rem 1.5rem; text-align:center;
        backdrop-filter:blur(14px); -webkit-backdrop-filter:blur(14px);
        position:relative; overflow:hidden;
    }
    .identity-card::before {
        content:''; position:absolute; top:-60px; left:50%; transform:translateX(-50%);
        width:180px; height:180px;
        background:radial-gradient(circle,rgba(248,113,113,.08) 0%,transparent 70%);
        pointer-events:none;
    }

    .avatar-wrap { position:relative; display:inline-block; margin-bottom:1.25rem; }
    .avatar-img {
        width:100px; height:100px; border-radius:22px; object-fit:cover;
        border:2.5px solid rgba(248,113,113,.35);
        box-shadow:0 0 24px rgba(248,113,113,.12); display:block;
    }
    .avatar-initials {
        width:100px; height:100px; border-radius:22px;
        background:linear-gradient(135deg,#3d2020 0%,#5c3030 100%);
        border:2.5px solid rgba(248,113,113,.35);
        box-shadow:0 0 24px rgba(248,113,113,.12);
        display:grid; place-items:center;
        font-family:'Bricolage Grotesque',sans-serif;
        font-size:2rem; font-weight:800; color:var(--white);
    }
    .avatar-cam {
        position:absolute; bottom:-5px; right:-5px; width:30px; height:30px;
        border-radius:9px; background:#f87171;
        display:grid; place-items:center;
        box-shadow:0 2px 12px rgba(248,113,113,.4);
        cursor:pointer; transition:transform .2s;
    }
    .avatar-cam:hover { transform:scale(1.1); }
    .avatar-cam svg { width:14px; height:14px; color:var(--white); }

    .identity-name { font-family:'Bricolage Grotesque',sans-serif; font-size:1.1rem; font-weight:800; letter-spacing:-.015em; color:var(--white); margin-bottom:.3rem; }
    .identity-email { font-size:.78rem; color:rgba(255,255,255,.38); font-weight:400; margin-bottom:.85rem; word-break:break-all; }
    .identity-role {
        display:inline-flex; align-items:center; gap:.4rem;
        font-size:.63rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase;
        padding:.28rem .8rem; border-radius:100px;
        background:rgba(248,113,113,.12); border:1px solid rgba(248,113,113,.22); color:#f87171;
    }
    .identity-role .dot { width:5px; height:5px; border-radius:50%; background:#f87171; animation:pulse-dot 2.2s ease infinite; }
    @keyframes pulse-dot { 0%,100%{opacity:1;transform:scale(1);}50%{opacity:.4;transform:scale(.72);} }

    .info-card { background:var(--glass-bg); border:1px solid var(--glass-border); border-radius:18px; padding:1.25rem 1.35rem; backdrop-filter:blur(14px); -webkit-backdrop-filter:blur(14px); }
    .info-card-label { font-size:.65rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:rgba(255,255,255,.25); margin-bottom:.85rem; display:flex; align-items:center; gap:.5rem; }
    .info-card-label::after { content:''; flex:1; height:1px; background:var(--glass-border); }
    .info-row { display:flex; align-items:center; justify-content:space-between; padding:.52rem 0; border-bottom:1px solid rgba(103,111,157,.1); gap:.5rem; }
    .info-row:last-child { border-bottom:none; padding-bottom:0; }
    .info-row-key { font-size:.75rem; font-weight:600; color:rgba(255,255,255,.32); flex-shrink:0; }
    .info-row-val { font-size:.78rem; font-weight:500; color:rgba(255,255,255,.62); text-align:right; }
    .info-row-val.red { color:#f87171; }

    /* Lock icon badge */
    .lock-badge {
        display:inline-flex; align-items:center; gap:.3rem;
        font-size:.62rem; font-weight:600; color:rgba(255,255,255,.28);
    }
    .lock-badge svg { width:10px; height:10px; }

    /* RIGHT */
    .profile-right { background:var(--glass-bg); border:1px solid var(--glass-border); border-radius:20px; backdrop-filter:blur(14px); -webkit-backdrop-filter:blur(14px); overflow:hidden; }
    .form-topbar { padding:1.35rem 2rem; border-bottom:1px solid var(--glass-border); }
    .form-topbar-title { font-family:'Bricolage Grotesque',sans-serif; font-size:1rem; font-weight:800; letter-spacing:-.01em; color:var(--white); }
    .form-topbar-sub { font-size:.775rem; color:rgba(255,255,255,.32); font-weight:400; margin-top:.18rem; }

    .form-body { padding:1.75rem 2rem 2rem; }

    /* Alerts */
    .alert-rpl-success {
        background:rgba(52,211,153,.1); border:1px solid rgba(52,211,153,.24);
        border-radius:11px; padding:.8rem 1rem;
        font-size:.835rem; color:#34d399; margin-bottom:1.5rem;
        display:flex; align-items:center; gap:.6rem;
    }
    .alert-rpl-danger {
        background:rgba(220,80,80,.11); border:1px solid rgba(220,80,80,.26);
        border-radius:11px; padding:.8rem 1rem;
        font-size:.835rem; color:#f87171; margin-bottom:1.5rem;
    }
    .alert-rpl-danger ul { margin:0; padding-left:1.1rem; }
    .alert-rpl-danger li { margin-top:.18rem; }

    /* Section separator */
    .form-sep { font-size:.65rem; font-weight:700; letter-spacing:.13em; text-transform:uppercase; color:rgba(255,255,255,.25); display:flex; align-items:center; gap:.6rem; margin:0 0 1.1rem; }
    .form-sep::after { content:''; flex:1; height:1px; background:var(--glass-border); }

    /* Fields */
    .fields-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:0 1.25rem; }
    .field-full { grid-column:1/-1; }
    .field-group { margin-bottom:1.1rem; }
    .field-label { display:block; font-size:.775rem; font-weight:600; color:rgba(255,255,255,.55); margin-bottom:.4rem; letter-spacing:.01em; }

    .field-input {
        width:100%; background:rgba(255,255,255,.05);
        border:1.5px solid rgba(103,111,157,.28);
        border-radius:11px; padding:.7rem .95rem;
        font-family:'Plus Jakarta Sans',sans-serif; font-size:.875rem; font-weight:400;
        color:var(--white); outline:none;
        transition:border-color .22s,background .22s,box-shadow .22s; -webkit-appearance:none;
    }
    .field-input::placeholder { color:rgba(255,255,255,.18); }
    .field-input:focus { border-color:var(--accent); background:rgba(249,177,122,.05); box-shadow:0 0 0 3px rgba(249,177,122,.11); }
    .field-input:-webkit-autofill,.field-input:-webkit-autofill:focus {
        -webkit-box-shadow:0 0 0 1000px rgba(37,40,66,.98) inset;
        -webkit-text-fill-color:var(--white); caret-color:var(--white);
    }

    /* Disabled / locked field */
    .field-input:disabled, .field-input[disabled] {
        background:rgba(255,255,255,.025);
        border-color:rgba(103,111,157,.16);
        color:rgba(255,255,255,.28);
        cursor:not-allowed;
    }
    .field-lock-wrap { position:relative; }
    .field-lock-icon {
        position:absolute; right:12px; top:50%; transform:translateY(-50%);
        color:rgba(255,255,255,.2); pointer-events:none;
    }
    .field-lock-icon svg { width:14px; height:14px; }
    .field-lock-wrap .field-input { padding-right:2.2rem; }
    .field-hint { font-size:.7rem; color:rgba(255,255,255,.22); margin-top:.3rem; }
    .field-hint.hint-lock { color:rgba(248,113,113,.5); }

    /* File input */
    .file-wrap { position:relative; }
    .file-hidden { position:absolute; inset:0; width:100%; opacity:0; cursor:pointer; z-index:2; }
    .file-display {
        width:100%; background:rgba(255,255,255,.04);
        border:1.5px dashed rgba(103,111,157,.32);
        border-radius:11px; padding:.7rem .95rem;
        font-size:.845rem; color:rgba(255,255,255,.32);
        display:flex; align-items:center; gap:.6rem; cursor:pointer;
        transition:border-color .22s,background .22s,color .22s;
    }
    .file-wrap:hover .file-display { border-color:rgba(249,177,122,.36); background:rgba(249,177,122,.04); color:rgba(255,255,255,.6); }
    .file-display svg { width:16px; height:16px; flex-shrink:0; opacity:.5; }

    /* Actions */
    .form-actions { display:flex; align-items:center; gap:.85rem; flex-wrap:wrap; padding-top:.75rem; border-top:1px solid var(--glass-border); margin-top:1.5rem; }
    .btn-save {
        background:var(--accent); color:var(--deep);
        font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; font-size:.9rem;
        padding:.75rem 2.2rem; border-radius:11px; border:none; cursor:pointer;
        box-shadow:0 4px 22px rgba(249,177,122,.28); transition:all .22s ease;
    }
    .btn-save:hover { background:#fbc08e; box-shadow:0 6px 30px rgba(249,177,122,.46); transform:translateY(-2px); }
    .btn-save:active { transform:translateY(0); }
    .btn-back {
        background:transparent; color:rgba(255,255,255,.38);
        font-family:'Plus Jakarta Sans',sans-serif; font-weight:500; font-size:.875rem;
        padding:.75rem 1.4rem; border-radius:11px; border:1.5px solid rgba(103,111,157,.26);
        cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; transition:all .22s;
    }
    .btn-back:hover { border-color:rgba(103,111,157,.5); color:rgba(255,255,255,.68); background:rgba(103,111,157,.1); }

    /* Responsive */
    @media (max-width:900px) { .profile-layout { grid-template-columns:1fr; } .profile-left { flex-direction:row; flex-wrap:wrap; } .identity-card,.info-card { flex:1 1 220px; } }
    @media (max-width:575px) { .profile-left { flex-direction:column; } .fields-grid-2 { grid-template-columns:1fr; } .form-body { padding:1.35rem 1.25rem 1.5rem; } .form-topbar { padding:1.1rem 1.25rem; } }
</style>

{{-- Page header --}}
<div class="dash-header anim-fade-up" style="margin-bottom:1.75rem;">
    <div class="dash-eyebrow">Akun Saya</div>
    <h1 class="dash-title">Profile Admin</h1>
    <p class="dash-subtitle">Kelola nama dan foto profil akun administrator.</p>
</div>

<div class="profile-layout">

    {{-- ══ LEFT ══ --}}
    <div class="profile-left">

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
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
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
            <div class="identity-role"><span class="dot"></span> Administrator</div>
        </div>

        <div class="info-card anim-fade-left delay-2">
            <div class="info-card-label">Detail Akun</div>
            <div class="info-row">
                <span class="info-row-key">Role</span>
                <span class="info-row-val red">Admin</span>
            </div>
            <div class="info-row">
                <span class="info-row-key">Email</span>
                <span class="info-row-val" style="font-size:.72rem;">{{ Auth::user()->email }}</span>
            </div>
            <div class="info-row">
                <span class="info-row-key">Password</span>
                <span class="lock-badge">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
                    </svg>
                    Terkunci
                </span>
            </div>
            <div class="info-row">
                <span class="info-row-key">Status</span>
                <span class="info-row-val red">Aktif</span>
            </div>
        </div>

    </div>

    {{-- ══ RIGHT ══ --}}
    <div class="profile-right anim-fade-right delay-1">

        <div class="form-topbar">
            <div class="form-topbar-title">Edit Profile Admin</div>
            <div class="form-topbar-sub">Email dan password tidak dapat diubah dari sini.</div>
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

            <form action="/admin/profile/update" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Informasi --}}
                <div class="form-sep">Informasi Admin</div>
                <div class="fields-grid-2">

                    {{-- NAMA ADMIN --}}
                    <div class="field-group field-full">
                        <label class="field-label">Nama Admin</label>
                        <input type="text" name="name" class="field-input"
                               placeholder="Nama lengkap admin"
                               value="{{ Auth::user()->name }}">
                    </div>

                    {{-- EMAIL LOCK --}}
                    <div class="field-group field-full">
                        <label class="field-label">Email Admin</label>
                        <div class="field-lock-wrap">
                            <input type="email" class="field-input"
                                   value="{{ Auth::user()->email }}" disabled>
                            <span class="field-lock-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
                                </svg>
                            </span>
                        </div>
                        <div class="field-hint hint-lock">Email tidak dapat diubah.</div>
                    </div>

                    {{-- PASSWORD LOCK --}}
                    <div class="field-group field-full">
                        <label class="field-label">Password</label>
                        <div class="field-lock-wrap">
                            <input type="password" class="field-input"
                                   value="••••••••" disabled>
                            <span class="field-lock-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
                                </svg>
                            </span>
                        </div>
                        <div class="field-hint hint-lock">Password tidak dapat diubah dari sini.</div>
                    </div>

                </div>

                {{-- Actions --}}
                <div class="form-actions">
                    <button type="submit" class="btn-save">Simpan Perubahan</button>
                    <a href="javascript:history.back()" class="btn-back">Batal</a>
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

    function handleFileChange(input) {
        if (!input.files || !input.files[0]) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById('avatarPreview');
            const init = document.getElementById('avatarInitials');
            if (img) { img.src = e.target.result; }
            else if (init) {
                const newImg = document.createElement('img');
                newImg.src = e.target.result; newImg.className = 'avatar-img';
                newImg.id = 'avatarPreview'; newImg.alt = 'Preview';
                init.replaceWith(newImg);
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
</script>

@endsection