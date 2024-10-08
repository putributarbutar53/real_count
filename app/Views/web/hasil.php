<!-- home.php -->
<?php $this->extend('web/layout/main') ?>

<?php $this->section('content') ?>
<!-- Page content goes here -->


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
<?php $this->endsection() ?>
<?php $this->section('script') ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<?php $this->endsection() ?>