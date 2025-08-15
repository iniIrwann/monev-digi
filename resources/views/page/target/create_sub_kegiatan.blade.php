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
                <p class="fs-14 sb mb-3">Subkegiatan baru</p>
                <hr class="my-1">
                <form action="{{ route('desa.target.store.subkegiatan') }}" method="POST">
                    @csrf
                    <input type="hidden" value="{{ $bidang->id }}" name="bidang_id" class="form-control">
                    <input type="hidden" value="{{ $kegiatan->id }}" name="kegiatan_id" class="form-control">
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
                            <input type="text" class="form-control form-control-sm w-100" id="kode_subkegiatan" disabled>
                        </div>
                    </div>
                    <hr class="my-1">

                    <div class="row g-1 mb-3">
                        <div class="mb-1">
                            <label class="fs-12 txt-tb-grey">Nama subkegiatan</label>
                            <input type="text" class="form-control form-control-sm rounded-1" name="nama_subkegiatan"
                                placeholder="nama sub kegiatan" required />
                        </div>
                        <!-- Kolom Kiri -->
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="fs-12 txt-tb-grey">Uraian keluaran</label>
                                <input type="text" class="form-control form-control-sm rounded-1" name="uraian_keluaran"
                                    placeholder="uraian keluaran" required />
                            </div>
                            <div class="mb-2">
                                <label class="fs-12 txt-tb-grey">Volume</label>
                                <input type="number" name="volume_keluaran" class="form-control form-control-sm rounded-1"
                                    placeholder="volume" required />
                            </div>
                            <div class="mb-2">
                                <label class="fs-12 txt-tb-grey">Tenaga kerja</label>
                                <input type="number" class="form-control form-control-sm rounded-1" name="tenaga_kerja"
                                    placeholder="jumlah tenaga kerja" required />
                            </div>
                            <div class="mb-2">
                                <label class="fs-12 txt-tb-grey">Upah</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text rounded-1 text-secondary">Rp</span>
                                    <input type="number" name="upah" class="form-control rounded-1" placeholder="upah"
                                        required />
                                </div>
                            </div>
                            <div class="mb-2">
                                <label class="fs-12 txt-tb-grey">BLT</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text rounded-1 text-secondary">Rp</span>
                                    <input type="number" class="form-control rounded-1" name="BLT"
                                        placeholder="masukkan jumlah BLT" required />
                                </div>
                            </div>
                            <div class="mb-2">
                                <label class="fs-12 txt-tb-grey">Keterangan</label>
                                <textarea required name="keterangan" class="form-control form-control-sm rounded-1" rows="3"
                                    placeholder="keterangan"></textarea>
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="fs-12 txt-tb-grey">Cara pengadaan</label>
                                <input type="text" class="form-control form-control-sm rounded-1" name="cara_pengadaan"
                                    required placeholder="cara_pengadaan" />
                            </div>
                            <div class="mb-2">
                                <label class="fs-12 txt-tb-grey">Tahun</label>
                                <select required name="tahun" class="form-select form-select-sm rounded-1 text-black">
                                    <option value="">Pilih tahun</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label class="fs-12 txt-tb-grey">Anggaran</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text rounded-1 text-secondary">Rp</span>
                                    <input type="number" name="anggaran_target" class="form-control rounded-1"
                                        placeholder="anggaran" required />
                                </div>
                            </div>
                            <div class="mb-2">
                                <label class="fs-12 txt-tb-grey">Periode Pencarian</label>
                                <input type="date" name="durasi" class="form-control form-control-sm rounded-1"
                                    placeholder="durasi" required />
                            </div>
                            <div class="mb-2">
                                <label class="fs-12 txt-tb-grey">Jumlah KPM</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text rounded-1 text-secondary">Orang</span>
                                    <input type="number" class="form-control rounded-1" name="KPM"
                                        placeholder="jumlah KPM" required />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol -->
                    <div class="row align-items-center">
                        <div class="col-md-12 d-flex justify-content-end">
                            <a href="{{ route('desa.target.index') }}"
                                class="btn btn-danger btn-sm fs-12 text-white me-2">
                                <i class="bi bi-x-square"></i> Batal
                            </a>

                            <button type="submit" class="btn btn-success btn-sm fs-12 text-white">
                                <i class="bi bi-plus-square me-1"></i> Tambah subkegiatan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
