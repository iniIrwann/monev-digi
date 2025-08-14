@extends('layout.app')

@section('title', 'Verifikasi - Monev Digi Dana Desa')

@section('main')
    <div class="main-content ps-3 pe-3 pt-4">
        <div class="d-flex align-items-center mb-2 mb-md-0 pb-4">
            <div class="bg-30x d-flex justify-content-center align-items-center flex-shrink-0">
                <i class="bi bi-patch-check-fill fs-16 text-white"></i>
            </div>
            <p class="fs-14 ms-2 mb-0">Verifikasi</p>
        </div>

        <!-- Peringatan -->
        <div class="card border-0 w-100 rd-5 mb-3">
            <div class="card-body pt-3 px-3 pb-1">
                <p class="fs-12">Halaman ini berisi Aktifitas Verifikator untuk memverifikasi dengan melakukan pencatatan,
                    memberikan tindak lanjut dan merekomendasikan hasil pemeriksaan dan hasil evaluasi realisasi capaian
                    kinerja dan keuangan untuk Dana Desa di setiap Desa di Kecamatan Soreang.</p>
            </div>
        </div>

        <!-- Filter -->
        <div class="card border-0 w-100 rd-5 mb-4">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <p class="fs-18 mb-0">Filter</p>
                </div>

                <form action="{{ route('kecamatan.verifikasi.index') }}" method="GET" class="mb-3">
                    <div class="row g-2 align-items-end">
                        <div class="col-12 col-md-3">
                            <label class="fs-12 mb-1">Pilih Kecamatan</label>
                            <select name="kecamatan" class="fs-12 form-select" disabled>
                                <option value="">Kecamatan Soreang</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-2">
                            <label class="fs-12 mb-1">Pilih Periode Capaian</label>
                            <select name="tahun" class="fs-12 form-select">
                                <option value="">{{ __('-- Semua Tahun --') }}</option>
                                <option value="2024" {{ request('tahun') == '2024' ? 'selected' : '' }}>Tahun 2024
                                </option>
                                <option value="2025" {{ request('tahun') == '2025' ? 'selected' : '' }}>Tahun 2025
                                </option>
                            </select>
                        </div>
                        <div class="col-12 col-md-2">
                            <label class="fs-12 mb-1">Pilih Tahap</label>
                            <select name="tahap" class="fs-12 form-select">
                                <option value="1" {{ request('tahap', '1') == '1' ? 'selected' : '' }}>Tahap 1</option>
                                <option value="2" {{ request('tahap') == '2' ? 'selected' : '' }}>Tahap 2</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
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
                        <div class="col-12 col-md-2">
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
                        <div class="col-12 ms-auto col-md-1 d-grid">
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
                    <a href="{{ route('kecamatan.verifikasi.index', array_merge(request()->query(), ['tahap' => '1'])) }}"
                        class="tab-link {{ request('tahap', '1') == '1' ? 'active' : '' }}">Tahap 1</a>
                    <a href="{{ route('kecamatan.verifikasi.index', array_merge(request()->query(), ['tahap' => '2'])) }}"
                        class="tab-link {{ request('tahap') == '2' ? 'active' : '' }}">Tahap 2</a>
                </div>
                <p class="my-2 sb">Tabel Capaian Realisasi Kinerja dan Keuangan Dana Desa</p>
                <p class="fs-12 my-2">
                    @if ($desa)
                        Kecamatan: Kecamatan Soreang <br>
                        Periode Capaian: <span class="fw-bold">{{ $tahun ?? '(semua tahun)' }}</span> <br>
                        Tahap: <span class="fw-bold">{{ $tahap == 1 ? 'Tahap 1' : 'Tahap 2' }}</span> <br>
                        Desa: <span class="fw-bold">{{ $desa->desa }}</span>
                    @else
                        Kecamatan: Kecamatan Soreang <br>
                        Periode Capaian: <span class="fw-bold">{{ $tahun ?? '(semua tahun)' }}</span> <br>
                        Tahap: <span class="fw-bold">{{ $tahap == 1 ? 'Tahap 1' : 'Tahap 2' }}</span>
                    @endif
                </p>
                <hr />

                <form action="{{ route('kecamatan.verifikasi.index') }}" method="GET" class="mb-3">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <input type="hidden" name="desa" value="{{ request('desa') }}">
                        <input type="hidden" name="tahun" value="{{ request('tahun') }}">
                        <input type="hidden" name="bidang" value="{{ request('bidang') }}">
                        <input type="hidden" name="tahap" value="{{ request('tahap') }}">
                        <div class="row g-3 mb-2">
                            <div class="col-auto">
                                <input type="text" name="query" class="form-control form-control-sm w-100"
                                    placeholder="Pencarian..." value="{{ request('query') }}" />
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-success fs-12 text-white">
                                    <i class="bi bi-search me-1"></i> Cari
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                @if (session('success'))
                    <div class="alert alert-success fs-12 mb-3">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table align-middle fs-12 tx-gray">
                        <thead class="border-bottom" style="border-color: #999999">
                            <tr class="text-start">
                                <th class="text-center">Aksi</th>
                                <th>Kode Rekening</th>
                                <th>Rencana Kegiatan</th>
                                <th>Catatan / Tindak Lanjut / Rekomendasi</th>
                                <th>Sasaran / Penerima Manfaat</th>
                                <th>Target Volume</th>
                                <th>Realisasi Volume</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $bidang)
                                <tr>
                                    <td></td>
                                    <td class="sb">{{ $bidang->kode_rekening }}</td>
                                    <td class="sb">{{ $bidang->nama_bidang }}</td>
                                    <td colspan="4"></td>
                                </tr>
                                @foreach ($bidang->kegiatan as $kegiatan)
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
                                        <tr>
                                            <td>
                                                <div class="d-flex gap-1 justify-content-end">
                                                    @if ($sub->realisasi && $sub->realisasi->id && $sub->realisasi->user_id)
                                                        <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                                            data-bs-target="#ModalTambahVerifikasi"
                                                            data-realisasi-id="{{ $sub->realisasi->id }}">
                                                            <i class="bi bi-clipboard-fill"></i>
                                                        </button>
                                                    @endif

                                                    <a href="{{ route('kecamatan.realisasi.create.sub', ['bidang_id' => $bidang->id, 'kegiatan_id' => $kegiatan->id, 'subkegiatan_id' => $sub->id, 'tahap' => $tahap]) }}"
                                                        class="btn btn-sm btn-secondary" title="Lihat Detail">
                                                        <i class="bi bi-eye-fill text-white"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="gap-td-table">{{ $bidang->kode_rekening }}</span>
                                                <span class="gap-td-table">{{ $kegiatan->kode_rekening }}</span>
                                                <span>{{ $sub->kode_rekening }}</span>
                                            </td>
                                            <td class="ps-5">{{ $sub->nama_subkegiatan }}</td>
                                            <td>
                                                @if ($sub->realisasi && $sub->realisasi->verifikasi)
                                                    <strong>Catatan:</strong>
                                                    {{ Str::limit($sub->realisasi->verifikasi->catatan, 50) }}<br>
                                                    <strong>Tindak Lanjut:</strong>
                                                    {{ Str::limit($sub->realisasi->verifikasi->tindak_lanjut, 50) }}<br>
                                                    <strong>Rekomendasi:</strong>
                                                    {{ Str::limit($sub->realisasi->verifikasi->rekomendasi, 50) }}
                                                @else
                                                    <span class="text-muted">Belum ada verifikasi</span>
                                                @endif
                                            </td>
                                            <td>{{ $sub->realisasi?->KPM ? $sub->realisasi->KPM . ' orang' : '-' }}</td>
                                            <td>{{ $sub->target?->volume_keluaran ?? '-' }}</td>
                                            <td>
                                                @if ($sub->realisasi)
                                                    {{ $sub->realisasi->volume_keluaran ?? '-' }}
                                                @else
                                                    <a class="text-decoration-none"
                                                        href="{{ route('kecamatan.realisasi.create.sub', ['bidang_id' => $bidang->id, 'kegiatan_id' => $kegiatan->id, 'subkegiatan_id' => $sub->id, 'tahap' => $tahap]) }}">
                                                        <span class="text-danger">Silahkan isi realisasi</span>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end mt-3">
                        {{ $data->links() }}
                    </div>
                </div>

                <!-- Modal Tambah Verifikasi -->
                <div class="modal fade" id="ModalTambahVerifikasi" tabindex="-1"
                    aria-labelledby="ModalTambahVerifikasi" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content rd-5">
                            <div class="modal-body p-3">
                                <p class="modal-title fs-14 sb grey" id="ModalTambahVerifikasi">
                                    Catatan / Tindak Lanjut / Rekomendasi
                                </p>
                                <hr class="mb-3">

                                <form action="{{ route('kecamatan.verifikasi.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="realisasi_id" id="inputRealisasiID">


                                    <div class="mb-2">
                                        <label for="inputCatatan" class="fs-12 mb-1">Catatan</label>
                                        <textarea class="form-control fs-12" id="inputCatatan" name="catatan" rows="3" placeholder="Masukkan catatan"
                                            required></textarea>
                                        @error('catatan')
                                            <div class="text-danger fs-12">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-2">
                                        <label for="inputTindakLanjut" class="fs-12 mb-1">Tindak Lanjut</label>
                                        <textarea class="form-control fs-12" id="inputTindakLanjut" name="tindak_lanjut" rows="3"
                                            placeholder="Masukkan tindak lanjut" required></textarea>
                                        @error('tindak_lanjut')
                                            <div class="text-danger fs-12">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="inputRekomendasi" class="fs-12 mb-1">Rekomendasi</label>
                                        <textarea class="form-control fs-12" id="inputRekomendasi" name="rekomendasi" rows="3"
                                            placeholder="Masukkan rekomendasi" required></textarea>
                                        @error('rekomendasi')
                                            <div class="text-danger fs-12">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="button" class="btn btn-outline-danger btn-sm fs-12"
                                            data-bs-dismiss="modal">
                                            <i class="bi bi-x-square"></i> Batal
                                        </button>
                                        <button type="submit" class="btn btn-success btn-sm fs-12 text-white">
                                            <i class="bi bi-save"></i> Simpan
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

    @section('script')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = document.getElementById('ModalTambahVerifikasi');
                if (!modal) return; // <-- mencegah error jika elemen belum ada

                modal.addEventListener('show.bs.modal', function(event) {
                    var button = event.relatedTarget;
                    var realisasiId = button?.getAttribute('data-realisasi-id') || '';
                    var input = document.getElementById('inputRealisasiID');
                    if (input) input.value = realisasiId;
                });
            });
        </script>
    @endsection
@endsection
