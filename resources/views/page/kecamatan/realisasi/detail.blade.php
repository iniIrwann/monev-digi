@extends('layout.app')

@section('main')
    <div class="main-content ps-3 pe-3 pt-4">
        <div class="d-flex align-items-center mb-2 mb-md-0 pb-4">
            <div class="bg-30x d-flex justify-content-center align-items-center flex-shrink-0">
                <i class="bi-list-check fs-16 text-white"></i>
            </div>
            <p class="fs-14 ms-2 mb-0">Realisasi</p>
        </div>

        <div class="card border-0 w-100 rounded-3">
            @if (request()->input('tahap') === 'all')
                <div class="row">
                    @foreach ($realisasi as $data)
                        <div class="col-md-6">
                            @include('page.kecamatan.realisasi.detail_partial', [
                                'realisasi' => $data,
                                'bidang' => $bidang,
                                'kegiatan' => $kegiatan,
                                'subKegiatan' => $subKegiatan,
                            ])
                        </div>
                    @endforeach

                </div>
            @else
                @include('page.kecamatan.realisasi.detail_partial', [
                    'realisasi' => $realisasi,
                    'bidang' => $bidang,
                    'kegiatan' => $kegiatan,
                    'subKegiatan' => $subKegiatan,
                ])
            @endif
            <!-- Tombol -->
            <div class="row align-items-center p-3">
                <div class="col-md-12 d-flex justify-content-end">
                    <a href="{{ route('kecamatan.realisasi.index') }}"
                        class="btn btn-secondary btn-sm fs-12 text-white me-2">
                        <i class="bi bi-arrow-return-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>


    </div>
@endsection
