<?php
session_start();
include 'conn.php';

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
</head>

<body>

    <div>
        <canvas id="myChart"></canvas>
        <br>
        <canvas id="pieChart"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        fetch('/report-data.php')
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (data.success) {
                    const labels = data.data.map(item => `Month ${item.month}`);
                    const totals = data.data.map(item => item.total);

                    const ctx = document.getElementById('myChart');

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels:labels,
                            datasets: [{
                                label: '# Jumlah',
                                data: data,
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });


                }
            })
            .catch(error => {
                console.error('Error:', error);
            });


        const config = {
            type: 'doughnut',
            data: {
                labels: [
                    'Red',
                    'Blue',
                    'Yellow'
                ],
                datasets: [{
                    label: 'My First Dataset',
                    data: [300, 50, 100],
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)'
                    ],
                    hoverOffset: 4
                }]
            },
        };

        const pie = document.getElementById('pieChart');
        new Chart(pie, config);
    </script>

</body>

</html>