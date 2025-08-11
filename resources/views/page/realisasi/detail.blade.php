@extends('layout.app')

@section('title', 'Realisasi - Monev Digi Dana Desa')

@section('main')
    <!-- Main content -->
    <div class="main-content ps-3 pe-3 pt-4">
        <div class="d-flex align-items-center mb-2 mb-md-0 pb-4">
            <div class="bg-30x d-flex justify-content-center align-items-center flex-shrink-0">
                <i class="bi-list-check fs-16 text-white"></i>
            </div>
            <p class="fs-14 ms-2 mb-0">Realisasi</p>
        </div>

        <!-- Tambah subkegiatan -->
        <div class="card border-0 w-100 rounded-3 mb-4">
            <div class="card-body p-3">
                <p class="fs-14 sb mb-3">Detail realisasi subkegiatan</p>
                <hr class="my-1">
                <input type="hidden" value="{{ $bidang->id }}" name="bidang_id" class="form-control">
                <input type="hidden" value="{{ $kegiatan->id }}" name="kegiatan_id" class="form-control">
                <input type="hidden" value="{{ $subKegiatan->id }}" name="subkegiatan_id" class="form-control">
                <!-- Baris Kode Rekening -->
                <div class="row g-2 align-items-center mb-3 ms-1 me-1">
                    <div class="col-12 col-md-4 input-group-sm">
                        <label for="kode_bidang" class="form-label black fs-12">Kode bidang</label>
                        <input type="text" class="form-control form-control-sm w-100"
                            value="{{ $bidang->kode_rekening }}" id="kode_bidang" disabled>
                    </div>
                    <div class="col-12 col-md-4 input-group-sm">
                        <label for="kode_kegiatan" class="form-label black fs-12">Kode kegiatan</label>
                        <input type="text" class="form-control form-control-sm w-100" id="kode_kegiatan"
                            value="{{ $kegiatan->kode_rekening }}" disabled>
                    </div>
                    <div class="col-12 col-md-4 input-group-sm">
                        <label for="kode_subkegiatan" class="form-label black fs-12">Kode subkegiatan</label>
                        <input type="text" value="{{ $subKegiatan->kode_rekening }}"
                            class="form-control form-control-sm w-100" id="kode_subkegiatan" disabled>
                    </div>
                </div>
                <hr class="my-1">

                <div class="row g-1 mb-3">
                    <div class="mb-1">
                        <label class="fs-12 txt-tb-grey">Nama subkegiatan</label>
                        <input type="text" class="form-control form-control-sm rounded-1"
                            value="{{ $subKegiatan->nama_subkegiatan }}" name="nama_subkegiatan" disabled />
                    </div>
                    <!-- Kolom Kiri -->
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label class="fs-12 txt-tb-grey">Uraian keluaran</label>
                            <input type="text" value="{{ $realisasi->uraian_keluaran }}"
                                class="form-control form-control-sm rounded-1" name="uraian_keluaran"
                                placeholder="uraian keluaran" disabled />
                        </div>
                        <div class="mb-2">
                            <label class="fs-12 txt-tb-grey">Volume</label>
                            <input required value="{{ $realisasi->volume_keluaran }}" type="number" name="volume_keluaran"
                                class="form-control form-control-sm rounded-1" placeholder="Kosong / Belum diisi"
                                disabled />
                        </div>
                        <div class="mb-2">
                            <label class="fs-12 txt-tb-grey">Tenaga kerja</label>
                            <input required value="{{ $realisasi->tenaga_kerja }}" type="number"
                                class="form-control form-control-sm rounded-1" name="tenaga_kerja"
                                placeholder="Kosong / Belum diisi" disabled />
                        </div>
                        <div class="mb-2">
                            <label class="fs-12 txt-tb-grey">Upah</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text rounded-1 text-secondary">Rp</span>
                                <input value="{{ $realisasi->upah }}" required type="number" name="upah"
                                    class="form-control rounded-1" placeholder="Kosong / Belum diisi" disabled />
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="fs-12 txt-tb-grey">BLT</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text rounded-1 text-secondary">Rp</span>
                                <input value="{{ $realisasi->BLT }}" required type="number" class="form-control rounded-1"
                                    name="BLT" placeholder="Kosong / Belum diisi" disabled />
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="fs-12 txt-tb-grey">Keterangan</label>
                            <textarea required name="keterangan" class="form-control form-control-sm rounded-1" rows="3"
                                placeholder="Kosong / Belum diisi" disabled>{{ $realisasi->keterangan }}</textarea>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label class="fs-12 txt-tb-grey">Cara pengadaan</label>
                            <input required value="{{ $realisasi->cara_pengadaan }}" type="text"
                                class="form-control form-control-sm rounded-1" name="cara_pengadaan"
                                placeholder="Kosong / Belum diisi" disabled />
                        </div>
                        <div class="mb-2">
                            <label class="fs-12 txt-tb-grey">Tahun</label>
                            <select name="tahun" required class="form-select form-select-sm rounded-1 text-black"
                                disabled>
                                <option value="">Kosong / Belum diisi</option>
                                <option value="2024" {{ $realisasi->tahun == 2024 ? 'selected' : '' }}>2024</option>
                                <option value="2025" {{ $realisasi->tahun == 2025 ? 'selected' : '' }}>2025</option>
                            </select>

                        </div>
                        <div class="mb-2">
                            <label class="fs-12 txt-tb-grey">Realisasi keuangan</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text rounded-1 text-secondary">Rp</span>
                                <input value="{{ $realisasi->realisasi_keuangan }}" required type="number"
                                    name="realisasi_keuangan" class="form-control rounded-1"
                                    placeholder="Kosong / Belum diisi" disabled />
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="fs-12 txt-tb-grey">periode pencarian</label>
                            <input value="{{ $realisasi->durasi }}" required type="number" name="durasi"
                                class="form-control form-control-sm rounded-1" placeholder="Kosong / Belum diisi"
                                disabled />
                        </div>
                        <div class="mb-2">
                            <label class="fs-12 txt-tb-grey">Jumlah KPM</label>
                            <input value="{{ $realisasi->KPM }}" required type="number"
                                class="form-control form-control-sm rounded-1" name="KPM"
                                placeholder="Kosong / Belum diisi" disabled />
                        </div>
                    </div>
                </div>

                <!-- Tombol -->
                <div class="row align-items-center">
                    <div class="col-md-12 d-flex justify-content-end">
                        <a href="{{ route('desa.realisasi.index') }}"
                            class="btn btn-secondary btn-sm fs-12 text-white me-2">
                            <i class="bi bi-arrow-return-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
