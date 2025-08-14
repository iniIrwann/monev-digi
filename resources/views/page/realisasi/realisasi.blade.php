@extends('layout.app')

@section('title', 'Realisasi - Monev Digi Dana Desa')


@section('main')
    <!-- Main content -->
    <div class="main-content ps-3 pe-3 pt-4">
        <div class="d-flex align-items-center mb-2 mb-md-0 pb-4">
            <div class="bg-30x d-flex justify-content-center align-items-center flex-shrink-0">
                <i class="bi-list-check fs-16 text-white"></i>
                <i class="bibi-patch-check-fill fs-16 text-white"></i>
            </div>
            <p class="fs-14 ms-2 mb-0">Verifikasi</p>
        </div>

        <!-- filter -->
        <div class="card border-0 w-100 rd-5 mb-4">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <p class="fs-18 mb-0">Filter</p>
                </div>
                <form action="{{ route('desa.realisasi.index') }}" method="GET" class="mb-3">
                    <div class="row g-2 align-items-end">
                        <div class="col-12 col-md-6">
                            <label for="" class="fs-12 mb-1">Pilih tahun</label>
                            <select name="tahun" class="fs-12 form-select">
                                <option value="">{{ __('-- Semua Tahun --') }}</option>
                                <option value="2024" {{ request('tahun') == '2024' ? 'selected' : '' }}>Tahun 2024
                                </option>
                                <option value="2025" {{ request('tahun') == '2025' ? 'selected' : '' }}>Tahun 2025
                                </option>
                            </select>


                        </div>
                        <div class="col-12 col-md-5">
                            <label for="" class="fs-12 mb-1">Pilih bidang</label>
                            <select name="bidang" class="fs-12 form-select">
                                <option value="">{{ __('-- Semua Bidang --') }}</option>
                                @foreach ($filterBidangs as $bidang)
                                    <option value="{{ $bidang->id }}"
                                        {{ request('bidang') == $bidang->id ? 'selected' : '' }}>
                                        {{ $bidang->nama_bidang }}
                                    </option>
                                @endforeach
                            </select>

                        </div>
                        {{-- <input type="hidden" name="query" value=''> --}}
                        <div class="col-12 col-md-1 d-grid">
                            <button type="submit" class="btn btn-success fs-12 text-white">
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
                <div class="tab-container mb-3">
                    <a href="{{ route('desa.realisasi.index', array_merge(request()->query(), ['tahap' => '1'])) }}"
                        class="tab-link {{ $tahap === '1' ? 'active' : '' }}">Tahap 1</a>
                    <a href="{{ route('desa.realisasi.index', array_merge(request()->query(), ['tahap' => '2'])) }}"
                        class="tab-link {{ $tahap === '2' ? 'active' : '' }}">Tahap 2</a>
                    <a href="{{ route('desa.realisasi.index') }}"
                        class="tab-link {{ $tahap === '' ? 'active' : '' }}">Total Realisasi</a>
                </div>
                <form action="  " method="GET" class="mb-3">
                    <div class="row g-3 mb-2">
                        <div class="col-auto">
                            <!-- Input text -->
                            <input type="text" name="query" class="form-control form-control-sm w-100"
                                placeholder="Pencarian..." />
                        </div>
                        <div class="col-auto">
                            <!-- Tombol cari -->
                            <button type="submit" class="btn btn-success fs-12 text-white">
                                <i class="bi bi-search me-1"></i> Cari
                            </button>
                        </div>
                    </div>
                </form>
                @php $tahap = isset($tahap) ? (string)$tahap : ''; @endphp

                {{-- <ul class="nav nav-tabs mb-3">
                    <li class="nav-item">
                        <a class="nav-link {{ $tahap === '1' ? 'active' : '' }}"
                            href="{{ route('desa.realisasi.index', array_merge(request()->query(), ['tahap' => '1'])) }}">
                            Tahap 1
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ $tahap === '2' ? 'active' : '' }}"
                            href="{{ route('desa.realisasi.index', array_merge(request()->query(), ['tahap' => '2'])) }}">
                            Tahap 2
                        </a>
                    </li>

                    {{-- <li class="nav-item">
                        <a class="nav-link {{ $tahap === '' ? 'active' : '' }}"
                            href="{{ route('desa.realisasi.index', array_merge(request()->query(), ['tahap' => ''])) }}">
                            Semua Tahap
                        </a>
                    </li>
                </ul> --}}
                <div class="table-responsive">
                    <table class="table align-middle fs-12 tx-gray">
                        <thead class="border-bottom" style="border-color: #999999">
                            @if ($tahap == 1 || $tahap == 2)
                                {{-- Jika tahap = 1 atau 2 --}}
                                <tr class="text-start">
                                    <th class="text-center">Aksi</th>
                                    <th>Kode rekening</th>
                                    <th>Rencana kegiatan</th>
                                    <th>Volume Fisik</th>
                                    <th>Realisasi keuangan (Rp)</th>
                                    <th>(%) Volume Fisik</th>
                                    <th>(%) Realisasi keuangan</th>
                                </tr>
                            @else
                                {{-- Jika tahap = all --}}
                                <tr class="text-start">
                                    <th class="text-center" rowspan="2">Aksi</th>
                                    <th rowspan="2">Kode rekening</th>
                                    <th rowspan="2">Rencana kegiatan</th>
                                    <th colspan="2" class="text-center">Tahap 1</th>
                                    <th colspan="2" class="text-center">Tahap 2</th>
                                    <th colspan="4" class="text-center">Total</th>
                                </tr>
                                <tr class="text-start">
                                    <th>Volume Fisik</th>
                                    <th>Realisasi keuangan (Rp)</th>
                                    <th>Volume Fisik</th>
                                    <th>Realisasi keuangan (Rp)</th>
                                    <th>Total Volume Fisik</th>
                                    <th>(%) Volume Fisik</th>
                                    <th>Total Keuangan</th>
                                    <th>(%) Realisasi keuangan</th>
                                </tr>
                            @endif
                        </thead>

                        <tbody>
                            @foreach ($data as $bidang)
                                <!-- BIDANG -->
                                <tr>
                                    <td>
                                        <div class="d-flex gap-1 justify-content-end"></div>
                                    </td>
                                    <td class="sb">{{ $bidang->kode_rekening }}</td>
                                    <td class="sb">{{ $bidang->nama_bidang }}</td>
                                    <td colspan="9"></td> <!-- Sesuaikan colspan dengan jumlah kolom -->
                                </tr>

                                @foreach ($bidang->kegiatan as $kegiatan)
                                    <!-- KEGIATAN -->
                                    <tr>
                                        <td>
                                            <div class="d-flex gap-1 justify-content-end"></div>
                                        </td>
                                        <td>
                                            <span class="gap-td-table">{{ $bidang->kode_rekening }}</span>
                                            <span>{{ $kegiatan->kode_rekening }}</span>
                                        </td>
                                        <td class="ps-4">{{ $kegiatan->nama_kegiatan }}</td>
                                        <td colspan="9"></td> <!-- Sesuaikan colspan dengan jumlah kolom -->
                                    </tr>

                                    @foreach ($kegiatan->subkegiatan as $sub)
                                        <!-- SUBKEGIATAN -->
                                        <tr>
                                            <td>
                                                <div class="d-flex gap-1 justify-content-end">
                                                    <a href="{{ route('desa.realisasi.detail', ['bidang_id' => $bidang->id, 'kegiatan_id' => $kegiatan->id, 'subkegiatan_id' => $sub->id]) }}"
                                                        class="btn btn-sm btn-secondary"><i class="bi bi-eye-fill"></i></a>
                                                    @if ($tahap != '' && $sub->realisasis->isNotEmpty())
                                                        <a href="{{ route('desa.realisasi.create.sub', [
                                                            'bidang_id' => $bidang->id,
                                                            'kegiatan_id' => $kegiatan->id,
                                                            'subkegiatan_id' => $sub->id,
                                                        ]) }}?tahap={{ $tahap }}"
                                                            class="btn btn-sm btn-warning">
                                                            <i class="bi bi-pencil-fill text-white"></i>
                                                        </a>
                                                    @endif
                                                    <button data-bs-toggle="modal"
                                                        data-bs-target="#ModalDeleteSubKegiatanRealisasi"
                                                        data-tahap-subKegiatan-realisasi-delete="{{ $tahap }}"
                                                        data-id-subKegiatan-realisasi-delete="{{ $sub->id }}"
                                                        class="btn btn-sm btn-danger"><i
                                                            class="bi bi-trash undermines"></i></button>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="gap-td-table">{{ $bidang->kode_rekening }}</span>
                                                <span class="gap-td-table">{{ $kegiatan->kode_rekening }}</span>
                                                <span>{{ $sub->kode_rekening }}</span>
                                            </td>
                                            <td class="ps-5">{{ $sub->nama_subkegiatan }}</td>
                                            {{-- Tahap All --}}
                                            @if ($tahap == '')
                                                @if ($sub->realisasis->isNotEmpty())
                                                    <!-- Tahap 1 -->
                                                    @if ($sub->tahap1Data->realisasi_keuangan)
                                                        <td>{{ $sub->tahap1Data?->volume_keluaran ?? '( - )' }}
                                                            {{ $sub->tahap1Data?->uraian_keluaran ?? '-' }}</td>
                                                    @else
                                                        <td colspan="2" class="text-center"><a
                                                                class="text-decoration-none"
                                                                href="{{ route('desa.realisasi.create.sub', [
                                                                    'bidang_id' => $bidang->id,
                                                                    'kegiatan_id' => $kegiatan->id,
                                                                    'subkegiatan_id' => $sub->id,
                                                                ]) }}?tahap=1"><span
                                                                    class="text-danger">Silahkan isi realisasi</span></a>
                                                        </td>
                                                    @endif
                                                    @if ($sub->tahap1Data?->realisasi_keuangan !== null)
                                                        <td>
                                                            Rp.{{ number_format($sub->tahap1Data->realisasi_keuangan, 0, ',', '.') }}
                                                        </td>
                                                    @endif
                                                    <!-- Tahap 2 -->
                                                    @if ($sub->tahap2Data->realisasi_keuangan)
                                                        <td>
                                                            {{ $sub->tahap2Data->volume_keluaran ?? '-' }}
                                                            {{ $sub->tahap2Data->uraian_keluaran ?? '-' }}
                                                        </td>
                                                    @else
                                                        <td colspan="2" class="text-center"><a
                                                                class="text-decoration-none"
                                                                href="{{ route('desa.realisasi.create.sub', [
                                                                    'bidang_id' => $bidang->id,
                                                                    'kegiatan_id' => $kegiatan->id,
                                                                    'subkegiatan_id' => $sub->id,
                                                                ]) }}?tahap=2"><span
                                                                    class="text-danger">Silahkan isi realisasi</span></a>
                                                        </td>
                                                    @endif
                                                    @if ($sub->tahap1Data?->realisasi_keuangan !== null)
                                                        <td>
                                                            Rp.{{ number_format($sub->tahap2Data->realisasi_keuangan, 0, ',', '.') }}
                                                        </td>
                                                    @endif
                                                    <!-- Total -->
                                                    @if ($sub->tahap1Data?->realisasi_keuangan !== null && $sub->tahap2Data?->realisasi_keuangan !== null)
                                                        <td>
                                                            {{ ($sub->tahap1Data?->volume_keluaran ?? 0) + ($sub->tahap2Data?->volume_keluaran ?? 0) }}
                                                        </td>
                                                        <td>{{ $sub->persenVolumeFisikTotal }} %</td>
                                                        <td>
                                                            Rp.{{ number_format(($sub->tahap1Data?->realisasi_keuangan ?? 0) + ($sub->tahap2Data?->realisasi_keuangan ?? 0), 0, ',', '.') }}
                                                        </td>
                                                        <td>{{ $sub->persenVolumeKuanganTotal }} %</td>
                                                    @else
                                                        <td colspan="4" class="text-danger text-center">Tahap 1 atau 2
                                                            belum terisi</td>
                                                    @endif
                                                @else
                                                    <td colspan="7" class="text-muted text-center">Belum ada realisasi
                                                    </td>
                                                @endif

                                                {{-- Tahap 1 dan 2 --}}
                                            @elseif ($tahap == '1' || $tahap == '2')
                                                @if ($sub->realisasis->isNotEmpty())
                                                    <td>{{ $sub->tahapData?->volume_keluaran ?? '( - )' }}
                                                        {{ $sub->tahapData?->uraian_keluaran ?? '-' }}</td>
                                                    <td>
                                                        @if ($sub->tahapData?->realisasi_keuangan !== null)
                                                            Rp.{{ number_format($sub->tahapData->realisasi_keuangan, 0, ',', '.') }}
                                                        @else
                                                            <a class="text-decoration-none"
                                                                href="{{ route('desa.realisasi.create.sub', [
                                                                    'bidang_id' => $bidang->id,
                                                                    'kegiatan_id' => $kegiatan->id,
                                                                    'subkegiatan_id' => $sub->id,
                                                                ]) }}?tahap={{ $tahap }}"><span
                                                                    class="text-danger">Silahkan isi realisasi</span></a>
                                                        @endif
                                                    </td>
                                                    <td>{{ number_format($sub->persenVolumeFisik, 2) }}%</td>
                                                    <td> {{ number_format($sub->persenKeuangan, 2) }}%</td>
                                                @else
                                                    <td colspan="3" class="text-muted text-center">Belum ada realisasi
                                                        untuk tahap {{ $tahap }}</td>
                                                @endif
                                            @else
                                                <td colspan="3" class="text-muted text-center">Tahap tidak valid</td>
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


                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal Mengosongkan isi data --}}
    <div class="modal fade" id="ModalDeleteSubKegiatanRealisasi" tabindex="-1" aria-labelledby="DeleteSubKegiatan"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-0">
                <div class="modal-body p-4">
                    <h5 class="modal-title fs-16 fw-bold text-danger mb-3" id="DeleteSubKegiatan">Konfirmasi
                        Hapus
                        Subkegiatan</h5>
                    <p class="text-muted mb-3 fs-14">
                        Tindakan ini akan menghapus <strong>semua isi data ( selain kode rekening, dan rencana kegiatan )
                            secara permanen </strong>. Data
                        yang terhapus tidak dapat
                        dikembalikan.
                    </p>

                    <form id="formDeleteSubKegiatanRealisasi" method="POST">
                        @csrf
                        @method('DELETE')

                        <input type="hidden" id="dataIdSubKegiatanDeleteRealisasi" name="id">
                        <input type="hidden" id="dataTahapSubKegiatanDeleteRealisasi" name="tahap">

                        <!-- Aksi -->
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
        // Delete Sub Kegiatan Modal Realisasi
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('ModalDeleteSubKegiatanRealisasi');

            modal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const dataIdSubKegiatanDeleteRealisasi = button.getAttribute(
                    'data-id-subKegiatan-realisasi-delete');
                const dataTahapSubKegiatanDeleteRealisasi = button.getAttribute(
                    'data-tahap-subKegiatan-realisasi-delete');

                // Hidden inputs
                modal.querySelector('#dataIdSubKegiatanDeleteRealisasi').value =
                    dataIdSubKegiatanDeleteRealisasi;
                modal.querySelector('#dataTahapSubKegiatanDeleteRealisasi').value =
                    dataTahapSubKegiatanDeleteRealisasi;

                // Set form action
                const form = document.getElementById('formDeleteSubKegiatanRealisasi');
                form.action =
                    `/realisasi/delete-subKegiatan/${dataIdSubKegiatanDeleteRealisasi}/${dataTahapSubKegiatanDeleteRealisasi}`;
            });
        });
    </script>
@endsection
@endsection
