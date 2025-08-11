@extends('layout.app')

@section('title', 'Profile - Monev Digi Dana Desa')


@section('main')
    <!-- Tambah Pengguna -->
    <div class="card border-0 w-100 rounded-3 mb-4">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center">
                <p class="fs-14 sb mb-3">Profile</p>
                <a href="{{ route('kecamatan.profile.edit', [$login_credential->id]) }}"
                    class="btn btn-sm btn-warning px-3 py-2"><i class="bi bi-pencil-fill text-white"></i></a>
            </div>
            <hr class="my-3">

            <div class="row align-items-start flex-wrap">
                <!-- FOTO -->
                <div class="col-md-3 text-center mb-3 mb-md-0">
                    <img src="{{ $login_credential->foto_profile === null ? asset('assets/images/new_profile.jpeg') : asset('assets/images/' . ($login_credential->foto_profile ?? 'default.png')) }}"
                        class="img-fluid rounded" alt="Foto Pengguna">
                    {{-- <a href="#"><button type="button" class="btn btn-success mt-3 w-100 fs-12 text-white">pilih
                            foto</button></a> --}}
                </div>

                <!-- FORM -->
                <div class="col-md-9">
                    <p class="fs-14 sb mb-1">informasi pengguna</p>
                    <form>
                        <div class="row gy-2">
                            <div class="col-md-6">
                                <label class="form-label fs-12">username</label>
                                <input readonly type="text" class="form-control form-control-sm"
                                    placeholder="{{ $login_credential->username ?? '' }}">
                            </div>
                            {{-- <div class="col-md-6">
                                <label class="form-label fs-12">password</label>
                                <div class="input-group input-group-sm">
                                    <input readonly type="password" class="form-control" id="password1"
                                        placeholder="{{ $login_credential->password ?? '' }}">
                                    <span class="input-group-text bg-white" id="togglePassword1" style="cursor: pointer;">
                                        <i class="bi bi-eye"></i>
                                    </span>
                                </div>
                            </div> --}}

                            <div class="col-md-6">
                                <label class="form-label fs-12">nama</label>
                                <input readonly type="text" class="form-control form-control-sm"
                                    placeholder="{{ $login_credential->name ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fs-12">role</label>
                                <input class="form-control form-control-sm text-black" readonly
                                    placeholder="{{ $login_credential->role ?? '' }}">
                                </input>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fs-12">email</label>
                                <input readonly type="email" class="form-control form-control-sm"
                                    placeholder="{{ $login_credential->email ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fs-12">nomor hp</label>
                                <input readonly type="text" class="form-control form-control-sm"
                                    placeholder="{{ $login_credential->nohp ?? '' }}">
                            </div>
                        </div>
                    </form>
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
