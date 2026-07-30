@extends('layouts.app')

@section('title', 'Edit Tugas')

@section('content')

<div class="card">

    <div class="card-body">

        <h3 class="mb-4">
            Edit Tugas
        </h3>

        {{-- ERROR --}}
        @if($errors->any())

            <div class="alert alert-danger">

                <ul class="mb-0">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif

        <form action="/guru/tasks/update/{{ $task->id }}"
            method="POST">

            @csrf
            @method('PUT')

            {{-- JUDUL --}}
            <div class="mb-3">

                <label class="form-label">
                    Judul Tugas
                </label>

                <input type="text"
                    name="title"
                    class="form-control"
                    value="{{ $task->title }}">

            </div>


            {{-- DESKRIPSI --}}
            <div class="mb-3">

                <label class="form-label">
                    Deskripsi
                </label>

                <textarea name="description"
                    rows="5"
                    class="form-control">{{ $task->description }}</textarea>

            </div>


            {{-- KELAS --}}
            <div class="mb-3">

                <label class="form-label">
                    Target Kelas
                </label>

                <input type="text"
                    name="class_target"
                    class="form-control"
                    value="{{ $task->class_target }}">

            </div>


            {{-- DEADLINE --}}
            <div class="mb-3">

                <label class="form-label">
                    Deadline
                </label>

                <input type="date"
                    name="deadline"
                    class="form-control"
                    value="{{ $task->deadline }}">

            </div>


            <button class="btn btn-warning">

                Update Tugas

            </button>

        </form>

    </div>

</div>

@endsection