@extends('layout.app')

@section('title', 'Profile - Monev Digi Dana Desa')


@section('main')
    <div class="main-content ps-3 pe-3 pt-4">
        <div class="d-flex align-items-center mb-2 mb-md-0 pb-4">
            <div class="bg-30x d-flex justify-content-center align-items-center flex-shrink-0">
                <i class="bi bi-person-fill fs-16 text-white"></i>
            </div>
            <p class="fs-14 ms-2 mb-0">Manajemen desa</p>
        </div>

        <div class="card border-0 w-100 rounded-3 mb-4">
            <div class="card-body p-3">
                <p class="fs-14 sb mb-3">Edit pengguna</p>
                <hr class="my-3">

                <form action="{{ route('kecamatan.dataDesa.update', $profile->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row align-items-start flex-wrap">
                        <!-- FOTO -->
                        <div class="col-md-3 flex ustify-content-between text-center mb-3 mb-md-0">
                            <img id="currentProfile"
                                src="{{ $profile->foto_profile === null ? asset('assets/images/new_profile.jpeg') : asset('assets/images/foto_profile/' . $profile->foto_profile) }}"
                                class="img-fluid rounded" alt="Foto Pengguna" style="max-width: 200px;" />
                            <input type="file" id="fotoInput" name="foto_profile" accept="image/*"
                                style="display:none" />
                            <div class="mb-2 mt-3">
                                <!-- Tombol untuk memicu input file -->
                                <button type="button" class="btn btn-success w-100 fs-12 text-white"
                                    onclick="document.getElementById('fotoInput').click()">
                                    <i class="bi bi-image me-1"></i> Ganti foto
                                </button>

                                <!-- Input file disembunyikan -->
                                {{-- <input type="file" id="fotoInput" name="foto_profile" accept="image/*" class="d-none"
                                    onchange="previewImage(event)"> --}}

                                @error('foto_profile')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror

                                <!-- Preview gambar -->
                                <img id="preview" class="img-fluid rounded" style="max-height: 150px; display: none;">
                            </div>
                        </div>

                        <!-- FORM -->
                        <div class="col-md-9">
                            <p class="fs-14 sb mb-1">Informasi pengguna</p>
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
                                            placeholder="biarkan kosong jika tidak diubah">
                                        <span class="input-group-text bg-white" id="togglePassword1"
                                            style="cursor: pointer;">
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
                                    <input class="form-control form-control-sm text-black" name="role"
                                        value="{{ $profile->role ?? '' }}" disabled>
                                    @error('role')
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
                                        title="Masukkan 10-15 digit angka" class="form-control form-control-sm"
                                        name="nohp" value="{{ $profile->nohp ?? '' }}">
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
                                        <button type="submit" class="btn btn-warning w-100 fs-12 text-white">Edit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if (session('success'))
        <script>
            new Notyf().success('{{ session('success') }}');
        </script>
    @endif
    <!-- SCRIPT -->
    <script>
        // Ketika input file berubah (file dipilih)
        document.getElementById('fotoInput').addEventListener('change', function(event) {
            const [file] = this.files;
            if (file) {
                // Buat URL sementara untuk preview gambar
                const preview = URL.createObjectURL(file);
                // Ganti src gambar profile dengan URL preview
                document.getElementById('currentProfile').src = preview;
            }
        });
    </script>
    <script>
        const togglePassword = document.querySelector('#togglePassword1');
        const passwordInput = document.querySelector('#password1');
        const icon = togglePassword.querySelector('i');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
        });
    </script>
@endsection
