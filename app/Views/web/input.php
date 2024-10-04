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
                <form action="<?= base_url('suara/save') ?>" method="post">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="kecamatan">Pilih Kecamatan</label>
                                <select class="form-control" id="kecamatan" name="id_kec">
                                    <option value="">-- Pilih Kecamatan --</option>
                                    <?php foreach ($kecamatan as $kec) : ?>
                                        <option value="<?= $kec['id'] ?>"><?= $kec['nama_kec'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="desa">Pilih Desa</label>
                                <select class="form-control" id="desa" name="id_desa" disabled>
                                    <option value="">-- Pilih Desa --</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="kode_konfirmasi">Kode Konfirmasi</label>
                                <input type="text" class="form-control" id="kode_konfirmasi" name="kode_konfirmasi" placeholder="Masukkan Kode Konfirmasi">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="paslon">Pilih Paslon</label>
                                <select class="form-control" id="paslon" name="id_paslon">
                                    <option value="">-- Pilih Paslon --</option>
                                    <?php foreach ($paslon as $p) : ?>
                                        <option value="<?= $p['id'] ?>"><?= $p['nama_paslon'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="tps">Nama TPS</label>
                                <input type="text" class="form-control" id="tps" name="tps" placeholder="Masukkan Nama TPS">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="suara_sah">Jumlah Suara Sah</label>
                                <input type="number" class="form-control" id="suara_sah" name="suara_sah" placeholder="Masukkan Jumlah Suara Sah" oninput="calculateTotal()">
                            </div>

                            <div class="form-group">
                                <label for="tidak_sah">Jumlah Suara Tidak Sah</label>
                                <input type="number" class="form-control" id="tidak_sah" name="tidak_sah" placeholder="Masukkan Jumlah Suara Tidak Sah" oninput="calculateTotal()">
                            </div>

                            <div class="form-group">
                                <label for="jlh_suara">Total Suara</label>
                                <input type="number" class="form-control" id="jlh_suara" name="jlh_suara" placeholder="Total Suara" readonly>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-danger">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $this->endsection() ?>
<?php $this->section('script') ?>
<script>
    $(document).ready(function() {
        // Saat kecamatan dipilih
        $('#kecamatan').change(function() {
            var kecamatanId = $(this).val();

            // Reset pilihan desa
            $('#desa').html('<option value="">-- Pilih Desa --</option>').prop('disabled', true);

            if (kecamatanId) {
                $.ajax({
                    url: "<?= base_url('suara/getDesaByKecamatan') ?>" + "/" + kecamatanId,
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
    function calculateTotal() {
        const suaraSah = parseInt(document.getElementById('suara_sah').value) || 0;
        const tidakSah = parseInt(document.getElementById('tidak_sah').value) || 0;

        const totalSuara = suaraSah + tidakSah;

        document.getElementById('jlh_suara').value = totalSuara;
    }
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