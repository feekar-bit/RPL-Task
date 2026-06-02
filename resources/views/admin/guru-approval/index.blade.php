@extends('layouts.app')

@section('title', 'Approval Guru')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-body">

        <h3 class="mb-4">

            Approval Akun Guru

        </h3>


        {{-- SUCCESS --}}
        @if(session('success'))

            <div class="alert alert-success">

                {{ session('success') }}

            </div>

        @endif


        <table class="table table-bordered">

            <thead>

                <tr>

                    <th>Nama</th>
                    <th>Email</th>
                    <th>ID Guru</th>
                    <th>Status</th>
                    <th>Aksi</th>

                </tr>

            </thead>

            <tbody>

                @forelse($gurus as $guru)

                    <tr>

                        <td>{{ $guru->name }}</td>

                        <td>{{ $guru->email }}</td>

                        <td>{{ $guru->teacher_id }}</td>

                        <td>

                            <span class="badge bg-warning">

                                Pending

                            </span>

                        </td>

                        <td>

                            <form action="/admin/guru-approval/{{ $guru->id }}"
                                method="POST">

                                @csrf
                                @method('PUT')

                                <button class="btn btn-success btn-sm">

                                    Approve

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5"
                            class="text-center">

                            Tidak ada guru pending.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection