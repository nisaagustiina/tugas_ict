
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        header {
            background-color: #4CAF50;
            padding: 10px;
            text-align: center;
            color: white;
            font-size: 24px;
        }

        .container {
            padding: 20px;
        }

        .chart-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .chart-container canvas {
            width: 48%;
            height: 400px;
        }

        .message {
            background-color: #e7f7e7;
            padding: 10px;
            border: 1px solid #b6d7b6;
            margin-bottom: 20px;
            color: green;
            display: none;
        }
    </style>
</head>

<body>

    <header>
        Laporan Keuangan
    </header>

    <div class="container">

        <!-- Success Message -->
        <div id="successMessage" class="message"><?php echo $successMessage; ?></div>

        <!-- Chart Section -->
        <div class="chart-container">
            <div>
                <h3>Pemasukan dan Pengeluaran Per Bulan</h3>
                <canvas id="barChart"></canvas>
            </div>
            <div>
                <h3>Pengeluaran Berdasarkan Kategori</h3>
                <canvas id="pieChart"></canvas>
            </div>
        </div>

    </div>

    <script>
        fetch('report-data.php')
            .then(response => response.json())
            .then(data => {
                console.log(data);

                if (data && data.monthly) {
                    generateBarChart(data.monthly);
                }
                if (data && data.category) {
                    generatePieChart(data.category);
                }
            })
            .catch(error => {
                console.error('Error fetching data:', error.message);
                alert('Gagal memuat data. Silakan coba lagi nanti.');
            });

        // Bar chart for monthly data
        function generateBarChart(data) {
            const ctxBar = document.getElementById('barChart').getContext('2d');

            new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: data.months.map(month => {
                        const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                        ];
                        return monthNames[month - 1];
                    }),
                    datasets: [{
                            label: 'Pemasukan',
                            data: data.income,
                            backgroundColor: 'rgba(75, 192, 192, 0.5)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Pengeluaran',
                            data: data.outcome,
                            backgroundColor: 'rgba(255, 99, 132, 0.5)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.dataset.label + ': Rp ' + tooltipItem.raw.toLocaleString();
                                }
                            }
                        },
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }

        // Pie chart for category outcome data
        function generatePieChart(data) {
            const ctxOutcome = document.getElementById('pieChart').getContext('2d');

            const colors = data.categories.map((_, index) => {
                const hue = (index * 137.508) % 360;
                return `hsl(${hue}, 70%, 60%)`;
            });

            new Chart(ctxOutcome, {
                type: 'pie',
                data: {
                    labels: data.categories,
                    datasets: [{
                        label: 'Pengeluaran',
                        data: data.outcome,
                        backgroundColor: colors,
                        borderColor: colors.map(color => color.replace('hsl', 'rgba').replace(')', ', 1)')),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.dataset.label + ': Rp ' + tooltipItem.raw.toLocaleString();
                                }
                            }
                        },
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });
        }
    </script>

</body>

</html>