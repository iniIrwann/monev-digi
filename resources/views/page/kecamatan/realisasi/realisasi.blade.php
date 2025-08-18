@extends('layout.app')

@section('title', 'Realisasi Kecamatan - Monev Digi Dana Desa')

@section('main')
    <!-- Main content -->
    <div class="main-content ps-3 pe-3 pt-4">
        <div class="d-flex align-items-center mb-2 mb-md-0 pb-4">
            <div class="bg-30x d-flex justify-content-center align-items-center flex-shrink-0">
                <i class="bi bi-list-check fs-16 text-white"></i>
            </div>
            <p class="fs-14 ms-2 mb-0">Realisasi</p>
        </div>

        <!-- Filter -->
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
                        <div class="col-12 col-md-4">
                            <label class="fs-12 mb-1">Pilih Tahun</label>
                            <select name="tahun" class="fs-12 form-select">
                                <option value="">{{ __('-- Semua Tahun --') }}</option>
                                <option value="2024" {{ request('tahun') == '2024' ? 'selected' : '' }}>Tahun 2024
                                </option>
                                <option value="2025" {{ request('tahun') == '2025' ? 'selected' : '' }}>Tahun 2025
                                </option>
                            </select>
                        </div>

                        <!-- Pilih Bidang -->
                        <div class="col-12 col-md-3">
                            <label class="fs-12 mb-1">Pilih Bidang</label>
                            <select name="bidang" class="fs-12 form-select">
                                <option value="">{{ __('-- Semua Bidang --') }}</option>
                                @foreach ($filterBidangs as $b)
                                    <option value="{{ $b->id }}"
                                        {{ request('bidang') == $b->id ? 'selected' : '' }}>
                                        {{ $b->nama_bidang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-1 d-grid">
                            <button type="submit" class="btn btn-success fs-12 text-white">
                                <i class="bi bi-filter"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel -->
        <div class="card border-0 w-100 rd-5">
            <div class="card-body p-3">
                <div class="tab-container mb-3">
                    <a href="{{ route('kecamatan.realisasi.index', array_merge(request()->query(), ['tahap' => '1'])) }}"
                        class="tab-link {{ $tahap === '1' ? 'active' : '' }}">Tahap 1</a>
                    <a href="{{ route('kecamatan.realisasi.index', array_merge(request()->query(), ['tahap' => '2'])) }}"
                        class="tab-link {{ $tahap === '2' ? 'active' : '' }}">Tahap 2</a>
                    <a href="{{ route('kecamatan.realisasi.index', array_merge(request()->query(), ['tahap' => 'all'])) }}"
                        class="tab-link {{ $tahap === 'all' ? 'active' : '' }}">Total Realisasi</a>
                </div>

                <form action="{{ route('kecamatan.realisasi.index') }}" method="GET" class="mb-3">
                    <div class="row g-3 mb-2">
                        <input type="hidden" name="desa" value="{{ request('desa') }}">
                        <input type="hidden" name="tahun" value="{{ request('tahun') }}">
                        <input type="hidden" name="bidang" value="{{ request('bidang') }}">
                        <input type="hidden" name="tahap" value="{{ $tahap }}">
                        <div class="col-auto">
                            <input type="text" name="query" class="form-control form-control-sm w-100"
                                placeholder="Pencarian..." value="{{ $search }}" />
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-success fs-12 text-white">
                                <i class="bi bi-search me-1"></i> Cari
                            </button>
                        </div>
                    </div>
                </form>

                <p class="fs-12 my-2">
                    @if ($desa)
                        Kinerja dan anggaran dana <span class="fw-bold">{{ $desa->desa }}</span>,
                        <span class="fw-bold">{{ $tahun ?? '(semua tahun)' }}</span>,
                        <span class="fw-bold">{{ $bidang->nama_bidang ?? '(semua bidang)' }}</span>,
                        <span class="fw-bold">{{ $tahap ? 'Tahap ' . $tahap : '(total realisasi)' }}</span>
                    @else
                        Menampilkan Semua Realisasi Desa.
                    @endif
                </p>
                <hr />

                <div class="table-responsive">
                    <table class="table align-middle fs-12 tx-gray">
                        <thead class="border-bottom" style="border-color: #999999">
                            @if ($tahap == '1' || $tahap == '2')
                                <tr class="text-start">
                                    <th class="text-center">Aksi</th>
                                    <th>Kode Rekening</th>
                                    <th>Rencana Kegiatan</th>
                                    <th>Volume Fisik</th>
                                    <th>Realisasi Keuangan (Rp)</th>
                                    <th>(%) Volume Fisik</th>
                                    <th>(%) Realisasi Keuangan</th>
                                </tr>
                            @else
                                <tr class="text-start">
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th colspan="2" class="text-center">Tahap 1</th>
                                    <th colspan="2" class="text-center">Tahap 2</th>
                                    <th colspan="4" class="text-center">Total</th>
                                </tr>
                                <tr class="text-start">
                                    <th class="text-center">Aksi</th>
                                    <th>Kode Rekening</th>
                                    <th class="">Rencana Kegiatan</th>
                                    <th>Volume Fisik</th>
                                    <th>Realisasi Keuangan (Rp)</th>
                                    <th>Volume Fisik</th>
                                    <th>Realisasi Keuangan (Rp)</th>
                                    <th>Total Volume Fisik</th>
                                    <th>(%) Volume Fisik</th>
                                    <th>Total Keuangan</th>
                                    <th>(%) Realisasi Keuangan</th>
                                </tr>
                            @endif
                        </thead>
                        <tbody>
                            @forelse ($data as $bidang)
                                <!-- BIDANG -->
                                <tr>
                                    <td></td>
                                    <td class="sb">{{ $bidang->kode_rekening }}</td>
                                    <td class="sb">{{ $bidang->nama_bidang }}</td>
                                    <td colspan="{{ $tahap == 'all' ? 8 : 4 }}"></td>
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
                                        <td colspan="{{ $tahap == 'all' ? 8 : 4 }}"></td>
                                    </tr>

                                    @foreach ($kegiatan->subkegiatan as $sub)
                                        <!-- SUBKEGIATAN -->
                                        <tr>
                                            <td>
                                                <div class="d-flex gap-1 justify-content-end">
                                                    <a href="{{ route('kecamatan.realisasi.detail', ['bidang_id' => $bidang->id, 'kegiatan_id' => $kegiatan->id, 'subkegiatan_id' => $sub->id]) }}?tahap={{ $tahap }}"
                                                        class="btn btn-sm btn-secondary" title="Detail"><i
                                                            class="bi bi-eye-fill"></i></a>
                                                    @if ($tahap != 'all')
                                                        <a href="{{ route('kecamatan.realisasi.create.sub', ['bidang_id' => $bidang->id, 'kegiatan_id' => $kegiatan->id, 'subkegiatan_id' => $sub->id, 'tahap' => $tahap]) }}"
                                                            class="btn btn-sm btn-warning" title="Isi/Edit"><i
                                                                class="bi bi-pencil-fill text-white"></i></a>
                                                        @if ($sub->tahapData)
                                                            <button type="button" data-bs-toggle="modal"
                                                                data-bs-target="#ModalDeleteSubKegiatanRealisasi"
                                                                data-realisasi-id="{{ $sub->tahapData->id }}"
                                                                data-tahap="{{ $tahap }}"
                                                                class="btn btn-sm btn-danger" title="Kosongkan"><i
                                                                    class="bi bi-trash-fill"></i></button>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <span class="gap-td-table">{{ $bidang->kode_rekening }}</span>
                                                <span class="gap-td-table">{{ $kegiatan->kode_rekening }}</span>
                                                <span>{{ $sub->kode_rekening }}</span>
                                            </td>
                                            <td class="ps-5">{{ $sub->nama_subkegiatan }}</td>
                                            @if ($tahap == 'all')
                                                <!-- Total Realisasi -->
                                                <!-- Tahap 1 -->
                                                @if ($sub->tahap1Data && $sub->tahap1Data->realisasi_keuangan !== null)
                                                    <td>{{ $sub->tahap1Data->volume_keluaran ?? '-' }}
                                                        {{ $sub->tahap1Data->uraian_keluaran ?? '-' }}</td>
                                                    <td>Rp.{{ number_format($sub->tahap1Data->realisasi_keuangan, 0, ',', '.') }}
                                                    </td>
                                                @else
                                                    <td colspan="2" class="text-center">
                                                        <a class="text-decoration-none"
                                                            href="{{ route('kecamatan.realisasi.create.sub', ['bidang_id' => $bidang->id, 'kegiatan_id' => $kegiatan->id, 'subkegiatan_id' => $sub->id]) }}?tahap=1"
                                                            class="text-decoration-none">
                                                            <span class="badge bg-danger fs-12">Silahkan isi
                                                                realisasi</span>
                                                        </a>
                                                    </td>
                                                @endif
                                                <!-- Tahap 2 -->
                                                @if ($sub->tahap2Data && $sub->tahap2Data->realisasi_keuangan !== null)
                                                    <td>{{ $sub->tahap2Data->volume_keluaran ?? '-' }}
                                                        {{ $sub->tahap2Data->uraian_keluaran ?? '-' }}</td>
                                                    <td>Rp.{{ number_format($sub->tahap2Data->realisasi_keuangan, 0, ',', '.') }}
                                                    </td>
                                                @else
                                                    <td colspan="2" class="text-center">
                                                        <a class="text-decoration-none"
                                                            href="{{ route('kecamatan.realisasi.create.sub', ['bidang_id' => $bidang->id, 'kegiatan_id' => $kegiatan->id, 'subkegiatan_id' => $sub->id]) }}?tahap=2"
                                                            class="text-decoration-none">
                                                            <span class="badge bg-danger fs-12">Silahkan isi
                                                                realisasi</span>
                                                        </a>
                                                    </td>
                                                @endif
                                                <!-- Total -->
                                                @if (
                                                    $sub->tahap1Data &&
                                                        $sub->tahap2Data &&
                                                        $sub->tahap1Data->realisasi_keuangan !== null &&
                                                        $sub->tahap2Data->realisasi_keuangan !== null)
                                                    <td>{{ ($sub->tahap1Data->volume_keluaran ?? 0) + ($sub->tahap2Data->volume_keluaran ?? 0) }}
                                                    </td>
                                                    <td>{{ number_format($sub->persenVolumeFisikTotal, 2) }}%</td>
                                                    <td>Rp.{{ number_format(($sub->tahap1Data->realisasi_keuangan ?? 0) + ($sub->tahap2Data->realisasi_keuangan ?? 0), 0, ',', '.') }}
                                                    </td>
                                                    <td>{{ number_format($sub->persenVolumeKeuanganTotal, 2) }}%</td>
                                                @else
                                                    <td colspan="4" class="text-center">
                                                        <span class="badge bg-danger fs-12">
                                                            Tahap 1 atau 2 belum terisi
                                                        </span>
                                                    </td>
                                                @endif
                                            @elseif ($tahap == '1' || $tahap == '2')
                                                <!-- Tahap 1 or 2 -->
                                                @if ($sub->realisasis->isNotEmpty() && $sub->tahapData)
                                                    <td>{{ $sub->tahapData->volume_keluaran ?? '-' }}
                                                        {{ $sub->tahapData->uraian_keluaran ?? '-' }}</td>
                                                    <td>
                                                        @if ($sub->tahapData->realisasi_keuangan !== null)
                                                            Rp.{{ number_format($sub->tahapData->realisasi_keuangan, 0, ',', '.') }}
                                                        @else
                                                            <a class="text-decoration-none"
                                                                href="{{ route('kecamatan.realisasi.create.sub', ['bidang_id' => $bidang->id, 'kegiatan_id' => $kegiatan->id, 'subkegiatan_id' => $sub->id]) }}?tahap={{ $tahap }}"
                                                                class="text-decoration-none">
                                                                <span class="badge bg-danger fs-12">Silahkan isi
                                                                    realisasi</span>
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td>{{ number_format($sub->persenVolumeFisik, 2) }}%</td>
                                                    <td>{{ number_format($sub->persenKeuangan, 2) }}%</td>
                                                @else
                                                    <td colspan="4" class="text-muted text-center">Belum ada realisasi
                                                        untuk tahap {{ $tahap }}</td>
                                                @endif
                                            @endif
                                        </tr>
                                    @endforeach
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="{{ $tahap == 'all' ? 10 : 7 }}" class="text-center text-muted">Tidak ada
                                        data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination -->
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
                                            <a class="page-link" href="{{ $data->previousPageUrl() }}"
                                                aria-label="Previous">
                                                <i class="bi bi-caret-left-fill"></i>
                                            </a>
                                        </li>
                                    @endif

                                    @foreach ($data->getUrlRange(1, $data->lastPage()) as $page => $url)
                                        <li class="page-item {{ $data->currentPage() == $page ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                    @endforeach

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

    <!-- Delete Modal -->
    <div class="modal fade" id="ModalDeleteSubKegiatanRealisasi" tabindex="-1" aria-labelledby="DeleteSubKegiatan"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-0">
                <div class="modal-body p-4">
                    <h5 class="modal-title fs-16 fw-bold text-danger mb-3" id="DeleteSubKegiatan">Konfirmasi Hapus
                        Realisasi</h5>
                    <p class="text-muted mb-3 fs-14">
                        Tindakan ini akan menghapus <strong>semua isi data realisasi untuk tahap yang dipilih</strong>. Data
                        yang terhapus tidak dapat dikembalikan.
                    </p>

                    <form id="formDeleteSubKegiatanRealisasi" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" id="realisasiId" name="id">
                        <input type="hidden" id="tahap" name="tahap">

                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-outline-danger btn-sm me-2" data-bs-dismiss="modal">
                                <i class="bi bi-x-square"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @section('script')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modal = document.getElementById('ModalDeleteSubKegiatanRealisasi');
                modal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const realisasiId = button.getAttribute('data-realisasi-id');
                    const tahap = button.getAttribute('data-tahap');

                    // Set hidden inputs
                    modal.querySelector('#realisasiId').value = realisasiId;
                    modal.querySelector('#tahap').value = tahap;

                    // Set form action
                    const urlTemplate =
                        "{{ route('kecamatan.realisasi.sub.delete', ['id' => 'ID_REPLACE', 'tahap' => 'TAHAP_REPLACE']) }}";
                    const action = urlTemplate.replace('ID_REPLACE', realisasiId).replace('TAHAP_REPLACE',
                        tahap || '');
                    modal.querySelector('#formDeleteSubKegiatanRealisasi').action = action;
                });
            });
        </script>
    @endsection
@endsection
