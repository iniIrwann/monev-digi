@extends('layout.app')

@section('main')
<div class="main-content ps-3 pe-3 pt-4">
    <div class="row g-3">
        <!-- Kartu total realisasi terpenuhi -->
        <div class="col-md-4">
            <div class="p-3 rounded text-white shadow-sm" style="background: linear-gradient(to right, #ff7eb3, #ff758c);">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-list-check fs-3"></i>
                    </div>
                    <div>
                        <div class="fs-14 text-capitalize">total realisasi tepenuhi</div>
                        <div class="fs-18 sb">{{ $totalRealisasiTepenuhi }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kartu Jumlah target -->
        <div class="col-md-4">
            <div class="p-3 rounded text-white shadow-sm" style="background: linear-gradient(to right, #4facfe, #00f2fe);">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-bullseye fs-3"></i>
                    </div>
                    <div>
                        <div class="fs-14 text-capitalize">total target</div>
                        <div class="fs-18 sb">{{ $totalTarget }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kartu total capaian yang sudah -->
        <div class="col-md-4">
            <div class="p-3 rounded text-white shadow-sm" style="background: linear-gradient(to right, #43e97b, #38f9d7);">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-award-fill fs-3"></i>
                    </div>
                    <div>
                        <div class="fs-14 text-capitalize">total capaian yang sudah</div>
                        <div class="fs-18 sb">{{ $capaianTercapai }} %</div>
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
                        <p class="mb-3 fs-14 sb">Data Target Yang Ditambahkan</p>
                        <canvas id="targetLineChart" class="flex-grow-1" style="height: 220px; width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="p-4 bg-white rounded shadow-sm h-100">
                <!-- Judul -->
                <p class="mb-2 fs-14 sb text-capitalize">capaian keluaran dan capaian keuangan</p>

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
                </div>

                <!-- Keterangan -->
                <p class="fs-12 sb mb-1">keterangan:</p>
                <ul class="mb-0 fs-12">
                                <li><strong>&lt; 40%</strong>: <em class="fw-bold text-danger">Sangat Kurang</em> – Capaian
                                    sangat
                                    rendah, perlu evaluasi
                                    menyeluruh dan perbaikan.</li>
                                <li><strong>40% - &lt; 60%</strong>: <em class="fw-bold "
                                        style="color: #fd7e14">Kurang</em> – Banyak
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
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
    // Data untuk donut chart
    const sudahTerpenuhi = {{ $jumlahTerpenuhi }};
    const belumTerpenuhi = {{ $jumlahBelumTerpenuhi }};

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
    const lineCtx = document.getElementById('targetLineChart').getContext('2d');
    new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Jumlah Target',
                data: {!! json_encode($data) !!},
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                tension: 0.3,
                fill: true,
                pointBackgroundColor: 'white',
                pointBorderColor: 'rgba(54, 162, 235, 1)',
                pointRadius: 4,
            }]
        },
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
                    titleFont: { weight: 'bold' }
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
                        text: 'Jumlah'
                    }
                }
            }
        }
    });
</script>
@endsection
