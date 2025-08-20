@extends('layout.app')

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
                <p class="fs-14 sb mb-3">Update realisasi subkegiatan</p>
                <hr class="my-1">
                <form action="{{ route('kecamatan.realisasi.store.sub') }}" method="POST">
                    @csrf
                    <input type="hidden" value="{{ $bidang->id }}" name="bidang_id" class="form-control">
                    <input type="hidden" value="{{ $kegiatan->id }}" name="kegiatan_id" class="form-control">
                    <input type="hidden" value="{{ $subKegiatan->id }}" name="subkegiatan_id" class="form-control">
                    <input type="hidden" value="{{ $tahap }}" name="tahap" class="form-control">
                    <input type="hidden" value="{{ $realisasi?->tahun }}" name="tahun" class="form-control">

                    <!-- Baris Kode Rekening -->
                    <div class="row g-2 align-items-center mb-3 ">
                        <div class="col-12 col-md-4 input-group-sm">
                            <label for="kode_bidang" class="form-label black fs-12">Kode bidang</label>
                            <input type="text" class="form-control form-control-sm w-100"
                                value="{{ $bidang->kode_rekening }}" id="kode_bidang" placeholder="A" disabled>
                        </div>
                        <div class="col-12 col-md-4 input-group-sm">
                            <label for="kode_kegiatan" class="form-label black fs-12">Kode kegiatan</label>
                            <input type="text" class="form-control form-control-sm w-100" id="kode_kegiatan"
                                value="{{ $kegiatan->kode_rekening }}" placeholder="1" disabled>
                        </div>
                        <div class="col-12 col-md-4 input-group-sm">
                            <label for="kode_subkegiatan" class="form-label black fs-12">Kode subkegiatan</label>
                            <input type="text" value="{{ $subKegiatan->kode_rekening }}"
                                class="form-control form-control-sm w-100" id="kode_subkegiatan" placeholder="" disabled>
                        </div>
                    </div>
                    <hr class="my-1">

                    <div class="row g-2 mb-3">
                        <div class="mb-1">
                            <label class="fs-12 txt-tb-grey">Nama subkegiatan</label>
                            <input type="text" class="form-control form-control-sm rounded-1"
                                value="{{ $subKegiatan->nama_subkegiatan }}" name="nama_subkegiatan"
                                placeholder="nama sub kegiatan" disabled />
                        </div>
                        <!-- Kolom Kiri -->
                        <div class="col-md-6">
                            <div class="row g-2 mb-2">
                                <div class="col-md-6">
                                    <label class="fs-12 txt-tb-grey">Volume</label>
                                    <input value="{{ old('volume_keluaran', $realisasi?->volume_keluaran) }}"
                                        type="number" name="volume_keluaran" class="form-control form-control-sm rounded-1"
                                        placeholder="ex: 100, 200, 300" required />
                                    @error('volume_keluaran')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="fs-12 txt-tb-grey">Uraian keluaran</label>
                                    <input readonly type="text"
                                        value="{{ old('uraian_keluaran', $realisasi?->uraian_keluaran) }}"
                                        class="form-control form-control-sm rounded-1" name="uraian_keluaran"
                                        placeholder="ex: meter, bulan" />
                                    @error('uraian_keluaran')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            {{-- <div class="mb-2">
                                <label class="fs-12 txt-tb-grey">Tenaga kerja</label>
                                <input value="{{ old('tenaga_kerja', $realisasi?->tenaga_kerja) }}" type="number"
                                    class="form-control form-control-sm rounded-1" name="tenaga_kerja"
                                    placeholder="jumlah tenaga kerja" />
                                @error('tenaga_kerja')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label class="fs-12 txt-tb-grey">Upah</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text rounded-1 text-secondary">Rp</span>
                                    <input value="{{ old('upah', $realisasi?->upah) }}" type="number" name="upah"
                                        class="form-control rounded-1" placeholder="upah" />
                                </div>
                                @error('upah')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label class="fs-12 txt-tb-grey">BLT</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text rounded-1 text-secondary">Rp</span>
                                    <input value="{{ old('BLT', $realisasi?->BLT) }}" type="number" name="BLT"
                                        class="form-control rounded-1" placeholder="masukkan jumlah BLT" />
                                </div>
                                @error('BLT')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div> --}}
                            <div class="row g-2 mb-2">
                                <div class="col-md-6">
                                    <label class="fs-12 txt-tb-grey">Jumlah KPM</label>
                                    <input type="number" value="{{ old('KPM', $realisasi?->KPM) }}"
                                        class="form-control form-control-sm rounded-1" name="KPM"
                                        placeholder="ex: 100, 200, 300" required />
                                    @error('KPM')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="fs-12 txt-tb-grey">Sasaran/Target Sasaran</label>
                                    <input type="text" value="{{ old('sasaran', $realisasi?->sasaran) }}"
                                        class="form-control form-control-sm rounded-1" name="sasaran" required
                                        placeholder="ex: Keluarga, Orang " />
                                    @error('sasaran')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-2">
                                <label class="fs-12 txt-tb-grey">Realisasi Keuangan</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text rounded-1 text-secondary">Rp</span>
                                    <input value="{{ old('realisasi_keuangan', $realisasi?->realisasi_keuangan) }}"
                                        type="number" name="realisasi_keuangan" class="form-control rounded-1"
                                        placeholder="Realisasi Keuangan" required />
                                </div>
                                @error('realisasi_keuangan')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="fs-12 txt-tb-grey">Cara pengadaan</label>
                                <input readonly value="{{ old('cara_pengadaan', $realisasi?->cara_pengadaan) }}"
                                    type="text" class="form-control form-control-sm rounded-1"
                                    name="cara pengadaannasolole" placeholder="cara pengadaan" required />
                                @error('cara_pengadaan')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label class="fs-12 txt-tb-grey">Tahun</label>
                                <select name="tahun" disabled
                                    class="form-select form-select-sm rounded-1 text-secondary" required>
                                    <option value="">Pilih tahun</option>
                                    <option value="2024"
                                        {{ old('tahun', $realisasi?->tahun) == 2024 ? 'selected' : '' }}>2024</option>
                                    <option value="2025"
                                        {{ old('tahun', $realisasi?->tahun) == 2025 ? 'selected' : '' }}>2025</option>
                                </select>
                                @error('tahun')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="row g-2 mb-2">
                                <div class="col-md-6">
                                    <label class="fs-12 txt-tb-grey">Tahap Pencairan</label>
                                    <input value="{{ old('tahap', $realisasi?->tahap) }}" type="numeric" name="tahap"
                                        class="form-control form-control-sm rounded-1" placeholder="durasi" disabled />
                                    @error('tahap')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6"> <label class="fs-12 txt-tb-grey">Periode Pencairan</label>
                                    <input value="{{ old('durasi', $realisasi?->durasi) }}" type="date"
                                        name="durasi" class="form-control form-control-sm rounded-1"
                                        placeholder="durasi" />
                                    @error('durasi')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol -->
                    <div class="row align-items-center">
                        <div class="col-md-12 d-flex justify-content-end">
                            <a href="{{ route('kecamatan.realisasi.index') }}"
                                class="btn btn-danger btn-sm fs-12 text-white me-2">
                                <i class="bi bi-x-square"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-warning btn-sm fs-12 text-white">
                                <i class="bi bi-pencil-fill"></i> Edit realisasi subkegiatan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
