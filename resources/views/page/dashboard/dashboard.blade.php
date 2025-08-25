@extends('layout.app')

@section('title', 'Dashboard - Monev Digi Dana Desa')


@section('main')
    <div class="main-content ps-3 pe-3 pt-4">
        <div class="row g-3">
            <!-- Kartu total realisasi terpenuhi -->
            <div class="col-md-3">
                <div class="p-3 rounded text-white shadow-sm"
                    style="background: linear-gradient(to right, #ff378b, #ff758c);">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bi bi-cash fs-3"></i>
                        </div>
                        <div>
                            {{-- Realisasi Keuangan --}}
                            <div class="fs-14 text-capitalize">
                                Target Keuangan
                                <br class="d-none d-md-block"> Tahun 2025
                            </div>
                            <div class="fs-18 sb">Rp.{{ number_format($totalAnggaran, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kartu Jumlah target -->
            <div class="col-md-3">
                <div class="p-3 rounded text-white shadow-sm"
                    style="background: linear-gradient(to right, #4facfe, #00f2fe);">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bi bi-cash-stack fs-3"></i>
                        </div>
                        <div>
                            <div class="fs-14 text-capitalize">Realisasi Keuangan <br class="d-none d-md-block"> Tahun 2025
                            </div>
                            <div class="fs-18 sb">Rp.{{ number_format($totalRealisasi, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kartu total capaian yang sudah -->
            <div class="col-md-3">
                <div class="p-3 rounded text-white shadow-sm"
                    style="background: linear-gradient(to right, #43e97b, #38f9d7);">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bi bi-award-fill fs-3"></i>
                        </div>
                        <div>
                            <div class="fs-14 text-capitalize">(%) Capaian Kinerja Fisik Tahun 2025</div>
                            <div class="fs-18 sb">{{ number_format($hasil['rata_fisik'], 0, ',', '.') }} %</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 rounded text-white shadow-sm"
                    style="background: linear-gradient(to right, #ff9b0f, #dff938);">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bi bi-award-fill fs-3"></i>
                        </div>
                        <div>
                            <div class="fs-14 text-capitalize">(%) Capaian Kinerja Fisik Tahun 2025</div>
                            <div class="fs-18 sb">{{ $hasil['rata_keuangan'] }} %</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CHART WRAPPER -->
            <div class="col-12">
                <div class="row g-3 align-items-stretch">
                    <!-- Donut Chart -->
                    <div class="col-12 col-md-6">
                        <div class="p-4 bg-white rounded shadow-sm d-flex flex-column flex-md-row align-items-center h-100">
                            <!-- Kiri: Judul dan chart -->
                            <div class="w-100 w-md-60 mb-3 mb-md-0">
                                <p class="mb-3 fs-14 sb">Rekap Capaian Keuangan (Rp)</p>
                                <canvas id="donutChart" class="mx-auto mx-md-0" style="max-height: 200px;"></canvas>
                            </div>
                            <!-- Kanan: Legend -->
                            <div class="w-100 w-md-40 ms-md-4">
                                <div class="d-flex align-items-center mb-2 justify-content-center justify-content-md-start">
                                    <div class="me-2" style="width: 12px; height: 12px; background-color: #00C49F;"></div>
                                    <span class="text-muted fs-12">Total Target Keuangan</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                                    <div class="me-2" style="width: 12px; height: 12px; background-color: #FF6384;"></div>
                                    <span class="text-muted fs-12">Total Realisasi Keuangan</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Line Chart -->
                    <div class="col-12 col-md-6">
                        <div class="p-4 bg-white rounded shadow-sm d-flex flex-column justify-content-between h-100">
                            <p class="mb-3 fs-12 sb">
                                Rata-rata (%) Capaian Kinerja Fisik dan Keuangan Kegiatan Dana Desa Tahun 2025
                            </p>
                            <canvas id="capaianChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="p-4 bg-white rounded shadow-sm h-100">
                    <!-- Judul -->
                    <p class="mb-2 fs-14 sb text-capitalize">capaian keluaran dan capaian keuangan</p>

                    <!-- Tabel capaian keluaran -->
                    {{-- <div class="row">
                        <!-- Tabel Capaian Keluaran -->
                        <div class="col-md-6">
                            <p class="mb-2 fs-12 sb">Tabel Capaian Keluaran</p>
                            <div class="table-responsive mb-2">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="table-info text-center">
                                            <th class="fs-12 sb">%</th>
                                            <th class="fs-12 sb">Jumlah Capaian Keluaran</th>
                                            <th class="fs-12 sb">Kategori</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="text-center">
                                            <td class="fs-12">&lt; 40</td>
                                            <td class="fs-12">{{ $kategoriKeluaran['sangat_kurang'] }}</td>
                                            <td class="fs-12 text-muted">sangat kurang</td>
                                        </tr>
                                        <tr class="text-center">
                                            <td class="fs-12">40 - 59</td>
                                            <td class="fs-12">{{ $kategoriKeluaran['kurang'] }}</td>
                                            <td class="fs-12 text-muted">kurang</td>
                                        </tr>
                                        <tr class="text-center">
                                            <td class="fs-12">60 - 74</td>
                                            <td class="fs-12">{{ $kategoriKeluaran['cukup'] }}</td>
                                            <td class="fs-12 text-muted">cukup</td>
                                        </tr>
                                        <tr class="text-center">
                                            <td class="fs-12">75 - 89</td>
                                            <td class="fs-12">{{ $kategoriKeluaran['baik'] }}</td>
                                            <td class="fs-12 text-muted">baik</td>
                                        </tr>
                                        <tr class="text-center">
                                            <td class="fs-12">90 - 100</td>
                                            <td class="fs-12">{{ $kategoriKeluaran['sangat_baik'] }}</td>
                                            <td class="fs-12 text-muted">sangat baik</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tabel Capaian Keuangan -->
                        <div class="col-md-6">
                            <p class="mb-2 fs-12 sb">Tabel Capaian Keuangan</p>
                            <div class="table-responsive mb-2">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="table-info text-center">
                                            <th class="fs-12 sb">%</th>
                                            <th class="fs-12 sb">Jumlah Capaian Keuangan</th>
                                            <th class="fs-12 sb">Kategori</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="text-center">
                                            <td class="fs-12">&lt; 40</td>
                                            <td class="fs-12">{{ $kategoriKeuangan['sangat_rendah'] }}</td>
                                            <td class="fs-12 text-muted">sangat rendah</td>
                                        </tr>
                                        <tr class="text-center">
                                            <td class="fs-12">40 - 59</td>
                                            <td class="fs-12">{{ $kategoriKeuangan['kurang'] }}</td>
                                            <td class="fs-12 text-muted">kurang</td>
                                        </tr>
                                        <tr class="text-center">
                                            <td class="fs-12">60 - 74</td>
                                            <td class="fs-12">{{ $kategoriKeuangan['cukup'] }}</td>
                                            <td class="fs-12 text-muted">cukup</td>
                                        </tr>
                                        <tr class="text-center">
                                            <td class="fs-12">75 - 89</td>
                                            <td class="fs-12">{{ $kategoriKeuangan['baik'] }}</td>
                                            <td class="fs-12 text-muted">baik</td>
                                        </tr>
                                        <tr class="text-center">
                                            <td class="fs-12">90 - 100</td>
                                            <td class="fs-12">{{ $kategoriKeuangan['sangat_baik'] }}</td>
                                            <td class="fs-12 text-muted">sangat baik</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> --}}
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2 fs-12 sb">Target Capaian Keluaran (Output) Tahun 2025</p>
                            <div class="table-responsive mb-2">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="table-info text-center">
                                            <th class="fs-12 sb">Kode Bidang</th>
                                            <th class="fs-12 sb">Jumlah Kegiatan</th>
                                            <th class="fs-12 sb">Target Anggaran</th>
                                            <th class="fs-12 sb">Realisasi Keuangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $item)
                                            <tr class="text-center">
                                                <td class="fs-12 sb">{{ $item->kode_bidang }}</td>
                                                <td class="fs-12 ">{{ $item->jumlah_target_per_bidang }}</td>
                                                <td class="fs-12 ">
                                                    {{ number_format($item->jumlah_target_anggaran_per_bidang, 0, ',', '.') }}
                                                </td>
                                                <td class="fs-12 ">
                                                    {{ number_format($item->jumlah_realisasi_keuangan_per_bidang, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr class="text-center">
                                            <td class="fs-12 sb">Total :</td>
                                            <td class="fs-12 ">{{ $total_target }}</td>
                                            <td class="fs-12">{{ number_format($totalAnggaran, 0, ',', '.') }}</td>
                                            <td class="fs-12">{{ number_format($totalRealisasi, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2 fs-12 sb"> Tabel (%) Capaian Kinerja & Keuangan Tahun 2025</p>
                            <div class="table-responsive mb-2">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="table-info text-center">
                                            {{-- <th class="fs-12 sb">%</th> --}}
                                            {{-- (Output) --}}
                                            <th class="fs-12 sb">(%) Rata-rata Capaian Kinerja </th>
                                            <th class="fs-12 sb">(%) Rata-rata Capaian Keuangan </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="text-center">
                                            @php
                                                $warnaVolume = '';
                                                $warnaUang = '';

                                                // Warna untuk rata_fisik
                                                if ($hasil['rata_fisik'] === null || $hasil['rata_fisik'] == 0) {
                                                    $warnaVolume = ''; // tidak ada style
                                                } elseif ($hasil['rata_fisik'] >= 90) {
                                                    $warnaVolume = 'bg-primary text-white';
                                                } elseif ($hasil['rata_fisik'] >= 75) {
                                                    $warnaVolume = 'bg-success text-white';
                                                } elseif ($hasil['rata_fisik'] >= 60) {
                                                    $warnaVolume = 'bg-warning text-dark';
                                                } elseif ($hasil['rata_fisik'] >= 40) {
                                                    $warnaVolume = 'bg-orange text-white';
                                                } else {
                                                    $warnaVolume = 'bg-danger text-white';
                                                }

                                                // Warna untuk rata_keuangan
                                                if ($hasil['rata_keuangan'] === null || $hasil['rata_keuangan'] == 0) {
                                                    $warnaUang = ''; // tidak ada style
                                                } elseif ($hasil['rata_keuangan'] >= 90) {
                                                    $warnaUang = 'bg-primary text-white';
                                                } elseif ($hasil['rata_keuangan'] >= 75) {
                                                    $warnaUang = 'bg-success text-white';
                                                } elseif ($hasil['rata_keuangan'] >= 60) {
                                                    $warnaUang = 'bg-warning text-dark';
                                                } elseif ($hasil['rata_keuangan'] >= 40) {
                                                    $warnaUang = 'bg-orange text-white';
                                                } else {
                                                    $warnaUang = 'bg-danger text-white';
                                                }

                                            @endphp

                                        <tr class="text-center">
                                            <td class="fs-12 {{ $warnaVolume }}">
                                                {{ $hasil['rata_fisik'] }}%
                                            </td>

                                            <td class="fs-12 {{ $warnaUang }}">
                                                {{ $hasil['rata_keuangan'] }}%
                                            </td>
                                        </tr>

                                        <tr class="text-center">
                                            <td class="fs-12">
                                                @php
                                                    if ($hasil['rata_fisik'] == null) {
                                                        echo 'tidak ada data';
                                                    } elseif ($hasil['rata_fisik'] < 40) {
                                                        echo 'sangat kurang';
                                                    } elseif ($hasil['rata_fisik'] < 60) {
                                                        echo 'kurang';
                                                    } elseif ($hasil['rata_fisik'] < 75) {
                                                        echo 'cukup';
                                                    } elseif ($hasil['rata_fisik'] < 90) {
                                                        echo 'baik';
                                                    } else {
                                                        echo 'sangat baik';
                                                    }
                                                @endphp
                                            </td>

                                            <td class="fs-12">
                                                @php
                                                    if (
                                                        $hasil['rata_keuangan'] === null ||
                                                        $hasil['rata_keuangan'] == 0
                                                    ) {
                                                        echo 'tidak ada data';
                                                    } elseif ($hasil['rata_keuangan'] < 40) {
                                                        echo 'sangat kurang';
                                                    } elseif ($hasil['rata_keuangan'] < 60) {
                                                        echo 'kurang';
                                                    } elseif ($hasil['rata_keuangan'] < 75) {
                                                        echo 'cukup';
                                                    } elseif ($hasil['rata_keuangan'] < 90) {
                                                        echo 'baik';
                                                    } else {
                                                        echo 'sangat baik';
                                                    }
                                                @endphp
                                            </td>
                                        </tr>


                                        {{-- <tr class="text-center">
                                            {{-- <td class="fs-12">60 - 74</td>
                                            <td class="fs-12">{{ $kategoriKeuangan['cukup'] }}</td>
                                            <td class="fs-12 text-muted">cukup</td>
                                        </tr>
                                        <tr class="text-center">
                                            {{-- <td class="fs-12">75 - 89</td>
                                            <td class="fs-12">{{ $kategoriKeuangan['baik'] }}</td>
                                            <td class="fs-12 text-muted">baik</td>
                                        </tr>
                                        <tr class="text-center">
                                            {{-- <td class="fs-12">90 - 100</td>
                                            <td class="fs-12">{{ $kategoriKeuangan['sangat_baik'] }}</td>
                                            <td class="fs-12 text-muted">sangat baik</td>
                                        </tr> --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <p class="fs-12 sb mb-1">keterangan:</p>
                    <ul class="mb-0 fs-12">
                        <li><strong>&lt; 40%</strong>: <em class="fw-bold text-danger">Sangat Kurang</em> – Capaian
                            sangat
                            rendah, perlu evaluasi
                            menyeluruh dan perbaikan.</li>
                        <li><strong>40% - &lt; 60%</strong>: <em class="fw-bold " style="color: #fd7e14">Kurang</em> –
                            Banyak
                            target tidak tercapai, perlu
                            peningkatan signifikan.</li>
                        <li><strong>60% - &lt; 75%</strong>: <em class="fw-bold text-warning">Cukup</em> – Capaian
                            sedang, masih
                            ada kekurangan yang
                            perlu diperbaiki.</li>
                        <li><strong>75% - &lt; 90%</strong>: <em class="fw-bold text-success">Baik</em> – Sebagian
                            besar target
                            tercapai, pelaksanaan
                            berjalan baik.</li>
                        <li><strong>90% - 100%</strong>: <em class="fw-bold text-primary">Sangat Baik</em> –
                            Capaian optimal,
                            seluruh target
                            kegiatan/keuangan tercapai.</li>
                    </ul>
                </div>

            </div>
        </div>

        <!-- Chart.js CDN -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

        <script>
            const ctx = document.getElementById('capaianChart').getContext('2d');

            const capaianChart = new Chart(ctx, {
                type: 'bar', // bisa line atau bar
                data: {
                    labels: ['Capaian Kinerja Fisik', 'Capaian Keuangan'],
                    datasets: [{
                        label: 'Rata-rata (%)',
                        data: [
                            {{ $hasil['rata_fisik'] }},
                            {{ $hasil['rata_keuangan'] }}
                        ],
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.6)', // biru
                            'rgba(255, 99, 132, 0.6)' // merah
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 99, 132, 1)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100 // karena persentase
                        }
                    }
                }
            });
        </script>
        <script>
            const totalAnggaran = {{ $totalAnggaran }};
            const totalRealisasi = {{ $totalRealisasi }};

            const donutCtx = document.getElementById('donutChart').getContext('2d');
            new Chart(donutCtx, {
                type: 'doughnut',
                data: {
                    // labels: ['Total Anggaran', 'Total Realisasi'],
                    datasets: [{
                        data: [totalAnggaran, totalRealisasi],
                        backgroundColor: ['#00C49F', '#FF6384'], // hijau & pink
                        borderWidth: 0
                    }]
                },
                options: {
                    cutout: '70%',
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom'
                        }
                    }
                }
            });
        </script>
    @endsection