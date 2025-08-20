<div class="card border-0 w-100 rounded-3 mb-4">
    <div class="card-body p-3">
        <p class="fs-14 sb mb-3">Detail realisasi subkegiatan</p>
        <hr class="my-1">

        <input type="hidden" value="{{ $bidang->id }}" name="bidang_id" class="form-control">
        <input type="hidden" value="{{ $kegiatan->id }}" name="kegiatan_id" class="form-control">
        <input type="hidden" value="{{ $subKegiatan->id }}" name="subkegiatan_id" class="form-control">

        <!-- Baris Kode Rekening -->
        <div class="row g-2 align-items-center mb-3">
            <div class="col-12 col-md-4 input-group-sm">
                <label for="kode_bidang" class="form-label black fs-12">Kode bidang</label>
                <input type="text" class="form-control form-control-sm w-100" value="{{ $bidang->kode_rekening }}"
                    id="kode_bidang" disabled>
            </div>
            <div class="col-12 col-md-4 input-group-sm">
                <label for="kode_kegiatan" class="form-label black fs-12">Kode kegiatan</label>
                <input type="text" class="form-control form-control-sm w-100" value="{{ $kegiatan->kode_rekening }}"
                    id="kode_kegiatan" disabled>
            </div>
            <div class="col-12 col-md-4 input-group-sm">
                <label for="kode_subkegiatan" class="form-label black fs-12">Kode subkegiatan</label>
                <input type="text" class="form-control form-control-sm w-100"
                    value="{{ $subKegiatan->kode_rekening }}" id="kode_subkegiatan" disabled>
            </div>
        </div>
        <hr class="my-1">

        <div class="row g-2 mb-3">
            <div class="mb-1">
                <label class="fs-12 txt-tb-grey">Nama subkegiatan</label>
                <input type="text" class="form-control form-control-sm rounded-1"
                    value="{{ $subKegiatan->nama_subkegiatan }}" disabled />
            </div>

            <!-- Kolom kiri -->
            <div class="col-md-6">
                <div class="row g-2 mb-2">
                    <div class="col-md-6">
                        <label class="fs-12 txt-tb-grey">Volume</label>
                        <input type="number" class="form-control form-control-sm rounded-1"
                            value="{{ $realisasi->volume_keluaran }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="fs-12 txt-tb-grey">Uraian keluaran</label>
                        <input type="text" class="form-control form-control-sm rounded-1"
                            value="{{ $realisasi->uraian_keluaran }}" readonly>
                    </div>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-md-6">
                        <label class="fs-12 txt-tb-grey">Jumlah KPM</label>
                        <input type="number" class="form-control form-control-sm rounded-1"
                            value="{{ $realisasi->KPM }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="fs-12 txt-tb-grey">Target Sasaran</label>
                        <input type="text" value="{{ $realisasi->sasaran }}"
                            class="form-control form-control-sm rounded-1" name="sasaran" placeholder="sasaran"
                            readonly />
                    </div>
                </div>
                <div class="mb-2">
                    <label class="fs-12 txt-tb-grey">Realisasi Keuangan</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text rounded-1 text-secondary">Rp</span>
                        <input type="number" class="form-control rounded-1"
                            value="{{ $realisasi->realisasi_keuangan }}" readonly>
                    </div>
                </div>

            </div>

            <!-- Kolom kanan -->
            <div class="col-md-6">
                <div class="mb-2">
                    <label class="fs-12 txt-tb-grey">Cara pengadaan</label>
                    <input type="text" class="form-control form-control-sm rounded-1"
                        value="{{ $realisasi->cara_pengadaan }}" readonly>
                </div>
                <div class="mb-2">
                    <label class="fs-12 txt-tb-grey">Tahun</label>
                    <select class="form-select form-select-sm rounded-1 text-secondary" disabled>
                        <option value="2024" {{ $realisasi->tahun == 2024 ? 'selected' : '' }}>2024</option>
                        <option value="2025" {{ $realisasi->tahun == 2025 ? 'selected' : '' }}>2025</option>
                    </select>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-md-6">
                        <label class="fs-12 txt-tb-grey">Tahap Pencairan</label>
                        <input type="number" class="form-control form-control-sm rounded-1"
                            value="{{ $realisasi->tahap }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="fs-12 txt-tb-grey">Periode Pencairan</label>
                        <input type="date" class="form-control form-control-sm rounded-1"
                            value="{{ $realisasi->durasi }}" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
