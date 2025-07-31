@extends('layout.app')

@section('main')

    <!-- Main content -->
    <div class="main-content ps-3 pe-3 pt-4">
        <div class="d-flex align-items-center mb-2 mb-md-0 pb-4">
            <div class="bg-30x d-flex justify-content-center align-items-center flex-shrink-0">
                <i class="bi bi-bullseye fs-16 text-white"></i>
            </div>
            <p class="fs-14 ms-2 mb-0">Capaian</p>
        </div>

        <!-- filter -->
        <div class="card border-0 w-100 rd-5 mb-4">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <p class="fs-18 mb-0">Filter</p>
                </div>
                <form action="{{ route('capaian.index') }}" method="GET" class="mb-3">
                    <div class="row g-2 align-items-end">
                        <div class="col-12 col-md-6">
                            <label for="" class="fs-12 mb-1">Pilih Tahun</label>
                            <select name="tahun" class="fs-12 form-select">
                                <option value="2024" {{ request('tahun') == '2024' ? 'selected' : '' }}>Tahun 2024
                                </option>
                                <option value="2025" {{ request('tahun') == '2025' ? 'selected' : '' }}>Tahun 2025
                                </option>
                            </select>


                        </div>
                        <div class="col-12 col-md-5">
                            <label for="" class="fs-12 mb-1">Pilih Bidang</label>
                            <select name="bidang" class="fs-12 form-select">
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
                    kinerja dan anggaran dana desa ( nama desa a ) tahun 2024. bidang
                    pembangunan
                </p>
                <hr />
                <form action=" {{ route('capaian.index') }} " method="GET" class="mb-3">
                    <div class="d-flex align-items-center gap-2 mb-3">

                        <!-- Input text -->
                        <input type="text" name="query" class="form-control form-control-sm" placeholder="Pencarian..."
                            style="width: 300px" />
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
                                <th>Volume (Target)</th>
                                <th>Volume (Realisasi)</th>
                                <th>Anggaran / Targetan</th>
                                <th>Realisasi Keuangan</th>
                                <th>%Capaian Keluaran</th>
                                <th>%Capaian Keuangan</th>
                                <th>Sisa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $bidang)
                                <!-- BIDANG -->
                                <tr>
                                    <td></td>
                                    <td class="sb">{{ $bidang->kode_rekening }}</td>
                                    <td class="sb">{{ $bidang->nama_bidang }}</td>
                                    <td colspan="4"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
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
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    @foreach ($kegiatan->subkegiatan as $sub)
                                        <!-- SUBKEGIATAN -->
                                        @php
                                            $realisasi = $sub->realisasis->first();
                                            $capaian = $realisasi?->capaian;
                                        @endphp
                                        @php
                                            $persenVolume = $capaian?->persen_capaian_keluaran;
                                            $persenUang = $capaian?->persen_capaian_keuangan;
                                            $warna = '';

                                            if ($persenVolume !== null && $persenUang !== null) {
                                                if ($persenUang >= 100) {
                                                    $warna = 'bg-red-opa text-white';
                                                } elseif ($persenUang >= 70) {
                                                    $warna = 'bg-green-opa text-black';
                                                } elseif ($persenUang >= 40) {
                                                    $warna = 'bg-yellow-opa text-black';
                                                } else {
                                                    $warna = 'bg-red-opa text-white';
                                                }
                                            } else {
                                                $warna = 'bg-red-opa text-white'; // atau kasih 'bg-gray-200' jika kosong
                                            }
                                        @endphp
                                        <tr class="{{ $warna }}">
                                            <td>
                                                <div class="d-flex gap-1 justify-content-end">
                                                    <a href="{{ route('capaian.detail', ['bidang_id' => $bidang->id, 'kegiatan_id' => $kegiatan->id, 'subkegiatan_id' => $sub->id]) }}"
                                                        class="btn btn-sm btn-secondary"><i class="bi bi-eye-fill"></i></a>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="gap-td-table">{{ $bidang->kode_rekening }}</span>
                                                <span class="gap-td-table">{{ $kegiatan->kode_rekening }}</span>
                                                <span>{{ $sub->kode_rekening }}</span>
                                            </td>
                                            <td class="ps-5">{{ $sub->nama_subkegiatan }}</td>


                                            @if ($realisasi)
                                                <td>{{ $capaian?->target?->volume_keluaran ?? '0' }}
                                                    {{ $realisasi->uraian_keluaran }}
                                                </td>
                                                <td>{{ number_format($realisasi->volume_keluaran ?? 0, 0, ',', '.') }}
                                                    {{ $realisasi->uraian_keluaran }}
                                                </td>
                                                <td>Rp.{{ number_format($capaian?->target?->anggaran_target ?? 0, 0, ',', '.') }}
                                                </td>
                                                <td>Rp.{{ number_format($realisasi->realisasi_keuangan ?? 0, 0, ',', '.') }}
                                                </td>
                                                <td
                                                    class="{{ $capaian?->persen_capaian_keluaran === null ? 'text-danger' : '' }}">
                                                    {{ $capaian?->persen_capaian_keluaran ?? 'Tidak Terhitung' }} %
                                                </td>
                                                <td
                                                    class="{{ $capaian?->persen_capaian_keluaran === null ? 'text-danger' : '' }}">
                                                    {{ $capaian?->persen_capaian_keuangan ?? 'Tidak Terhitung' }} %
                                                </td>
                                                <td>{{ number_format($capaian?->sisa ?? 0, 0, ',', '.') }}</td>
                                            @else
                                                <td colspan="4" class="text-muted text-center">Belum ada realisasi</td>
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
@endsection
