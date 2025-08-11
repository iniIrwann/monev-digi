@extends('layout.app')

@section('main')
    <!-- Main content -->
    <div class="main-content ps-3 pe-3 pt-4">
        <div class="d-flex align-items-center mb-2 mb-md-0 pb-4">
            <div class="bg-30x d-flex justify-content-center align-items-center flex-shrink-0">
                <i class="bi-list-check fs-16 text-white"></i>
            </div>
            <p class="fs-14 ms-2 mb-0">Realisasi (Kecamatan)</p>
        </div>

        <!-- filter -->
        <div class="card border-0 w-100 rd-5 mb-4">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <p class="fs-18 mb-0">Filter</p>
                </div>

                <form action="{{ route('kecamatan.realisasi.index') }}" method="GET" class="mb-3">
                    <div class="row g-2 align-items-end">

                        <!-- Pilih Desa -->
                        <div class="col-12 col-md-4">
                            <label class="fs-12 mb-1">Pilih Desa</label>
                            <select name="desa" class="fs-12 form-select">
                                <option value="">{{ __('-- Semua Desa --') }}</option>
                                @foreach ($selectDesa as $d)
                                    <option value="{{ $d->id }}" {{ request('desa') == $d->id ? 'selected' : '' }}>
                                        {{ $d->desa }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Pilih Tahun -->
                        <div class="col-12 col-md-3">
                            <label for="" class="fs-12 mb-1">Pilih Tahun</label>
                            <select name="tahun" class="fs-12 form-select">
                                <option value="">{{ __('-- Semua Tahun --') }}</option>
                                <option value="2024" {{ request('tahun') == '2024' ? 'selected' : '' }}>Tahun 2024</option>
                                <option value="2025" {{ request('tahun') == '2025' ? 'selected' : '' }}>Tahun 2025</option>
                            </select>
                        </div>

                        <!-- Pilih Bidang (tergantung desa) -->
                        <div class="col-12 col-md-4">
                            <label for="" class="fs-12 mb-1">Pilih Bidang</label>
                            <select name="bidang" class="fs-12 form-select">
                                <option value="">{{ __('-- Semua Bidang --') }}</option>
                                @foreach ($filterBidangs as $b)
                                    <option value="{{ $b->id }}" {{ request('bidang') == $b->id ? 'selected' : '' }}>
                                        {{ $b->nama_bidang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-1 d-grid">
                            <button type="submit" class="btn btn-success btn-sm fs-12 text-white">
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
                <p class="fs-12 my-2">
                    @if ($desa)
                        kinerja dan anggaran dana <span class="fw-bold">{{ $desa->desa }}</span> tahun
                        {{ $tahun ?? '( semua tahun )' }}. <span
                            class="fw-bold">{{ $bidang->nama_bidang ?? 'semua bidang' }}</span>
                    @else
                        Menampilkan Semua Realisasi Desa.
                    @endif
                </p>
                <hr />

                <form action="{{ route('kecamatan.realisasi.index') }}" method="GET" class="mb-3">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <!-- Keep existing query params when searching (year/desa/bidang) -->
                        <input type="hidden" name="desa" value="{{ request('desa') }}">
                        <input type="hidden" name="tahun" value="{{ request('tahun') }}">
                        <input type="hidden" name="bidang" value="{{ request('bidang') }}">

                        <!-- Input text -->
                        <input type="text" name="query" class="form-control form-control-sm"
                            placeholder="Pencarian..." style="width: 300px"
                            value="{{ request('query') }}" />
                        <!-- Tombol cari -->
                        <button type="submit" class="btn btn-success btn-sm text-white">
                            <i class="bi bi-search me-1"></i> Cari
                        </button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table align-middle fs-12 tx-gray">
                        <thead class="border-bottom" style="border-color: #999999">
                            <tr class="text-start">
                                <th class="text-center">Aksi</th>
                                <th>Kode<br />Rekening</th>
                                <th>Rencana Kegiatan</th>
                                <th>Volume</th>
                                <th>Uraian</th>
                                <th>Realisasi Keuangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $bidang)
                                <!-- BIDANG -->
                                <tr>
                                    <td></td>
                                    <td class="sb">{{ $bidang->kode_rekening }}</td>
                                    <td class="sb">{{ $bidang->nama_bidang }}</td>
                                    <td colspan="4"></td>
                                </tr>

                                @foreach ($bidang->kegiatan as $kegiatan)
                                    <!-- KEGIATAN -->
                                    <tr>
                                        <td></td>
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
                                                <div class="d-flex gap-1 justify-content-end">
                                                    <a href="{{ route('kecamatan.realisasi.detail', ['bidang_id' => $bidang->id, 'kegiatan_id' => $kegiatan->id, 'subkegiatan_id' => $sub->id]) }}"
                                                        class="btn btn-sm btn-secondary" title="Detail"><i class="bi bi-eye-fill"></i></a>

                                                    <a href="{{ route('kecamatan.realisasi.create.sub', ['bidang_id' => $bidang->id, 'kegiatan_id' => $kegiatan->id, 'subkegiatan_id' => $sub->id]) }}"
                                                        class="btn btn-sm btn-warning" title="Isi / Edit"><i class="bi bi-pencil-fill text-white"></i></a>

                                                    <button type="button" data-bs-toggle="modal"
                                                        data-bs-target="#ModalDeleteSubKegiatanRealisasi"
                                                        data-id-subKegiatan-realisasi-delete="{{ $sub->id }}"
                                                        class="btn btn-sm btn-danger" title="Kosongkan"><i class="bi bi-trash-fill"></i></button>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="gap-td-table">{{ $bidang->kode_rekening }}</span>
                                                <span class="gap-td-table">{{ $kegiatan->kode_rekening }}</span>
                                                <span>{{ $sub->kode_rekening }}</span>
                                            </td>
                                            <td class="ps-5">{{ $sub->nama_subkegiatan }}</td>

                                            @if ($sub->realisasis->isNotEmpty())
                                                @php $realisasi = $sub->realisasis->first(); @endphp
                                                <td>{{ $realisasi->volume_keluaran }}</td>
                                                <td>{{ $realisasi->uraian_keluaran }}</td>
                                                <td>
                                                    @if ($realisasi->realisasi_keuangan !== null)
                                                        Rp.{{ number_format($realisasi->realisasi_keuangan, 0, ',', '.') }}
                                                    @else
                                                        <a class="text-decoration-none"
                                                            href="{{ route('kecamatan.realisasi.create.sub', ['bidang_id' => $bidang->id, 'kegiatan_id' => $kegiatan->id, 'subkegiatan_id' => $sub->id]) }}"><span
                                                                class="text-danger">Silahkan isi realisasi</span></a>
                                                    @endif
                                                </td>
                                            @else
                                                <td colspan="4" class="text-muted text-center">Belum ada realisasi</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination di akhir kanan -->
                    <div class="d-flex justify-content-end mt-3">
                        @if ($data->hasPages())
                            <nav aria-label="Page navigation example" style="color: #626262">
                                <ul class="pagination m-0">
                                    {{-- Previous --}}
                                    @if ($data->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link"><i class="bi bi-caret-left-fill"></i></span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $data->previousPageUrl() }}" aria-label="Previous">
                                                <i class="bi bi-caret-left-fill"></i>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- Pages (gunakan URL yang sudah mengandung query karena controller ->appends()) --}}
                                    @foreach ($data->getUrlRange(1, $data->lastPage()) as $page => $url)
                                        <li class="page-item {{ $data->currentPage() == $page ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                    @endforeach

                                    {{-- Next --}}
                                    @if ($data->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $data->nextPageUrl() }}" aria-label="Next">
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
    </div>

    {{-- Mengosongkan isi data (Modal) --}}
    <div class="modal fade" id="ModalDeleteSubKegiatanRealisasi" tabindex="-1" aria-labelledby="DeleteSubKegiatan"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-0">
                <div class="modal-body p-4">
                    <h5 class="modal-title fs-16 fw-bold text-danger mb-3" id="DeleteSubKegiatan">Konfirmasi
                        Hapus
                        Sub Kegiatan</h5>
                    <p class="text-muted mb-3">
                        Tindakan ini akan menghapus <strong>semua isi data ( selain kode rekening, dan rencana kegiatan )
                            secara permanen </strong>. Data yang terhapus tidak dapat dikembalikan.
                    </p>

                    <form id="formDeleteSubKegiatanRealisasi" method="POST" action="">
                        @csrf
                        @method('DELETE')

                        <input type="hidden" id="dataIdSubKegiatanDeleteRealisasi" name="id">

                        <!-- Aksi -->
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-outline-secondary btn-sm me-2" data-bs-dismiss="modal">
                                <i class="bi bi-x-square"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="bi bi-trash"></i> Hapus Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Script untuk set action form delete modal --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var deleteModal = document.getElementById('ModalDeleteSubKegiatanRealisasi');
                deleteModal.addEventListener('show.bs.modal', function(event) {
                    var button = event.relatedTarget;
                    var subId = button.getAttribute('data-id-subKegiatan-realisasi-delete');

                    // set input id
                    document.getElementById('dataIdSubKegiatanDeleteRealisasi').value = subId;

                    // generate route (ganti nama route jika berbeda)
                    var urlTemplate = "{{ route('kecamatan.realisasi.sub.delete', ['id' => 'ID_REPLACE']) }}";
                    var action = urlTemplate.replace('ID_REPLACE', subId);

                    document.getElementById('formDeleteSubKegiatanRealisasi').action = action;
                });
            });
        </script>
    @endpush
@endsection
