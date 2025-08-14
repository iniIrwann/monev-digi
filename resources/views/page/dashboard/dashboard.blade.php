@extends('layout.app')

@section('title', 'Dashboard - Monev Digi Dana Desa')

@section('main')
    <div class="main-content ps-3 pe-3 pt-4">
        <div class="row g-3">
            <!-- Kartu total realisasi terpenuhi -->
            <div class="col-md-4">
                <div class="p-3 rounded text-white shadow-sm"
                    style="background: linear-gradient(to right, #ff7eb3, #ff758c);">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bi bi-list-check fs-3"></i>
                        </div>
                        <div>
                            <div class="fs-14 text-capitalize">Total Realisasi Terpenuhi</div>
                            <div class="fs-18 sb">{{ $totalRealisasiTerpenuhi }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kartu total target -->
            <div class="col-md-4">
                <div class="p-3 rounded text-white shadow-sm"
                    style="background: linear-gradient(to right, #4facfe, #00f2fe);">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bi bi-bullseye fs-3"></i>
                        </div>
                        <div>
                            <div class="fs-14 text-capitalize">Total Target</div>
                            <div class="fs-18 sb">{{ $totalTarget }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kartu persentase capaian sempurna -->
            <div class="col-md-4">
                <div class="p-3 rounded text-white shadow-sm"
                    style="background: linear-gradient(to right, #43e97b, #38f9d7);">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bi bi-award-fill fs-3"></i>
                        </div>
                        <div>
                            <div class="fs-14 text-capitalize">Persentase Capaian Sempurna</div>
                            <div class="fs-18 sb">{{ $capaianTercapai }}% ({{ $jumlahCapaianSempurna }} dari {{ $totalCapaian }})</div>
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
                                <p class="mb-3 fs-14 sb">Total Realisasi</p>
                                <canvas id="donutChart" class="mb-4 mx-auto mx-md-0" style="max-height: 200px;"></canvas>
                            </div>
                            <!-- Kanan: Legend -->
                            <div class="w-100 w-md-40 ms-md-4">
                                <div class="d-flex align-items-center mb-2 justify-content-center justify-content-md-start">
                                    <div class="me-2" style="width: 12px; height: 12px; background-color: #00C49F;"></div>
                                    <span class="text-muted fs-12">Sudah Terpenuhi</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                                    <div class="me-2" style="width: 12px; height: 12px; background-color: #FF6384;"></div>
                                    <span class="text-muted fs-12">Belum Terpenuhi</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Line Chart -->
                    <div class="col-12 col-md-6">
                        <div class="p-4 bg-white rounded shadow-sm d-flex flex-column justify-content-between h-100">
                            <p class="mb-3 fs-14 sb">Realisasi Per Tahap Berdasarkan Tanggal</p>
                            <canvas id="realisasiLineChart" class="flex-grow-1" style="height: 220px; width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Capaian -->
            <div class="col-12">
                <div class="p-4 bg-white rounded shadow-sm h-100">
                    <!-- Judul -->
                    <p class="mb-2 fs-14 sb text-capitalize">Capaian Keluaran dan Capaian Keuangan</p>

                    <!-- Tabel capaian keluaran -->
                    <div class="row">
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
                                            <td class="fs-12 text-muted">Sangat Kurang</td>
                                        </tr>
                                        <tr class="text-center">
                                            <td class="fs-12">40 - 59</td>
                                            <td class="fs-12">{{ $kategoriKeluaran['kurang'] }}</td>
                                            <td class="fs-12 text-muted">Kurang</td>
                                        </tr>
                                        <tr class="text-center">
                                            <td class="fs-12">60 - 74</td>
                                            <td class="fs-12">{{ $kategoriKeluaran['cukup'] }}</td>
                                            <td class="fs-12 text-muted">Cukup</td>
                                        </tr>
                                        <tr class="text-center">
                                            <td class="fs-12">75 - 89</td>
                                            <td class="fs-12">{{ $kategoriKeluaran['baik'] }}</td>
                                            <td class="fs-12 text-muted">Baik</td>
                                        </tr>
                                        <tr class="text-center">
                                            <td class="fs-12">≥ 90</td>
                                            <td class="fs-12">{{ $kategoriKeluaran['sangat_baik'] }}</td>
                                            <td class="fs-12 text-muted">Sangat Baik</td>
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
                                            <td class="fs-12 text-muted">Sangat Rendah</td>
                                        </tr>
                                        <tr class="text-center">
                                            <td class="fs-12">40 - 59</td>
                                            <td class="fs-12">{{ $kategoriKeuangan['kurang'] }}</td>
                                            <td class="fs-12 text-muted">Kurang</td>
                                        </tr>
                                        <tr class="text-center">
                                            <td class="fs-12">60 - 74</td>
                                            <td class="fs-12">{{ $kategoriKeuangan['cukup'] }}</td>
                                            <td class="fs-12 text-muted">Cukup</td>
                                        </tr>
                                        <tr class="text-center">
                                            <td class="fs-12">75 - 89</td>
                                            <td class="fs-12">{{ $kategoriKeuangan['baik'] }}</td>
                                            <td class="fs-12 text-muted">Baik</td>
                                        </tr>
                                        <tr class="text-center">
                                            <td class="fs-12">≥ 90</td>
                                            <td class="fs-12">{{ $kategoriKeuangan['sangat_baik'] }}</td>
                                            <td class="fs-12 text-muted">Sangat Baik</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <p class="fs-12 sb mb-1">Keterangan:</p>
                    <ul class="mb-0 fs-12">
                        <li><strong>&lt; 40%</strong>: <em class="fw-bold text-danger">Sangat Kurang/Rendah</em> – Capaian sangat rendah, perlu evaluasi menyeluruh dan perbaikan.</li>
                        <li><strong>40% - &lt; 60%</strong>: <em class="fw-bold" style="color: #fd7e14">Kurang</em> – Banyak target tidak tercapai, perlu peningkatan signifikan.</li>
                        <li><strong>60% - &lt; 75%</strong>: <em class="fw-bold text-warning">Cukup</em> – Capaian sedang, masih ada kekurangan yang perlu diperbaiki.</li>
                        <li><strong>75% - &lt; 90%</strong>: <em class="fw-bold text-success">Baik</em> – Sebagian besar target tercapai, pelaksanaan berjalan baik.</li>
                        <li><strong>≥ 90%</strong>: <em class="fw-bold text-primary">Sangat Baik</em> – Capaian optimal, seluruh target kegiatan/keuangan tercapai.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Chart.js CDN -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

        <script>
            // Data untuk donut chart
            const sudahTerpenuhi = {{ $totalRealisasiTerpenuhi }};
            const belumTerpenuhi = {{ $totalRealisasiBelumTerpenuhi }};

            const donutCtx = document.getElementById('donutChart').getContext('2d');
            new Chart(donutCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Sudah Terpenuhi', 'Belum Terpenuhi'],
                    datasets: [{
                        data: [sudahTerpenuhi, belumTerpenuhi],
                        backgroundColor: ['#00C49F', '#FF6384'],
                        borderWidth: 0
                    }]
                },
                options: {
                    cutout: '70%',
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Data untuk line chart
            const lineCtx = document.getElementById('realisasiLineChart').getContext('2d');
            new Chart(lineCtx, {
                type: 'line',
                data: @json($chartData),
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            labels: {
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: '#222',
                            titleFont: {
                                weight: 'bold'
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Tanggal'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah Realisasi'
                            }
                        }
                    }
                }
            });
        </script>
    </section>
@endsection
