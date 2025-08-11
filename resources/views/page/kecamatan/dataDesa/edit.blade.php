@extends('layout.app')

@section('title', 'Manajemen Desa - Monev Digi Dana Desa')


@section('main')
    <div class="card border-0 w-100 rounded-3 mb-4">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center">
                <p class="fs-14 sb mb-3">Profile</p>
                {{-- <a href="{{ route('profile.edit', [$profile->id]) }}" class="btn btn-sm btn-warning px-3 py-2">
                    <i class="bi bi-pencil-fill text-white"></i>
                </a> --}}
            </div>
            <hr class="my-3">

            <form action="{{ route('kecamatan.dataDesa.update', $profile->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row align-items-start flex-wrap">
                    <!-- FOTO -->
                    <div class="col-md-3 text-center mb-3 mb-md-0">
                        <img id="currentProfile"
                            src="{{ $profile->foto_profile === null ? asset('assets/images/profil.png') : asset('assets/images/foto_profile/' . ($profile->foto_profile ?? 'profil.png')) }}"
                            class="img-fluid rounded" alt="Foto Pengguna">
                        <div class="mb-2 mt-3">
                            <label class="form-label fs-12">Ganti Foto Profile</label>
                            <input type="file" class="form-control form-control-sm" name="foto_profile" accept="image/*"
                                onchange="previewImage(event)">
                            @error('foto_profile')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                            <img id="preview" class="img-fluid rounded mt-2" style="max-height: 150px; display:none;">
                        </div>
                    </div>

                    <!-- FORM -->
                    <div class="col-md-9">
                        <p class="fs-14 sb mb-1">Informasi Pengguna</p>
                        <div class="row gy-2">
                            <div class="col-md-6">
                                <label class="form-label fs-12">Username</label>
                                <input type="text" class="form-control form-control-sm" name="username"
                                    value="{{ $profile->username ?? '' }}">
                                @error('username')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fs-12">Password</label>
                                <div class="input-group input-group-sm">
                                    <input type="password" class="form-control" id="password1" name="password"
                                        placeholder="Biarkan kosong jika tidak diubah">
                                    <span class="input-group-text bg-white" id="togglePassword1" style="cursor: pointer;">
                                        <i class="bi bi-eye"></i>
                                    </span>
                                </div>
                                @error('password')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fs-12">Nama</label>
                                <input type="text" class="form-control form-control-sm" name="name"
                                    value="{{ $profile->name ?? '' }}">
                                @error('name')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fs-12">Role</label>
                                <select class="form-control form-control-sm" name="role">
                                    <option value="">-- Pilih Role --</option>
                                    <option value="kecamatan"
                                        {{ old('role', $profile->role ?? '') == 'kecamatan' ? 'selected' : '' }}>Kecamatan
                                    </option>
                                    <option value="desa"
                                        {{ old('role', $profile->role ?? '') == 'desa' ? 'selected' : '' }}>Desa</option>
                                </select>
                                @error('role')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fs-12">Desa</label>
                                <input type="text" class="form-control form-control-sm" name="desa"
                                    value="{{ $profile->desa ?? '' }}">
                                @error('desa')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fs-12">Email</label>
                                <input type="email" class="form-control form-control-sm" name="email"
                                    value="{{ $profile->email ?? '' }}">
                                @error('email')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fs-12">Nomor HP</label>
                                <input type="tel" pattern="[0-9]{10,15}" maxlength="15"
                                    title="Masukkan 10-15 digit angka" class="form-control form-control-sm" name="nohp"
                                    value="{{ $profile->nohp ?? '' }}">
                                @error('nohp')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row mt-4">
                                <div class="col-6">
                                    <a href="{{ route('kecamatan.dataDesa.index') }}"
                                        class="btn btn-secondary w-100 fs-12 text-white">
                                        Kembali
                                    </a>
                                </div>
                                <div class="col-6">
                                    <button type="submit" class="btn btn-success w-100 fs-12 text-white">
                                        Simpan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @if (session('success'))
        <script>
            new Notyf().success('{{ session('success') }}');
        </script>
    @endif
    <!-- SCRIPT -->
    <script>
        // Toggle Password Visibility
        document.getElementById('togglePassword1').addEventListener('click', function() {
            const input = document.getElementById('password1');
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });

        // Preview Image Before Upload
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('preview');
            preview.src = URL.createObjectURL(input.files[0]);
            preview.style.display = 'block';
        }
    </script>
@endsection
