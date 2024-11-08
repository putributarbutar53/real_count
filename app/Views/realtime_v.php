<!DOCTYPE html>
<html>

<head>
    <title>Chart with Pusher</title>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <canvas id="myChart" width="400" height="200"></canvas>

    <script>
        // Inisialisasi Pusher
        var pusher = new Pusher('9ac0d2af743317b62be2', {
            cluster: 'ap1'
        });

        // Berlangganan ke channel dan event
        const channel = pusher.subscribe('my-channel');
        channel.bind('my-event', function(data) {
            console.log(data);
            updateChart(data);
        });

        // Fungsi untuk memperbarui chart
        function updateChart(data) {
            const ctx = document.getElementById('myChart').getContext('2d');

            // Hapus chart lama jika ada
            if (window.myChart) {
                window.myChart.destroy();
            }

            // Buat chart baru
            window.myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                            label: 'Total Suara Sah',
                            data: data.total_suara,
                            backgroundColor: 'rgba(75, 192, 192, 0.5)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Persentase Suara Sah (%)',
                            data: data.persentase_suara,
                            backgroundColor: 'rgba(153, 102, 255, 0.5)',
                            borderColor: 'rgba(153, 102, 255, 1)',
                            borderWidth: 1
                        }
                    ]
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
    </script>
</body>

</html>