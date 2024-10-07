<!-- home.php -->
<?php $this->extend('web/layout/main') ?>

<?php $this->section('content') ?>
<div class="row no-gutters">
    <div class="col-sm-6 col-xxl-3 pr-sm-2 mb-3 mb-xxl-0">
        <div class="card">
            <canvas class="max-w-100" id="bar" width="1618" height="1000"></canvas>
        </div>
    </div>
    <div class="col-sm-6 col-xxl-3 pl-sm-2 order-xxl-1 mb-3 mb-xxl-0">
        <div class="card h-100">
            <canvas id="pie" width="400" height="200"></canvas>
        </div>
    </div>
    <div class="col-xxl-6 px-xxl-2">
        <div class="card h-100">


        </div>
    </div>
</div>
<?php $this->endSection() ?>

<?php $this->section('script') ?>
<script>
    var chartBar = document.getElementById('bar').getContext('2d');
    var chartPie = document.getElementById('pie').getContext('2d');
    var barChart, pieChart;

    function loadChart() {
        $.ajax({
            url: '<?= site_url() ?>/chart/getchart', // Endpoint untuk mengambil data chart dari controller
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                var warna = [];

                response.labels.forEach(function(label) {
                    if (label === 'Efendi & Audimurphi') {
                        warna.push('#006BFF');
                    } else if (label === 'Robertson Tonny') {
                        warna.push('#08C2FF');
                    } else if (label === 'Poltak & Anugerah') {
                        warna.push('#E72929');
                    } else {
                        warna.push('#4BC0C0');
                    }
                });
                // Jika bar chart sudah ada, update datanya
                if (barChart) {
                    barChart.data.labels = response.labels;
                    barChart.data.datasets[0].data = response.total_suara;
                    barChart.data.datasets[0].backgroundColor = warna;
                    barChart.update();
                } else {
                    // Membuat bar chart baru
                    barChart = new Chart(chartBar, {
                        type: 'bar',
                        data: {
                            labels: response.labels,
                            datasets: [{
                                label: 'Total Suara Sah',
                                backgroundColor: warna,
                                borderColor: '#ffffff',
                                data: response.total_suara // Menampilkan total suara sah
                            }]
                        },
                        options: {
                            scales: {
                                y: { // 'yAxes' diubah menjadi 'y'
                                    beginAtZero: true
                                },
                                x: { // 'xAxes' diubah menjadi 'x'
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }

                // Jika pie chart sudah ada, update datanya
                if (pieChart) {
                    pieChart.data.labels = response.labels;
                    pieChart.data.datasets[0].data = response.persentase_suara; // Menampilkan persentase suara sah
                    pieChart.update();
                } else {
                    // Membuat pie chart baru
                    pieChart = new Chart(chartPie, {
                        type: 'pie',
                        data: {
                            labels: response.labels,
                            datasets: [{
                                label: 'Persentase Suara Sah',
                                backgroundColor: warna,
                                borderColor: '#ffffff',
                                data: response.persentase_suara // Menampilkan persentase suara sah
                            }]
                        },
                        options: {
                            responsive: true,
                            legend: {
                                position: 'top',
                            },
                            animation: {
                                animateScale: true,
                                animateRotate: true
                            },
                            tooltips: {
                                callbacks: {
                                    label: function(tooltipItem, data) {
                                        var dataset = data.datasets[tooltipItem.datasetIndex];
                                        var currentValue = dataset.data[tooltipItem.index];
                                        var label = data.labels[tooltipItem.index];
                                        // Menampilkan label dengan nilai persentase
                                        return label + ': ' + currentValue.toFixed(2) + '%'; // Format dengan 2 decimal
                                    }
                                }
                            },
                            onClick: function(evt, elements) {
                                if (elements.length > 0) {
                                    var index = elements[0]._index; // Ambil index elemen yang diklik
                                    var label = this.data.labels[index];
                                    var percentage = this.data.datasets[0].data[index].toFixed(2) + '%';

                                    // Menampilkan alert atau modal dengan informasi yang diinginkan
                                    alert("Paslon: " + label + "\nPersentase Suara Sah: " + percentage);
                                }
                            }
                        }
                    });
                }
            }
        });
    }

    // Panggil loadChart setiap 5 detik untuk memperbarui kedua chart
    setInterval(loadChart, 5000);

    // Panggilan pertama untuk memuat chart saat halaman dibuka
    loadChart();
</script>
<?php $this->endSection() ?>