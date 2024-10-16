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
        <div class="card text-center h-100"> <!-- Tambahkan kelas text-center dari Bootstrap -->
            <h4>Grafik perolehan suara</h4>
            <canvas class="max-w-100" id="bar" width="1618" height="1000"></canvas>
        </div>
    </div>

    <div class="col-sm-6 col-xxl-3 pl-sm-2 order-xxl-1 mb-3 mb-xxl-0">
        <div class="card text-center">
            <h5>Persentase perolehan suara</h5>
            <canvas id="pie" width="700" height="350"></canvas>
        </div>
    </div>

</div>
<div class="card bg-light mb-3">
    <div class="card-body p-3">
        <p class="fs--1 mb-0">
            <strong>Data Suara per Kecamatan</strong>
        </p>
    </div>
</div>
<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-center">
            <div class="col">
            </div>
        </div>
    </div>
    <div class="card-body bg-light">
        <div class="row">
            <?php foreach ($paslon as $item): ?>
                <div class="col-md-4 mb-4">
                    <div class="card" style="width: 100%; min-height: 350px;">
                        <div class="d-flex justify-content-center mt-3">
                            <img class="img-fluid" style="width: 100%; height: 250px;" src="<?= base_url() . getenv('dir.uploads.paslon') . $item['img'] ?>">
                        </div>
                        <div class="card-body text-center">
                            <h1><?= esc($item['id']) ?></h1>
                            <h5 class="card-title"><?= esc($item['nama_paslon']) ?></h5>
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <?php foreach ($kecamatan as $kec): ?>
                                        <tr class="align-middle" style="line-height: 1;">
                                            <td class="text-left" style="padding: 0.2rem;"><?= esc($kec['nama_kec']) ?></td>
                                            <td style="padding: 0.2rem;" id="suara-<?= $kec['id'] ?>-<?= $item['nama_paslon'] ?>">
                                                <?php if (isset($suara_per_kecamatan[$kec['id']])) : ?>
                                                    <?php
                                                    $suara_sah = isset($suara_per_kecamatan[$kec['id']]['suara'][$item['nama_paslon']]['suara_sah']) ? $suara_per_kecamatan[$kec['id']]['suara'][$item['nama_paslon']]['suara_sah'] : 0;
                                                    ?>
                                                    <small class="countup" data-countup='{"count":<?= esc($suara_sah) ?>}'>0</small> /
                                                    <small><?= esc(array_sum(array_column($suara_per_kecamatan[$kec['id']]['suara'], 'suara_sah'))) ?></small>
                                                <?php else: ?>
                                                    <small class="countup" data-countup='{"count":0}'>0</small> /
                                                    <small>0</small>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<div class="card bg-light mb-3">
    <div class="card-body p-3">
        <p class="fs--1 mb-0">
            <strong>Grafik perolehan suara per kecamatan</strong>
        </p>
    </div>
</div>
<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="kecamatanSelect">Pilih Kecamatan :</label>
                    <div id="kecamatanSelect" class="row">
                        <?php if (isset($kecamatan) && count($kecamatan) > 0): ?>
                            <?php foreach ($kecamatan as $index => $kec): ?>
                                <div class="col-md-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input kecamatan-checkbox" type="checkbox" id="kec_<?= $kec['id'] ?>" name="kecamatan[]" value="<?= $kec['id'] ?>">
                                        <label class="form-check-label" for="kec_<?= $kec['id'] ?>">
                                            <?= $kec['nama_kec'] ?>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Tidak ada kecamatan tersedia</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="grafikContainer" class="row no-gutters"></div>
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

    setInterval(refreshData, 5000);

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
            url: '<?= site_url('chart/getchart') ?>', // Endpoint untuk mengambil data chart dari controller
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
                                label: 'Grafik Jumlah Suara Yang Diperoleh Setiap Paslon',
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
                        },
                        plugins: {
                            legend: {
                                display: false // Sembunyikan legend
                            }
                        }
                    });
                }

                if (pieChart) {
                    pieChart.data.labels = response.labels.map((label, index) => `${label} (${response.persentase_suara[index].toFixed(2)}%)`);
                    pieChart.data.datasets[0].data = response.persentase_suara; // Menampilkan persentase suara sah
                    pieChart.update();
                } else {
                    pieChart = new Chart(chartPie, {
                        type: 'pie',
                        data: {
                            labels: response.labels.map((label, index) => `${label} (${response.persentase_suara[index].toFixed(2)}%)`),
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

                        }
                    });
                }
            }
        });
    }

    setInterval(loadChart, 5000);

    loadChart();
</script>
<script>
    $('.kecamatan-checkbox').on('change', function() {
        let selectedKecamatan = [];
        $('.kecamatan-checkbox:checked').each(function() {
            selectedKecamatan.push($(this).val());
        });

        if (selectedKecamatan.length > 0) {
            $.ajax({
                url: '<?= base_url('chart/getGrafikByKecamatan') ?>',
                type: 'POST',
                data: {
                    kecamatan: selectedKecamatan
                },
                success: function(response) {
                    $('#grafikContainer').empty();

                    // Menentukan warna berdasarkan nama paslon
                    var warna = {};
                    response.labels.forEach(function(label) {
                        if (label === 'Efendi & Audimurphi') {
                            warna[label] = '#006BFF';
                        } else if (label === 'Robertson Tonny') {
                            warna[label] = '#08C2FF';
                        } else if (label === 'Poltak & Anugerah') {
                            warna[label] = '#E72929';
                        } else {
                            warna[label] = '#4BC0C0';
                        }
                    });

                
                    response.dataGrafik.forEach(function(grafikData) {
                        let chartId = 'chart_' + grafikData.kecamatan.replace(/\s/g, '');
                        $('#grafikContainer').append(`
                            <div class="col-sm-6 col-xxl-3 pr-sm-2 mb-3 mb-xxl-0">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Grafik Suara Paslon Kecamatan ${grafikData.kecamatan}</h5>
                                    </div>
                                    <canvas id="${chartId}" width="1618" height="1000"></canvas>
                                </div>
                            </div>
                        `);

                        // Mengambil label dan data untuk chart
                        let labels = grafikData.data.map(function(item) {
                            return response.labels[item.id_paslon - 1]; // Sesuaikan ID dengan index label
                        });
                        let data = grafikData.data.map(function(item) {
                            return item.total_suara; // Pastikan ini mengambil total suara yang benar
                        });
                        let percentages = grafikData.data.map(function(item) {
                            return item.persentase; // Ambil persentase dari respons
                        });

                        // Membuat chart
                        let ctx = document.getElementById(chartId).getContext('2d');
                        new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: labels.map((label, index) => `${label} (${percentages[index]}%)`), // Menampilkan persentase di label
                                datasets: [{
                                    data: data,
                                    backgroundColor: labels.map(label => warna[label]) // Ambil warna sesuai label
                                }]
                            },
                            options: {
                                responsive: true,
                                title: {
                                    display: false
                                }
                            }
                        });
                    });
                }
            });
        } else {
            $('#grafikContainer').empty();
        }
    });
</script>

<?php $this->endSection() ?>