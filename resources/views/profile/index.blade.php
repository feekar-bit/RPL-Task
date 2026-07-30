@extends('layouts.app')

@section('title', 'Profile Admin')

@section('content')

<div class="row justify-content-center">

    <div class="col-md-8">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <h3 class="mb-4">

                    Profile Admin

                </h3>

                {{-- SUCCESS --}}
                @if(session('success'))

                    <div class="alert alert-success">

                        {{ session('success') }}

                    </div>

                @endif


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


                <form action="/admin/profile/update"
                    method="POST"
                    enctype="multipart/form-data">

                    @csrf
                    @method('PUT')


                    {{-- FOTO --}}
                    <div class="text-center mb-4">

                        @if(Auth::user()->photo)

                            <img src="{{ asset('storage/' . Auth::user()->photo) }}"
                                width="120"
                                height="120"
                                class="rounded-circle object-fit-cover">

                        @else

                            <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}"
                                class="rounded-circle">

                        @endif

                    </div>


                    {{-- PHOTO --}}
                    <div class="mb-3">

                        <label class="form-label">

                            Foto Profile

                        </label>

                        <input type="file"
                            name="photo"
                            class="form-control">

                    </div>


                    {{-- NAMA --}}
                    <div class="mb-4">

                        <label class="form-label">

                            Nama Admin

                        </label>

                        <input type="text"
                            name="name"
                            class="form-control"
                            value="{{ Auth::user()->name }}">

                    </div>


                    {{-- EMAIL LOCK --}}
                    <div class="mb-3">

                        <label class="form-label">

                            Email Admin

                        </label>

                        <input type="email"
                            class="form-control"
                            value="{{ Auth::user()->email }}"
                            disabled>

                    </div>


                    {{-- PASSWORD LOCK --}}
                    <div class="mb-4">

                        <label class="form-label">

                            Password

                        </label>

                        <input type="password"
                            class="form-control"
                            value="********"
                            disabled>

                    </div>


                    <button class="btn btn-primary">

                        Update Profile

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection