@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')

<style>
    :root {
        --white:#ffffff; --slate:#676f9d; --mid:#424769;
        --deep:#2d3250; --accent:#f9b17a;
        --glass-bg:rgba(45,50,80,0.48); --glass-border:rgba(103,111,157,0.22);
    }
    .anim-fade-up { opacity:0; transform:translateY(18px); transition:opacity .55s cubic-bezier(.22,.68,0,1.1),transform .55s cubic-bezier(.22,.68,0,1.1); }
    .anim-fade-up.is-visible { opacity:1; transform:translateY(0); }
    .delay-1{transition-delay:.06s;} .delay-2{transition-delay:.14s;} .delay-3{transition-delay:.22s;}

    .dash-eyebrow{font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--accent);margin-bottom:.28rem;}
    .dash-title{font-family:'Bricolage Grotesque',sans-serif;font-size:clamp(1.4rem,2.8vw,1.85rem);font-weight:800;letter-spacing:-.02em;color:var(--white);margin-bottom:.22rem;}
    .dash-subtitle{font-size:.855rem;color:rgba(255,255,255,.38);font-weight:400;}

    /* Glass card */
    .glass-card{background:var(--glass-bg);border:1px solid var(--glass-border);border-radius:20px;backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);overflow:hidden;}

    /* ── FILTER KELAS ── */
    .filter-wrap{
        display:flex;
        align-items:center;
        gap:.65rem;
        flex-wrap:wrap;
        margin-bottom:1.25rem;
    }
    .filter-label{
        font-size:.7rem;
        font-weight:700;
        letter-spacing:.1em;
        text-transform:uppercase;
        color:rgba(255,255,255,.38);
        white-space:nowrap;
    }
    .filter-btn{
        display:inline-flex;
        align-items:center;
        gap:.4rem;
        font-family:'Plus Jakarta Sans',sans-serif;
        font-size:.75rem;
        font-weight:700;
        padding:.38rem 1rem;
        border-radius:100px;
        border:1px solid rgba(103,111,157,.3);
        background:rgba(45,50,80,.55);
        color:rgba(255,255,255,.5);
        cursor:pointer;
        transition:all .2s;
        white-space:nowrap;
    }
    .filter-btn:hover{
        border-color:rgba(249,177,122,.4);
        color:var(--accent);
        background:rgba(249,177,122,.08);
    }
    .filter-btn.active{
        background:rgba(249,177,122,.16);
        border-color:var(--accent);
        color:var(--accent);
        box-shadow:0 0 14px rgba(249,177,122,.18);
    }
    .filter-btn .count-badge{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        min-width:18px;
        height:18px;
        padding:0 5px;
        border-radius:100px;
        background:rgba(249,177,122,.2);
        font-size:.65rem;
        font-weight:800;
        color:var(--accent);
    }

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

    /* Avatar */
    .tbl-avatar-init{width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,var(--mid),var(--slate));border:1.5px solid rgba(103,111,157,.3);display:grid;place-items:center;font-family:'Bricolage Grotesque',sans-serif;font-size:.72rem;font-weight:800;color:var(--white);}

    /* Name + email cell */
    .student-name{font-weight:600;color:var(--white);}
    .student-email{font-size:.75rem;color:rgba(255,255,255,.35);margin-top:.12rem;}

    /* Class badge */
    .class-badge{display:inline-flex;align-items:center;background:rgba(103,111,157,.18);border:1px solid rgba(103,111,157,.28);color:var(--slate);font-size:.7rem;font-weight:700;letter-spacing:.04em;padding:.2rem .65rem;border-radius:100px;font-family:'Bricolage Grotesque',sans-serif;}

    /* Absen badge */
    .absen-badge{display:inline-flex;align-items:center;justify-content:center;min-width:32px;height:28px;padding:0 .7rem;border-radius:8px;background:rgba(249,177,122,.1);border:1px solid rgba(249,177,122,.22);color:var(--accent);font-size:.75rem;font-weight:800;font-family:'Bricolage Grotesque',sans-serif;}

    /* Detail button */
    .btn-detail-sm{display:inline-flex;align-items:center;gap:.35rem;font-family:'Plus Jakarta Sans',sans-serif;font-size:.75rem;font-weight:700;padding:.38rem .9rem;border-radius:9px;border:none;cursor:pointer;white-space:nowrap;transition:all .2s;background:rgba(249,177,122,.14);border:1px solid rgba(249,177,122,.24);color:var(--accent);}
    .btn-detail-sm svg{width:12px;height:12px;}
    .btn-detail-sm:hover{background:rgba(249,177,122,.26);border-color:var(--accent);}

    /* Empty state */
    .empty-state{text-align:center;padding:4rem 1.5rem;color:rgba(255,255,255,.28);}
    .empty-icon{width:56px;height:56px;border-radius:16px;background:rgba(103,111,157,.14);border:1px solid rgba(103,111,157,.22);display:grid;place-items:center;margin:0 auto 1rem;}
    .empty-icon svg{width:24px;height:24px;opacity:.45;}
    .empty-title{font-family:'Bricolage Grotesque',sans-serif;font-size:1rem;font-weight:800;color:rgba(255,255,255,.4);margin-bottom:.4rem;}
    .empty-sub{font-size:.82rem;}

    .table-scroll{overflow-x:auto;}
    .table-scroll::-webkit-scrollbar{height:4px;}
    .table-scroll::-webkit-scrollbar-thumb{background:rgba(103,111,157,.3);border-radius:4px;}

    /* Kelas section header */
    .class-section-head{
        display:none; /* hidden by default, shown when "all" active */
        padding:.65rem 1.5rem;
        background:rgba(37,40,66,.5);
        border-bottom:1px solid rgba(103,111,157,.15);
        font-family:'Bricolage Grotesque',sans-serif;
        font-size:.72rem;
        font-weight:800;
        letter-spacing:.08em;
        text-transform:uppercase;
        color:var(--accent);
    }
    .show-class-head .class-section-head{ display:table-row; }

    /* ── MODAL ── */
    .modal-rpl { z-index: 1060 !important; }
    .modal-rpl .modal-dialog { margin: 1.75rem auto; max-width: 420px; }
    .modal-rpl .modal-content{
        background:linear-gradient(145deg, rgba(30,33,56,0.98) 0%, rgba(20,22,38,0.98) 100%);
        border:1px solid var(--accent);
        border-radius:32px;
        backdrop-filter:blur(24px);
        -webkit-backdrop-filter:blur(24px);
        box-shadow:0 40px 100px rgba(0,0,0,0.7), 0 0 0 1px rgba(249,177,122,0.3) inset;
        color:var(--white);
        font-family:'Plus Jakarta Sans',sans-serif;
        overflow:hidden;
    }
    .modal-rpl .modal-header{ border-bottom:none; padding:1.4rem 1.8rem 0.5rem 1.8rem; }
    .modal-rpl .modal-title{
        font-family:'Bricolage Grotesque',sans-serif;
        font-size:0.85rem; font-weight:800; letter-spacing:0.1em; color:var(--accent);
        text-transform:uppercase; background:rgba(249,177,122,0.12);
        display:inline-block; padding:0.3rem 1.2rem; border-radius:40px;
        border:1px solid rgba(249,177,122,0.3);
    }
    .modal-rpl .btn-close{ filter:brightness(0) invert(1); opacity:0.6; transition:all 0.2s; background-size:1rem; }
    .modal-rpl .btn-close:hover{ opacity:1; transform:scale(1.1); }
    .modal-rpl .modal-body{ padding:0.2rem 1.8rem 1.8rem 1.8rem; }

    .modal-avatar-init{
        width:110px; height:110px; border-radius:30px;
        background:linear-gradient(145deg, var(--mid), var(--deep));
        border:3px solid var(--accent);
        box-shadow:0 20px 35px -12px rgba(0,0,0,0.5);
        display:grid; place-items:center; margin:0 auto 1rem;
        font-family:'Bricolage Grotesque',sans-serif;
        font-size:2.8rem; font-weight:800; color:var(--white);
    }
    .modal-student-name{
        font-family:'Bricolage Grotesque',sans-serif; font-size:1.5rem; font-weight:800;
        letter-spacing:-0.02em; color:var(--white); margin-bottom:0.3rem; text-align:center;
    }
    .modal-role-badge{
        display:inline-flex; align-items:center; gap:0.5rem;
        font-size:0.68rem; font-weight:800; letter-spacing:0.12em; text-transform:uppercase;
        padding:0.3rem 1.1rem; border-radius:100px;
        background:rgba(249,177,122,0.12); border:1px solid rgba(249,177,122,0.35);
        color:var(--accent); margin:0 auto 1rem; width:fit-content;
    }
    .modal-role-badge .dot{
        width:6px; height:6px; border-radius:50%;
        background:var(--accent); box-shadow:0 0 6px var(--accent);
        animation:pulse 1.5s infinite;
    }
    @keyframes pulse{
        0%,100%{opacity:0.4;transform:scale(0.8);}
        50%{opacity:1;transform:scale(1.2);}
    }
    .modal-divider{
        height:2px;
        background:linear-gradient(90deg, transparent, var(--accent), var(--slate), transparent);
        margin:1rem 0 1.3rem 0; opacity:0.5; border-radius:4px;
    }
    .modal-info-row{
        display:flex; align-items:center; justify-content:space-between;
        padding:0.75rem 0; border-bottom:1px solid rgba(103,111,157,0.2); gap:0.8rem;
    }
    .modal-info-row:last-child{ border-bottom:none; padding-bottom:0; }
    .modal-info-key{ font-size:.72rem; font-weight:700; color:rgba(255,255,255,0.5); letter-spacing:.04em; text-transform:uppercase; flex-shrink:0; }
    .modal-info-val{ font-size:.88rem; font-weight:500; color:rgba(255,255,255,0.85); text-align:right; word-break:break-all; font-family:'Plus Jakarta Sans',sans-serif; }
    .modal-info-val.accent{ color:var(--accent); font-weight:700; }
    .modal-rpl .modal-content::before{
        content:''; position:absolute; top:0; left:0; right:0; height:3px;
        background:linear-gradient(90deg, transparent, var(--accent), var(--accent), transparent);
        pointer-events:none; z-index:5;
    }
    .modal-backdrop { z-index:1050 !important; backdrop-filter:blur(8px); background-color:rgba(0,0,0,0.65); }
    .modal-backdrop.fade { opacity:1; }

    /* hidden row utility */
    .student-row{ transition: opacity .2s; }
    .student-row.hidden-row{ display:none; }
</style>

{{-- Page header --}}
<div class="anim-fade-up" style="margin-bottom:1.5rem;">
    <div class="dash-eyebrow">Manajemen</div>
    <h1 class="dash-title">Data Siswa Per Kelas</h1>
    <p class="dash-subtitle">Daftar seluruh siswa yang terdaftar dalam sistem.</p>
</div>

{{-- Filter Kelas --}}
<div class="anim-fade-up delay-1 filter-wrap">
    <span class="filter-label">Filter Kelas :</span>
    <button class="filter-btn active" data-filter="all">
        Semua Kelas
        <span class="count-badge">{{ $classes->sum(fn($c) => $c->students->count()) }}</span>
    </button>
    @foreach($classes as $class)
        <button class="filter-btn" data-filter="class-{{ $class->id }}">
            {{ $class->name }}
            <span class="count-badge">{{ $class->students->count() }}</span>
        </button>
    @endforeach
</div>

{{-- Table card --}}
<div class="glass-card anim-fade-up delay-2" id="studentTableCard">
    <div class="table-scroll">
        <table class="rpl-table" id="studentTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Siswa</th>
                    <th>Email</th>
                    <th>Kelas</th>
                    <th>No Absen</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="studentTbody">
                @php $globalNo = 1; @endphp
                @foreach($classes as $class)
                    {{-- Class separator row (shown when "all" active) --}}
                    <tr class="class-sep-row" data-sep-for="class-{{ $class->id }}">
                        <td colspan="6" style="padding:.6rem 1.5rem;background:rgba(37,40,66,.55);border-bottom:1px solid rgba(103,111,157,.18);">
                            <span style="font-family:'Bricolage Grotesque',sans-serif;font-size:.7rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:var(--accent);">
                                🏫 {{ $class->name }}
                            </span>
                            <span style="font-size:.68rem;color:rgba(255,255,255,.3);margin-left:.6rem;">{{ $class->students->count() }} siswa</span>
                        </td>
                    </tr>

                    @forelse($class->students as $student)
                        <tr class="student-row" data-class="class-{{ $class->id }}" data-row-num="{{ $globalNo }}">
                            <td><span class="row-num display-num">{{ $globalNo }}</span></td>
                            <td>
                                <div style="display:flex;align-items:center;gap:.75rem;">
                                    <div class="tbl-avatar-init">{{ strtoupper(substr($student->name,0,2)) }}</div>
                                    <div>
                                        <div class="student-name">{{ $student->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="student-email" style="font-size:.82rem;color:rgba(255,255,255,.55);">{{ $student->email }}</span>
                            </td>
                            <td>
                                <span class="class-badge">{{ $class->name }}</span>
                            </td>
                            <td>
                                <span class="absen-badge">{{ $student->attendance_number }}</span>
                            </td>
                            <td>
                                <button class="btn-detail-sm" data-bs-toggle="modal" data-bs-target="#studentModal{{ $student->id }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                    </svg>
                                    Detail
                                </button>
                            </td>
                        </tr>
                        @php $globalNo++; @endphp
                    @empty
                        {{-- no students in this class, separator still shown --}}
                        <tr class="student-row empty-class-row" data-class="class-{{ $class->id }}">
                            <td colspan="6">
                                <div class="empty-state" style="padding:2rem 1.5rem;">
                                    <div class="empty-title" style="font-size:.85rem;">Belum ada siswa di kelas ini.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                @endforeach

                {{-- Global empty state (shown only if no classes at all) --}}
                @if($classes->count() === 0)
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                                        <circle cx="9" cy="7" r="4"/>
                                        <path d="M23 21v-2a4 4 0 00-3-3.87"/>
                                        <path d="M16 3.13a4 4 0 010 7.75"/>
                                    </svg>
                                </div>
                                <div class="empty-title">Belum ada data</div>
                                <p class="empty-sub">Belum ada kelas maupun siswa yang terdaftar.</p>
                            </div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

{{-- MODALS --}}
@foreach($classes as $class)
    @foreach($class->students as $student)
    <div class="modal fade modal-rpl" id="studentModal{{ $student->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">PROFIL SISWA</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="modal-avatar-init">{{ strtoupper(substr($student->name,0,2)) }}</div>

                    <div class="modal-student-name">{{ $student->name }}</div>
                    <div style="text-align:center;">
                        <div class="modal-role-badge">
                            <span class="dot"></span> SISWA AKTIF
                        </div>
                    </div>

                    <div class="modal-divider"></div>

                    <div class="modal-info-row">
                        <span class="modal-info-key">📧 EMAIL</span>
                        <span class="modal-info-val">{{ $student->email }}</span>
                    </div>
                    <div class="modal-info-row">
                        <span class="modal-info-key">🏫 KELAS</span>
                        <span class="modal-info-val accent">{{ $class->name }}</span>
                    </div>
                    <div class="modal-info-row">
                        <span class="modal-info-key">🔢 NO ABSEN</span>
                        <span class="modal-info-val accent">{{ $student->attendance_number }}</span>
                    </div>
                    <div class="modal-info-row">
                        <span class="modal-info-key">✅ STATUS</span>
                        <span class="modal-info-val accent">Aktif</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endforeach

<script>
    // Animate on load
    setTimeout(() => {
        document.querySelectorAll('.anim-fade-up').forEach(el => el.classList.add('is-visible'));
    }, 60);

    // ── FILTER LOGIC ──
    const filterBtns = document.querySelectorAll('.filter-btn');
    const studentRows = document.querySelectorAll('.student-row');
    const sepRows = document.querySelectorAll('.class-sep-row');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // update active button
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const filter = btn.dataset.filter;

            // show/hide separator rows
            sepRows.forEach(sep => {
                if (filter === 'all') {
                    sep.style.display = '';
                } else {
                    // show sep only for the selected class
                    sep.style.display = sep.dataset.sepFor === filter ? '' : 'none';
                }
            });

            // show/hide student rows
            let visibleNo = 1;
            studentRows.forEach(row => {
                const rowClass = row.dataset.class;
                const show = filter === 'all' || rowClass === filter;

                if (show) {
                    row.style.display = '';
                    // renumber
                    const numEl = row.querySelector('.display-num');
                    if (numEl) { numEl.textContent = visibleNo++; }
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>

@endsection