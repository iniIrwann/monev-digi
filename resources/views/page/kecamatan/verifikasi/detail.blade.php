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
                @endif
            @else
                @include('page.kecamatan.verifikasi.detail_partial', [
                    'realisasi' => $realisasi,
                    'bidang' => $bidang,
                    'kegiatan' => $kegiatan,
                    'subKegiatan' => $subKegiatan,
                ])
                <div class="mx-3">

                    <div class="mb-2">
                        <label class="fs-12 txt-tb-grey">Catatan</label>
                        <div class="input-group input-group-sm">
                            <textarea class="form-control" readonly>{{ $realisasi->verifikasi->catatan }}</textarea>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="fs-12 txt-tb-grey">Tindak Lanjut</label>
                        <div class="input-group input-group-sm">
                            <textarea class="form-control" readonly>{{ $realisasi->verifikasi->tindak_lanjut }}</textarea>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="fs-12 txt-tb-grey">Rekomendasi</label>
                        <div class="input-group input-group-sm">
                            <textarea class="form-control" readonly>{{ $realisasi->verifikasi->rekomendasi }}</textarea>
                        </div>
                    </div>
                </div>
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


    </div>
@endsection
