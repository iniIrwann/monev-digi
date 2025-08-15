<div class="card border-0 w-100 rounded-3">
    <div class="card-body px-3 pt-3">
        <p class="fs-14 sb mb-3">detail realisasi subkegiatan</p>
        <hr class="my-1">

        <input type="hidden" value="{{ $bidang->id }}" name="bidang_id" class="form-control">
        <input type="hidden" value="{{ $kegiatan->id }}" name="kegiatan_id" class="form-control">
        <input type="hidden" value="{{ $subKegiatan->id }}" name="subkegiatan_id" class="form-control">

        <!-- Baris Kode Rekening -->
        <div class="row g-2 align-items-center mb-3 ms-1 me-1">
            <div class="col-12 col-md-4 input-group-sm">
                <label class="form-label black fs-12">kode bidang</label>
                <input type="text" class="form-control form-control-sm" value="{{ $bidang->kode_rekening }}"
                    disabled>
            </div>
            <div class="col-12 col-md-4 input-group-sm">
                <label class="form-label black fs-12">kode kegiatan</label>
                <input type="text" class="form-control form-control-sm" value="{{ $kegiatan->kode_rekening }}"
                    disabled>
            </div>
            <div class="col-12 col-md-4 input-group-sm">
                <label class="form-label black fs-12">kode subkegiatan</label>
                <input type="text" class="form-control form-control-sm" value="{{ $subKegiatan->kode_rekening }}"
                    disabled>
            </div>
        </div>
        <hr class="my-1">

        <div class="row g-3 mb-3">
            <div class="mb-2">
                <label class="fs-12 txt-tb-grey">nama sub kegiatan</label>
                <input type="text" class="form-control form-control-sm" value="{{ $subKegiatan->nama_subkegiatan }}"
                    disabled />
            </div>

            <!-- Kolom kiri -->
            <div class="col-md-6">
                <div class="mb-2">
                    <label class="fs-12 txt-tb-grey">uraian keluaran</label>
                    <input type="text" class="form-control form-control-sm" value="{{ $realisasi->uraian_keluaran }}"
                        readonly>
                </div>
                <div class="mb-2">
                    <label class="fs-12 txt-tb-grey">volume</label>
                    <input type="number" class="form-control form-control-sm" value="{{ $realisasi->volume_keluaran }}"
                        readonly>
                </div>
                <div class="mb-2">
                    <label class="fs-12 txt-tb-grey">tenaga kerja</label>
                    <input type="number" class="form-control form-control-sm" value="{{ $realisasi->tenaga_kerja }}"
                        readonly>
                </div>
                <div class="mb-2">
                    <label class="fs-12 txt-tb-grey">upah</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" value="{{ $realisasi->upah }}" readonly>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="fs-12 txt-tb-grey">BLT</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" value="{{ $realisasi->BLT }}" readonly>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="fs-12 txt-tb-grey">keterangan</label>
                    <textarea class="form-control form-control-sm" rows="3" readonly>{{ $realisasi->keterangan }}</textarea>
                </div>
            </div>

            <!-- Kolom kanan -->
            <div class="col-md-6">
                <div class="mb-2">
                    <label class="fs-12 txt-tb-grey">cara pengadaan</label>
                    <input type="text" class="form-control form-control-sm" value="{{ $realisasi->cara_pengadaan }}"
                        readonly>
                </div>
                <div class="mb-2">
                    <label class="fs-12 txt-tb-grey">tahun</label>
                    <select class="form-select form-select-sm" disabled>
                        <option value="2024" {{ $realisasi->tahun == 2024 ? 'selected' : '' }}>2024</option>
                        <option value="2025" {{ $realisasi->tahun == 2025 ? 'selected' : '' }}>2025</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label class="fs-12 txt-tb-grey">tahap pencairan</label>
                    <input type="number" class="form-control form-control-sm" value="{{ $realisasi->tahap }}"
                        readonly>
                </div>
                <div class="mb-2">
                    <label class="fs-12 txt-tb-grey">realisasi keuangan</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" value="{{ $realisasi->realisasi_keuangan }}"
                            readonly>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="fs-12 txt-tb-grey">periode pencarian</label>
                    <input type="number" class="form-control form-control-sm" value="{{ $realisasi->durasi }}"
                        readonly>
                </div>
                <div class="mb-2">
                    <label class="fs-12 txt-tb-grey">Jumlah KPM</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Orang</span>
                        <input type="number" class="form-control" value="{{ $realisasi->KPM }}" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
