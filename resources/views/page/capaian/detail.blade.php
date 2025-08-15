@extends('layout.app')

@section('title', 'Detail Realisasi - Monev Digi Dana Desa')

@section('main')
    <!-- Main content -->
    <div class="main-content ps-3 pe-3 pt-4">
        <div class="d-flex align-items-center mb-2 mb-md-0 pb-4">
            <div class="bg-30x d-flex justify-content-center align-items-center flex-shrink-0">
                <i class="bi bi-award-fill fs-16 text-white"></i>
            </div>
            <p class="fs-14 ms-2 mb-0">Detail Capaian {{ $subkegiatan->nama_subkegiatan }}</p>
        </div>

        <!-- Detail View -->
        <div class="card border-0 w-100 rd-5 mb-4">
            <div class="card-body p-3">
                <p class="fs-14 sb mb-3">Detail Realisasi Subkegiatan</p>
                <hr class="my-1">

                <!-- Kode Rekening -->
                <div class="row g-2 align-items-center mb-3">
                    <div class="col-12 col-md-4">
                        <label class="fs-12 mb-1">Kode Bidang</label>
                        <input type="text" class="form-control fs-12" value="{{ $bidang->kode_rekening }}" readonly>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="fs-12 mb-1">Kode Kegiatan</label>
                        <input type="text" class="form-control fs-12" value="{{ $kegiatan->kode_rekening }}" readonly>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="fs-12 mb-1">Kode Subkegiatan</label>
                        <input type="text" class="form-control fs-12" value="{{ $subkegiatan->kode_rekening }}" readonly>
                    </div>
                </div>
                <hr class="my-1">

                <div class="mb-3">
                    <label class="fs-12 mb-1">Nama Subkegiatan</label>
                    <input type="text" class="form-control fs-12" value="{{ $subkegiatan->nama_subkegiatan }}" readonly>
                </div>

                <div class="row mb-3">
                    <span class="fw-bold fs-12">Capaian</span>
                    <div class="col-md-4 mb-2">
                        <label class="fs-12 mb-1">% Capaian Keluaran</label>
                        <input type="text" class="form-control fs-12"
                            value="{{ $capaian?->persen_capaian_keluaran ? number_format($capaian->persen_capaian_keluaran, 2) . ' %' : '-' }}"
                            readonly>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="fs-12 mb-1">% Capaian Keuangan</label>
                        <input type="text" class="form-control fs-12"
                            value="{{ $capaian?->persen_capaian_keuangan ? number_format($capaian->persen_capaian_keuangan, 2) . ' %' : '-' }}"
                            readonly>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="fs-12 mb-1">Sisa</label>
                        <input type="text" class="form-control fs-12"
                            value="{{ number_format($capaian?->sisa ?? 0, 0, ',', '.') }}" readonly>
                    </div>
                </div>

                <!-- Target and Realisasi Columns -->
                <div class="row g-3 mb-3">
                    <!-- Kolom Kiri: Target -->
                    <div class="col-md-6">
                        <span class="fw-bold">Target</span>
                        <div class="mb-2">
                            <label class="fs-12 mb-1">Uraian Keluaran</label>
                            <input type="text" class="form-control fs-12" value="{{ $target?->uraian_keluaran ?? '-' }}"
                                readonly>
                        </div>
                        <div class="mb-2">
                            <label class="fs-12 mb-1">Volume</label>
                            <input type="text" class="form-control fs-12" value="{{ $target?->volume_keluaran ?? '-' }}"
                                readonly>
                        </div>
                        <div class="mb-2">
                            <label class="fs-12 mb-1">Cara Pengadaan</label>
                            <input type="text" class="form-control fs-12" value="{{ $target?->cara_pengadaan ?? '-' }}"
                                readonly>
                        </div>
                        <div class="mb-2">
                            <label class="fs-12 mb-1">Tahun</label>
                            <input type="text" class="form-control fs-12" value="{{ $target?->tahun ?? '-' }}" readonly>
                        </div>
                        <div class="mb-2">
                            <label class="fs-12 mb-1">Anggaran Target</label>
                            <div class="input-group">
                                <span class="input-group-text fs-12">Rp</span>
                                <input type="text" class="form-control fs-12"
                                    value="{{ number_format($target?->anggaran_target ?? 0, 0, ',', '.') }}" readonly>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="fs-12 mb-1">Periode Pencairan</label>
                            <input type="text" class="form-control fs-12" value="{{ $target?->durasi ?? '-' }}"
                                readonly>
                        </div>
                        <div class="mb-2">
                            <label class="fs-12 txt-tb-grey">Jumlah KPM</label>
                            <div class="input-group input-group-sm">
                                <span class="fs-12 input-group-text rounded-1 text-secondary">Orang</span>
                                <input type="number" value="{{ $target?->KPM }}" class="form-control fs-12 rounded-1"
                                    name="KPM" placeholder="jumlah KPM" disabled />
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="fs-12 mb-1">Tenaga Kerja</label>
                            <input type="text" class="form-control fs-12" value="{{ $target?->tenaga_kerja ?? '-' }}"
                                readonly>
                        </div>
                        <div class="mb-2">
                            <label class="fs-12 mb-1">Upah</label>
                            <div class="input-group">
                                <span class="input-group-text fs-12">Rp</span>
                                <input type="text" class="form-control fs-12"
                                    value="{{ number_format($target?->upah ?? 0, 0, ',', '.') }}" readonly>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="fs-12 mb-1">BLT</label>
                            <div class="input-group">
                                <span class="input-group-text fs-12">Rp</span>
                                <input type="text" class="form-control fs-12"
                                    value="{{ number_format($target?->BLT ?? 0, 0, ',', '.') }}" readonly>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="fs-12 mb-1">Keterangan</label>
                            <textarea class="form-control fs-12" rows="3" readonly>{{ $target?->keterangan ?? '-' }}</textarea>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Realisasi Tahap 1 dan Tahap 2 -->
                    <div class="col-md-6">
                        <span class="fw-bold">Realisasi</span>
                        <div class="row">

                            <!-- Tahap 1 -->
                            <div class="col-md-6 mb-3">
                                <div class="mb-2">
                                    <label class="fs-12 mb-1"> <span class="fw-bold fs-12">Tahap 1</span>
                                        Uraian Keluaran</label>
                                    <input type="text" class="form-control fs-12"
                                        value="{{ $tahap1Data?->uraian_keluaran ?? '-' }}" readonly>
                                </div>
                                <div class="mb-2">
                                    <label class="fs-12 mb-1">Volume</label>
                                    <input type="text" class="form-control fs-12"
                                        value="{{ $tahap1Data?->volume_keluaran ?? '-' }}" readonly>
                                </div>
                                <div class="mb-2">
                                    <label class="fs-12 mb-1">Cara Pengadaan</label>
                                    <input type="text" class="form-control fs-12"
                                        value="{{ $tahap1Data?->cara_pengadaan ?? '-' }}" readonly>
                                </div>
                                <div class="mb-2">
                                    <label class="fs-12 mb-1">Tahun</label>
                                    <input type="text" class="form-control fs-12"
                                        value="{{ $tahap1Data?->tahun ?? '-' }}" readonly>
                                </div>
                                <div class="mb-2">
                                    <label class="fs-12 mb-1">Realisasi Keuangan</label>
                                    <div class="input-group">
                                        <span class="input-group-text fs-12">Rp</span>
                                        <input type="text" class="form-control fs-12"
                                            value="{{ number_format($tahap1Data?->realisasi_keuangan ?? 0, 0, ',', '.') }}"
                                            readonly>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label class="fs-12 mb-1">Periode Pencairan</label>
                                    <input type="text" class="form-control fs-12"
                                        value="{{ $tahap1Data?->durasi ?? '-' }}" readonly>
                                </div>
                                <div class="mb-2">
                                    <label class="fs-12 txt-tb-grey">Jumlah KPM</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text rounded-1 text-secondary">Orang</span>
                                        <input type="number" value="{{ $tahap1Data?->KPM }}"
                                            class="form-control rounded-1" name="KPM" placeholder="jumlah KPM"
                                            disabled />
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label class="fs-12 mb-1">Tenaga Kerja</label>
                                    <input type="text" class="form-control fs-12"
                                        value="{{ $tahap1Data?->tenaga_kerja ?? '-' }}" readonly>
                                </div>
                                <div class="mb-2">
                                    <label class="fs-12 mb-1">Upah</label>
                                    <div class="input-group">
                                        <span class="input-group-text fs-12">Rp</span>
                                        <input type="text" class="form-control fs-12"
                                            value="{{ number_format($tahap1Data?->upah ?? 0, 0, ',', '.') }}" readonly>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label class="fs-12 mb-1">BLT</label>
                                    <div class="input-group">
                                        <span class="input-group-text fs-12">Rp</span>
                                        <input type="text" class="form-control fs-12"
                                            value="{{ number_format($tahap1Data?->BLT ?? 0, 0, ',', '.') }}" readonly>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label class="fs-12 mb-1">Keterangan</label>
                                    <textarea class="form-control fs-12" rows="3" readonly>{{ $tahap1Data?->keterangan ?? '-' }}</textarea>
                                </div>
                            </div>

                            <!-- Tahap 2 -->
                            <div class="col-md-6 mb-3">
                                <div class="mb-2">
                                    <label class="fs-12 mb-1"> <span class="fw-bold fs-12">Tahap 2</span>
                                        Uraian Keluaran</label>
                                    <input type="text" class="form-control fs-12"
                                        value="{{ $tahap2Data?->uraian_keluaran ?? '-' }}" readonly>
                                </div>
                                <div class="mb-2">
                                    <label class="fs-12 mb-1">Volume</label>
                                    <input type="text" class="form-control fs-12"
                                        value="{{ $tahap2Data?->volume_keluaran ?? '-' }}" readonly>
                                </div>
                                <div class="mb-2">
                                    <label class="fs-12 mb-1">Cara Pengadaan</label>
                                    <input type="text" class="form-control fs-12"
                                        value="{{ $tahap2Data?->cara_pengadaan ?? '-' }}" readonly>
                                </div>
                                <div class="mb-2">
                                    <label class="fs-12 mb-1">Tahun</label>
                                    <input type="text" class="form-control fs-12"
                                        value="{{ $tahap2Data?->tahun ?? '-' }}" readonly>
                                </div>
                                <div class="mb-2">
                                    <label class="fs-12 mb-1">Realisasi Keuangan</label>
                                    <div class="input-group">
                                        <span class="input-group-text fs-12">Rp</span>
                                        <input type="text" class="form-control fs-12"
                                            value="{{ number_format($tahap2Data?->realisasi_keuangan ?? 0, 0, ',', '.') }}"
                                            readonly>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label class="fs-12 mb-1">Periode Pencairan</label>
                                    <input type="text" class="form-control fs-12"
                                        value="{{ $tahap2Data?->durasi ?? '-' }}" readonly>
                                </div>
                                <div class="mb-2">
                                    <label class="fs-12 txt-tb-grey">Jumlah KPM</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text rounded-1 text-secondary">Orang</span>
                                        <input type="number" value="{{ $tahap2Data?->KPM }}"
                                            class="form-control rounded-1" name="KPM" placeholder="jumlah KPM"
                                            disabled />
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label class="fs-12 mb-1">Tenaga Kerja</label>
                                    <input type="text" class="form-control fs-12"
                                        value="{{ $tahap2Data?->tenaga_kerja ?? '-' }}" readonly>
                                </div>
                                <div class="mb-2">
                                    <label class="fs-12 mb-1">Upah</label>
                                    <div class="input-group">
                                        <span class="input-group-text fs-12">Rp</span>
                                        <input type="text" class="form-control fs-12"
                                            value="{{ number_format($tahap2Data?->upah ?? 0, 0, ',', '.') }}" readonly>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label class="fs-12 mb-1">BLT</label>
                                    <div class="input-group">
                                        <span class="input-group-text fs-12">Rp</span>
                                        <input type="text" class="form-control fs-12"
                                            value="{{ number_format($tahap2Data?->BLT ?? 0, 0, ',', '.') }}" readonly>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label class="fs-12 mb-1">Keterangan</label>
                                    <textarea class="form-control fs-12" rows="3" readonly>{{ $tahap2Data?->keterangan ?? '-' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-end">
                    <a href="{{ route('desa.capaian.index') }}" class="btn btn-secondary btn-sm fs-12 text-white me-2">
                        <i class="bi bi-arrow-return-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
