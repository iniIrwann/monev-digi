@extends('layout.app')

@section('title', 'Manajemen Desa - Monev Digi Dana Desa')


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
            <div class="d-flex justify-content-between align-items-center">
                <p class="fs-14 sb mb-3">Tambah Pengguna</p>
            </div>
            <hr class="my-3">

           <form action="{{ route('kecamatan.dataDesa.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row align-items-start flex-wrap">
        <!-- FOTO -->
        <div class="col-md-3 text-center mb-3 mb-md-0">
            <!-- Foto saat ini -->
            <img id="currentProfile" src="{{ asset('assets/images/new_profile.jpeg') }}"
                class="img-fluid rounded" alt="Foto Pengguna">

            <div class="mb-2 mt-3">
                <label class="form-label fs-12">Foto Profile</label>

                <!-- Input file disembunyikan -->
                <input type="file" id="fotoInput" name="foto_profile" accept="image/*"
                    class="d-none" onchange="previewImage(event)">

                <!-- Tombol pilih foto -->
                <button type="button" class="btn btn-success w-100 fs-12 text-white"
                    onclick="document.getElementById('fotoInput').click()">
                    <i class="bi bi-image me-1"></i> Pilih Foto
                </button>

                <!-- Error -->
                @error('foto_profile')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror

                <!-- Preview foto -->
                <img id="preview" class="img-fluid rounded mt-2" style="max-height: 150px; display:none;">
            </div>
        </div>

        <!-- FORM -->
        <div class="col-md-9">
            <p class="fs-14 sb mb-1">Informasi Pengguna</p>
            <div class="row gy-2">
                <div class="col-md-6">
                    <label class="form-label fs-12">username</label>
                    <input placeholder="username" type="text" class="form-control form-control-sm"
                        name="username" value="{{ old('username') }}">
                    @error('username')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fs-12">password</label>
                    <div class="input-group input-group-sm">
                        <input placeholder="password" type="password" class="form-control" id="password1"
                            name="password" required>
                        <span class="input-group-text bg-white" id="togglePassword1" style="cursor: pointer;">
                            <i class="bi bi-eye"></i>
                        </span>
                    </div>
                    @error('password')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fs-12">nama</label>
                    <input placeholder="nama" type="text" class="form-control form-control-sm" name="name"
                        value="{{ old('name') }}">
                    @error('name')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fs-12">role</label>
                    <select class="form-control form-control-sm" name="role">
                        <option value="">-- Pilih Role --</option>
                        <option value="admin" {{ old('role') == 'kecamatan' ? 'selected' : '' }}>kecamatan</option>
                        <option value="desa" {{ old('role') == 'desa' ? 'selected' : '' }}>desa</option>
                    </select>
                    @error('role')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fs-12">desa</label>
                    <input placeholder="desa" type="text" class="form-control form-control-sm" name="desa"
                        value="{{ old('desa') }}">
                    @error('desa')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fs-12">email</label>
                    <input placeholder="email" type="email" class="form-control form-control-sm"
                        name="email" value="{{ old('email') }}">
                    @error('email')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fs-12">nomor hp</label>
                    <input placeholder="nomor handphone" type="tel" pattern="[0-9]{10,15}" maxlength="15"
                        title="Masukkan 10-15 digit angka" class="form-control form-control-sm" name="nohp"
                        value="{{ old('nohp') }}">
                    @error('nohp')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-success w-100 fs-12 text-white">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const preview = document.getElementById('preview');
        preview.src = reader.result;
        preview.style.display = 'block';
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>

        </div>
    </div>

    <!-- SCRIPT -->
    <script>
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

        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('preview');
            preview.src = URL.createObjectURL(input.files[0]);
            preview.style.display = 'block';
        }
    </script>
@endsection
