<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- boostrap -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- font montserrat -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- icon boostrap -->
    <link rel="stylesheet" href="../../assets/css/style.css">
    <!-- css -->
    <link rel="icon" type="image/png" href="../../assets/images/logo.png">
    <!-- icon web -->
    <title>dashboard - monev digi dana desa</title>
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
                        <img class="p-1 img-fluid" src="../../assets/images/logo.png" alt="">
                    </div>
                    <p class="mb-0 ms-2 fs-12 tx-green w-72">Monev Digi Dana Desa</p>
                </div>
                <!-- kanan -->
                <div class="d-flex align-items-center me-3">
                    <!-- profil hanya tampil di ukuran desktop -->
                    <div class="d-none d-md-flex align-items-center">
                        <div class="w-34">
                            <img class="p-1 img-fluid" src="../../assets/images/profil.png" alt="">
                        </div>
                        <p class="mb-0 ms-2 fs-12 me-1">admin_desa</p>
                    </div>
                    <!-- logout -->
                    <a href="../login.html" class="d-flex align-items-center ms-3 me-2 text-dark text-decoration-none fs-14">
                        <i class="bi bi-box-arrow-right me-1 fs-14"></i> logout
                    </a>

                    <!-- tombol toggle sidebar -->
                    <button class="sidebar-toggle-btn" onclick="toggleSidebar()">
                        <i class="bi bi-list"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <div class="d-flex">
    <!-- sidebar -->
        <div class="sidebar-custom sidebar-hidden bg-white vh-100" id="sidebar">
            <!-- profil -->
            <div class="text-center ps-4 pe-4 pt-4">
                <div class="d-flex align-items-center me-3">
                    <img class="p-1 img-fluid w-34" src="../../assets/images/profil.png" alt="">
                    <div class="ms-2 text-start">
                        <p class="mb-0 fs-12 sb">admin_desa</p>
                        <p class="mb-0 fs-10 text-muted">admin</p>
                    </div>
                </div>
            </div>
            <!-- menu -->
            <ul class="nav nav-pills flex-column ps-3 pe-3 pt-3">
                <li class="nav-item">
                    <a href="dashboard.html" class="nav-link text-dark fs-12 d-flex justify-content-between align-items-center">
                        dashboard
                        <i class="bi bi-house-door-fill text-dark"></i>
                    </a>
                </li>
            <li>
                <a class="nav-link d-flex justify-content-between align-items-center text-dark fs-12" data-bs-toggle="collapse" href="#capaianMenu" role="button" aria-expanded="false" aria-controls="capaianMenu">
                    capaian RKP
                    <i class="bi bi-clipboard-fill fs-12 text-dark"></i>
                </a>
                <div class="collapse mt-2" id="capaianMenu">
                    <ul class="list-unstyled ps-3">
                        <li><a href="target.html" class="text-decoration-none d-block mb-2 fs-12 text-dark"><i class="bi bi-arrow-right-short"></i> target</a></li>
                        <li><a href="#" class="text-decoration-none text-dark d-block mb-2 fs-12"><i class="bi bi-arrow-right-short"></i> relasi</a></li>
                        <li><a href="#" class="text-decoration-none text-dark d-block fs-12"><i class="bi bi-arrow-right-short"></i> capaian</a></li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link fs-12 d-flex justify-content-between align-items-center tx-green">
                    pengguna
                    <i class="bi bi-person-fill tx-orange"></i>
                </a>
            </li>
            </ul>
        </div>

        <!-- Main content -->
        <div class="main-content ps-3 pe-3 pt-4">
            <div class="d-flex align-items-center mb-2 mb-md-0 pb-4">
                <div class="bg-30x d-flex justify-content-center align-items-center flex-shrink-0">
                    <i class="bi bi-person-fill fs-16 text-white"></i>
                </div>
                <p class="fs-14 ms-2 mb-0">pengguna</p>
            </div>

            <!-- Tambah Pengguna -->
            <div class="card border-0 w-100 rounded-3 mb-4">
            <div class="card-body p-3">
                <p class="fs-14 sb mb-3">pengguna baru</p>
                <hr class="my-3">

                <div class="row align-items-start flex-wrap">
                <!-- FOTO -->
                <div class="col-md-3 text-center mb-3 mb-md-0">
                    <img src="../../assets/images/contoh.jpeg" class="img-fluid rounded" alt="Foto Pengguna">
                    <a href="#"><button type="button" class="btn btn-success mt-3 w-100 fs-12 text-white">pilih foto</button></a>
                </div>

                <!-- FORM -->
                <div class="col-md-9">
                    <p class="fs-14 sb mb-1">informasi pengguna</p>
                    <form>
                    <div class="row gy-2">
                        <div class="col-md-6">
                        <label class="form-label fs-12">username</label>
                        <input type="text" class="form-control form-control-sm" placeholder="username">
                        </div>
                        <div class="col-md-6">
                        <label class="form-label fs-12">password</label>
                        <div class="input-group input-group-sm">
                            <input type="password" class="form-control" id="password1" placeholder="password">
                            <span class="input-group-text bg-white" id="togglePassword1" style="cursor: pointer;">
                            <i class="bi bi-eye"></i>
                            </span>
                        </div>
                        </div>

                        <div class="col-md-6">
                        <label class="form-label fs-12">nama</label>
                        <input type="text" class="form-control form-control-sm" placeholder="nama">
                        </div>
                        <div class="col-md-6">
                        <label class="form-label fs-12">role</label>
                        <select class="form-select form-select-sm text-secondary">
                            <option selected>pilih role</option>
                        </select>
                        </div>

                        <div class="col-md-6">
                        <label class="form-label fs-12">email</label>
                        <input type="email" class="form-control form-control-sm" placeholder="email">
                        </div>
                        <div class="col-md-6">
                        <label class="form-label fs-12">nomor hp</label>
                        <input type="text" class="form-control form-control-sm" placeholder="nomor hp">
                        </div>

                        <div class="col-12 mt-2">
                        <a href="#"><button type="button" class="btn btn-success mt-3 w-100 fs-12 text-white">simpan</button></a>
                        </div>
                    </div>
                    </form>
                </div>
                </div>

            </div>
            </div>

        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- boostrap -->
    <script src="../../assets/js/main.js"></script>
    <script src="../../assets/js/js-pw.js"></script>
  </body>
</html>
