@extends('layout.app')

@section('title', 'Manajemen Desa - Monev Digi Dana Desa')


@section('main')
    <div class="main-content ps-3 pe-3 pt-4">
        <div class="d-flex align-items-center mb-2 mb-md-0 pb-4">
            <div class="bg-30x d-flex justify-content-center align-items-center flex-shrink-0">
                <i class="bi bi-person-fill fs-16 text-white"></i>
            </div>
            <p class="fs-14 ms-2 mb-0">manajemen desa</p>
        </div>

        <!-- card -->
        <div class="card border-0 w-100 rd-5 mb-4">
            <div class="card-body p-3">
                <a href="{{ route('kecamatan.dataDesa.create') }}">
                    <button type="button" class="btn btn-success fs-12 text-white">
                        <i class="bi bi-plus-square"></i> tambah
                    </button>
                </a>
                <hr class="my-3">

                <!-- pencarian -->
                <form method="GET" action="{{ route('kecamatan.dataDesa.index') }}">
                    <div class="row g-3 mb-2">
                        <div class="col-auto">
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="form-control form-control-sm w-100" placeholder="pencarian">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-success fs-12 text-white">
                                <i class="bi bi-search"></i> Cari
                            </button>
                        </div>
                    </div>
                </form>

                <!-- tabel -->
                <div class="table-responsive">
                    <table class="table fs-12 text-center">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Desa</th>
                                <th>Email</th>
                                <th>No HP</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $index => $user)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if ($user->foto_profile)
                                            <img style="width: 63px" class="w-63"
                                                src="{{ asset('assets/images/foto_profile/' . $user->foto_profile) }}"
                                                alt="foto">
                                        @else
                                            <img style="width: 63px" class="w-63"
                                                src="{{ asset('assets/images/new_profile.jpeg') }}" alt="foto">
                                        @endif
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->role }}</td>
                                    <td>{{ $user->desa }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->nohp }}</td>
                                    <td>
                                        <div class="d-flex flex-wrap justify-content-center gap-1">
                                            <a href="{{ route('kecamatan.dataDesa.edit', $user->id) }}"
                                                class="btn btn-warning fs-12 text-white">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            <form action="{{ route('kecamatan.dataDesa.destroy', $user->id) }}"
                                                method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger fs-12 text-white">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </button>
                                            </form>

                                            <a href="{{ route('kecamatan.dataDesa.show', $user->id) }}"
                                                class="btn btn-secondary fs-12 text-white">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if ($users->hasPages())
                        <nav aria-label="Page navigation example" style="color: #626262">
                            <ul class="pagination m-0">

                                @if ($users->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link"><i class="bi bi-caret-left-fill"></i></span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link"
                                            href="{{ $users->previousPageUrl() }}&search={{ request('search') }}"
                                            aria-label="Previous">
                                            <i class="bi bi-caret-left-fill"></i>
                                        </a>
                                    </li>
                                @endif

                                @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                                    <li class="page-item {{ $users->currentPage() == $page ? 'active' : '' }}">
                                        <a class="page-link"
                                            href="{{ $url }}&search={{ request('search') }}">{{ $page }}</a>
                                    </li>
                                @endforeach

                                @if ($users->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link"
                                            href="{{ $users->nextPageUrl() }}&search={{ request('search') }}"
                                            aria-label="Next">
                                            <i class="bi bi-caret-right-fill"></i>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link"><i class="bi bi-caret-right-fill"></i></span>
                                    </li>
                                @endif

                            </ul>
                        </nav>
                    @endif

                </div>

            </div>
        </div>
    </div>
@endsection
