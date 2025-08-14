// resources/js/dashboard-charts.js
document.addEventListener('DOMContentLoaded', () => {
    // Donut Chart
    const donutCtx = document.getElementById('donutChart')?.getContext('2d');
    if (donutCtx) {
        new Chart(donutCtx, {
            type: 'doughnut',
            data: {
                labels: ['Sudah Terpenuhi', 'Belum Terpenuhi'],
                datasets: [{
                    data: [window.sudahTerpenuhi || 0, window.belumTerpenuhi || 0],
                    backgroundColor: ['#00C49F', '#FF6384'],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '70%',
                responsive: true,
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }

    // Line Chart
    const lineCtx = document.getElementById('targetLineChart')?.getContext('2d');
    if (lineCtx) {
        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: window.chartLabels || [],
                datasets: [{
                    label: 'Jumlah Target',
                    data: window.chartData || [],
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: 'white',
                    pointBorderColor: 'rgba(54, 162, 235, 1)',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: {
                            font: { size: 14, weight: 'bold' }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#222',
                        titleFont: { weight: 'bold' }
                    }
                },
                scales: {
                    x: { title: { display: true, text: 'Tanggal' } },
                    y: { beginAtZero: true, title: { display: true, text: 'Jumlah' } }
                }
            }
        });
    }
});
