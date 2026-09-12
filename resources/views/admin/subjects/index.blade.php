@extends('layouts.app')

@section('title', 'Penugasan Guru')

@section('content')
<style>
    .assignment-header { margin-bottom: 1.5rem; }
    .assignment-eyebrow { color: #f9b17a; font-size: .7rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; }
    .assignment-title { color: #fff; font-family: 'Bricolage Grotesque', sans-serif; font-size: 1.85rem; font-weight: 800; margin: .25rem 0; }
    .assignment-subtitle { color: rgba(255,255,255,.45); font-size: .855rem; }
    .assignment-grid { display: grid; gap: 1.25rem; grid-template-columns: minmax(250px, .8fr) minmax(0, 1.6fr); }
    .assignment-card { background: rgba(45,50,80,.48); border: 1px solid rgba(103,111,157,.22); border-radius: 16px; padding: 1.35rem; }
    .assignment-card h2 { color: #fff; font-family: 'Bricolage Grotesque', sans-serif; font-size: 1rem; margin: 0 0 .3rem; }
    .assignment-card p { color: rgba(255,255,255,.4); font-size: .78rem; margin: 0 0 1.2rem; }
    .assignment-label { color: rgba(255,255,255,.65); display: block; font-size: .75rem; font-weight: 700; margin: .85rem 0 .4rem; }
    .assignment-input { background: rgba(37,40,66,.8); border: 1px solid rgba(103,111,157,.32); border-radius: 9px; color: #fff; font: inherit; font-size: .82rem; padding: .68rem .75rem; width: 100%; }
    .assignment-input:focus { border-color: #f9b17a; outline: none; }
    .assignment-input option { background: #2d3250; color: #fff; }
    .assignment-button { background: #f9b17a; border: 0; border-radius: 9px; color: #252842; cursor: pointer; font: inherit; font-size: .78rem; font-weight: 800; margin-top: 1rem; padding: .7rem 1rem; width: 100%; }
    .assignment-button:hover { background: #ffc493; }
    .assignment-table-wrap { overflow-x: auto; }
    .assignment-table { border-collapse: collapse; min-width: 560px; width: 100%; }
    .assignment-table th { color: rgba(255,255,255,.38); font-size: .68rem; letter-spacing: .08em; padding: .65rem .7rem; text-align: left; text-transform: uppercase; }
    .assignment-table td { border-top: 1px solid rgba(103,111,157,.14); color: rgba(255,255,255,.75); font-size: .8rem; padding: .8rem .7rem; vertical-align: middle; }
    .assignment-table th:first-child, .assignment-table td:first-child { padding-left: 0; }
    .assignment-table th:last-child, .assignment-table td:last-child { padding-right: 0; }
    .assignment-actions { align-items: center; display: flex; gap: .45rem; }
    .assignment-actions .assignment-input { min-width: 150px; padding: .5rem .6rem; }
    .assignment-actions button, .delete-button { border: 0; border-radius: 7px; cursor: pointer; font: inherit; font-size: .7rem; font-weight: 700; padding: .52rem .65rem; }
    .assignment-actions button { background: rgba(249,177,122,.15); color: #f9b17a; }
    .delete-button { background: rgba(248,113,113,.12); color: #fca5a5; }
    .teacher-unassigned { color: rgba(255,255,255,.35); font-style: italic; }
    .assignment-alert { border-radius: 9px; font-size: .8rem; margin-bottom: 1rem; padding: .75rem 1rem; }
    .assignment-alert.success { background: rgba(74,222,128,.12); color: #86efac; }
    .assignment-alert.error { background: rgba(248,113,113,.12); color: #fca5a5; }
    @media (max-width: 800px) { .assignment-grid { grid-template-columns: 1fr; } }
</style>

<div class="assignment-header">
    <div class="assignment-eyebrow">Manajemen Akademik</div>
    <h1 class="assignment-title">Penugasan Guru</h1>
    <div class="assignment-subtitle">Tentukan guru pengampu untuk setiap mata pelajaran.</div>
</div>

@if(session('success'))
    <div class="assignment-alert success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="assignment-alert error">{{ $errors->first() }}</div>
@endif

<div class="assignment-grid">
    <section class="assignment-card">
        <h2>Tambah Mata Pelajaran</h2>
        <p>Buat mapel baru dan pilih guru yang sudah disetujui.</p>
        <form action="{{ route('admin.subjects.store') }}" method="POST">
            @csrf
            <label class="assignment-label" for="subject-name">Nama Mata Pelajaran</label>
            <input class="assignment-input" id="subject-name" name="name" value="{{ old('name') }}" placeholder="Contoh: Pemrograman Web" required>

            <label class="assignment-label" for="subject-teacher">Guru Pengampu</label>
            <select class="assignment-input" id="subject-teacher" name="teacher_id">
                <option value="">Belum ditentukan</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                @endforeach
            </select>
            <button class="assignment-button" type="submit">Simpan Mata Pelajaran</button>
        </form>
    </section>

    <section class="assignment-card">
        <h2>Daftar Penugasan</h2>
        <p>Perbarui guru pengampu langsung dari daftar berikut.</p>
        <div class="assignment-table-wrap">
            <table class="assignment-table">
                <thead>
                    <tr><th>No</th><th>Mata Pelajaran</th><th>Guru Pengampu</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($subjects as $subject)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $subject->name }}</td>
                            <td>
                                {{ $subject->teacher?->name ?? '' }}
                                @if(!$subject->teacher)<span class="teacher-unassigned">Belum ditentukan</span>@endif
                            </td>
                            <td>
                                <form class="assignment-actions" action="{{ route('admin.subjects.update', $subject) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input class="assignment-input" type="text" name="name" value="{{ $subject->name }}" required aria-label="Nama mata pelajaran">
                                    <select class="assignment-input" name="teacher_id" aria-label="Guru pengampu">
                                        <option value="">Belum ditentukan</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" {{ $subject->teacher_id == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit">Simpan</button>
                                </form>
                                <form action="{{ route('admin.subjects.destroy', $subject) }}" method="POST" style="margin-top:.45rem; text-align:right;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="delete-button" type="submit" onclick="return confirm('Hapus mata pelajaran ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align:center;">Belum ada mata pelajaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
