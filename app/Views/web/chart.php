<!-- home.php -->
<?php $this->extend('web/layout/main') ?>

<?php $this->section('content') ?>
<div class="card-deck">
    <div class="card mb-3 overflow-hidden" style="min-width: 12rem">
        <div class="bg-holder bg-card" style="background-image:url(assets/img/illustrations/authentication-corner.png);"></div>
        <div class="card-body position-relative">
            <h6>Total Suara</h6>
            <div id="total-suara" class="display-4 fs-4 mb-2 font-weight-normal text-sans-serif">110</div>
        </div>
    </div>

    <div class="card mb-3 overflow-hidden" style="min-width: 12rem">
        <div class="bg-holder bg-card" style="background-image:url(assets/img/illustrations/polga2p.png); width: 95px; height: 30px; background-position: bottom; margin-left: 160px;"></div>

        <div class="card-body position-relative">
            <h6>1. Poltak - Anugerah<span id="badge-poltak" class="badge badge-danger rounded-capsule ml-2">0%</span></h6>
            <div id="suara-poltak" class="display-4 fs-4 mb-2 font-weight-normal text-sans-serif">0</div>
        </div>
    </div>

    <div class="card mb-3 overflow-hidden" style="min-width: 12rem">
        <div class="bg-holder bg-card" style="background-image:url(assets/img/illustrations/robton.png); width: 95px; height: 90px; background-position: bottom; margin-left: 160px;"></div>
        <div class="card-body position-relative">
            <h6>2. Robinson - Tonny<span id="badge-robinson" class="badge badge-info rounded-capsule ml-2">0%</span></h6>
            <div id="suara-robinson" class="display-4 fs-4 mb-2 font-weight-normal text-sans-serif">0</div>
        </div>
    </div>

    <div class="card mb-3 overflow-hidden" style="min-width: 12rem">
        <div class="bg-holder bg-card" style="background-image:url(assets/img/illustrations/2diaudiho.png); width:135px; background-position: bottom; margin-left: 125px;"></div>

        <div class="card-body position-relative">
            <h6>3. Effendi - Audi<span id="badge-effendi" class="badge badge-primary rounded-capsule ml-2">0%</span></h6>
            <div id="suara-effendi" class="display-4 fs-4 mb-2 font-weight-normal text-sans-serif">0</div>
        </div>
    </div>

</div>
<div class="row no-gutters">
    <div class="col-sm-6 col-xxl-3 pr-sm-2 mb-3 mb-xxl-0">
        <div class="card">
            <canvas class="max-w-100" id="bar" width="1618" height="1000"></canvas>
        </div>
    </div>
    <div class="col-sm-6 col-xxl-3 pl-sm-2 order-xxl-1 mb-3 mb-xxl-0">
        <div class="card h-100">
            <canvas id="pie" width="700" height="350"></canvas>
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
    function refreshData() {
        $.ajax({
            url: '<?= site_url('chart/getSuara') ?>', // URL ke controller
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                // Update jumlah suara
                $('#total-suara').text(parseInt(data.total_suara).toLocaleString('id-ID'));
                $('#suara-poltak').text(parseInt(data.suara_poltak).toLocaleString('id-ID'));
                $('#suara-robinson').text(parseInt(data.suara_robinson).toLocaleString('id-ID'));
                $('#suara-effendi').text(parseInt(data.suara_effendi).toLocaleString('id-ID'));

                // Hitung persentase
                let totalSuara = data.total_suara || 1; // untuk menghindari pembagian dengan nol
                let persentasePoltak = (data.suara_poltak / totalSuara * 100).toFixed(1);
                let persentaseRobinson = (data.suara_robinson / totalSuara * 100).toFixed(1);
                let persentaseEffendi = (data.suara_effendi / totalSuara * 100).toFixed(1);

                // Update badge dengan persentase
                $('#badge-poltak').text(persentasePoltak + '%');
                $('#badge-robinson').text(persentaseRobinson + '%');
                $('#badge-effendi').text(persentaseEffendi + '%');
            }
        });
    }

    // Memanggil fungsi refreshData setiap 5 detik
    setInterval(refreshData, 5000);

    // Panggil sekali untuk load data pertama kali
    $(document).ready(function() {
        refreshData();
    });
</script>

<script>
    var chartBar = document.getElementById('bar').getContext('2d');
    var chartPie = document.getElementById('pie').getContext('2d');
    var barChart, pieChart;

    function loadChart() {
        $.ajax({
            url: '<?= site_url() ?>chart/getchart', // Endpoint untuk mengambil data chart dari controller
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

                if (pieChart) {
                    pieChart.data.labels = response.labels;
                    pieChart.data.datasets[0].data = response.persentase_suara; // Menampilkan persentase suara sah
                    pieChart.update();
                } else {
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
                                    var index = elements[0]._index;
                                    var label = this.data.labels[index];
                                    var percentage = this.data.datasets[0].data[index].toFixed(2) + '%';

                                    alert("Paslon: " + label + "\nPersentase Suara Sah: " + percentage);
                                }
                            }
                        }
                    });
                }
            }
        });
    }

    setInterval(loadChart, 5000);

    loadChart();
</script>
<?php $this->endSection() ?>