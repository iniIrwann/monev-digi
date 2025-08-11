@extends('layout.app')

@section('main')
    <!-- Main content -->
    <div class="main-content ps-3 pe-3 pt-4">
        <div class="d-flex align-items-center mb-2 mb-md-0 pb-4">
            <div class="bg-30x d-flex justify-content-center align-items-center flex-shrink-0">
                <i class="bi bi-bullseye fs-16 text-white"></i>
            </div>
            <p class="fs-14 ms-2 mb-0">Target</p>
        </div>

        <!-- filter -->
        <div class="card border-0 w-100 rd-5 mb-4">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <p class="fs-18 mb-0">Filter</p>
                </div>
                <form action="{{ route('target.index') }}" method="GET" class="mb-3">
                    <div class="row g-2 align-items-end">
                        <div class="col-12 col-md-6">
                            <label for="" class="fs-12 mb-1">Pilih tahun</label>
                            <select name="tahun" class="fs-12 form-select">
                                <option value="2024" {{ request('tahun') == '2024' ? 'selected' : '' }}>Tahun 2024
                                </option>
                                <option value="2025" {{ request('tahun') == '2025' ? 'selected' : '' }}>Tahun 2025
                                </option>
                            </select>


                        </div>
                        <div class="col-12 col-md-5">
                            <label for="" class="fs-12 mb-1">Pilih bidang</label>
                            <select name="bidang" class="fs-12 form-select">
                                {{-- Opsi 1 --}}
                                {{-- @foreach ($data as $bidang)
                                    <option value="{{ $bidang->id }}"
                                        {{ request('bidang') == $bidang->id ? 'selected' : '' }}>
                                        {{ $bidang->nama_bidang }}
                                    </option>
                                @endforeach --}}
                                {{-- Opsi 2 --}}
                                @foreach ($filterBidangs as $bidang)
                                    <option value="{{ $bidang->id }}"
                                        {{ request('bidang') == $bidang->id ? 'selected' : '' }}>
                                        {{ $bidang->nama_bidang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-1 d-grid">
                            <button type="submit" class="btn btn-success fs-14 btn-sm text-white">
                                <i class="bi bi-filter"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- tabel -->
        <div class="card border-0 w-100 rd-5">
            <div class="card-body p-3">
                <button type="button" class="btn btn-success btn-sm fs-12 p-2 text-white" data-bs-toggle="modal"
                    data-bs-target="#ModalTambahBidang">
                    <i class="bi bi-plus-square me-1"></i> Tambah
                </button>
                {{-- <p class="fs-12 my-2">
                    kinerja dan anggaran dana desa ( nama desa a ) tahun 2024. bidang
                    pembangunan
                </p> --}}
                <hr/>
                <form action="{{ route('target.index') }}" method="GET" class="mb-3">
                    <div class="row g-3 mb-2">
                        <div class="col-auto">
                            <!-- Input text -->
                            <input type="text" name="query" class="form-control form-control-sm w-100" placeholder="Pencarian..." />
                        </div>
                        <div class="col-auto">
                            <!-- Tombol cari -->
                            <button type="submit" class="btn btn-success fs-12 text-white">
                                <i class="bi bi-search me-1"></i> Cari
                            </button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table align-middle fs-12 tx-gray">
                        <thead class="border-bottom" style="border-color: #999999">
                            <tr class="text-start">
                                <th class="text-center">Aksi</th>
                                <th>Kode rekening</th>
                                <th>Rencana kegiatan</th>
                                <th>Volume</th>
                                <th>Uraian</th>
                                <th>Anggaran / Target</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $bidang)
                                <!-- BIDANG -->
                                <tr>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-end">
                                            {{-- Tambah Kegiatan --}}
                                            <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                                data-bs-target="#ModalTambahKegiatan"
                                                data-kode="{{ $bidang->kode_rekening }}" data-id="{{ $bidang->id }}">
                                                <i class="bi bi-plus-square"></i>
                                            </button>
                                            {{-- Edit Bidang --}}
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#ModalEditBidang" data-id-bidang-edit="{{ $bidang->id }}"
                                                data-kode-bidang-edit="{{ $bidang->kode_rekening }}"
                                                data-nama-bidang-edit="{{ $bidang->nama_bidang }}"
                                                data-keterangan-bidang-edit="{{ $bidang->keterangan }}"><i
                                                    class="bi bi-pencil-fill text-white"></i></button>
                                            {{-- Delete Bidang --}}
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#ModalDeleteBidang"
                                                data-id-bidang-delete="{{ $bidang->id }}"><i
                                                    class="bi bi-trash-fill"></i></button>
                                        </div>
                                    </td>
                                    <td class="sb">{{ $bidang->kode_rekening }}</td>
                                    <td class="sb">{{ $bidang->nama_bidang }}</td>
                                    <td colspan="4"></td>
                                </tr>

                                @foreach ($bidang->kegiatan as $kegiatan)
                                    <!-- KEGIATAN -->
                                    <tr>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-end">
                                                <a href="#" class="btn btn-sm btn-secondary"><i
                                                        class="bi bi-eye-fill"></i></a>
                                                <a class="btn btn-sm btn-success"
                                                    href="{{ route('target.create.subkegiatan', ['bidang_id' => $bidang->id, 'kegiatan_id' => $kegiatan->id]) }} "><i
                                                        class="bi bi-plus-square"></i></a>
                                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                    data-bs-target="#ModalEditKegiatan" data-id="{{ $kegiatan->id }}"
                                                    data-kode-bidang="{{ $kegiatan->bidang->kode_rekening }}"
                                                    data-kode-bidang-id="{{ $kegiatan->bidang->id }}"
                                                    data-kode-kegiatan="{{ $kegiatan->kode_rekening }}"
                                                    data-nama="{{ $kegiatan->nama_kegiatan }}"
                                                    data-kategori="{{ $kegiatan->kategori }}"><i
                                                        class="bi bi-pencil-fill text-white"></i></button>
                                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#ModalDeleteKegiatan"
                                                    data-id-kegiatan-delete="{{ $kegiatan->id }}"><i
                                                        class="bi bi-trash-fill"></i></button>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="gap-td-table">{{ $bidang->kode_rekening }}</span>
                                            <span>{{ $kegiatan->kode_rekening }}</span>
                                        </td>
                                        <td class="ps-4">{{ $kegiatan->nama_kegiatan }}</td>
                                        <td colspan="4"></td>
                                    </tr>

                                    @foreach ($kegiatan->subkegiatan as $sub)
                                        <!-- SUBKEGIATAN -->
                                        <tr>
                                            <td>
                                                <div class="d-flex gap-2 justify-content-end">
                                                    <a href="{{ route('target.detail', ['bidang_id' => $bidang->id, 'kegiatan_id' => $kegiatan->id, 'subkegiatan_id' => $sub->id]) }}"
                                                        class="btn btn-sm btn-secondary"><i
                                                            class="bi bi-eye-fill"></i></a>
                                                    <a href="{{ route('target.edit.sub', ['bidang_id' => $bidang->id, 'kegiatan_id' => $kegiatan->id, 'subkegiatan_id' => $sub->id]) }}"
                                                        class="btn btn-sm btn-warning"><i
                                                            class="bi bi-pencil-fill text-white"></i></a>
                                                    <button data-bs-toggle="modal"
                                                        data-bs-target="#ModalDeleteSubKegiatan"
                                                        data-id-subKegiatan-delete="{{ $sub->id }}"
                                                        class="btn btn-sm btn-danger"><i
                                                            class="bi bi-trash-fill"></i></button>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="gap-td-table">{{ $bidang->kode_rekening }}</span>
                                                <span class="gap-td-table">{{ $kegiatan->kode_rekening }}</span>
                                                <span>{{ $sub->kode_rekening }}</span>
                                            </td>
                                            <td class="ps-5">{{ $sub->nama_subkegiatan }}</td>

                                            @if ($sub->targets->isNotEmpty())
                                                @php $target = $sub->targets->first(); @endphp
                                                <td>{{ $target->volume_keluaran }}</td>
                                                <td>{{ $target->uraian_keluaran }}</td>
                                                <td>Rp{{ number_format($target->anggaran_target, 0, ',', '.') }}</td>
                                            @else
                                                <td colspan="4" class="text-muted text-center">Belum ada target</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination di akhir kanan -->
                    <div class="d-flex justify-content-end mt-3">
                        @if ($data->hasPages())
                            <nav aria-label="Page navigation example" style="color: #626262">
                                <ul class="pagination m-0">

                                    @if ($data->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link"><i class="bi bi-caret-left-fill"></i></span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="{{ $data->previousPageUrl() }}&query={{ request('query') }}"
                                                aria-label="Previous">
                                                <i class="bi bi-caret-left-fill"></i>
                                            </a>
                                        </li>
                                    @endif

                                    @foreach ($data->getUrlRange(1, $data->lastPage()) as $page => $url)
                                        <li class="page-item {{ $data->currentPage() == $page ? 'active' : '' }}">
                                            <a class="page-link"
                                                href="{{ $url }}&query={{ request('query') }}">{{ $page }}</a>
                                        </li>
                                    @endforeach

                                    @if ($data->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="{{ $data->nextPageUrl() }}&query={{ request('query') }}"
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
                        {{-- <div class="d-flex justify-content-center mt-3">
                            {{ $data->links() }}
                        </div> --}}

                    </div>
                </div>
                <!-- modal tambah bidang -->
                <div class="modal fade" id="ModalTambahBidang" tabindex="-1" aria-labelledby="tmbhbidang"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content rounded-0">
                            <div class="modal-body p-3">
                                <p class="modal-title fs-14 sb grey" id="tmbhbidang">Bidang baru</p>
                                <hr style="border: 1px solid #919191;" class="mb-3">
                                <form action="{{ route('target.store.bidang') }}" method="POST">
                                    @csrf
                                    <div class="row g-2 align-items-center mb-2 ms-1 me-1">
                                        <div class="col-3">
                                            <label for="kode_bidang" class="form-label black fs-12">Kode bidang</label>
                                        </div>
                                        <div class="col-9 input-group-sm">
                                            <input type="text" class="form-control form-control-sm w-100"
                                                id="kode_bidang" placeholder="" disabled>
                                        </div>
                                    </div>
                                    <div class="row g-2 align-items-center mb-2 ms-1 me-1">
                                        <div class="col-3">
                                            <label for="nama_bidang" class="form-label black fs-12">Bidang</label>
                                        </div>
                                        <div class="col-9 input-group-sm">
                                            <input type="text" class="form-control form-control-sm w-100"
                                                name="nama_bidang" id="nama_bidang" placeholder="nama bidang">
                                        </div>
                                    </div>
                                    <div class="row g-2 align-items-center mb-3 ms-1 me-1">
                                        <div class="col-3">
                                            <label for="keterangan" class="form-label black fs-12">Keterangan</label>
                                        </div>
                                        <div class="col-9 input-group-sm">
                                            <input type="text" class="form-control form-control-sm w-100"
                                                name="keterangan" id="keterangan" placeholder="keterangan">
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-md-12 d-flex justify-content-end">
                                            <button type="button" class="btn btn-danger btn-sm fs-12 text-white me-2"
                                                data-bs-dismiss="modal"><i class="bi bi-x-square"></i> Batal</button>
                                            <button type="submit" class="btn btn-success btn-sm fs-12 text-white"><i
                                                    class="bi bi-plus-square me-1"></i> Tambah bidang</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- modal tambah kegiatan -->
                <div class="modal fade" id="ModalTambahKegiatan" tabindex="-1" aria-labelledby="ModalTambahKegiatan"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content rounded-0">
                            <div class="modal-body p-3">
                                <p class="modal-title fs-14 sb grey" id="ModalTambahKegiatan">Kegiatan baru</p>
                                <hr style="border: 1px solid #919191;" class="mb-3">
                                <form action="{{ route('target.store.kegiatan') }}" method="POST">
                                    @csrf
                                    <div class="row g-2 align-items-center mb-2 ms-1 me-1">
                                        <div class="col-3">
                                            <p class="black fs-12 mt-4">Kode rekening</p>
                                        </div>
                                        <div class="col-4 input-group-sm">
                                            <label for="inputKodeRekening" class="form-label black fs-12">Kode
                                                bidang</label>
                                            <input type="text" class="form-control form-control-sm w-100"
                                                id="inputKodeRekening" name="kode_bidang" placeholder="A" disabled>
                                            <input type="hidden" name="bidang_id" class="form-control"
                                                id="inputBidangId">
                                        </div>
                                        <div class="col-5 input-group-sm">
                                            <label for="kode_kegiatan" class="form-label black fs-12">Kode
                                                kegiatan</label>
                                            <input type="text" class="form-control form-control-sm w-100"
                                                id="kode_kegiatan" placeholder="" disabled>
                                        </div>
                                    </div>
                                    <div class="row g-2 align-items-center mb-2 ms-1 me-1">
                                        <div class="col-3">
                                            <label for="kegiatan" class="form-label black fs-12">Nama
                                                kegiatan</label>
                                        </div>
                                        <div class="col-9 input-group-sm">
                                            <input type="text" class="form-control form-control-sm w-100"
                                                id="kegiatan" name="kegiatan" placeholder="nama kegiatan">
                                        </div>
                                    </div>
                                    <div class="row g-2 align-items-center mb-3 ms-1 me-1">
                                        <div class="col-3">
                                            <label for="kategori" class="form-label black fs-12">Kategori</label>
                                        </div>
                                        <div class="col-9 input-group-sm">
                                            <input type="text" class="form-control form-control-sm w-100"
                                                id="kategori" name="kategori" placeholder="kategori" required>
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-md-12 d-flex justify-content-end">
                                            <button type="button" class="btn btn-danger btn-sm fs-12 text-white me-2"
                                                data-bs-dismiss="modal"><i class="bi bi-x-square"></i> Batal</button>
                                            <button type="submit" class="btn btn-success btn-sm fs-12 text-white"><i
                                                    class="bi bi-plus-square me-1"></i> Tambah kegiatan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modals Edit --}}

                {{-- modal edit bidang --}}
                <div class="modal fade" id="ModalEditBidang" tabindex="-1" aria-labelledby="tmbhbidang"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content rounded-0">
                            <div class="modal-body p-3">
                                <p class="modal-title fs-14 sb grey" id="tmbhbidang">Edit bidang</p>
                                <hr style="border: 1px solid #919191;" class="mb-3">
                                <form id="formEditBidang" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <!-- Hidden ID -->
                                    <input type="hidden" id="dataIdBidangEdit" name="id">

                                    <!-- Kode Bidang (Readonly) -->
                                    <div class="row g-2 align-items-center mb-2 ms-1 me-1">
                                        <div class="col-3">
                                            <label for="dataKodeBidangEdit" class="form-label black fs-12">Kode
                                                bidang</label>
                                        </div>
                                        <div class="col-9 input-group-sm">
                                            <input type="text" class="form-control form-control-sm w-100"
                                                id="dataKodeBidangEdit" name="kode_bidang" readonly>
                                        </div>
                                    </div>

                                    <!-- Nama Bidang -->
                                    <div class="row g-2 align-items-center mb-2 ms-1 me-1">
                                        <div class="col-3">
                                            <label for="dataNamaBidangEdit" class="form-label black fs-12">Bidang</label>
                                        </div>
                                        <div class="col-9 input-group-sm">
                                            <input type="text" class="form-control form-control-sm w-100"
                                                id="dataNamaBidangEdit" name="nama_bidang" placeholder="nama bidang">
                                        </div>
                                    </div>

                                    <!-- Keterangan -->
                                    <div class="row g-2 align-items-center mb-3 ms-1 me-1">
                                        <div class="col-3">
                                            <label for="dataKeteranganBidangEdit"
                                                class="form-label black fs-12">Keterangan</label>
                                        </div>
                                        <div class="col-9 input-group-sm">
                                            <input type="text" class="form-control form-control-sm w-100"
                                                id="dataKeteranganBidangEdit" name="keterangan" placeholder="keterangan">
                                        </div>
                                    </div>

                                    <!-- Button -->
                                    <div class="row align-items-center">
                                        <div class="col-md-12 d-flex justify-content-end">
                                            <button type="button" class="btn btn-danger btn-sm fs-12 text-white me-2"
                                                data-bs-dismiss="modal">
                                                <i class="bi bi-x-square"></i> Batal
                                            </button>
                                            <button type="submit" class="btn btn-warning btn-sm fs-12 text-white">
                                                <i class="bi bi-pencil-fill me-1"></i> Edit bidang
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- modal edit kegiatan -->
                <div class="modal fade" id="ModalEditKegiatan" tabindex="-1" aria-labelledby="ModalEditKegiatan"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content rounded-0">
                            <div class="modal-body p-3">
                                <p class="modal-title fs-14 sb grey" id="ModalEditKegiatan">Edit kegiatan</p>
                                <hr style="border: 1px solid #919191;" class="mb-3">
                                <form method="POST" id="formEditKegiatan" action="">
                                    @csrf
                                    @method('PUT')

                                    {{-- Hidden fields untuk dikirim ke server --}}
                                    <input type="hidden" id="inputKegiatanIdEdit" name="id">
                                    <input type="hidden" id="inputBidangIdEdit" name="bidang_id">
                                    <input type="hidden" id="inputKodeBidangHidden" name="kode_bidang">
                                    <input type="hidden" id="inputKodeKegiatanHidden" name="kode_kegiatan">

                                    {{-- Visible fields (readonly) --}}
                                    <div class="row g-2 align-items-center mb-2 ms-1 me-1">
                                        <div class="col-3">
                                            <p class="black fs-12 mt-4">Kode rekening</p>
                                        </div>
                                        <div class="col-4 input-group-sm">
                                            <label class="form-label fs-12">Kode Bidang</label>
                                            <input type="text" class="form-control form-control-sm"
                                                id="inputKoRekBidangEdit" readonly>
                                        </div>
                                        <div class="col-5 input-group-sm">
                                            <label class="form-label fs-12">Kode Kegiatan</label>
                                            <input type="text" class="form-control form-control-sm"
                                                id="inputKoRekKegiatanEdit" readonly>
                                        </div>
                                    </div>

                                    {{-- Editable --}}
                                    <div class="row g-2 align-items-center mb-2 ms-1 me-1">
                                        <div class="col-3">
                                            <label class="form-label fs-12">Nama Kegiatan</label>
                                        </div>
                                        <div class="col-9">
                                            <input type="text" class="form-control form-control-sm" id="kegiatan"
                                                name="kegiatan" placeholder="Nama kegiatan">
                                        </div>
                                    </div>
                                    <div class="row g-2 align-items-center mb-2 ms-1 me-1">
                                        <div class="col-3">
                                            <label class="form-label fs-12">Kategori</label>
                                        </div>
                                        <div class="col-9">
                                            <input type="text" class="form-control form-control-sm" id="kategori"
                                                name="kategori" placeholder="Kategori">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-danger btn-sm fs-12 text-white me-2" data-bs-dismiss="modal">
                                            <i class="bi bi-x-square"></i> Batal
                                        </button>
                                        <button type="submit" class="btn btn-warning btn-sm fs-12 text-white">
                                            <i class="bi bi-pencil-fill me-1"></i> Edit kegiatan
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modals Delete --}}

                {{-- modal delete bidang --}}
                <div class="modal fade" id="ModalDeleteBidang" tabindex="-1" aria-labelledby="DeleteBidang"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content rounded-0">
                            <div class="modal-body p-4">
                                <h5 class="modal-title fs-16 fw-bold text-danger mb-3" id="DeleteBidang">Konfirmasi Hapus
                                    Bidang</h5>
                                <p class="text-muted mb-3 fs-14">
                                    Tindakan ini akan <strong>menghapus bidang secara permanen</strong> beserta seluruh
                                    <strong>kegiatan</strong> dan <strong> subkegiatan</strong> yang ada di dalamnya. Data
                                    yang terhapus tidak dapat
                                    dikembalikan.
                                </p>

                                <form id="formDeleteBidang" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <input type="hidden" id="dataIdBidangDelete" name="id">

                                    <!-- Aksi -->
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-outline-danger btn-sm me-2"
                                            data-bs-dismiss="modal">
                                            <i class="bi bi-x-square"></i> Batal
                                        </button>
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash-fill"></i> Hapus
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- modal delete kegiatan  --}}
                <div class="modal fade" id="ModalDeleteKegiatan" tabindex="-1" aria-labelledby="DeleteKegiatan"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content rounded-0">
                            <div class="modal-body p-4">
                                <h5 class="modal-title fs-16 fw-bold text-danger mb-3" id="DeleteKegiatan">Konfirmasi
                                    Hapus
                                    Kegiatan</h5>
                                <p class="text-muted mb-3 fs-14">
                                    Tindakan ini akan <strong>menghapus kegiatan secara permanen</strong> beserta seluruh
                                    <strong>subkegiatan</strong> yang ada di dalamnya. Data
                                    yang terhapus tidak dapat
                                    dikembalikan.
                                </p>

                                <form id="formDeleteKegiatan" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <input type="hidden" id="dataIdKegiatanDelete" name="id">

                                    <!-- Aksi -->
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-outline-danger btn-sm me-2"
                                            data-bs-dismiss="modal">
                                            <i class="bi bi-x-square"></i> Batal
                                        </button>
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash-fill"></i> Hapus
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- modal delete sub kegiatan  --}}
                <div class="modal fade" id="ModalDeleteSubKegiatan" tabindex="-1" aria-labelledby="DeleteSubKegiatan"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content rounded-0">
                            <div class="modal-body p-4">
                                <h5 class="modal-title fs-16 fw-bold text-danger mb-3" id="DeleteSubKegiatan">Konfirmasi
                                    Hapus
                                    Subkegiatan</h5>
                                <p class="text-muted mb-3 fs-14">
                                    Tindakan ini akan menghapus <strong>subkegiatan target</strong> dan <strong>data sub
                                        kegiatan di realisasi secara permanen</strong>. Data
                                    yang terhapus tidak dapat
                                    dikembalikan.
                                </p>

                                <form id="formDeleteSubKegiatan" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <input type="hidden" id="dataIdSubKegiatanDelete" name="id">

                                    <!-- Aksi -->
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-outline-danger btn-sm me-2"
                                            data-bs-dismiss="modal">
                                            <i class="bi bi-x-square"></i> Batal
                                        </button>
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash-fill"></i> Hapus
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
