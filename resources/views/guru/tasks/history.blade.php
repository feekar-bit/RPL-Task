@extends('layouts.app')

@section('title', 'Riwayat Tugas')

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
    .anim-fade-up.is-visible { opacity: 1; transform: translateY(0); }
    .delay-1 { transition-delay: 0.06s; }
    .delay-2 { transition-delay: 0.14s; }

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

    /* ── Top action bar ── */
    .page-topbar {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .btn-add {
        display: inline-flex; align-items: center; gap: 0.5rem;
        background: var(--accent); color: var(--deep);
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700; font-size: 0.875rem;
        padding: 0.65rem 1.4rem; border-radius: 11px;
        text-decoration: none; border: none;
        box-shadow: 0 4px 20px rgba(249,177,122,0.28);
        transition: all 0.22s ease;
        white-space: nowrap;
    }
    .btn-add:hover {
        background: #fbc08e; color: var(--deep);
        box-shadow: 0 6px 28px rgba(249,177,122,0.44);
        transform: translateY(-2px);
    }
    .btn-add svg { width: 16px; height: 16px; flex-shrink: 0; }

    /* ── Alert ── */
    .alert-rpl-success {
        background: rgba(52,211,153,0.10); border: 1px solid rgba(52,211,153,0.24);
        border-radius: 12px; padding: 0.82rem 1rem;
        font-size: 0.84rem; color: #34d399;
        margin-bottom: 1.25rem;
        display: flex; align-items: center; gap: 0.6rem;
    }

    /* ── Glass card ── */
    .glass-card {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);
        overflow: hidden;
    }

    /* ── Table ── */
    .rpl-table { width: 100%; border-collapse: separate; border-spacing: 0; }

    .rpl-table thead tr {
        background: rgba(37,40,66,0.7);
    }
    .rpl-table thead th {
        padding: 0.9rem 1.1rem;
        font-size: 0.7rem; font-weight: 700;
        letter-spacing: 0.1em; text-transform: uppercase;
        color: rgba(255,255,255,0.38);
        border-bottom: 1px solid var(--glass-border);
        white-space: nowrap;
    }
    .rpl-table thead th:first-child { padding-left: 1.5rem; }
    .rpl-table thead th:last-child  { padding-right: 1.5rem; }

    .rpl-table tbody tr {
        border-bottom: 1px solid rgba(103,111,157,0.1);
        transition: background 0.18s;
    }
    .rpl-table tbody tr:last-child { border-bottom: none; }
    .rpl-table tbody tr:hover { background: rgba(103,111,157,0.08); }

    .rpl-table tbody td {
        padding: 1rem 1.1rem;
        font-size: 0.875rem; font-weight: 400;
        color: rgba(255,255,255,0.75);
        vertical-align: middle;
    }
    .rpl-table tbody td:first-child { padding-left: 1.5rem; }
    .rpl-table tbody td:last-child  { padding-right: 1.5rem; }

    /* Row number */
    .row-num {
        font-size: 0.75rem; font-weight: 600;
        color: rgba(255,255,255,0.28);
        font-family: 'Bricolage Grotesque', sans-serif;
    }

    /* Task title cell */
    .task-title-cell { font-weight: 600; color: var(--white); }
    .task-title-cell .task-desc-preview {
        font-size: 0.75rem; color: rgba(255,255,255,0.32);
        font-weight: 400; margin-top: 0.15rem;
        max-width: 260px;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }

    /* Class badge */
    .class-badge {
        display: inline-flex; align-items: center;
        background: rgba(103,111,157,0.18);
        border: 1px solid rgba(103,111,157,0.28);
        color: var(--slate);
        font-size: 0.72rem; font-weight: 700;
        letter-spacing: 0.04em;
        padding: 0.22rem 0.7rem; border-radius: 100px;
    }

    /* Deadline */
    .deadline-wrap { display: flex; flex-direction: column; gap: 0.1rem; }
    .deadline-date { font-size: 0.82rem; font-weight: 600; color: rgba(255,255,255,0.7); }
    .deadline-badge {
        font-size: 0.65rem; font-weight: 700;
        letter-spacing: 0.06em; text-transform: uppercase;
        padding: 0.18rem 0.55rem; border-radius: 100px;
        width: fit-content;
    }
    .deadline-soon    { background: rgba(251,191,36,0.14); color: #fbbf24; }
    .deadline-ok      { background: rgba(52,211,153,0.12); color: #34d399; }
    .deadline-overdue { background: rgba(248,113,113,0.12); color: #f87171; }

    /* Attachment */
    .btn-file {
        display: inline-flex; align-items: center; gap: 0.38rem;
        background: rgba(103,111,157,0.18);
        border: 1px solid rgba(103,111,157,0.3);
        color: var(--slate);
        font-size: 0.75rem; font-weight: 600;
        padding: 0.3rem 0.75rem; border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-file:hover {
        background: rgba(103,111,157,0.3);
        color: var(--white); border-color: var(--slate);
    }
    .btn-file svg { width: 13px; height: 13px; }

    .no-file {
        font-size: 0.75rem; color: rgba(255,255,255,0.22);
        font-style: italic;
    }

    /* Action buttons */
    .action-group { display: flex; align-items: center; gap: 0.45rem; flex-wrap: nowrap; }

    .btn-action {
        display: inline-flex; align-items: center; gap: 0.35rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.75rem; font-weight: 600;
        padding: 0.38rem 0.8rem; border-radius: 8px;
        text-decoration: none; border: none; cursor: pointer;
        white-space: nowrap;
        transition: all 0.2s ease;
    }
    .btn-action svg { width: 13px; height: 13px; flex-shrink: 0; }

    .btn-submission {
        background: rgba(249,177,122,0.14);
        border: 1px solid rgba(249,177,122,0.25);
        color: var(--accent);
    }
    .btn-submission:hover {
        background: rgba(249,177,122,0.26);
        color: var(--accent); border-color: var(--accent);
    }

    .btn-edit {
        background: rgba(251,191,36,0.12);
        border: 1px solid rgba(251,191,36,0.22);
        color: #fbbf24;
    }
    .btn-edit:hover {
        background: rgba(251,191,36,0.24);
        color: #fbbf24; border-color: #fbbf24;
    }

    .btn-delete {
        background: rgba(248,113,113,0.12);
        border: 1px solid rgba(248,113,113,0.22);
        color: #f87171;
    }
    .btn-delete:hover {
        background: rgba(248,113,113,0.24);
        color: #f87171; border-color: #f87171;
    }

    /* Empty state */
    .empty-state {
        text-align: center; padding: 4rem 1.5rem;
        color: rgba(255,255,255,0.28);
    }
    .empty-icon {
        width: 56px; height: 56px;
        background: rgba(103,111,157,0.14);
        border: 1px solid rgba(103,111,157,0.22);
        border-radius: 16px;
        display: grid; place-items: center;
        margin: 0 auto 1rem;
    }
    .empty-icon svg { width: 24px; height: 24px; opacity: 0.5; }
    .empty-title {
        font-family: 'Bricolage Grotesque', sans-serif;
        font-size: 1rem; font-weight: 700;
        color: rgba(255,255,255,0.4); margin-bottom: 0.4rem;
    }
    .empty-sub { font-size: 0.82rem; }

    /* Mobile scroll */
    .table-scroll { overflow-x: auto; }
    .table-scroll::-webkit-scrollbar { height: 4px; }
    .table-scroll::-webkit-scrollbar-thumb { background: rgba(103,111,157,0.3); border-radius: 4px; }
</style>

{{-- ── Page header ── --}}
<div class="anim-fade-up" style="margin-bottom:1.5rem;">
    <div class="dash-eyebrow">Manajemen</div>
    <h1 class="dash-title">Riwayat Tugas</h1>
    <p class="dash-subtitle">Kamu bisa pantau semua riwayat tugas yang sudah selesai tenggat waktunya.</p>
</div>

{{-- ── Top action bar ── --}}
{{-- <div class="page-topbar anim-fade-up delay-1">
    <div></div>
    <a href="/guru/tasks/create" class="btn-add">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
             stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"/>
            <line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Tambah Tugas
    </a>
</div> --}}

{{-- ── Alert success ── --}}
@if(session('success'))
    <div class="alert-rpl-success anim-fade-up delay-1">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
             stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

{{-- ── Table card ── --}}
<div class="glass-card anim-fade-up delay-2">
    <div class="table-scroll">
        <table class="rpl-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul Tugas</th>
                    <th>Kelas</th>
                    <th>Deadline</th>
                    <th>Lampiran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>

                @forelse($tasks as $task)
                    @php
                        $deadlineDate = \Carbon\Carbon::parse($task->deadline);
                        $now = now();
                        $diffDays = $now->diffInDays($deadlineDate, false);
                        if ($deadlineDate->isPast()) {
                            $deadlineClass = 'deadline-overdue';
                            $deadlineText  = 'Terlambat';
                        } elseif ($diffDays <= 3) {
                            $deadlineClass = 'deadline-soon';
                            $deadlineText  = 'Segera';
                        } else {
                            $deadlineClass = 'deadline-ok';
                            $deadlineText  = $diffDays . ' hari lagi';
                        }
                    @endphp

                    <tr>

                        {{-- No --}}
                        <td><span class="row-num">{{ $loop->iteration }}</span></td>

                        {{-- Judul --}}
                        <td>
                            <div class="task-title-cell">
                                {{ $task->title }}
                                @if($task->description)
                                    <div class="task-desc-preview">{{ $task->description }}</div>
                                @endif
                            </div>
                        </td>

                        {{-- Kelas --}}
                        <td>
                            <span class="class-badge">{{ $task->class_target }}</span>
                        </td>

                        {{-- Deadline --}}
                        <td>
                            <div class="deadline-wrap">
                                <span class="deadline-date">
                                    {{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }}
                                </span>
                                <span class="deadline-badge {{ $deadlineClass }}">{{ $deadlineText }}</span>
                            </div>
                        </td>

                        {{-- Lampiran --}}
                        <td>
                            @if($task->attachment)
                                <a href="{{ asset('storage/' . $task->attachment) }}"
                                   target="_blank" class="btn-file">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66L9.41 17.41a2 2 0 01-2.83-2.83l8.49-8.48"/>
                                    </svg>
                                    Lihat File
                                </a>
                            @else
                                <span class="no-file">Tidak ada</span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td>
                            <div class="action-group">

                                {{-- Submission --}}
                                <a href="/guru/tasks/{{ $task->id }}/submissions"
                                   class="btn-action btn-submission">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                        <polyline points="14 2 14 8 20 8"/>
                                    </svg>
                                    Submission
                                </a>

                                {{-- Edit --}}
                                {{-- <a href="/guru/tasks/edit/{{ $task->id }}"
                                   class="btn-action btn-edit">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                    Edit
                                </a> --}}

                                {{-- Delete --}}
                                <form action="/guru/tasks/delete/{{ $task->id }}"
                                      method="POST" style="margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn-action btn-delete"
                                            onclick="return confirm('Hapus tugas ini?')">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                                            <path d="M10 11v6M14 11v6"/>
                                            <path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>

                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                        <polyline points="14 2 14 8 20 8"/>
                                        <line x1="16" y1="13" x2="8" y2="13"/>
                                        <line x1="16" y1="17" x2="8" y2="17"/>
                                    </svg>
                                </div>
                                <div class="empty-title">Belum ada tugas</div>
                                <p class="empty-sub">Klik tombol "Tambah Tugas" untuk membuat tugas pertama.</p>
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