@extends('layout.app')

@section('main')
    <div class="main-content ps-3 pe-3 pt-4">
        <div class="d-flex align-items-center mb-2 mb-md-0 pb-4">
            <div class="bg-30x d-flex justify-content-center align-items-center flex-shrink-0">
                <i class="bi bi-person-fill fs-16 text-white"></i>
            </div>
            <p class="fs-14 ms-2 mb-0">Profil</p>
        </div>

        <div class="card border-0 w-100 rounded-3 mb-4">
            <div class="card-body p-3">
                <p class="fs-14 sb mb-3">Edit profil</p>
                <hr class="my-3">

                <form action="{{ route('profile.update', $profile->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row align-items-start flex-wrap">
                        <!-- FOTO -->
                        <div class="col-md-3 text-center mb-3 mb-md-0">
                            <img id="currentProfile"
                                src="{{ $profile->foto_profile === null ? asset('assets/images/new_profile.jpeg') : asset('assets/images/' . ($profile->foto_profile ?? 'default.png')) }}"
                                class="img-fluid rounded" alt="Foto Pengguna">
                            <div class="mb-2 mt-3">
                                <!-- Tombol untuk memicu input file -->
                                <button type="button" class="btn btn-success w-100 fs-12 text-white" onclick="document.getElementById('fotoInput').click()">
                                    <i class="bi bi-image me-1"></i> Ganti foto
                                </button>

                                <!-- Input file disembunyikan -->
                                <input type="file" id="fotoInput" name="foto_profile" accept="image/*"
                                    class="d-none" onchange="previewImage(event)">

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
                                        title="Masukkan 10-15 digit angka" class="form-control form-control-sm" name="nohp"
                                        value="{{ $profile->nohp ?? '' }}">
                                    @error('nohp')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mt-2">
                                    <button type="submit" class="btn btn-success mt-3 w-100 fs-12 text-white">Edit</button>
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
