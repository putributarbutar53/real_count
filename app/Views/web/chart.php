<!-- home.php -->
<?php $this->extend('web/layout/main') ?>

<?php $this->section('content') ?>
<div class="card-deck">
    <!-- Card Partisipasi -->
    <div class="card mb-3 overflow-hidden" style="min-width: 12rem; height: 95px;">
        <div class="card-body d-flex flex-column justify-content-between">
            <h6>
                TPS yang Telah Input Data
                <span id="persen-tps" class="badge badge-secondary rounded-capsule ml-2">0%</span>
            </h6>
            <div class="d-flex align-items-center">
                <h6 class="mb-0">Jumlah:</h6>
                <div id="total-tps" class="ml-2 ">0 / 0</div>
            </div>
        </div>
    </div>
    <div class="card mb-3 overflow-hidden" style="min-width: 12rem; height: 95px;">
        <div class="card-body d-flex flex-column justify-content-between">
            <h6>
                Partisipasi Pemilih
                <span id="badge-prov" class="badge badge-secondary rounded-capsule ml-2">0%</span>
            </h6>
            <div class="d-flex align-items-center">
                <h6 class="mb-0">Sah:</h6>
                <div id="total-sah" class="ml-2 ">0 / 0</div>
            </div>
            <div class="d-flex align-items-center">
                <h6 class="mb-0">Tidak Sah:</h6>
                <div id="total-tidak" class="ml-2 ">0 / 0</div>
            </div>
        </div>
    </div>

    <!-- Card Bobby - Surya -->
    <div class="card mb-3 overflow-hidden d-flex flex-row align-items-center" style="min-width: 12rem; height: 95px;">
        <!-- Konten Teks -->
        <div class="card-body d-flex flex-column justify-content-center">
            <span id="badge-bobby" class="badge badge-primary rounded-pill ml-2">0%</span>
            <h6>
                1. Bobby Surya

            </h6>
            <div id="suara-bobby" class=" font-weight-bold">0</div>
        </div>
        <!-- Gambar -->
        <div class="d-flex align-items-center justify-content-center ml-3" style="width: 120px; height: 80px; overflow: hidden;">
            <img src="assets/img/illustrations/1.png" alt="Bobby - Surya" class="img-fluid rounded"
                style="width: 100%; height: 100%; object-fit: cover;">
        </div>
    </div>


    <!-- Card Edy - Hasan -->
    <div class="card mb-3 overflow-hidden d-flex flex-row align-items-center" style="min-width: 12rem; height: 95px;">
        <!-- Konten Teks -->
        <div class="card-body d-flex flex-column justify-content-center">
            <span id="badge-edy" class="badge badge-danger rounded-pill ml-2">0%</span>
            <h6>
                2. Edy Hasan

            </h6>
            <div id="suara-edy" class=" font-weight-bold">0</div>
        </div>
        <!-- Gambar -->
        <div class="d-flex align-items-center justify-content-center ml-3" style="width: 120px; height: 80px;">
            <img src="assets/img/illustrations/2.png" alt="Edy - Hasan" class="img-fluid rounded"
                style="object-fit: cover; width: 100%; height: 100%;">
        </div>
    </div>


</div>

<div class="card-deck" style="margin-top: 0px;">
    <div class="card mb-3 overflow-hidden" style="min-width: 12rem; height: 100px;">
        <div class="card-body d-flex flex-column justify-content-between">
            <h6>
                Partisipasi Pemilih
                <span id="badge-partisipasi" class="badge badge-secondary rounded-capsule ml-2">0%</span>
            </h6>
            <div class="d-flex align-items-center">
                <h6 class="mb-0">Sah:</h6>
                <div id="total-suara-sah" class="ml-2 ">0 / 0</div>
            </div>
            <div class="d-flex align-items-center">
                <h6 class="mb-0">Tidak Sah:</h6>
                <div id="total-suara-tidak" class="ml-2 ">0 / 0</div>
            </div>
        </div>
    </div>
    <!-- Card Poltak Anugrah -->
    <div class="card mb-3 overflow-hidden d-flex flex-row align-items-center" style="min-width: 12rem; height: 100px;">
        <!-- Konten Teks -->
        <div class="card-body d-flex flex-column justify-content-center">
            <span id="badge-poltak" class="badge badge-primary rounded-pill ml-2">0%</span>
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    1. Poltak Anugerah
                </h6>

            </div>
            <div id="suara-poltak" class=" font-weight-bold">0</div>
        </div>
        <!-- Gambar -->
        <div class="d-flex align-items-center justify-content-center ml-3" style="width: 100px; height: 80px; overflow: hidden;">
            <img src="assets/img/illustrations/1fixx.png" alt="Poltak - Anugerah" class="img-fluid rounded"
                style="width: 100%; height: 100%; object-fit: cover;">
        </div>
    </div>

    <div class="card mb-3 overflow-hidden d-flex flex-row align-items-center" style="min-width: 12rem; height: 100px;">
        <!-- Konten Teks -->
        <div class="card-body d-flex flex-column justify-content-center">
            <span id="badge-robinson" class="badge badge-primary rounded-pill">0%</span>
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    2. Robinson Tonny
                </h6>
            </div>
            <div id="suara-robinson" class="font-weight-bold">0</div>
        </div>
        <!-- Gambar -->
        <div class="d-flex align-items-center justify-content-center ml-3" style="width: 100px; height: 80px; overflow: hidden;">
            <img src="assets/img/illustrations/2fix-removebg.png" alt="2. Robinson - Tonny" class="img-fluid rounded"
                style="width: 100%; height: 100%; object-fit: cover;">
        </div>
    </div>

    <div class="card mb-3 overflow-hidden d-flex flex-row align-items-center" style="min-width: 12rem; height: 100px;">
        <!-- Konten Teks -->
        <div class="card-body d-flex flex-column justify-content-center">
            <span id="badge-effendi" class="badge badge-primary rounded-pill">0%</span>
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    3. Effendi Audi
                </h6>
            </div>
            <div id="suara-effendi" class=" font-weight-bold">0</div>
        </div>
        <!-- Gambar -->
        <div class="d-flex align-items-center justify-content-center ml-3" style="width: 110px; height: 80px; overflow: hidden;">
            <img src="assets/img/illustrations/3fixx.png" alt="3. Effendi - Audi" class="img-fluid rounded"
                style="width: 100%; height: 100%; object-fit: cover;">
        </div>
    </div>

</div>

<div class="row no-gutters" style="margin-top: 0px;">
    <div class="col-sm-6 col-xxl-3 pr-sm-2 mb-3 mb-xxl-0">
        <div class="card text-center h-100 card-center">
            <h5>Persentase Perolehan Suara Gubernur Sumut</h5>
            <div class="chart-container">
                <canvas id="chart-bar-gubernur"></canvas>
            </div>
        </div>
    </div>

    <div class=" col-sm-6 col-xxl-3 pl-sm-2 order-xxl-1 mb-3 mb-xxl-0">
        <div class="card text-center card-center">
            <h5>Persentase Perolehan Suara Bupati Toba</h5>
            <div class="chart-container">
                <canvas id="bar"></canvas>
            </div>
        </div>
    </div>
</div>

<?php if (session()->get('admin_role') == 'superadmin') { ?>
    <!-- <div class="card bg-light mb-3">
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
    </div> -->
    <!-- <div class="card bg-light mb-3">
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

    <div id="grafikContainer" class="row no-gutters"></div> -->
<?php } ?>
<?php $this->endSection() ?>

<?php $this->section('script') ?>
<script>
    function refreshData() {
        $.ajax({
            url: '<?= site_url('chart/getSuara') ?>', // URL ke controller untuk data suara
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                const totalSuara = parseInt(data.total_suara_sah);
                const totalSuaraTidakSah = parseInt(data.total_suara_tidak);
                const totalDpt = data.total_dpt; // Mendapatkan total DPT dari response
                const partisipasi = ((totalSuara + totalSuaraTidakSah) / totalDpt) * 100; // Menghit
                // Update jumlah suara
                $('#total-suara-sah').text(parseInt(data.total_suara_sah).toLocaleString('id-ID'));
                $('#total-suara-tidak').text(totalSuaraTidakSah.toLocaleString('id-ID'));
                $('#suara-poltak').text(parseInt(data.suara_poltak).toLocaleString('id-ID'));
                $('#suara-robinson').text(parseInt(data.suara_robinson).toLocaleString('id-ID'));
                $('#suara-effendi').text(parseInt(data.suara_effendi).toLocaleString('id-ID'));
                $('#total-suara-sah').text(totalSuara.toLocaleString('id-ID') + ' / ' + totalDpt.toLocaleString('id-ID'));
                $('#total-suara-tidak').text(totalSuaraTidakSah.toLocaleString('id-ID') + ' / ' + totalDpt.toLocaleString('id-ID'));

                // Menampilkan persentase partisipasi dengan 2 angka di belakang koma
                $('#badge-partisipasi').text(partisipasi.toFixed(2) + '%');

                // Panggil loadChartAndCards untuk menghitung persentase dengan benar
                loadChartAndCards();
            }
        });
    }

    function loadChartAndCards() {
        $.ajax({
            url: '<?= base_url('chart/getchart') ?>', // URL ke controller untuk persentase
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                // Menghitung total suara dari response.total_suara saja
                const totalSuaraKeseluruhan = response.total_suara.reduce((acc, val) => acc + val, 0);

                // Memetakan data ke paslon berdasarkan label
                const labelMap = {
                    "Poltak - Anugerah": {
                        id: "poltak",
                        color: "badge-danger"
                    },
                    "Robinson - Tonny": {
                        id: "robinson",
                        color: "badge-info"
                    },
                    "Effendi - Audi": {
                        id: "effendi",
                        color: "badge-primary"
                    }
                };

                response.labels.forEach((label, index) => {
                    const paslon = labelMap[label];
                    if (paslon) {
                        // Update persentase di badge menggunakan data dari loadChartAndCards
                        $(`#badge-${paslon.id}`)
                            .text(`${response.persentase_suara[index].toFixed(1)}%`)
                            .removeClass()
                            .addClass(`badge ${paslon.color} rounded-capsule ml-2`);
                    }
                });
            }
        });
    }

    setInterval(refreshData, 5000);

    // Panggil fungsi refreshData saat halaman dimuat
    $(document).ready(function() {
        refreshData();
    });
</script>
<script>
    function refreshDataProv() {
        $.ajax({
            url: '<?= site_url('chart/getSuaraProv') ?>',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                const totalSuara = parseInt(data.total_suara_sah);
                const totalSuaraTidakSah = parseInt(data.total_suara_tidak);
                const totalDpt = data.total_dpt;
                const partisipasi = ((totalSuara + totalSuaraTidakSah) / totalDpt) * 100;

                // Update total votes
                $('#total-sah').text(`${totalSuara.toLocaleString('id-ID')} / ${totalDpt.toLocaleString('id-ID')}`);
                $('#total-tidak').text(`${totalSuaraTidakSah.toLocaleString('id-ID')} / ${totalDpt.toLocaleString('id-ID')}`);

                // Update candidate votes
                $('#suara-bobby').text(parseInt(data.suara_bobby).toLocaleString('id-ID'));
                $('#suara-edy').text(parseInt(data.suara_edy).toLocaleString('id-ID'));

                // Display participation percentage
                $('#badge-prov').text(partisipasi.toFixed(2) + '%');

                loadChartAndCardsProv();
            }
        });
    }

    function loadChartAndCardsProv() {
        $.ajax({
            url: '<?= base_url('chart/getchartprov') ?>',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                const labelMap = {
                    "Bobby - Surya": {
                        id: "bobby",
                        color: "badge-primary"
                    },
                    "Edy - Hasan": {
                        id: "edy",
                        color: "badge-danger"
                    }
                };

                response.labels.forEach((label, index) => {
                    const paslon = labelMap[label];
                    if (paslon) {
                        $(`#badge-${paslon.id}`)
                            .text(`${response.persentase_suara[index].toFixed(1)}%`)
                            .removeClass()
                            .addClass(`badge ${paslon.color} rounded-capsule ml-2`);
                    }
                });
            }
        });
    }
    setInterval(refreshDataProv, 5000);

    $(document).ready(function() {
        refreshDataProv();
    });
</script>
<script>
    // Chart Bar untuk Bupati
    var chartBarBupati = document.getElementById('bar').getContext('2d');
    var barChartBupati;

    function loadChartBupati() {
        $.ajax({
            url: '<?= site_url('chart/getchart') ?>',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                // Tentukan urutan label yang pasti
                var orderedLabels = ["Poltak - Anugerah", "Robinson - Tonny", "Effendi - Audi", "Suara Tidak Sah"];
                var orderedColors = ['#E72929', '#08C2FF', '#006BFF', '#808080'];
                var orderedData = [];
                var orderedPersentase = [];

                // Tambahkan data "Suara Tidak Sah" ke response jika belum ada
                if (!response.labels.includes("Suara Tidak Sah")) {
                    response.labels.push("Suara Tidak Sah");
                    response.total_suara.push(response.tidak_sah);
                    response.persentase_suara.push(response.persentase_tidak_sah);
                }

                // Urutkan data berdasarkan orderedLabels
                orderedLabels.forEach(function(label) {
                    var index = response.labels.indexOf(label);
                    if (index !== -1) {
                        orderedData.push(response.total_suara[index]);
                        orderedPersentase.push(response.persentase_suara[index]);
                    }
                });

                $('#totalDptContainer').text(`Total DPT: ${response.total_dpt}`);

                if (barChartBupati) {
                    // Update chart dengan data yang sudah diurutkan
                    barChartBupati.data.labels = orderedLabels.map((label, index) => `${label} (${orderedPersentase[index].toFixed(2)}%)`);
                    barChartBupati.data.datasets[0].data = orderedData;
                    barChartBupati.data.datasets[0].backgroundColor = orderedColors;
                    barChartBupati.update();
                } else {
                    // Buat chart baru dengan data yang sudah diurutkan
                    barChartBupati = new Chart(chartBarBupati, {
                        type: 'bar',
                        data: {
                            labels: orderedLabels.map((label, index) => `${label} (${orderedPersentase[index].toFixed(2)}%)`),
                            datasets: [{
                                label: 'Total Suara',
                                backgroundColor: orderedColors,
                                borderColor: '#ffffff',
                                data: orderedData,
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                x: {
                                    beginAtZero: true,
                                    ticks: {
                                        padding: 10
                                    }
                                },
                                y: {
                                    beginAtZero: true
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false // Hide legend to avoid cluttering the chart
                                }
                            },
                            layout: {
                                padding: {
                                    right: 10
                                }
                            },
                            animation: {
                                duration: 0 // Disable animation for static chart
                            }
                        }
                    });
                }
            }
        });
    }

    loadChartBupati(); // First load
    setInterval(loadChartBupati, 5000); // Update every 5 seconds (can be removed if not needed)
</script>

<script>
    var chartBarGubernur = document.getElementById('chart-bar-gubernur').getContext('2d');
    var barChartGubernur;

    function loadChartGubernur() {
        $.ajax({
            url: '<?= site_url('chart/getchartprov') ?>',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                // Tentukan urutan label yang pasti
                var orderedLabels = ["Bobby - Surya", "Edy - Hasan", "Suara Tidak Sah"];
                var orderedColors = ['#006BFF', '#E72929', '#808080'];
                var orderedData = [];
                var orderedPersentase = [];

                // Tambahkan data "Suara Tidak Sah" ke response jika belum ada
                if (!response.labels.includes("Suara Tidak Sah")) {
                    response.labels.push("Suara Tidak Sah");
                    response.total_suara.push(response.tidak_sah);
                    response.persentase_suara.push(response.persentase_tidak_sah);
                }

                // Urutkan data berdasarkan orderedLabels
                orderedLabels.forEach(function(label) {
                    var index = response.labels.indexOf(label);
                    if (index !== -1) {
                        orderedData.push(response.total_suara[index]);
                        orderedPersentase.push(response.persentase_suara[index]);
                    }
                });

                $('#totalDptContainer').text(`Total DPT: ${response.total_dpt}`);

                if (barChartGubernur) {
                    // Update chart dengan data yang sudah diurutkan
                    barChartGubernur.data.labels = orderedLabels.map((label, index) => `${label} (${orderedPersentase[index].toFixed(2)}%)`);
                    barChartGubernur.data.datasets[0].data = orderedData;
                    barChartGubernur.data.datasets[0].backgroundColor = orderedColors;
                    barChartGubernur.update();
                } else {
                    // Buat chart baru dengan data yang sudah diurutkan
                    barChartGubernur = new Chart(chartBarGubernur, {
                        type: 'bar',
                        data: {
                            labels: orderedLabels.map((label, index) => `${label} (${orderedPersentase[index].toFixed(2)}%)`),
                            datasets: [{
                                label: 'Total Suara',
                                backgroundColor: orderedColors,
                                borderColor: '#ffffff',
                                data: orderedData,
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                x: {
                                    beginAtZero: true,
                                    ticks: {
                                        padding: 10
                                    }
                                },
                                y: {
                                    beginAtZero: true
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false // Hide legend to avoid cluttering the chart
                                }
                            },
                            layout: {
                                padding: {
                                    right: 10
                                }
                            },
                            animation: {
                                duration: 0 // Disable animation for static chart
                            }
                        }
                    });
                }
            }
        });
    }

    loadChartGubernur(); // First load
    setInterval(loadChartGubernur, 5000); // Update every 5 seconds (can be removed if not needed)
</script>
<script>
    // Fungsi untuk memuat TPS yang sudah diinput
    function loadTps() {
        $.ajax({
            url: '<?= site_url('chart/loadtps') ?>', // Panggil route load-tps
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Update jumlah TPS yang sudah diinput
                $('#total-tps').text(data.tps_inputed + ' / ' + (data.tps_total));

                // Update persentase TPS yang sudah diinput
                $('#persen-tps').text(data.persen + '%');
            },
            error: function() {
                console.log('Terjadi kesalahan dalam memuat data TPS');
            }
        });
    }

    // Panggil loadTps pertama kali
    loadTps();

    // Panggil loadTps setiap 5 detik (5000ms)
    setInterval(loadTps, 5000);
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
                    console.log(response); // Debugging: melihat data respons

                    $('#grafikContainer').empty();

                    // Menentukan warna berdasarkan nama paslon dengan urutan yang benar
                    var warna = {
                        'Effendi - Audi': '#006BFF',
                        'Poltak - Anugerah': '#E72929',
                        'Robinson - Tonny': '#08C2FF',
                        'Suara Tidak Sah': '#808080'
                    };

                    response.dataGrafik.forEach(function(grafikData) {
                        let chartId = 'chart_' + grafikData.kecamatan.replace(/\s/g, '');
                        $('#grafikContainer').append(`
                            <div class="col-sm-6 col-xxl-3 pr-sm-2 mb-3 mb-xxl-0">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Grafik Suara Paslon Kecamatan ${grafikData.kecamatan}</h5>
                                        <p>Total Suara Sah: ${grafikData.total_suara_kecamatan}</p>
                                        <p>Total DPT: ${grafikData.total_dpt}</p>
                                    </div>
                                    <canvas id="${chartId}" width="1618" height="1000"></canvas>
                                </div>
                            </div>
                        `);

                        // Mengambil label dan data untuk chart
                        let labels = grafikData.data.map(function(item) {
                            return `${response.labels[item.id_paslon - 1]} (Suara: ${item.total_suara})`;
                        });
                        let data = grafikData.data.map(function(item) {
                            return item.total_suara;
                        });
                        let percentages = grafikData.data.map(function(item) {
                            return item.persentase;
                        });

                        // Tambahkan "Suara Tidak Sah" sebagai label dan data tambahan
                        labels.push("Suara Tidak Sah");
                        data.push(grafikData.total_tidak_sah);
                        percentages.push(((grafikData.total_tidak_sah / grafikData.total_dpt) * 100).toFixed(2));

                        // Membuat chart dengan warna yang sesuai
                        let ctx = document.getElementById(chartId).getContext('2d');
                        new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: labels.map((label, index) => `${label} (${percentages[index]}%)`),
                                datasets: [{
                                    data: data,
                                    backgroundColor: labels.map(label => warna[label.split(' (')[0]]) // mengambil nama paslon dari label untuk warna
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