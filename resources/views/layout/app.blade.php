<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
    <!-- boostrap -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />
    <!-- font montserrat -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <!-- icon boostrap -->
    <link rel="stylesheet" href="/assets/css/style.css" />
    {{-- notyf --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <!-- css -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}" />
    <!-- icon web -->
    <title>@yield('title', 'Monev Digi Dana Desa')</title>

</head>

<body>
    <!-- main -->

    <!-- header -->
    <header class="p-3 bg-white h-73 bt-grey">
        <div class="container-fluid">
            <div class="d-flex flex-wrap flex-md-nowrap justify-content-between align-items-center">
                <!-- kiri -->
                <div class="d-flex align-items-center mb-2 mb-md-0">
                    <div class="bg-35x">
                        <img class="p-1 img-fluid" src="{{ asset('assets/images/logo.png') }}" alt="" />
                    </div>
                    <p class="mb-0 ms-2 fs-12 tx-green w-72">Monev Digi Dana Desa</p>
                </div>
                <!-- kanan -->
                <div class="d-flex align-items-center me-3">
                    <!-- profil hanya tampil di ukuran desktop -->
                    <div class="d-none d-md-flex align-items-center">
                        <div class="w-34">
                            <img class="p-1 img-fluid" src="{{ asset('assets/images/profil.png') }}" alt="" />
                        </div>
                        <p class="mb-0 ms-2 fs-12 me-1">{{ Auth::user()->desa }}</p>
                    </div>
                    <a href="" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="d-flex align-items-center ms-3 me-2 text-dark text-decoration-none fs-14"
                        style="pointer-events: auto">
                        <i class="bi bi-box-arrow-right me-1 fs-14"></i> logout
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                    <!-- tombol toggle sidebar -->
                    <button class="sidebar-toggle-btn" onclick="toggleSidebar()">
                        <i class="bi bi-list"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <div class="d-flex">
        @include('layout.sidebar')

        @yield('main')
    </div>

    {{-- modal --}}
    <script>
        const myModal = document.getElementById('myModal')
        const myInput = document.getElementById('myInput')

        myModal.addEventListener('shown.bs.modal', () => {
            myInput.focus()
        })
    </script>
    @yield('script')
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- boostrap -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
</body>

</html>
