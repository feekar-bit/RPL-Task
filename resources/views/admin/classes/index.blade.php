@extends('layouts.app')

@section('title', 'Manajemen Kelas RPL')

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
        transition: opacity .55s cubic-bezier(.22,.68,0,1.1),
                    transform .55s cubic-bezier(.22,.68,0,1.1);
    }
    .anim-fade-up.is-visible { opacity: 1; transform: translateY(0); }
    .delay-1 { transition-delay: .06s; }
    .delay-2 { transition-delay: .14s; }
    .delay-3 { transition-delay: .22s; }

    /* Header */
    .dash-eyebrow {
        font-size: .7rem; font-weight: 700; letter-spacing: .12em;
        text-transform: uppercase; color: var(--accent); margin-bottom: .28rem;
    }
    .dash-title {
        font-family: 'Bricolage Grotesque', sans-serif;
        font-size: clamp(1.4rem, 2.8vw, 1.85rem); font-weight: 800;
        letter-spacing: -.02em; color: var(--white); margin-bottom: .22rem;
    }
    .dash-subtitle {
        font-size: .855rem; color: rgba(255,255,255,.4); font-weight: 400;
    }

    /* Glass Cards */
    .glass-card {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        position: relative;
        overflow: hidden;
    }

    /* Stat Cards */
    .stat-card-grade {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 18px;
        padding: 1.25rem 1.4rem;
        backdrop-filter: blur(12px);
        transition: transform .25s ease, border-color .25s ease, box-shadow .25s ease;
        height: 100%;
        position: relative;
        overflow: hidden;
    }
    .stat-card-grade:hover {
        transform: translateY(-3px);
        border-color: rgba(249,177,122,0.35);
        box-shadow: 0 12px 30px rgba(0,0,0,0.25);
    }
    .stat-badge-ang {
        display: inline-flex; align-items: center; gap: .35rem;
        font-family: 'Bricolage Grotesque', sans-serif;
        font-size: .75rem; font-weight: 800; letter-spacing: .06em;
        text-transform: uppercase; padding: .25rem .75rem; border-radius: 100px;
        background: rgba(249,177,122,0.12); color: var(--accent);
        border: 1px solid rgba(249,177,122,0.25);
        margin-bottom: .75rem;
    }
    .stat-num-val {
        font-family: 'Bricolage Grotesque', sans-serif;
        font-size: 1.9rem; font-weight: 800; color: var(--white); line-height: 1;
        margin-bottom: .4rem;
    }
    .stat-num-sub {
        font-size: .76rem; color: rgba(255,255,255,0.45); font-weight: 500;
    }

    /* Progress bar */
    .capacity-progress {
        height: 6px; border-radius: 100px;
        background: rgba(255,255,255,0.08);
        overflow: hidden; margin-top: .85rem;
    }
    .capacity-bar-fill {
        height: 100%; border-radius: 100px;
        transition: width .6s ease;
    }
    .fill-normal { background: linear-gradient(90deg, var(--accent), #f7c59f); }
    .fill-warning { background: linear-gradient(90deg, #fbbf24, #f59e0b); }
    .fill-full { background: linear-gradient(90deg, #f87171, #ef4444); }

    /* Action Buttons */
    .btn-rpl-primary {
        display: inline-flex; align-items: center; gap: .5rem;
        background: var(--accent); color: var(--deep);
        font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; font-size: .84rem;
        padding: .65rem 1.25rem; border-radius: 12px; border: none; cursor: pointer;
        box-shadow: 0 4px 18px rgba(249,177,122,.28);
        transition: all .22s ease; text-decoration: none;
    }
    .btn-rpl-primary:hover {
        background: #fbc08e; color: var(--deep);
        box-shadow: 0 6px 26px rgba(249,177,122,.45); transform: translateY(-2px);
    }
    .btn-rpl-secondary {
        display: inline-flex; align-items: center; gap: .5rem;
        background: rgba(103,111,157,0.18); color: var(--white);
        font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 600; font-size: .84rem;
        padding: .65rem 1.25rem; border-radius: 12px;
        border: 1px solid rgba(103,111,157,0.32); cursor: pointer;
        transition: all .22s ease; text-decoration: none;
    }
    .btn-rpl-secondary:hover {
        background: rgba(103,111,157,0.3); color: var(--white);
        border-color: rgba(249,177,122,0.4); transform: translateY(-2px);
    }

    /* Filter Tabs */
    .filter-tabs-wrap {
        display: flex; align-items: center; justify-content: space-between;
        gap: .8rem; flex-wrap: wrap; margin-bottom: 1.25rem;
    }
    .tab-group {
        display: inline-flex; align-items: center;
        background: rgba(37,40,66,0.65); border: 1px solid var(--glass-border);
        border-radius: 14px; padding: 4px; gap: 4px;
    }
    .tab-item-btn {
        display: inline-flex; align-items: center; gap: .45rem;
        font-family: 'Plus Jakarta Sans', sans-serif; font-size: .78rem; font-weight: 700;
        padding: .45rem 1rem; border-radius: 10px; border: none;
        background: transparent; color: rgba(255,255,255,0.48);
        cursor: pointer; transition: all .2s; text-decoration: none;
    }
    .tab-item-btn:hover { color: var(--white); background: rgba(255,255,255,0.06); }
    .tab-item-btn.active {
        background: rgba(249,177,122,0.18); color: var(--accent);
        box-shadow: inset 0 0 0 1px rgba(249,177,122,0.3);
    }
    .tab-count {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 18px; height: 18px; padding: 0 5px; border-radius: 100px;
        background: rgba(255,255,255,0.1); font-size: .65rem; font-weight: 800;
        color: inherit;
    }
    .tab-item-btn.active .tab-count {
        background: rgba(249,177,122,0.25); color: var(--accent);
    }

    /* Table */
    .rpl-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .rpl-table thead tr { background: rgba(37,40,66,0.75); }
    .rpl-table thead th {
        padding: .95rem 1.15rem; font-size: .7rem; font-weight: 700;
        letter-spacing: .1em; text-transform: uppercase; color: rgba(255,255,255,.4);
        border-bottom: 1px solid var(--glass-border); white-space: nowrap;
    }
    .rpl-table thead th:first-child { padding-left: 1.6rem; }
    .rpl-table thead th:last-child { padding-right: 1.6rem; }
    .rpl-table tbody tr {
        border-bottom: 1px solid rgba(103,111,157,.12);
        transition: background .18s;
    }
    .rpl-table tbody tr:last-child { border-bottom: none; }
    .rpl-table tbody tr:hover { background: rgba(103,111,157,.08); }
    .rpl-table tbody td {
        padding: 1rem 1.15rem; font-size: .875rem; color: rgba(255,255,255,.78);
        vertical-align: middle;
    }
    .rpl-table tbody td:first-child { padding-left: 1.6rem; }
    .rpl-table tbody td:last-child { padding-right: 1.6rem; }

    /* Badges */
    .badge-grade-x {
        background: rgba(96,165,250,0.12); color: #93c5fd;
        border: 1px solid rgba(96,165,250,0.25);
        font-family: 'Bricolage Grotesque', sans-serif; font-size: .72rem; font-weight: 800;
        padding: .25rem .65rem; border-radius: 8px;
    }
    .badge-grade-xi {
        background: rgba(249,177,122,0.12); color: var(--accent);
        border: 1px solid rgba(249,177,122,0.25);
        font-family: 'Bricolage Grotesque', sans-serif; font-size: .72rem; font-weight: 800;
        padding: .25rem .65rem; border-radius: 8px;
    }
    .badge-grade-xii {
        background: rgba(167,139,250,0.12); color: #c4b5fd;
        border: 1px solid rgba(167,139,250,0.25);
        font-family: 'Bricolage Grotesque', sans-serif; font-size: .72rem; font-weight: 800;
        padding: .25rem .65rem; border-radius: 8px;
    }

    .badge-status-active {
        display: inline-flex; align-items: center; gap: .35rem;
        background: rgba(52,211,153,0.1); color: #34d399;
        border: 1px solid rgba(52,211,153,0.25);
        font-size: .7rem; font-weight: 700; padding: .2rem .6rem; border-radius: 100px;
    }
    .badge-status-inactive {
        display: inline-flex; align-items: center; gap: .35rem;
        background: rgba(248,113,113,0.1); color: #f87171;
        border: 1px solid rgba(248,113,113,0.25);
        font-size: .7rem; font-weight: 700; padding: .2rem .6rem; border-radius: 100px;
    }
    .badge-status-dot { width: 5px; height: 5px; border-radius: 50%; background: currentColor; }

    /* Action Buttons in Row */
    .row-actions { display: flex; align-items: center; gap: .45rem; }
    .btn-action-view {
        display: inline-flex; align-items: center; gap: .3rem;
        background: rgba(103,111,157,0.16); color: var(--white);
        border: 1px solid rgba(103,111,157,0.28);
        padding: .35rem .75rem; border-radius: 8px; font-size: .75rem; font-weight: 600;
        cursor: pointer; transition: all .2s;
    }
    .btn-action-view:hover {
        background: rgba(103,111,157,0.3); border-color: rgba(249,177,122,0.4);
    }
    .btn-action-edit {
        display: inline-flex; align-items: center; justify-content: center;
        width: 32px; height: 32px; border-radius: 8px;
        background: rgba(249,177,122,0.12); color: var(--accent);
        border: 1px solid rgba(249,177,122,0.24);
        cursor: pointer; transition: all .2s;
    }
    .btn-action-edit:hover {
        background: rgba(249,177,122,0.24); transform: translateY(-1px);
    }
    .btn-action-delete {
        display: inline-flex; align-items: center; justify-content: center;
        width: 32px; height: 32px; border-radius: 8px;
        background: rgba(248,113,113,0.1); color: #f87171;
        border: 1px solid rgba(248,113,113,0.22);
        cursor: pointer; transition: all .2s;
    }
    .btn-action-delete:hover {
        background: rgba(248,113,113,0.22); transform: translateY(-1px);
    }

    /* Modal Styling */
    .modal-rpl .modal-dialog { max-width: 520px; }
    .modal-rpl .modal-content {
        background: linear-gradient(145deg, rgba(37,40,66,0.98) 0%, rgba(26,29,48,0.98) 100%);
        border: 1px solid rgba(249,177,122,0.3);
        border-radius: 24px;
        backdrop-filter: blur(20px);
        box-shadow: 0 30px 80px rgba(0,0,0,0.65);
        color: var(--white);
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .modal-rpl .modal-header {
        border-bottom: 1px solid var(--glass-border);
        padding: 1.35rem 1.65rem 1.15rem;
    }
    .modal-rpl .modal-title {
        font-family: 'Bricolage Grotesque', sans-serif;
        font-size: 1.15rem; font-weight: 800; color: var(--white);
        letter-spacing: -.01em;
    }
    .modal-rpl .btn-close {
        filter: brightness(0) invert(1); opacity: .6;
    }
    .modal-rpl .modal-body { padding: 1.5rem 1.65rem; }
    .modal-rpl .modal-footer {
        border-top: 1px solid var(--glass-border);
        padding: 1.15rem 1.65rem;
    }

    .form-group-rpl { margin-bottom: 1.15rem; }
    .form-label-rpl {
        display: block; font-size: .78rem; font-weight: 700;
        letter-spacing: .02em; color: rgba(255,255,255,0.7);
        margin-bottom: .45rem;
    }
    .form-control-rpl {
        width: 100%;
        background: rgba(255,255,255,0.06);
        border: 1.5px solid rgba(103,111,157,0.3);
        border-radius: 11px; padding: .65rem .9rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: .865rem; color: var(--white); outline: none;
        transition: all .2s;
    }
    .form-control-rpl option { background-color: #252842; color: #fff; }
    .form-control-rpl:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(249,177,122,0.12);
        background: rgba(249,177,122,0.04);
    }
    .form-hint-rpl {
        font-size: .72rem; color: rgba(255,255,255,0.38); margin-top: .3rem;
    }

    /* Alerts */
    .alert-rpl-success {
        background: rgba(52,211,153,0.12); border: 1px solid rgba(52,211,153,0.28);
        border-radius: 14px; padding: .85rem 1.15rem; color: #6ee7b7;
        font-size: .84rem; display: flex; align-items: center; gap: .65rem;
        margin-bottom: 1.5rem;
    }
    .alert-rpl-error {
        background: rgba(248,113,113,0.12); border: 1px solid rgba(248,113,113,0.28);
        border-radius: 14px; padding: .85rem 1.15rem; color: #fca5a5;
        font-size: .84rem; display: flex; align-items: center; gap: .65rem;
        margin-bottom: 1.5rem;
    }
</style>

{{-- PAGE HEADER --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 anim-fade-up mb-4">
    <div>
        <div class="dash-eyebrow">Pusat Kendali Akademik</div>
        <h1 class="dash-title">Manajemen Kelas RPL Seluruh Angkatan</h1>
        <p class="dash-subtitle mb-0">Kelola rombongan belajar (rombel), struktur kelas X, XI, XII, dan kuota kapasitas siswa.</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        {{-- Tombol Atur Jumlah Rombel Angkatan (Bulk Generator) --}}
        <button type="button" class="btn-rpl-primary" data-bs-toggle="modal" data-bs-target="#generateRombelModal">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
            </svg>
            Atur Rombel Angkatan
        </button>

        {{-- Tombol Tambah Kelas Tunggal --}}
        <button type="button" class="btn-rpl-secondary" data-bs-toggle="modal" data-bs-target="#createClassModal">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"/>
                <line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Tambah Kelas
        </button>
    </div>
</div>

{{-- NOTIFIKASI FLASH MESSAGE --}}
@if(session('success'))
    <div class="alert-rpl-success anim-fade-up">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"/>
        </svg>
        <div>{{ session('success') }}</div>
    </div>
@endif

@if(session('error'))
    <div class="alert-rpl-error anim-fade-up">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <div>{{ session('error') }}</div>
    </div>
@endif

@if(isset($errors) && $errors->any())
    <div class="alert-rpl-error anim-fade-up">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- OVERVIEW ANGKATAN CARDS (Kelas X, XI, XII & Total) --}}
<div class="row g-3 mb-4 anim-fade-up delay-1">

    {{-- Ringkasan Total Kelas RPL --}}
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card-grade">
            <div class="stat-badge-ang" style="background:rgba(255,255,255,0.08);color:#fff;border-color:rgba(255,255,255,0.18);">
                🏢 TOTAL KELAS RPL
            </div>
            <div class="stat-num-val">{{ $totalClasses }} <span style="font-size:1rem;color:rgba(255,255,255,0.4);">Rombel</span></div>
            <div class="stat-num-sub">{{ $totalActiveClasses }} Aktif &bull; {{ $totalStudents }} Siswa Terdaftar</div>
            <div class="capacity-progress">
                @php
                    $totalFillPercent = $totalCapacity > 0 ? min(100, (int)round(($totalStudents / $totalCapacity) * 100)) : 0;
                @endphp
                <div class="capacity-bar-fill fill-normal" style="width: {{ $totalFillPercent }}%;"></div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-2" style="font-size:.7rem;color:rgba(255,255,255,0.4);">
                <span>Kapasitas: {{ $totalCapacity }}</span>
                <span>{{ $totalFillPercent }}% Terisi</span>
            </div>
        </div>
    </div>

    {{-- Angkatan Kelas X (Sepuluh) --}}
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card-grade">
            <div class="stat-badge-ang" style="background:rgba(96,165,250,0.12);color:#93c5fd;border-color:rgba(96,165,250,0.25);">
                💻 KELAS X (10) RPL
            </div>
            <div class="stat-num-val">{{ $gradeStats['X']['total_rombel'] }} <span style="font-size:1rem;color:rgba(255,255,255,0.4);">Rombel</span></div>
            <div class="stat-num-sub">{{ $gradeStats['X']['total_students'] }} Siswa &bull; Kuota {{ $gradeStats['X']['total_capacity'] }}</div>
            <div class="capacity-progress">
                <div class="capacity-bar-fill fill-normal" style="width: {{ $gradeStats['X']['percentage'] }}%;"></div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-2" style="font-size:.7rem;color:rgba(255,255,255,0.4);">
                <span>{{ $gradeStats['X']['active_rombel'] }} Rombel Aktif</span>
                <span>{{ $gradeStats['X']['percentage'] }}% Terisi</span>
            </div>
        </div>
    </div>

    {{-- Angkatan Kelas XI (Sebelas) --}}
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card-grade">
            <div class="stat-badge-ang">
                🚀 KELAS XI (11) RPL
            </div>
            <div class="stat-num-val">{{ $gradeStats['XI']['total_rombel'] }} <span style="font-size:1rem;color:rgba(255,255,255,0.4);">Rombel</span></div>
            <div class="stat-num-sub">{{ $gradeStats['XI']['total_students'] }} Siswa &bull; Kuota {{ $gradeStats['XI']['total_capacity'] }}</div>
            <div class="capacity-progress">
                <div class="capacity-bar-fill fill-normal" style="width: {{ $gradeStats['XI']['percentage'] }}%;"></div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-2" style="font-size:.7rem;color:rgba(255,255,255,0.4);">
                <span>{{ $gradeStats['XI']['active_rombel'] }} Rombel Aktif</span>
                <span>{{ $gradeStats['XI']['percentage'] }}% Terisi</span>
            </div>
        </div>
    </div>

    {{-- Angkatan Kelas XII (Dua Belas) --}}
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card-grade">
            <div class="stat-badge-ang" style="background:rgba(167,139,250,0.12);color:#c4b5fd;border-color:rgba(167,139,250,0.25);">
                🎓 KELAS XII (12) RPL
            </div>
            <div class="stat-num-val">{{ $gradeStats['XII']['total_rombel'] }} <span style="font-size:1rem;color:rgba(255,255,255,0.4);">Rombel</span></div>
            <div class="stat-num-sub">{{ $gradeStats['XII']['total_students'] }} Siswa &bull; Kuota {{ $gradeStats['XII']['total_capacity'] }}</div>
            <div class="capacity-progress">
                <div class="capacity-bar-fill fill-normal" style="width: {{ $gradeStats['XII']['percentage'] }}%;"></div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-2" style="font-size:.7rem;color:rgba(255,255,255,0.4);">
                <span>{{ $gradeStats['XII']['active_rombel'] }} Rombel Aktif</span>
                <span>{{ $gradeStats['XII']['percentage'] }}% Terisi</span>
            </div>
        </div>
    </div>

</div>

{{-- FILTER TABS & SEARCH --}}
<div class="filter-tabs-wrap anim-fade-up delay-2">
    {{-- Tabs Angkatan --}}
    <div class="tab-group">
        <a href="{{ route('admin.classes.index', ['grade' => 'all', 'status' => request('status', 'all')]) }}"
           class="tab-item-btn {{ $selectedGrade === 'all' ? 'active' : '' }}">
            Semua Angkatan
            <span class="tab-count">{{ $totalClasses }}</span>
        </a>
        <a href="{{ route('admin.classes.index', ['grade' => 'X', 'status' => request('status', 'all')]) }}"
           class="tab-item-btn {{ $selectedGrade === 'X' ? 'active' : '' }}">
            Kelas X (10)
            <span class="tab-count">{{ $gradeStats['X']['total_rombel'] }}</span>
        </a>
        <a href="{{ route('admin.classes.index', ['grade' => 'XI', 'status' => request('status', 'all')]) }}"
           class="tab-item-btn {{ $selectedGrade === 'XI' ? 'active' : '' }}">
            Kelas XI (11)
            <span class="tab-count">{{ $gradeStats['XI']['total_rombel'] }}</span>
        </a>
        <a href="{{ route('admin.classes.index', ['grade' => 'XII', 'status' => request('status', 'all')]) }}"
           class="tab-item-btn {{ $selectedGrade === 'XII' ? 'active' : '' }}">
            Kelas XII (12)
            <span class="tab-count">{{ $gradeStats['XII']['total_rombel'] }}</span>
        </a>
    </div>

    {{-- Filter Status Dropdown --}}
    <div class="d-flex align-items-center gap-2">
        <label style="font-size:.75rem;color:rgba(255,255,255,0.45);text-transform:uppercase;font-weight:700;">Status:</label>
        <select onchange="window.location.href=this.value" class="form-control-rpl py-1 px-3" style="width:auto;font-size:.8rem;">
            <option value="{{ route('admin.classes.index', ['grade' => $selectedGrade, 'status' => 'all']) }}" {{ $selectedStatus === 'all' ? 'selected' : '' }}>Semua Status</option>
            <option value="{{ route('admin.classes.index', ['grade' => $selectedGrade, 'status' => 'active']) }}" {{ $selectedStatus === 'active' ? 'selected' : '' }}>Hanya Aktif</option>
            <option value="{{ route('admin.classes.index', ['grade' => $selectedGrade, 'status' => 'inactive']) }}" {{ $selectedStatus === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
        </select>
    </div>
</div>

{{-- DAFTAR KELAS & KUOTA TABLE --}}
<div class="glass-card anim-fade-up delay-3">
    <div class="table-responsive">
        <table class="rpl-table">
            <thead>
                <tr>
                    <th>Kelas RPL</th>
                    <th>Tingkat</th>
                    <th>Rombel</th>
                    <th>Kapasitas & Keterisian Kuota</th>
                    <th>Status</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($classes as $c)
                    @php
                        $studentsCount = $c->students_count ?? $c->students->count();
                        $capacity = max(1, (int)$c->capacity);
                        $percent = min(100, (int)round(($studentsCount / $capacity) * 100));
                        $remaining = max(0, (int)$c->capacity - $studentsCount);
                        $isFull = $studentsCount >= (int)$c->capacity;

                        $fillClass = 'fill-normal';
                        if ($isFull) {
                            $fillClass = 'fill-full';
                        } elseif ($percent >= 80) {
                            $fillClass = 'fill-warning';
                        }
                    @endphp
                    <tr>
                        {{-- Nama Kelas --}}
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:36px;height:36px;border-radius:10px;background:rgba(249,177,122,0.14);border:1px solid rgba(249,177,122,0.25);display:grid;place-items:center;font-size:.9rem;color:var(--accent);">
                                    🏫
                                </div>
                                <div>
                                    <div style="font-family:'Bricolage Grotesque',sans-serif;font-weight:800;font-size:1rem;color:var(--white);">
                                        {{ $c->name }}
                                    </div>
                                    <div style="font-size:.72rem;color:rgba(255,255,255,0.4);">
                                        T.A {{ $c->academic_year ?? '2025/2026' }} &bull; {{ $c->tasks_count }} Tugas
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Tingkat --}}
                        <td>
                            @if($c->grade === 'X')
                                <span class="badge-grade-x">Kelas 10 (X)</span>
                            @elseif($c->grade === 'XI')
                                <span class="badge-grade-xi">Kelas 11 (XI)</span>
                            @elseif($c->grade === 'XII')
                                <span class="badge-grade-xii">Kelas 12 (XII)</span>
                            @else
                                <span class="badge-grade-xi">{{ $c->grade }}</span>
                            @endif
                        </td>

                        {{-- Rombel --}}
                        <td>
                            <span style="font-family:'Bricolage Grotesque',sans-serif;font-weight:700;color:rgba(255,255,255,0.75);">
                                Rombel {{ $c->rombel ?? '-' }}
                            </span>
                        </td>

                        {{-- Kuota & Kapasitas Bar --}}
                        <td style="min-width: 220px;">
                            <div class="d-flex align-items-center justify-content-between mb-1" style="font-size:.76rem;">
                                <div>
                                    <strong style="color:var(--white);">{{ $studentsCount }}</strong>
                                    <span style="color:rgba(255,255,255,0.45);">/ {{ $c->capacity }} Siswa</span>
                                </div>
                                <div>
                                    @if($isFull)
                                        <span class="badge" style="background:rgba(248,113,113,0.18);color:#f87171;font-size:.65rem;border:1px solid rgba(248,113,113,0.3);">PENUH</span>
                                    @else
                                        <span class="badge" style="background:rgba(52,211,153,0.14);color:#34d399;font-size:.65rem;border:1px solid rgba(52,211,153,0.25);">Sisa {{ $remaining }} Slot</span>
                                    @endif
                                </div>
                            </div>
                            <div class="capacity-progress mt-0">
                                <div class="capacity-bar-fill {{ $fillClass }}" style="width: {{ $percent }}%;"></div>
                            </div>
                        </td>

                        {{-- Status --}}
                        <td>
                            @if($c->status === 'active')
                                <span class="badge-status-active">
                                    <span class="badge-status-dot"></span> Aktif
                                </span>
                            @else
                                <span class="badge-status-inactive">
                                    <span class="badge-status-dot"></span> Nonaktif
                                </span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td>
                            <div class="row-actions justify-content-end">
                                {{-- Lihat Siswa --}}
                                <button type="button" class="btn-action-view"
                                        onclick="showStudentsModal({{ $c->id }}, '{{ addslashes($c->name) }}', {{ $studentsCount }}, {{ $c->capacity }})">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                    </svg>
                                    Siswa ({{ $studentsCount }})
                                </button>

                                {{-- Edit Kelas & Kapasitas --}}
                                <button type="button" class="btn-action-edit" title="Edit Kelas & Kapasitas"
                                        onclick="openEditModal({{ json_encode($c) }}, {{ $studentsCount }})">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </button>

                                {{-- Hapus Kelas --}}
                                <form action="{{ route('admin.classes.destroy', $c->id) }}" method="POST"
                                      onsubmit="return confirmDeleteClass('{{ addslashes($c->name) }}', {{ $studentsCount }}, {{ $c->tasks_count }})" style="margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action-delete" title="Hapus Kelas">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div style="font-size:2.5rem;margin-bottom:.5rem;">🏫</div>
                            <div style="font-family:'Bricolage Grotesque',sans-serif;font-size:1.1rem;font-weight:700;color:rgba(255,255,255,0.6);">
                                Belum Ada Kelas pada Kriteria Ini
                            </div>
                            <p style="font-size:.82rem;color:rgba(255,255,255,0.35);margin-top:.25rem;">
                                Klik tombol <strong>"Atur Rombel Angkatan"</strong> untuk membuat rombel secara instan.
                            </p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════
     MODAL 1: ATUR JUMLAH ROMBEL ANGKATAN (BULK GENERATOR)
═══════════════════════════════════════════════════════════════ --}}
<div class="modal fade modal-rpl" id="generateRombelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.classes.generate') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <div>
                        <div style="font-size:.7rem;font-weight:800;color:var(--accent);letter-spacing:.1em;text-transform:uppercase;">Setup Cepat</div>
                        <h5 class="modal-title">Atur Rombel per Angkatan</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p style="font-size:.82rem;color:rgba(255,255,255,0.48);margin-bottom:1.25rem;">
                        Tentukan jumlah rombel dan kapasitas per kelas untuk angkatan yang dipilih. Sistem akan otomatis menyiapkan rombel (contoh: <strong>X RPL 1</strong>, <strong>X RPL 2</strong>).
                    </p>

                    {{-- Pilih Angkatan --}}
                    <div class="form-group-rpl">
                        <label class="form-label-rpl">Pilih Angkatan / Tingkat</label>
                        <select name="grade" class="form-control-rpl" required id="genGradeSelect" onchange="previewRombelGen()">
                            <option value="X" {{ $selectedGrade === 'X' ? 'selected' : '' }}>Kelas 10 (X RPL)</option>
                            <option value="XI" {{ $selectedGrade === 'XI' ? 'selected' : '' }}>Kelas 11 (XI RPL)</option>
                            <option value="XII" {{ $selectedGrade === 'XII' ? 'selected' : '' }}>Kelas 12 (XII RPL)</option>
                        </select>
                    </div>

                    {{-- Jumlah Rombel --}}
                    <div class="form-group-rpl">
                        <label class="form-label-rpl">Jumlah Rombel Kelas</label>
                        <input type="number" name="rombel_count" class="form-control-rpl"
                               min="1" max="15" value="2" required id="genCountInput" oninput="previewRombelGen()">
                        <div class="form-hint-rpl">Berapa kelas rombel yang ingin dibuka untuk angkatan ini (misal: 2, 3, atau 4).</div>
                    </div>

                    {{-- Kapasitas / Kuota per Kelas --}}
                    <div class="form-group-rpl">
                        <label class="form-label-rpl">Kapasitas Maksimal Siswa (Kuota / Kelas)</label>
                        <input type="number" name="capacity" class="form-control-rpl"
                               min="10" max="100" value="36" required>
                        <div class="form-hint-rpl">Batas maksimal kuota siswa untuk masing-masing kelas rombel (standar: 36 siswa).</div>
                    </div>

                    {{-- Tahun Ajaran --}}
                    <div class="form-group-rpl">
                        <label class="form-label-rpl">Tahun Ajaran</label>
                        <input type="text" name="academic_year" class="form-control-rpl"
                               value="{{ date('Y') }}/{{ date('Y') + 1 }}" placeholder="2025/2026">
                    </div>

                    {{-- Preview Box --}}
                    <div style="background:rgba(249,177,122,0.08);border:1px dashed rgba(249,177,122,0.3);border-radius:12px;padding:.85rem 1rem;">
                        <div style="font-size:.7rem;font-weight:800;color:var(--accent);letter-spacing:.08em;text-transform:uppercase;margin-bottom:.35rem;">
                            📋 Preview Kelas yang Disiapkan:
                        </div>
                        <div id="genPreviewText" style="font-family:'Bricolage Grotesque',sans-serif;font-size:.88rem;color:var(--white);font-weight:700;">
                            X RPL 1, X RPL 2
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-rpl-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-rpl-primary">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Simpan & Terapkan Rombel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════
     MODAL 2: TAMBAH KELAS TUNGGAL MANUAL
═══════════════════════════════════════════════════════════════ --}}
<div class="modal fade modal-rpl" id="createClassModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.classes.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <div>
                        <div style="font-size:.7rem;font-weight:800;color:var(--accent);letter-spacing:.1em;text-transform:uppercase;">Input Manual</div>
                        <h5 class="modal-title">Tambah Kelas RPL Baru</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Tingkat --}}
                    <div class="form-group-rpl">
                        <label class="form-label-rpl">Angkatan / Tingkat</label>
                        <select name="grade" class="form-control-rpl" required id="manualGradeSelect" onchange="previewManualName()">
                            <option value="X">Kelas 10 (X RPL)</option>
                            <option value="XI" selected>Kelas 11 (XI RPL)</option>
                            <option value="XII">Kelas 12 (XII RPL)</option>
                        </select>
                    </div>

                    {{-- Rombel Ke --}}
                    <div class="form-group-rpl">
                        <label class="form-label-rpl">Nomor Rombel</label>
                        <input type="number" name="rombel" class="form-control-rpl"
                               min="1" max="25" value="1" required id="manualRombelInput" oninput="previewManualName()">
                    </div>

                    {{-- Nama Kelas --}}
                    <div class="form-group-rpl">
                        <label class="form-label-rpl">Nama Kelas (Opsional, otomatis dibuat)</label>
                        <input type="text" name="name" class="form-control-rpl" id="manualNameInput"
                               placeholder="Contoh: XI RPL 1">
                        <div class="form-hint-rpl">Biarkan kosong untuk otomatis format "{Tingkat} RPL {Rombel}".</div>
                    </div>

                    {{-- Kapasitas Kuota --}}
                    <div class="form-group-rpl">
                        <label class="form-label-rpl">Kapasitas Maksimal Siswa (Kuota)</label>
                        <input type="number" name="capacity" class="form-control-rpl"
                               min="1" max="100" value="36" required>
                    </div>

                    {{-- Tahun Ajaran --}}
                    <div class="form-group-rpl">
                        <label class="form-label-rpl">Tahun Ajaran</label>
                        <input type="text" name="academic_year" class="form-control-rpl"
                               value="{{ date('Y') }}/{{ date('Y') + 1 }}" placeholder="2025/2026">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-rpl-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-rpl-primary">Simpan Kelas</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════
     MODAL 3: EDIT KELAS & KUOTA
═══════════════════════════════════════════════════════════════ --}}
<div class="modal fade modal-rpl" id="editClassModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editClassForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <div>
                        <div style="font-size:.7rem;font-weight:800;color:var(--accent);letter-spacing:.1em;text-transform:uppercase;">Pengaturan Kelas</div>
                        <h5 class="modal-title">Edit Data Kelas & Kuota</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Nama Kelas --}}
                    <div class="form-group-rpl">
                        <label class="form-label-rpl">Nama Kelas</label>
                        <input type="text" name="name" class="form-control-rpl" id="editName" required>
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <div class="form-group-rpl">
                                <label class="form-label-rpl">Tingkat</label>
                                <select name="grade" class="form-control-rpl" id="editGrade" required>
                                    <option value="X">Kelas 10 (X)</option>
                                    <option value="XI">Kelas 11 (XI)</option>
                                    <option value="XII">Kelas 12 (XII)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group-rpl">
                                <label class="form-label-rpl">Nomor Rombel</label>
                                <input type="number" name="rombel" class="form-control-rpl" id="editRombel" min="1" max="25" required>
                            </div>
                        </div>
                    </div>

                    {{-- Kapasitas --}}
                    <div class="form-group-rpl">
                        <label class="form-label-rpl">Batas Kuota Siswa</label>
                        <input type="number" name="capacity" class="form-control-rpl" id="editCapacity" min="1" max="100" required>
                        <div class="form-hint-rpl" id="editCapacityWarning"></div>
                    </div>

                    {{-- Status --}}
                    <div class="form-group-rpl">
                        <label class="form-label-rpl">Status Kelas</label>
                        <select name="status" class="form-control-rpl" id="editStatus" required>
                            <option value="active">Aktif (Bisa dipilih siswa & guru)</option>
                            <option value="inactive">Nonaktif (Arsip / Tidak aktif)</option>
                        </select>
                    </div>

                    {{-- Tahun Ajaran --}}
                    <div class="form-group-rpl">
                        <label class="form-label-rpl">Tahun Ajaran</label>
                        <input type="text" name="academic_year" class="form-control-rpl" id="editAcademicYear">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-rpl-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-rpl-primary">Perbarui Kelas</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════
     MODAL 4: LIHAT SISWA DALAM KELAS
═══════════════════════════════════════════════════════════════ --}}
<div class="modal fade modal-rpl" id="studentsListModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 640px;">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <div style="font-size:.7rem;font-weight:800;color:var(--accent);letter-spacing:.1em;text-transform:uppercase;">Daftar Anggota Kelas</div>
                    <h5 class="modal-title" id="studentsModalClassName">Siswa Kelas</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="d-flex justify-content-between align-items-center px-4 py-3" style="background:rgba(255,255,255,0.03);border-bottom:1px solid var(--glass-border);">
                    <div style="font-size:.82rem;color:rgba(255,255,255,0.6);" id="studentsModalSummary">
                        Memuat data...
                    </div>
                    <a href="{{ route('admin.students.index') }}" class="btn-rpl-secondary py-1 px-3" style="font-size:.74rem;">
                        Buka di Data Siswa
                    </a>
                </div>

                <div id="studentsListContainer" style="max-height: 380px; overflow-y: auto;">
                    <div class="text-center py-5 text-muted">
                        <div class="spinner-border text-warning spinner-border-sm" role="status"></div>
                        <div class="mt-2" style="font-size:.82rem;">Memuat daftar siswa...</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-rpl-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT INTERAKSI --}}
<script>
    // Animate on load
    setTimeout(() => {
        document.querySelectorAll('.anim-fade-up').forEach(el => el.classList.add('is-visible'));
    }, 60);

    // Preview Generator Rombel
    function previewRombelGen() {
        const grade = document.getElementById('genGradeSelect').value;
        const count = parseInt(document.getElementById('genCountInput').value) || 0;
        const previewEl = document.getElementById('genPreviewText');

        if (count <= 0) {
            previewEl.textContent = 'Masukkan jumlah rombel minimal 1.';
            return;
        }

        const rombelNames = [];
        for (let i = 1; i <= Math.min(count, 15); i++) {
            rombelNames.push(`${grade} RPL ${i}`);
        }
        previewEl.textContent = rombelNames.join(', ');
    }

    // Preview Manual Name
    function previewManualName() {
        const grade = document.getElementById('manualGradeSelect').value;
        const rombel = document.getElementById('manualRombelInput').value;
        const nameInput = document.getElementById('manualNameInput');
        nameInput.placeholder = `${grade} RPL ${rombel}`;
    }

    // Open Edit Modal
    function openEditModal(c, currentStudents) {
        document.getElementById('editClassForm').action = `/admin/classes/${c.id}`;
        document.getElementById('editName').value = c.name;
        document.getElementById('editGrade').value = c.grade || 'XI';
        document.getElementById('editRombel').value = c.rombel || 1;
        document.getElementById('editCapacity').value = c.capacity || 36;
        document.getElementById('editCapacity').min = currentStudents;
        document.getElementById('editStatus').value = c.status || 'active';
        document.getElementById('editAcademicYear').value = c.academic_year || '2025/2026';

        const warning = document.getElementById('editCapacityWarning');
        if (currentStudents > 0) {
            warning.textContent = `Saat ini ada ${currentStudents} siswa di kelas ini. Kuota minimal tidak boleh di bawah ${currentStudents}.`;
            warning.style.color = 'var(--accent)';
        } else {
            warning.textContent = 'Belum ada siswa di kelas ini.';
            warning.style.color = 'rgba(255,255,255,0.38)';
        }

        const modal = new bootstrap.Modal(document.getElementById('editClassModal'));
        modal.show();
    }

    // Confirm Delete Safety
    function confirmDeleteClass(className, studentsCount, tasksCount) {
        if (studentsCount > 0) {
            alert(`Kelas "${className}" tidak dapat dihapus karena masih memiliki ${studentsCount} siswa terdaftar. Silakan nonaktifkan kelas ini atau pindahkan siswa terlebih dahulu.`);
            return false;
        }
        if (tasksCount > 0) {
            return confirm(`Kelas "${className}" memiliki ${tasksCount} riwayat tugas. Apakah Anda yakin tetap ingin menghapusnya?`);
        }
        return confirm(`Apakah Anda yakin ingin menghapus kelas "${className}"?`);
    }

    // Show Students Modal via AJAX
    function showStudentsModal(classId, className, studentsCount, capacity) {
        document.getElementById('studentsModalClassName').textContent = `Siswa: ${className}`;
        document.getElementById('studentsModalSummary').innerHTML = `
            Terisi: <strong style="color:var(--white);">${studentsCount}</strong> / ${capacity} Kursi &bull;
            Sisa: <strong style="color:var(--accent);">${Math.max(0, capacity - studentsCount)}</strong> Slot
        `;

        const container = document.getElementById('studentsListContainer');
        container.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-warning spinner-border-sm" role="status"></div>
                <div class="mt-2" style="font-size:.82rem;color:rgba(255,255,255,0.4);">Mengambil data siswa...</div>
            </div>
        `;

        const modal = new bootstrap.Modal(document.getElementById('studentsListModal'));
        modal.show();

        fetch(`/admin/classes/${classId}/students`)
            .then(res => res.json())
            .then(data => {
                if (!data.students || data.students.length === 0) {
                    container.innerHTML = `
                        <div class="text-center py-5">
                            <div style="font-size:2rem;margin-bottom:.35rem;">🎒</div>
                            <div style="color:rgba(255,255,255,0.5);font-size:.85rem;">Belum ada siswa yang terdaftar di kelas ini.</div>
                        </div>
                    `;
                    return;
                }

                let html = '<table class="rpl-table mb-0">';
                html += '<thead><tr><th style="padding-left:1.5rem;">No</th><th>Nama Siswa</th><th>Email</th><th>No Absen</th></tr></thead><tbody>';

                data.students.forEach((s, idx) => {
                    html += `
                        <tr>
                            <td style="padding-left:1.5rem;font-weight:700;color:rgba(255,255,255,0.3);">${idx + 1}</td>
                            <td>
                                <div style="font-weight:600;color:var(--white);">${s.name}</div>
                            </td>
                            <td style="font-size:.8rem;color:rgba(255,255,255,0.5);">${s.email}</td>
                            <td>
                                <span style="display:inline-block;padding:.2rem .6rem;background:rgba(249,177,122,0.12);color:var(--accent);border-radius:6px;font-weight:800;font-size:.75rem;">
                                    ${s.attendance_number || '-'}
                                </span>
                            </td>
                        </tr>
                    `;
                });

                html += '</tbody></table>';
                container.innerHTML = html;
            })
            .catch(err => {
                container.innerHTML = `
                    <div class="text-center py-4 text-danger" style="font-size:.82rem;">
                        Gagal memuat data siswa. Silakan coba kembali.
                    </div>
                `;
            });
    }

    // Initial preview setup
    previewRombelGen();
</script>

@endsection
