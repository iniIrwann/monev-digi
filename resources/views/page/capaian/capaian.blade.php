@extends('layout.app')

@section('title', 'Capaian - Monev Digi Dana Desa')


@section('main')

    <!-- Main content -->
    <div class="main-content ps-3 pe-3 pt-4">
        <div class="d-flex align-items-center mb-2 mb-md-0 pb-4">
            <div class="bg-30x d-flex justify-content-center align-items-center flex-shrink-0">
                <i class="bi bi-award-fill fs-16 text-white"></i>
            </div>
            <p class="fs-14 ms-2 mb-0">Capaian</p>
        </div>
        {{-- <div class="card border-0 w-100 rd-5 fs-12 mb-4">
            <div class="card-body p-3">
                <p>Keterangan : </p>
                <ul class="mb-0 ">
                    <li><strong>&lt; 40%</strong>: <em class="fw-bold text-danger">Sangat Kurang</em> – Capaian sangat
                        rendah, perlu evaluasi
                        menyeluruh dan perbaikan.</li>
                    <li><strong>40% - &lt; 60%</strong>: <em class="fw-bold " style="color: #fd7e14">Kurang</em> – Banyak
                        target tidak tercapai, perlu
                        peningkatan signifikan.</li>
                    <li><strong>60% - &lt; 75%</strong>: <em class="fw-bold text-warning">Cukup</em> – Capaian sedang, masih
                        ada kekurangan yang
                        perlu diperbaiki.</li>
                    <li><strong>75% - &lt; 90%</strong>: <em class="fw-bold text-success">Baik</em> – Sebagian besar target
                        tercapai, pelaksanaan
                        berjalan baik.</li>
                    <li><strong>90% - 100%</strong>: <em class="fw-bold text-primary">Sangat Baik</em> – Capaian optimal,
                        seluruh target
                        kegiatan/keuangan tercapai.</li>
                </ul>
            </div>
        </div>
        <div class="card border-0 w-100 rd-5 mb-4"> --}}
        <div class="card border-0 w-100 rd-5 mb-3">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <p class="fs-18 mb-0">Filter</p>
                </div>
                <form action="{{ route('desa.capaian.index') }}" method="GET" class="mb-3">
                    <div class="row g-2 align-items-end">
                        <div class="col-12 col-md-6">
                            {{-- <label class="fs-12 mb-1">Pilih Tahun</label> --}}
                            <label class="fs-12 mb-1">Pilih tahun</label>
                            <select name="tahun" class="fs-12 form-select">
                                <option value="">{{ __('-- Semua Tahun --') }}</option>
                                <option value="2024" {{ request('tahun') == '2024' ? 'selected' : '' }}>
                                    Tahun 2024
                                </option>
                                <option value="2025" {{ request('tahun') == '2025' ? 'selected' : '' }}>
                                    Tahun 2025
                                </option>
                            </select>
                        </div>
                        <div class="col-12 col-md-5">
                            <label class="fs-12 mb-1">Pilih bidang</label>
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
                        <div class="col-12 col-md-1 d-grid">
                            <button type="submit" class="btn btn-success fs-12 text-white">
                                <i class="bi bi-filter"></i>
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>

        {{-- keterangan --}}
        <div class="card border-0 w-100 rd-5 fs-12 mb-3">
            <div class="card-body p-3">
                <p class="fs-12 sb mb-1">Keterangan : </p>
                <ul class="mb-0 ">
                    <li><strong>&lt; 40%</strong>: <em class="fw-bold text-danger">Sangat Kurang</em> – Capaian sangat
                        rendah, perlu evaluasi
                        menyeluruh dan perbaikan.</li>
                    <li><strong>40% - &lt; 60%</strong>: <em class="fw-bold " style="color: #fd7e14">Kurang</em> – Banyak
                        target tidak tercapai, perlu
                        peningkatan signifikan.</li>
                    <li><strong>60% - &lt; 75%</strong>: <em class="fw-bold text-warning">Cukup</em> – Capaian sedang, masih
                        ada kekurangan yang
                        perlu diperbaiki.</li>
                    <li><strong>75% - &lt; 90%</strong>: <em class="fw-bold text-success">Baik</em> – Sebagian besar target
                        tercapai, pelaksanaan
                        berjalan baik.</li>
                    <li><strong>90% - 100%</strong>: <em class="fw-bold text-primary">Sangat Baik</em> – Capaian optimal,
                        seluruh target
                        kegiatan/keuangan tercapai.</li>
                </ul>
            </div>
        </div>

        <!-- tabel -->
        <div class="card border-0 w-100 rd-5">
            <div class="card-body p-3">
                <form action=" {{ route('desa.capaian.index') }} " method="GET" class="mb-3">
                    <div class="d-flex align-items-center gap-2 mb-3">
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

                <div class="table-responsive">
                    <table class="table align-middle fs-12 tx-gray">
                        <thead class="border-bottom" style="border-color: #999999">
                            <tr class="text-start">
                                {{-- <th class="text-center">Aksi</th>
                                <th>Kode<br />Rekening</th>
                                <th>Rencana Kegiatan</th>
                                <th>Volume (Target)</th>
                                <th>Volume (Realisasi)</th>
                                <th>Anggaran / Targetan</th>
                                <th>Realisasi Keuangan</th>
                                <th>%Capaian<br />Keluaran</th>
                                <th>%Capaian<br />Keuangan</th>
                                <th>Sisa</th> --}}
                                <th class="text-center fs-10">Aksi</th>
                                <th class="fs-10">Kode rekening</th>
                                <th class="fs-10 text-center">Rencana kegiatan</th>
                                <th class="fs-10">Volume (Target)</th>
                                <th class="fs-10">Volume (Realisasi)</th>
                                <th class="fs-10">Anggaran / Targetan</th>
                                <th class="fs-10">Realisasi keuangan</th>
                                <th class="fs-10">%Capaian keluaran</th>
                                <th class="fs-10">%Capaian keuangan</th>
                                <th class="fs-10">Sisa</th>
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
                                            $warnaUang = '';
                                            $warnaVolume = '';

                                            if ($persenUang >= 90) {
                                                $warnaUang = 'text-primary fw-bold';
                                            } elseif ($persenUang >= 75 && $persenUang < 90) {
                                                $warnaUang = 'text-success fw-bold';
                                            } elseif ($persenUang >= 60 && $persenUang < 75) {
                                                $warnaUang = 'text-orange fw-bold';
                                            } elseif ($persenUang >= 40 && $persenUang < 60) {
                                                $warnaUang = 'text-warning fw-bold';
                                            } elseif ($persenUang < 40) {
                                                $warnaUang = 'text-danger fw-bold';
                                            } else {
                                                $warnaUang = 'text-black fw-bold';
                                            }

                                            if ($persenVolume >= 90) {
                                                $warnaVolume = 'text-primary fw-bold';
                                            } elseif ($persenVolume >= 75 && $persenVolume < 90) {
                                                $warnaVolume = 'text-success fw-bold';
                                            } elseif ($persenVolume >= 60 && $persenVolume < 75) {
                                                $warnaVolume = 'text-orange fw-bold';
                                            } elseif ($persenVolume >= 40 && $persenVolume < 60) {
                                                $warnaVolume = 'text-warning fw-bold';
                                            } elseif ($persenVolume < 40) {
                                                $warnaVolume = 'text-danger fw-bold';
                                            } else {
                                                $warnaVolume = 'text-black fw-bold';
                                            }

                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex gap-1 justify-content-end">
                                                    <a href="{{ route('desa.capaian.detail', ['bidang_id' => $bidang->id, 'kegiatan_id' => $kegiatan->id, 'subkegiatan_id' => $sub->id]) }}"
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
                                                <td>{{ number_format($capaian?->target?->volume_keluaran ?? 0, 0, ',', '.') }}
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
                                                    class="{{ $capaian?->persen_capaian_keluaran === null ? 'text-danger' : $warnaVolume }}">
                                                    {{ $capaian?->persen_capaian_keluaran ?? '-' }} %
                                                </td>
                                                <td
                                                    class="{{ $capaian?->persen_capaian_keuangan === null ? 'text-danger' : $warnaUang }}">
                                                    {{ $capaian?->persen_capaian_keuangan ?? '-' }} %
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
    <script>
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(el => new bootstrap.Tooltip(el));
    </script>

@endsection
