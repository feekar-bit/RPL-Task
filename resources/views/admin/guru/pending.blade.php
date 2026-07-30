@extends('layouts.app')

@section('title', 'Approval Guru')

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

    /* Page header */
    .dash-eyebrow { font-size:.7rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:var(--accent); margin-bottom:.28rem; }
    .dash-title { font-family:'Bricolage Grotesque',sans-serif; font-size:clamp(1.4rem,2.8vw,1.85rem); font-weight:800; letter-spacing:-.02em; color:var(--white); margin-bottom:.22rem; }
    .dash-subtitle { font-size:.855rem; color:rgba(255,255,255,.38); font-weight:400; }

    /* Alert */
    .alert-rpl-success {
        background: rgba(52,211,153,.1); border: 1px solid rgba(52,211,153,.24);
        border-radius: 12px; padding: .82rem 1rem;
        font-size: .84rem; color: #34d399;
        margin-bottom: 1.25rem;
        display: flex; align-items: center; gap: .6rem;
    }

    /* Glass card */
    .glass-card {
        background: var(--glass-bg); border: 1px solid var(--glass-border);
        border-radius: 20px;
        backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);
        overflow: hidden;
    }

    /* Pending count badge */
    .pending-count {
        display: inline-flex; align-items: center; gap: .45rem;
        background: rgba(248,113,113,.12); border: 1px solid rgba(248,113,113,.22);
        color: #f87171; font-size:.68rem; font-weight:700;
        letter-spacing:.08em; text-transform:uppercase;
        padding:.3rem .8rem; border-radius:100px;
    }
    .pending-count .count-dot { width:6px; height:6px; border-radius:50%; background:#f87171; animation:pulse-dot 2s ease infinite; }
    @keyframes pulse-dot { 0%,100%{opacity:1;transform:scale(1);}50%{opacity:.4;transform:scale(.72);} }

    /* Table */
    .rpl-table { width:100%; border-collapse:separate; border-spacing:0; }
    .rpl-table thead tr { background:rgba(37,40,66,.7); }
    .rpl-table thead th {
        padding:.9rem 1.1rem; font-size:.7rem; font-weight:700;
        letter-spacing:.1em; text-transform:uppercase;
        color:rgba(255,255,255,.38);
        border-bottom:1px solid var(--glass-border); white-space:nowrap;
    }
    .rpl-table thead th:first-child { padding-left:1.5rem; }
    .rpl-table thead th:last-child  { padding-right:1.5rem; }

    .rpl-table tbody tr { border-bottom:1px solid rgba(103,111,157,.1); transition:background .18s; }
    .rpl-table tbody tr:last-child { border-bottom:none; }
    .rpl-table tbody tr:hover { background:rgba(103,111,157,.08); }
    .rpl-table tbody td {
        padding:1rem 1.1rem; font-size:.875rem; font-weight:400;
        color:rgba(255,255,255,.72); vertical-align:middle;
    }
    .rpl-table tbody td:first-child { padding-left:1.5rem; }
    .rpl-table tbody td:last-child  { padding-right:1.5rem; }

    /* Row number */
    .row-num { font-size:.75rem; font-weight:600; color:rgba(255,255,255,.28); font-family:'Bricolage Grotesque',sans-serif; }

    /* ID badge */
    .id-badge {
        display:inline-flex; align-items:center;
        background:rgba(103,111,157,.18); border:1px solid rgba(103,111,157,.28);
        color:var(--slate); font-size:.72rem; font-weight:700;
        letter-spacing:.04em; padding:.22rem .7rem; border-radius:8px;
        font-family:'Bricolage Grotesque',sans-serif;
    }

    /* Name cell */
    .name-cell { font-weight:600; color:var(--white); }
    .email-cell { font-size:.78rem; color:rgba(255,255,255,.42); margin-top:.15rem; }

    /* Avatar initials in table */
    .row-avatar {
        width:32px; height:32px; border-radius:9px; flex-shrink:0;
        background:linear-gradient(135deg,var(--mid),var(--slate));
        display:grid; place-items:center;
        font-family:'Bricolage Grotesque',sans-serif;
        font-size:.72rem; font-weight:800; color:var(--white);
    }
    .name-with-avatar { display:flex; align-items:center; gap:.75rem; }

    /* Action buttons */
    .action-group { display:flex; align-items:center; gap:.5rem; flex-wrap:nowrap; }

    .btn-action {
        display:inline-flex; align-items:center; gap:.35rem;
        font-family:'Plus Jakarta Sans',sans-serif;
        font-size:.75rem; font-weight:700;
        padding:.4rem .9rem; border-radius:9px;
        border:none; cursor:pointer; white-space:nowrap;
        transition:all .2s ease;
    }
    .btn-action svg { width:13px; height:13px; flex-shrink:0; }

    .btn-approve {
        background:rgba(52,211,153,.14);
        border:1px solid rgba(52,211,153,.24);
        color:#34d399;
    }
    .btn-approve:hover { background:rgba(52,211,153,.26); border-color:#34d399; }

    .btn-delete {
        background:rgba(248,113,113,.12);
        border:1px solid rgba(248,113,113,.22);
        color:#f87171;
    }
    .btn-delete:hover { background:rgba(248,113,113,.24); border-color:#f87171; }

    /* Empty state */
    .empty-state { text-align:center; padding:4rem 1.5rem; color:rgba(255,255,255,.28); }
    .empty-icon {
        width:56px; height:56px; border-radius:16px;
        background:rgba(52,211,153,.08); border:1px solid rgba(52,211,153,.16);
        display:grid; place-items:center; margin:0 auto 1rem;
    }
    .empty-icon svg { width:24px; height:24px; color:#34d399; opacity:.5; }
    .empty-title { font-family:'Bricolage Grotesque',sans-serif; font-size:1rem; font-weight:800; color:rgba(255,255,255,.4); margin-bottom:.4rem; }
    .empty-sub { font-size:.82rem; }

    /* Table mobile scroll */
    .table-scroll { overflow-x:auto; }
    .table-scroll::-webkit-scrollbar { height:4px; }
    .table-scroll::-webkit-scrollbar-thumb { background:rgba(103,111,157,.3); border-radius:4px; }
</style>

{{-- Page header --}}
<div class="anim-fade-up" style="margin-bottom:1.5rem;">
    <div class="dash-eyebrow">Manajemen</div>
    <h1 class="dash-title">Approval Guru</h1>
    <p class="dash-subtitle">Tinjau dan kelola pendaftaran guru yang menunggu persetujuan.</p>
</div>

{{-- Pending count + alert --}}
<div class="d-flex align-items-center gap-3 flex-wrap anim-fade-up delay-1" style="margin-bottom:1.25rem;">
    @if($gurus->count() > 0)
        <span class="pending-count">
            <span class="count-dot"></span>
            {{ $gurus->count() }} Pending
        </span>
    @endif
</div>

@if(session('success'))
    <div class="alert-rpl-success anim-fade-up delay-1">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
             stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

{{-- Table card --}}
<div class="glass-card anim-fade-up delay-2">
    <div class="table-scroll">
        <table class="rpl-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Guru</th>
                    <th>Nama & Email</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>

                @forelse($gurus as $guru)
                    <tr>
                        {{-- No --}}
                        <td><span class="row-num">{{ $loop->iteration }}</span></td>

                        {{-- ID --}}
                        <td><span class="id-badge">{{ $guru->teacher_id }}</span></td>

                        {{-- Nama & Email --}}
                        <td>
                            <div class="name-with-avatar">
                                <div class="row-avatar">{{ strtoupper(substr($guru->name, 0, 2)) }}</div>
                                <div>
                                    <div class="name-cell">{{ $guru->name }}</div>
                                    <div class="email-cell">{{ $guru->email }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- Aksi --}}
                        <td>
                            <div class="action-group">

                                {{-- APPROVE --}}
                                <form action="/admin/guru/approve/{{ $guru->id }}" method="POST" style="margin:0;">
                                    @csrf
                                    <button type="submit" class="btn-action btn-approve">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"/>
                                        </svg>
                                        Approve
                                    </button>
                                </form>

                                {{-- DELETE --}}
                                <form action="/admin/guru/delete/{{ $guru->id }}" method="POST" style="margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete"
                                            onclick="return confirm('Hapus akun guru ini?')">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                                            <path d="M10 11v6M14 11v6"/>
                                            <path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/>
                                        </svg>
                                        Delete
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="4">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"/>
                                    </svg>
                                </div>
                                <div class="empty-title">Semua sudah disetujui</div>
                                <p class="empty-sub">Tidak ada guru yang sedang menunggu approval.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>
</div>

<script>
    setTimeout(() => {
        document.querySelectorAll('.anim-fade-up').forEach(el => el.classList.add('is-visible'));
    }, 60);
</script>

@endsection