<!-- home.php -->
<?php $this->extend('web/layout/main') ?>

<?php $this->section('content') ?>
<!-- Page content goes here -->

<div class="card mb-3">
    <div class="bg-holder d-none d-lg-block bg-card" style="background-image:url(<?= base_url() ?>/assets/img/illustrations/corner-4.png);">
    </div>
    <!--/.bg-holder-->
    <div class="card-body">
        <div class="row">
            <div class="col-lg-8">
                <h4 class="mb-0">Rekap Perolehan Suara Sah Pemilihan Bupati Toba/Wakil Bupati Toba Tahun 2024
                    Rabu, 27 Nopember 2024
                </h4>
            </div>
        </div>
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

<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-center">
            <div class="col">
                <table id="hasilSuaraTable" class="table table-sm table-dashboard no-wrap mb-0 fs--1 w-100">
                    <thead class="bg-200">
                        <tr>
                            <th class="sort">No</th>
                            <th class="sort">Paslon</th>
                            <th class="sort">Kec</th>
                            <th class="sort">Desa</th>
                            <th class="sort">TPS</th>
                            <th class="sort">Suara Sah</th>
                            <th class="sort">Suara Tidak Sah</th>
                            <th class="sort">Jumlah Suara</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <!-- Data akan diisi oleh AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card-body bg-light">
        <div class="row">

        </div>
    </div>
</div>
<?php $this->endsection() ?>
<?php $this->section('script') ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function refreshSuara() {
        $.ajax({
            url: '<?= base_url("hasil/getget") ?>', // Ganti dengan nama controller Anda
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Update suara di tabel
                for (const kec_id in data) {
                    for (const paslon in data[kec_id].suara) {
                        const suara_sah = data[kec_id].suara[paslon].suara_sah;
                        const elementId = `suara-${kec_id}-${paslon}`;
                        const $element = $(`#${elementId} .countup`);
                        $element.attr('data-countup', `{"count":${suara_sah}}`);
                        $element.text(0); // Reset angka sebelum animasi
                        countUp($element); // Panggil fungsi untuk menghitung angka
                    }
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error fetching data: ", textStatus, errorThrown);
            }
        });
    }

    function countUp(element) {
        const count = JSON.parse(element.attr('data-countup')).count;
        $(element).text(count);
        // Jika menggunakan animasi, tambahkan logika di sini
    }

    setInterval(refreshSuara, 180000); // Setiap 3 menit
</script>
<script>
    function fetchHasilSuara() {
        $.ajax({
            url: '<?= base_url('hasil/data') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                var tbody = $('#hasilSuaraTable tbody');
                tbody.empty();

                if (data.length > 0) {
                    $.each(data, function(index, hasil) {
                        var jumlahSuara = hasil.suara_sah + hasil.tidak_sah;
                        tbody.append('<tr>' +
                            '<td>' + (index + 1) + '</td>' +
                            '<td>' + hasil.nama_paslon + '</td>' +
                            '<td>' + hasil.nama_kec + '</td>' +
                            '<td>' + hasil.nama_desa + '</td>' +
                            '<td>' + hasil.tps + '</td>' +
                            '<td>' + hasil.suara_sah + '</td>' +
                            '<td>' + hasil.tidak_sah + '</td>' +
                            '<td>' + jumlahSuara + '</td>' +
                            '</tr>');
                    });

                    // Inisialisasi DataTables
                    $('#hasilSuaraTable').DataTable({
                        "destroy": true, // Hapus instansi sebelumnya
                        "paging": true,
                        "searching": true,
                        "ordering": true
                    });
                } else {
                    tbody.append('<tr><td colspan="8" class="text-center">Tidak ada data tersedia</td></tr>');
                }
            },
            error: function() {
                console.error('Error fetching data');
            }
        });
    }

    setInterval(fetchHasilSuara, 500);
    $(document).ready(function() {
        fetchHasilSuara();
    });
</script>

<?php $this->endsection() ?>