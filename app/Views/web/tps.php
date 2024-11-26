<!-- home.php -->
<?php $this->extend('web/layout/main') ?>

<?php $this->section('content') ?>
<!-- Page content goes here -->


<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-center">
            <div class="col">
                <div class="dashboard-data-table">
                    <table class="table table-sm table-dashboard data-table no-wrap mb-0 fs--1 w-100">
                        <thead class="bg-200">
                            <tr>
                                <th class="sort">Kecamatan</th>
                                <th class="sort">Desa/Kel</th>
                                <th class="sort">TPS</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            <?php if (!empty($dptBelumInput)) : ?>
                                <?php foreach ($dptBelumInput as $row) : ?>
                                    <tr>
                                        <td><?= esc($row['nama_kec']); ?></td>
                                        <td><?= esc($row['nama_desa']); ?></td>
                                        <td><?= esc($row['tps']); ?></td>

                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada data TPS yang belum diinput</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->endsection() ?>
<?php $this->section('script') ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/lib/datatables/js/jquery.dataTables.min.js"></script>
<script src="assets/lib/datatables-bs4/dataTables.bootstrap4.min.js"></script>
<script src="assets/lib/datatables.net-responsive/dataTables.responsive.js"></script>
<script src="assets/lib/datatables.net-responsive-bs4/responsive.bootstrap4.js"></script>
<?php $this->endsection() ?>