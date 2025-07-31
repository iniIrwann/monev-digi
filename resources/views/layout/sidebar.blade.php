    <div class="sidebar-custom sidebar-hidden bg-white vh-100" id="sidebar">
        <!-- profil -->
        <div class="text-center ps-4 pe-4 pt-4">
            <div class="d-flex align-items-center me-3">
                <img class="p-1 img-fluid w-34" src="{{ asset('assets/images/profil.png') }}" alt="" />
                <div class="ms-2 text-start">
                    <p class="mb-0 fs-10 sb">
                        {{ Auth::check() ? (Auth::user()->desa != '-' ? Auth::user()->desa : Auth::user()->role) : 'role' }}
                    </p>
                    </p>
                    <p class="mb-0 fs-10 text-muted">
                        {{ Auth::check() ? (Auth::user()->role != '-' ? 'admin ' . Auth::user()->role  : 'role') : 'role' }}
                    </p>
                </div>
            </div>
        </div>
        <!-- menu -->
        <ul class="nav nav-pills flex-column ps-3 pe-3 pt-3">
            <li class="nav-item">
                <a href="dashboard.html"
                    class="nav-link text-dark fs-12 d-flex justify-content-between align-items-center">
                    dashboard
                    <i class="bi bi-house-door-fill {{ Request::is('dashboard*') ? 'tx-orange' : '' }}"></i>
                </a>
            </li>
            <li>
                <a class="nav-link d-flex justify-content-between align-items-center text-dark fs-12"
                data-bs-toggle="collapse" href="#capaianMenu" role="button" aria-expanded="false"
                aria-controls="capaianMenu">
                capaian RKP
                <i class="bi bi-clipboard-fill fs-12 text-dark"></i>
            </a>
            <div class="collapse show mt-2" id="capaianMenu">
                <ul class="list-unstyled ps-3">
                        <li>
                            <a href="{{ route('target.index') }}"
                            class="text-decoration-none  {{ Request::is('target*') ? 'tx-green' : 'text-dark' }} d-block mb-2 fs-12"><i
                            class="bi bi-arrow-right-short {{ Request::is('target*') ? 'tx-orange' : '' }}"></i>
                            target</a>
                        </li>
                        <li>
                            <a href="{{ route('realisasi.index') }}"
                            class="text-decoration-none  {{ Request::is('realisasi*') ? 'tx-green' : 'text-dark' }} d-block mb-2 fs-12"><i
                            class="bi bi-arrow-right-short {{ Request::is('realisasi*') ? 'tx-orange' : '' }}"></i>
                            realisasi</a>
                        </li>
                        <li>
                            <a href="{{ route('capaian.index') }}"
                            class="text-decoration-none  {{ Request::is('capaian*') ? 'tx-green' : 'text-dark' }} d-block fs-12"><i
                            class="bi bi-arrow-right-short {{ Request::is('capaian*') ? 'tx-orange' : '' }}"></i>
                            capaian</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a href="{{ route('profile.index') }}"
                class="{{ Request::is('profile*') ? 'tx-green' : 'text-dark' }} nav-link fs-12 d-flex justify-content-between align-items-center">
                profil
                <i class="bi bi-person-fill {{ Request::is('profile*') ? 'tx-orange' : 'text-dark' }}"></i>
            </a>
        </li>
    </ul>
</div>