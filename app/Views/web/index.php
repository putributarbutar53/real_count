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
                <!-- <button class="btn btn-primary" onclick="adddata()"><i class="fas fa-plus-square"></i> Tambah Category</button> -->
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
                            <h1><?= $item['id'] ?></h1>
                            <h5 class="card-title"><?= $item['nama_paslon'] ?></h5>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php $this->endsection() ?>
<?php $this->section('script') ?>
<?php $this->endsection() ?>