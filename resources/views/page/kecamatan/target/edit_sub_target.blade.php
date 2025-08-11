@extends('layout.app')

@section('title', 'Target - Monev Digi Dana Desa')


@section('main')
    <!-- Main content -->
    <div class="main-content ps-3 pe-3 pt-4">
        <div class="d-flex align-items-center mb-2 mb-md-0 pb-4">
            <div class="bg-30x d-flex justify-content-center align-items-center flex-shrink-0">
                <i class="bi bi-bullseye fs-16 text-white"></i>
            </div>
            <p class="fs-14 ms-2 mb-0">Target</p>
        </div>

        <!-- Tambah subkegiatan -->
        <div class="card border-0 w-100 rounded-3 mb-4">
            <div class="card-body p-3">
                <p class="fs-14 sb mb-3">edit target sub kegiatan</p>
                <hr class="my-1">
                <form action="{{ route('kecamatan.target.update.sub') }}" method="POST">
                    @csrf
                    <input type="hidden" value="{{ $bidang->id }}" name="bidang_id" class="form-control">
                    <input type="hidden" value="{{ $kegiatan->id }}" name="kegiatan_id" class="form-control">
                    <input type="hidden" value="{{ $subKegiatan->id }}" name="subkegiatan_id" class="form-control">
                    <!-- Baris Kode Rekening -->
                    <div class="row g-2 align-items-center mb-3 ms-1 me-1">
                        <div class="col-12 col-md-4 input-group-sm">
                            <label for="kode_bidang" class="form-label black fs-12">kode bidang</label>
                            <input type="text" class="form-control form-control-sm w-100"
                                value="{{ $bidang->kode_rekening }}" id="kode_bidang" placeholder="A" disabled>
                        </div>
                        <div class="col-12 col-md-4 input-group-sm">
                            <label for="kode_kegiatan" class="form-label black fs-12">kode kegiatan</label>
                            <input type="text" class="form-control form-control-sm w-100" id="kode_kegiatan"
                                value="{{ $kegiatan->kode_rekening }}" placeholder="1" disabled>
                        </div>
                        <div class="col-12 col-md-4 input-group-sm">
                            <label for="kode_subkegiatan" class="form-label black fs-12">kode subkegiatan</label>
                            <input type="text" value="{{ $subKegiatan->kode_rekening }}"
                                class="form-control form-control-sm w-100" id="kode_subkegiatan" placeholder="" disabled>
                        </div>
                    </div>
                    <hr class="my-1">

                    <div class="row g-3 mb-3">
                        <div class="mb-2">
                            <label class="fs-12 txt-tb-grey">nama sub kegiatan</label>
                            <input type="text" class="form-control form-control-sm rounded-1"
                                value="{{ $subKegiatan->nama_subkegiatan }}" name="nama_subkegiatan"
                                placeholder="nama sub kegiatan" />
                        </div>
                        <!-- Kolom Kiri -->
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="fs-12 txt-tb-grey">uraian keluaran</label>
                                <input type="text" value="{{ $target->uraian_keluaran }}"
                                    class="form-control form-control-sm rounded-1" name="uraian_keluaran"
                                    placeholder="uraian keluaran" />
                            </div>
                            <div class="mb-2">
                                <label class="fs-12 txt-tb-grey">volume</label>
                                <input required value="{{ $target->volume_keluaran }}" type="number" name="volume_keluaran"
                                    class="form-control form-control-sm rounded-1" placeholder="volume" />
                            </div>
                            <div class="mb-2">
                                <label class="fs-12 txt-tb-grey">tenaga kerja</label>
                                <input required value="{{ $target->tenaga_kerja }}" type="number"
                                    class="form-control form-control-sm rounded-1" name="tenaga_kerja"
                                    placeholder="jumlah tenaga kerja" />
                            </div>
                            <div class="mb-2">
                                <label class="fs-12 txt-tb-grey">upah</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text rounded-1 text-secondary">Rp</span>
                                    <input value="{{ $target->upah }}" required type="number" name="upah"
                                        class="form-control rounded-1" placeholder="upah" />
                                </div>
                            </div>
                            <div class="mb-2">
                                <label class="fs-12 txt-tb-grey">BLT</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text rounded-1 text-secondary">Rp</span>
                                    <input value="{{ $target->BLT }}" required type="number"
                                        class="form-control rounded-1" name="BLT" placeholder="masukkan jumlah BLT" />
                                </div>
                            </div>
                            <div class="mb-2">
                                <label class="fs-12 txt-tb-grey">keterangan</label>
                                <textarea required name="keterangan" class="form-control form-control-sm rounded-1" rows="3"
                                    placeholder="keterangan">{{ $target->keterangan }}</textarea>
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="fs-12 txt-tb-grey">cara pengadaan</label>
                                <input required value="{{ $target->cara_pengadaan }}" type="text"
                                    class="form-control form-control-sm rounded-1" name="cara_pengadaan"
                                    placeholder="cara_pengadaan" />
                            </div>
                            <div class="mb-2">
                                <label class="fs-12 txt-tb-grey">tahun</label>
                                <select name="tahun" required class="form-select form-select-sm rounded-1 ">
                                    <option value="">pilih tahun</option>
                                    <option value="2024" {{ $target->tahun == 2024 ? 'selected' : '' }}>2024</option>
                                    <option value="2025" {{ $target->tahun == 2025 ? 'selected' : '' }}>2025</option>
                                </select>

                            </div>
                            <div class="mb-2">
                                <label class="fs-12 txt-tb-grey">target keuangan</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text rounded-1 text-secondary">Rp</span>
                                    <input value="{{ $target->anggaran_target }}" required type="number"
                                        name="anggaran_target" class="form-control rounded-1"
                                        placeholder="target keuangan" />
                                </div>
                            </div>
                            <div class="mb-2">
                                <label class="fs-12 txt-tb-grey">durasi</label>
                                <input value="{{ $target->durasi }}" required type="number" name="durasi"
                                    class="form-control form-control-sm rounded-1" placeholder="durasi" />
                            </div>
                            <div class="mb-2">
                                <label class="fs-12 txt-tb-grey">jumlah KPM</label>
                                <input value="{{ $target->KPM }}" required type="number"
                                    class="form-control form-control-sm rounded-1" name="KPM"
                                    placeholder="jumlah KPM" />
                            </div>
                        </div>
                    </div>

                    <!-- Tombol -->
                    <div class="row align-items-center">
                        <div class="col-md-12 d-flex justify-content-end">
                            <a href="{{ route('kecamatan.target.index') }}" class="btn btn-danger btn-sm fs-12 text-white me-2">
                                <i class="bi bi-x-square"></i> batal
                            </a>
                            <button type="submit" class="btn btn-warning btn-sm fs-12 text-white">
                                <i class="bi bi-pencil-fill"></i> edit subkegiatan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
