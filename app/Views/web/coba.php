<!-- home.php -->
<?php $this->extend('web/layout/main') ?>

<?php $this->section('content') ?>
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
    $('.kecamatan-checkbox').on('change', function() {
        let selectedKecamatan = [];
        $('.kecamatan-checkbox:checked').each(function() {
            selectedKecamatan.push($(this).val());
        });

        if (selectedKecamatan.length > 0) {
            $.ajax({
                url: '<?= base_url('coba/getGrafikByKecamatan') ?>',
                type: 'POST',
                data: {
                    kecamatan: selectedKecamatan
                },
                success: function(response) {
                    $('#grafikContainer').empty();

                    // Menentukan warna berdasarkan nama paslon
                    var warna = {};
                    response.labels.forEach(function(label) {
                        if (label === 'Effendi - Audi') {
                            warna[label] = '#006BFF';
                        } else if (label === 'Robinson - Tonny') {
                            warna[label] = '#08C2FF';
                        } else if (label === 'Poltak - Anugerah') {
                            warna[label] = '#E72929';
                        } else {
                            warna[label] = '#4BC0C0'; // Warna default
                        }
                    });

                    // Membuat grafik untuk setiap kecamatan
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
                            return item.total_suara;
                        });

                        // Membuat chart
                        let ctx = document.getElementById(chartId).getContext('2d');
                        new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: labels,
                                datasets: [{
                                    data: data,
                                    backgroundColor: labels.map(label => warna[label]) // Ambil warna sesuai label
                                }]
                            },
                            options: {
                                responsive: true,
                                title: {
                                    display: false // Matikan judul di chart karena kita sudah menambahkannya di card-header
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