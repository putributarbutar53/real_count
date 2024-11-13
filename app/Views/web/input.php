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
                <h4 class="mb-0">Input Suara Pada Setiap Paslon
                </h4>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-center">
            <div class="col">
                <form id="formInput" action="<?= base_url('suara24/suara/save') ?>" method="post">
                    <div class="row">
                        <!-- Kecamatan, Desa, Kode Konfirmasi -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kecamatan">Pilih Kecamatan</label>
                                <select class="form-control selectpicker" id="kecamatan" name="id_kec" readonly>
                                    <?php foreach ($kecamatan as $kec) : ?>
                                        <option value="<?= $kec['id'] ?>" <?= (session()->get('admin_id_kec') == $kec['id']) ? 'selected' : '' ?>><?= $kec['nama_kec'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="desa">Pilih Desa</label>
                                <select class="form-control selectpicker" id="desa" name="id_desa" disabled>
                                    <option value="">-- Pilih Desa --</option>
                                </select>
                            </div>
                        </div>

                        <!-- <div class="col-md-4">
                            <div class="form-group">
                                <label for="kode_konfirmasi">Kode Konfirmasi</label>
                                <input type="text" class="form-control" id="kode_konfirmasi" name="kode_konfirmasi" placeholder="Masukkan Kode Konfirmasi">
                                <small>Masukkan kode konfirmasi yang telah diberikan admin</small>
                            </div>
                        </div> -->
                    </div>

                    <!-- Nama Paslon dari Database & TPS Dinamis -->
                    <div id="tpsContainer">
                        <!-- Initial TPS form -->
                        <div class="tps-form" id="tps1">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="tps_number">TPS</label>
                                        <input type="number" class="form-control" name="tps_number[]" placeholder="No TPS" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <?php foreach ($paslon as $key => $p) : ?>
                                    <!-- Nama Paslon dan Input Suara Sah -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="paslon<?= $key + 1 ?>"><?= $p['nama_paslon'] ?></label>
                                            <input type="hidden" name="id_paslon[]" value="<?= $p['id'] ?>">
                                            <input type="number" class="form-control" name="suara_sah[<?= $p['id'] ?>][]" placeholder="Suara Sah <?= $p['nama_paslon'] ?>" required>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                                <!-- Jumlah Suara Tidak Sah -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="tidak_sah">Jumlah Suara Tidak Sah</label>
                                        <input type="number" class="form-control" name="tidak_sah[]" placeholder="Jumlah Suara Tidak Sah" required>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-danger removeTPSButton"><i class="fas fa-trash"></i> </button>
                        </div>
                    </div>

                    <div class="form-group d-flex justify-content-end"> <!-- Menggunakan flexbox untuk mengatur alignment -->
                        <button type="button" class="btn btn-primary" id="addTPSButton">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>


                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-paper-plane"></i> Kirim
                    </button>

                </form>

            </div>
        </div>
    </div>
</div>
<?php $this->endsection() ?>
<?php $this->section('script') ?>
<script>
    document.getElementById('formInput').addEventListener('submit', function(event) {
        event.preventDefault(); // Mencegah submit form secara langsung
        checkDuplicateData();
    });

    function checkDuplicateData() {
        // Ambil data yang akan diperiksa
        var kecamatan = document.getElementById('kecamatan').value;
        var desa = document.getElementById('desa').value;
        var tpsNumber = document.querySelector('[name="tps_number[]"]').value;

        // AJAX Request
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '<?= base_url('suara24/suara/checkDuplicate') ?>', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.exists) {
                    // Jika data sudah ada, tampilkan peringatan
                    displayError('Data ini sudah pernah ditambahkan ke database.');
                } else {
                    // Jika tidak ada duplikasi, submit form
                    document.getElementById('formInput').submit();
                }
            }
        };
        xhr.send('kecamatan=' + kecamatan + '&desa=' + desa + '&tpsNumber=' + tpsNumber);
    }

    function displayError(message) {
        // Tambahkan pesan error di input TPS
        var tpsInput = document.querySelector('[name="tps_number[]"]');
        tpsInput.classList.add('is-invalid'); // Tambah class bootstrap merah
        var errorDiv = document.createElement('div');
        errorDiv.classList.add('invalid-feedback');
        errorDiv.innerText = message;
        tpsInput.parentElement.appendChild(errorDiv);

        // Tambahkan class merah ke pembungkus form
        var tpsContainer = document.getElementById('tpsContainer');
        tpsContainer.classList.add('has-error');
    }
</script>
<script>
    let tpsCount = 1;

    document.getElementById('addTPSButton').addEventListener('click', function() {
        tpsCount++;
        const tpsContainer = document.getElementById('tpsContainer');

        // Create new TPS form
        const newTpsForm = document.createElement('div');
        newTpsForm.classList.add('tps-form');
        newTpsForm.setAttribute('id', 'tps' + tpsCount);

        // Add the form structure for new TPS
        newTpsForm.innerHTML = `
            <div class="row align-items-center">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="tps_number">TPS</label>
                        <input type="number" class="form-control" name="tps_number[]" placeholder="No TPS" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <?php foreach ($paslon as $key => $p) : ?>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="paslon${<?= $key + 1 ?>}"><?= $p['nama_paslon'] ?></label>
                        <input type="hidden" name="id_paslon[]" value="<?= $p['id'] ?>">
                        <input type="number" class="form-control" name="suara_sah[${<?= $p['id'] ?>}][]" placeholder="Suara Sah <?= $p['nama_paslon'] ?>" required>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="tidak_sah">Jumlah Suara Tidak Sah</label>
                        <input type="number" class="form-control" name="tidak_sah[]" placeholder="Jumlah Suara Tidak Sah" required>
                    </div>
                </div>
            </div>

             <button type="button" class="btn btn-danger removeTPSButton"><i class="fas fa-trash"></i> </button>
        `;

        // Append the new TPS form to the container
        tpsContainer.appendChild(newTpsForm);
    });

    // Event delegation to handle removal of TPS forms
    document.getElementById('tpsContainer').addEventListener('click', function(event) {
        if (event.target.classList.contains('removeTPSButton')) {
            event.target.closest('.tps-form').remove(); // Hapus TPS form yang diklik
        }
    });
</script>
<script>
    $(document).ready(function() {
        // Mengambil id_kec dari session
        var id_kec = $('#kecamatan').val();

        if (id_kec) {
            loadDesa(id_kec); // Panggil fungsi untuk load desa berdasarkan id_kec dari session
        }

        // Saat kecamatan diubah
        $('#kecamatan').on('change', function() {
            var id_kec = $(this).val();
            if (id_kec) {
                loadDesa(id_kec);
            } else {
                $('#desa').empty().append('<option value="">-- Pilih Desa --</option>').prop('disabled', true);
            }
        });

        // Fungsi untuk memuat desa berdasarkan kecamatan
        function loadDesa(id_kec) {
            $.ajax({
                url: "<?= site_url('suara24/suara/getDesaByKecamatan') ?>/" + id_kec,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('#desa').empty().append('<option value="">-- Pilih Desa --</option>');
                    $.each(data, function(key, value) {
                        $('#desa').append('<option value="' + value.id + '">' + value.nama_desa + '</option>');
                    });
                    $('#desa').prop('disabled', false);
                },
                error: function() {
                    alert('Terjadi kesalahan saat mengambil data desa');
                }
            });
        }
    });

    $(document).ready(function() {
        // Saat kecamatan dipilih
        $('#kecamatan').change(function() {
            var kecamatanId = $(this).val();

            // Reset pilihan desa
            $('#desa').html('<option value="">-- Pilih Desa --</option>').prop('disabled', true);

            if (kecamatanId) {
                $.ajax({
                    url: "<?= base_url('suara24/suara/getDesaByKecamatan') ?>" + "/" + kecamatanId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        if (data.length > 0) {
                            $('#desa').prop('disabled', false); // Aktifkan dropdown desa
                            $.each(data, function(key, value) {
                                $('#desa').append('<option value="' + value.id + '">' + value.nama_desa + '</option>');
                            });
                        }
                    },
                    error: function() {
                        console.error('Gagal mengambil data desa.');
                    }
                });
            }
        });
    });
</script>
<script>
    <?php if (session()->get('error')) : ?>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '<?= session()->get('error') ?>',
        });
    <?php endif; ?>

    <?php if (session()->get('success')) : ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '<?= session()->get('success') ?>',
        });
    <?php endif; ?>
</script>

<?php $this->endsection() ?>