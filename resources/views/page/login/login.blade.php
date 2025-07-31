<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- boostrap -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- font montserrat -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- icon boostrap -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <!-- css -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
    {{-- Notify --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <!-- icon web -->
    <title>login - monev digi dana desa</title>
</head>

<body>
    <!-- main -->
    <div class="d-flex justify-content-center align-items-center vh-100 p-4">
        <!-- Blade template -->
        <div class="card border-0 w-1011 rd-10">
            <div class="card-body">
                <div class="row align-items-center">
                    <!-- gambar -->
                    <div class="col-md-6 col-12 mb-3 mb-md-0">
                        <div class="card border-0 w-100 rd-5 bg-green">
                            <div class="card-body ps-3 pt-3 pe-3 text-white scroll-card">
                                <p class="fs-12 mb-0">Monev
                                    Digi Dana Desa</p>
                                <hr class="l-white">
                                <p class="fs-10 mb-1">Monev Digi adalah aplikasi dengan platform digital yang
                                    dikembangkan untuk mendukung pengelolaan, pemantauan dan evaluasi penggunaan Dana
                                    Desa (DD) di Kabupaten Bandung. Aplikasi ini bertujuan untuk meningkatkan
                                    transparansi, akuntabilitas serta efektifitas penggunaan Dana Desa sesuai dengan
                                    peraturan yang berlaku dan yang tercantum dalam Undang-Undang Desa dan peraturan
                                    daerah yang terkait</p>
                                <p class="fs-10 mb-1">manfaat :</p>
                                <p class="fs-10">1. Efisiensi: Mengurangi kebutuhan pertemuan fisik untuk Monev dengan
                                    menyediakan platform digital yang terpusat, <br>
                                    2. Transparansi: Memastikan semua pihak, termasuk masyarakat, dapat mengakses
                                    informasi penggunaan dana desa, <br>
                                    3. Akuntabilitas: Mencegah penyimpangan dana dengan dokumentasi yang terstruktur dan
                                    terverifikasi <br>
                                    4. Peningkatan kualitas: Membantu desa memperbaiki perencanaan dan pelaksanaan
                                    kegiatan berdasarkan evaluasi yang dilakukan melalui Aplikasi.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Form -->
                    <div class="col-md-6 col-12">
                        <div class="row align-items-center gx-1">
                            <div class="col-auto">
                                <div class="bg-43x">
                                    <img class="p-1 img-fluid" src="{{ asset('assets/images/logo.png') }}"
                                        alt="">
                                </div>
                            </div>
                            <div class="col-auto">
                                <p class="mb-0 ms-1 fs-12 tx-green w-72">Monev Digi Dana Desa</p>
                            </div>
                        </div>
                        <hr class="mb-2 l-grey w-140">
                        <p class="fs-12">masukkan username dan password sesuai dengan level pengguna</p>
                        <hr class="mb-2 l-grey w-402">
                        <form class="pb-0" action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="mb-1 input-group-sm">
                                <label for="username" class="form-label black fs-12">username</label>
                                <input type="text" name="username" autofocus class="form-control" id="username"
                                    placeholder="username">
                            </div>
                            <div class="mb-1 input-group flex-column pt-1">
                                <label for="password" class="form-label black fs-12 d-block">password</label>
                                <div class="input-group input-group-sm">
                                    <input type="password" name="password" class="form-control" id="password1"
                                        placeholder="password">
                                    <span class="input-group-text bg-white" id="togglePassword1"
                                        style="cursor: pointer;">
                                        <i class="bi bi-eye"></i>
                                    </span>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success mt-3 w-100 fs-12 text-white">login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <!-- js pw -->
    <script src="{{ asset('assets/js/js-pw.js') }}"></script>
    @if (session('success'))
        <script>
            var notyf = new Notyf({
                duration: 9000,
                dismissible: true,
            });
            notyf.success("{{ session('success') }}");
        </script>
    @endif
    @if (session('error'))
        <script>
            var notyf = new Notyf({
                duration: 9000,
                dismissible: true,
            });

            notyf.error(@json(session('error')));
        </script>
    @endif
</body>

</html>
