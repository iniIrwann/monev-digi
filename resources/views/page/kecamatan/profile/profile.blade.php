@extends('layout.app')

@section('main')
    {{-- <div class="table-responsive">
        <table class="table fs-12 text-center">
            <thead>
                <tr>
                    <th scope="col" class="fw-normal">no</th>
                    <th scope="col" class="fw-normal">foto</th>
                    <th scope="col" class="fw-normal">nama</th>
                    <th scope="col" class="fw-normal">email</th>
                    <th scope="col" class="fw-normal">nomo hp</th>
                    <th scope="col" class="fw-normal">username</th>
                    <th scope="col" class="fw-normal">role</th>
                    <th scope="col" class="fw-normal">aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td><img class="w-63" src="../../assets/images/pasfoto.jpeg" alt=""></td>
                    <td>Luna cahya putri</td>
                    <td>lunacahya01@gmail.com</td>
                    <td>08982142689</td>
                    <td>Luna</td>
                    <td>pegawai</td>
                    <td>
                        <div class="d-flex flex-wrap justify-content-center gap-1">
                            <button type="button" class="btn btn-warning fs-12 text-white">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <button type="button" class="btn btn-danger fs-12 text-white">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                            <button type="button" class="btn btn-secondary fs-12 text-white">
                                <i class="bi bi-eye-fill"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div> --}}
    <!-- Tambah Pengguna -->
    <div class="card border-0 w-100 rounded-3 mb-4">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center">
                <p class="fs-14 sb mb-3">Profile</p>
                <a href="{{ route('profile.edit', [$profile->id]) }}" class="btn btn-sm btn-warning px-3 py-2"><i
                        class="bi bi-pencil-fill text-white"></i></a>
            </div>
            <hr class="my-3">

            <div class="row align-items-start flex-wrap">
                <!-- FOTO -->
                <div class="col-md-3 text-center mb-3 mb-md-0">
                    <img src="{{ $profile->foto_profile === null ? asset('assets/images/new_profile.jpeg') : asset('assets/images/' . ($profile->foto_profile ?? 'default.png')) }}"
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
                                    placeholder="{{ $profile->username ?? '' }}">
                            </div>
                            {{-- <div class="col-md-6">
                                <label class="form-label fs-12">password</label>
                                <div class="input-group input-group-sm">
                                    <input readonly type="password" class="form-control" id="password1"
                                        placeholder="{{ $profile->password ?? '' }}">
                                    <span class="input-group-text bg-white" id="togglePassword1" style="cursor: pointer;">
                                        <i class="bi bi-eye"></i>
                                    </span>
                                </div>
                            </div> --}}

                            <div class="col-md-6">
                                <label class="form-label fs-12">nama</label>
                                <input readonly type="text" class="form-control form-control-sm"
                                    placeholder="{{ $profile->name ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fs-12">role</label>
                                <input class="form-control form-control-sm text-black" readonly
                                    placeholder="{{ $profile->role ?? '' }}">
                                </input>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fs-12">email</label>
                                <input readonly type="email" class="form-control form-control-sm"
                                    placeholder="{{ $profile->email ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fs-12">nomor hp</label>
                                <input readonly type="text" class="form-control form-control-sm"
                                    placeholder="{{ $profile->nohp ?? '' }}">
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
