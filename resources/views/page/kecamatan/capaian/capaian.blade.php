@extends('layout.app')

@section('title', 'Capaian Kecamatan - Monev Digi Dana Desa')

@section('main')
    <!-- Main content -->
    <div class="main-content ps-3 pe-3 pt-4">
        <div class="d-flex align-items-center mb-2 mb-md-0 pb-4">
            <div class="bg-30x d-flex justify-content-center align-items-center flex-shrink-0">
                <i class="bi bi-award-fill fs-16 text-white"></i>
            </div>
            <p class="fs-14 ms-2 mb-0">Capaian</p>
        </div>

        <!-- Filter -->
        <div class="card border-0 w-100 rd-5 mb-4">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <p class="fs-18 mb-0">Filter</p>
                </div>
                <form action="{{ route('kecamatan.capaian.index') }}" method="GET" class="mb-3">
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
                        <div class="col-12 col-md-4">
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
                            <button type="submit" class="btn btn-success btn-sm fs-12 text-white">
                                <i class="bi bi-filter"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Keterangan -->
        <div class="card border-0 w-100 rd-5 fs-12 mb-4">
            <div class="card-body p-3">
                <p>Keterangan :</p>
                <ul class="mb-0">
                    <li><strong>&lt; 40%</strong>: <em class="fw-bold text-danger">Sangat Kurang</em> – Capaian sangat
                        rendah, perlu evaluasi menyeluruh dan perbaikan.</li>
                    <li><strong>40% - &lt; 60%</strong>: <em class="fw-bold" style="color: #fd7e14">Kurang</em> – Banyak
                        target tidak tercapai, perlu peningkatan signifikan.</li>
                    <li><strong>60% - &lt; 75%</strong>: <em class="fw-bold text-warning">Cukup</em> – Capaian sedang, masih
                        ada kekurangan yang perlu diperbaiki.</li>
                    <li><strong>75% - &lt; 90%</strong>: <em class="fw-bold text-success">Baik</em> – Sebagian besar target
                        tercapai, pelaksanaan berjalan baik.</li>
                    <li><strong>90% - 100%</strong>: <em class="fw-bold text-primary">Sangat Baik</em> – Capaian optimal,
                        seluruh target kegiatan/keuangan tercapai.</li>
                </ul>
            </div>
        </div>

        <!-- Tabel -->
        <div class="card border-0 w-100 rd-5">
            <div class="card-body p-3">
                <p class="fs-12 my-2">
                    @if ($desa)
                        Kinerja dan anggaran dana <span class="fw-bold">{{ $desa->desa }}</span>,
                        <span class="fw-bold">{{ $tahun ?? '(semua tahun)' }}</span>,
                        <span class="fw-bold">{{ $bidang->nama_bidang ?? '(semua bidang)' }}</span>
                    @else
                        Menampilkan Semua Capaian Desa.
                    @endif
                </p>
                <hr />
                <form action="{{ route('kecamatan.capaian.index') }}" method="GET" class="mb-3">
                    <div class="row g-3 mb-2">
                        <input type="hidden" name="desa" value="{{ request('desa') }}">
                        <input type="hidden" name="tahun" value="{{ request('tahun') }}">
                        <input type="hidden" name="bidang" value="{{ request('bidang') }}">
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
                                <th>% Capaian Keluaran</th>
                                <th>% Capaian Keuangan</th>
                                <th>Sisa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $bidang)
                                <!-- BIDANG -->
                                <tr>
                                    <td></td>
                                    <td class="sb">{{ $bidang->kode_rekening }}</td>
                                    <td class="sb">{{ $bidang->nama_bidang }}</td>
                                    <td colspan="7"></td>
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
                                        <td colspan="7"></td>
                                    </tr>

                                    @foreach ($kegiatan->subkegiatan as $sub)
                                        <!-- SUBKEGIATAN -->
                                        @php
                                            $persenVolume = $sub->target?->capaian?->persen_capaian_keluaran;
                                            $persenUang = $sub->target?->capaian?->persen_capaian_keuangan;
                                            $warnaUang = '';
                                            $warnaVolume = '';

                                            if ($persenUang >= 90) {
                                                $warnaUang = 'text-primary fw-bold';
                                            } elseif ($persenUang >= 75 && $persenUang < 90) {
                                                $warnaUang = 'text-success fw-bold';
                                            } elseif ($persenUang >= 60 && $persenUang < 75) {
                                                $warnaUang = 'text-warning fw-bold';
                                            } elseif ($persenUang >= 40 && $persenUang < 60) {
                                                $warnaUang = 'text-orange fw-bold';
                                            } elseif ($persenUang < 40 && $persenUang !== null) {
                                                $warnaUang = 'text-danger fw-bold';
                                            } else {
                                                $warnaUang = 'text-black fw-bold';
                                            }

                                            if ($persenVolume >= 90) {
                                                $warnaVolume = 'text-primary fw-bold';
                                            } elseif ($persenVolume >= 75 && $persenVolume < 90) {
                                                $warnaVolume = 'text-success fw-bold';
                                            } elseif ($persenVolume >= 60 && $persenVolume < 75) {
                                                $warnaVolume = 'text-warning fw-bold';
                                            } elseif ($persenVolume >= 40 && $persenVolume < 60) {
                                                $warnaVolume = 'text-orange fw-bold';
                                            } elseif ($persenVolume < 40 && $persenVolume !== null) {
                                                $warnaVolume = 'text-danger fw-bold';
                                            } else {
                                                $warnaVolume = 'text-black fw-bold';
                                            }
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex gap-1 justify-content-end">
                                                    <a href="{{ route('kecamatan.capaian.detail', ['bidang_id' => $bidang->id, 'kegiatan_id' => $kegiatan->id, 'subkegiatan_id' => $sub->id]) }}"
                                                        class="btn btn-sm btn-secondary" title="Detail"><i
                                                            class="bi bi-eye-fill"></i></a>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="gap-td-table">{{ $bidang->kode_rekening }}</span>
                                                <span class="gap-td-table">{{ $kegiatan->kode_rekening }}</span>
                                                <span>{{ $sub->kode_rekening }}</span>
                                            </td>
                                            <td class="ps-5">{{ $sub->nama_subkegiatan }}</td>
                                            @if ($sub->realisasis->isNotEmpty())
                                                <td>{{ $sub->target?->volume_keluaran ?? '-' }}</td>
                                                <td>{{ number_format($sub->total_volume_keluaran ?? 0, 0, ',', '.') }}</td>
                                                <td>Rp.{{ number_format($sub->target?->anggaran_target ?? 0, 0, ',', '.') }}
                                                </td>
                                                <td>Rp.{{ number_format($sub->total_realisasi_keuangan ?? 0, 0, ',', '.') }}
                                                </td>
                                                <td class="{{ $persenVolume === null ? 'text-danger' : $warnaVolume }}">
                                                    {{ $persenVolume !== null ? number_format($persenVolume, 2) : '-' }} %
                                                </td>
                                                <td class="{{ $persenUang === null ? 'text-danger' : $warnaUang }}">
                                                    {{ $persenUang !== null ? number_format($persenUang, 2) : '-' }} %
                                                </td>
                                                <td>{{ number_format($sub->capaian?->sisa ?? 0, 0, ',', '.') }}</td>
                                            @else
                                                <td colspan="7" class="text-muted text-center">Belum ada realisasi</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="10" class="text-muted text-center">Tidak ada data</td>
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

    <script>
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(el => new bootstrap.Tooltip(el));
    </script>
@endsection
