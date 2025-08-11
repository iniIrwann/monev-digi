@extends('layout.app')

@section('title', 'Profile - Monev Digi Dana Desa')


@section('main')
    <div class="main-content ps-3 pe-3 pt-4">
        <div class="d-flex align-items-center mb-2 mb-md-0 pb-4">
            <div class="bg-30x d-flex justify-content-center align-items-center flex-shrink-0">
                <i class="bi bi-person-fill fs-16 text-white"></i>
            </div>
            <div class="d-flex justify-content-between align-items-center">

                <p class="fs-14 ms-2 mb-0">Profil</p>
            </div>
        </div>

        <!-- Tambah Pengguna -->
        <div class="card border-0 w-100 rounded-3 mb-4">
            <div class="card-body ps-3 pt-3 pe-3 pb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <p class="fs-14 sb mb-0">Profil</p>
                    <a href="{{ route('desa.profile.edit', [$profile->id]) }}"
                        class="btn btn-warning btn-sm fs-12 text-white">
                        <i class="bi bi-pencil me-1"></i> Edit pengguna
                    </a>
                </div>
                <hr class="my-3">

                <div class="row align-items-start flex-wrap">
                    <!-- FOTO -->
                    <div class="col-md-3 text-center mb-3 mb-md-0">
                        <img src="{{ $profile->foto_profile === null ? asset('assets/images/new_profile.jpeg') : asset('assets/images/' . ($profile->foto_profile ?? 'default.png')) }}"
                            class="img-fluid rounded" alt="Foto Pengguna">
                        {{-- <a href="#"><button type="button" class="btn btn-success mt-3 w-100 fs-12 text-white">pilih foto</button></a> --}}
                    </div>

                    <!-- FORM -->
                    <div class="col-md-9">
                        <p class="fs-14 sb mb-1">Informasi pengguna</p>
                        <form>
                            <div class="row gy-2">
                                <div class="col-md-6">
                                    <label class="form-label fs-12">Username</label>
                                    <input type="text" class="form-control form-control-sm"
                                        value="{{ $profile->username ?? '' }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fs-12">Password</label>
                                    <input type="password" class="form-control form-control-sm" id="password1"
                                        value="•••••" disabled>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fs-12">Nama</label>
                                    <input type="text" class="form-control form-control-sm"
                                        value="{{ $profile->name ?? '' }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fs-12">Role</label>
                                    <input type="text" class="form-control form-control-sm"
                                        value="{{ $profile->role ?? '' }}" disabled>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fs-12">Email</label>
                                    <input type="email" class="form-control form-control-sm"
                                        value="{{ $profile->email ?? '' }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fs-12">Nomor HP</label>
                                    <input type="text" class="form-control form-control-sm"
                                        value="{{ $profile->nohp ?? '' }}" disabled>
                                </div>

                                {{-- <div class="col-12 mt-2">
                        <a href="#"><button type="button" class="btn btn-success mt-3 w-100 fs-12 text-white">simpan</button></a>
                        </div> --}}
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @if (session('success'))
        <script>
            new Notyf().success('{{ session('success') }}');
        </script>
    @endif
@endsection
