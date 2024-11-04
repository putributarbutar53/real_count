<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-Time Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
</head>
<body>
    <canvas id="myChart" width="400" height="200"></canvas>

    <script>
        // Inisialisasi Chart.js
        const ctx = document.getElementById('myChart').getContext('2d');
        let chart = new Chart(ctx, {
            type: 'bar', // jenis chart bisa diganti
            data: {
                labels: [],
                datasets: [{
                    label: 'Sample Data',
                    data: [],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
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

        // Inisialisasi Pusher
        Pusher.logToConsole = true;

        var pusher = new Pusher('9ac0d2af743317b62be2', {
        cluster: 'ap1',
        encrypted: true
    });

        const channel = pusher.subscribe('chart-channel');
        channel.bind('update-event', function(data) {
            // Update chart dengan data baru dari Pusher
            chart.data.labels = data.labels;
            chart.data.datasets[0].data = data.values;
            chart.update();
        });
    </script>
</body>
</html>
