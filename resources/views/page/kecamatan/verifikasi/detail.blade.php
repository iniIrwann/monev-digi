@extends('layout.app')

@section('main')
    <div class="main-content ps-3 pe-3 pt-4">
        <div class="d-flex align-items-center mb-2 mb-md-0 pb-4">
            <div class="bg-30x d-flex justify-content-center align-items-center flex-shrink-0">
                <i class="bi-list-check fs-16 text-white"></i>
            </div>
            <p class="fs-14 ms-2 mb-0">Verifikasi</p>
        </div>

        <div class="card border-0 w-100 rounded-3">
            @if (request()->input('tahap') === 'all')
                <div class="row">
                    @foreach ($realisasi as $data)
                        <div class="col-md-6">
                            @include('page.kecamatan.verifikasi.detail_partial', [
                                'realisasi' => $data,
                                'bidang' => $bidang,
                                'kegiatan' => $kegiatan,
                                'subKegiatan' => $subKegiatan,
                            ])
                        </div>
                    @endforeach
                </div>

                {{-- Catatan, Tindak Lanjut, Rekomendasi di luar loop --}}
                @php
                    $firstVerifikasi = $realisasi->first()->verifikasi ?? null;
                @endphp
                @if ($firstVerifikasi)
                    <div class="mx-3">
                        <div class="mb-2">
                            <label class="fs-12 txt-tb-grey">Catatan</label>
                            <div class="input-group input-group-sm">
                                <textarea class="form-control" readonly>{{ $firstVerifikasi->catatan }}</textarea>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="fs-12 txt-tb-grey">Tindak Lanjut</label>
                            <div class="input-group input-group-sm">
                                <textarea class="form-control" readonly>{{ $firstVerifikasi->tindak_lanjut }}</textarea>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="fs-12 txt-tb-grey">Rekomendasi</label>
                            <div class="input-group input-group-sm">
                                <textarea class="form-control" readonly>{{ $firstVerifikasi->rekomendasi }}</textarea>
                            </div>
                        </div>
                    </div>
                @else
                    @php
                        $firstRealisasi = $realisasi->first() ?? null;
                    @endphp
                    <div class="mx-3 my-1">
                        <button class="btn btn-sm btn-success fw-bold fs-12 py-1 px-2" data-bs-toggle="modal"
                            data-bs-target="#ModalTambahVerifikasi" data-realisasi-id="{{ $firstRealisasi->id }}">
                            Tambah Catatan
                        </button>
                        <span class="text-white fw-bold rounded-pill w-50 fs-12 py-2 bg-warning px-4"><i
                                class="bi bi-exclamation-circle-fill me-2"></i>Menunggu Verifikasi oleh Kecamatan</span>
                    </div>
                @endif
            @else
                @include('page.kecamatan.verifikasi.detail_partial', [
                    'realisasi' => $realisasi,
                    'bidang' => $bidang,
                    'kegiatan' => $kegiatan,
                    'subKegiatan' => $subKegiatan,
                ])
                @php
                    $firstVerifikasi = $realisasi->verifikasi ?? null;
                @endphp
                @if ($firstVerifikasi)
                    <div class="mx-3">
                        <div class="mb-2">
                            <label class="fs-12 txt-tb-grey">Catatan</label>
                            <div class="input-group input-group-sm">
                                <textarea class="form-control" readonly>{{ $realisasi->verifikasi?->catatan }}</textarea>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="fs-12 txt-tb-grey">Tindak Lanjut</label>
                            <div class="input-group input-group-sm">
                                <textarea class="form-control" readonly>{{ $realisasi->verifikasi?->tindak_lanjut }}</textarea>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="fs-12 txt-tb-grey">Rekomendasi</label>
                            <div class="input-group input-group-sm">
                                <textarea class="form-control" readonly>{{ $realisasi->verifikasi?->rekomendasi }}</textarea>
                            </div>
                        </div>
                    </div>
                @else
                    @php
                        $firstRealisasi = $realisasi ?? null;
                    @endphp
                    <div class="mx-3 my-1">
                        <button class="btn btn-sm btn-success fw-bold fs-12 py-1 px-2" data-bs-toggle="modal"
                            data-bs-target="#ModalTambahVerifikasi" data-realisasi-id="{{ $firstRealisasi->id }}">
                            Tambah Catatan
                        </button>
                        <span class="text-white fw-bold rounded-pill w-50 fs-12 py-2 bg-warning px-4"><i
                                class="bi bi-exclamation-circle-fill me-2"></i>Menunggu Verifikasi oleh Kecamatan</span>
                    </div>
                @endif
            @endif

            <!-- Tombol -->
            <div class="row align-items-center p-3">
                <div class="col-md-12 d-flex justify-content-end">
                    <a href="{{ route('kecamatan.verifikasi.index') }}"
                        class="btn btn-secondary btn-sm fs-12 text-white me-2">
                        <i class="bi bi-arrow-return-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        {{-- Modal Tambah Catatan --}}
        <div class="modal fade" id="ModalTambahVerifikasi" tabindex="-1" aria-labelledby="ModalTambahVerifikasi"
            aria-hidden="true">
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
                                <button type="button" class="btn btn-danger btn-sm fs-12" data-bs-dismiss="modal">
                                    <i class="bi bi-x-square"></i> Batal
                                </button>
                                <button type="submit" class="btn btn-success btn-sm fs-12 text-white">
                                    <i class="bi bi-plus-square me-1"></i> Tambah
                                </button>
                            </div>
                        </form>
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
