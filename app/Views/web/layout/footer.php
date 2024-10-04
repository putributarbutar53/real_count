<footer>
    <div class="row no-gutters justify-content-between fs--1 mt-2 mb-3">
        <div class="col-12 col-sm-auto text-center">
            <p class="mb-0 text-600">Copyright &copy; 2024 - Diskominfo Toba<sup>&reg;</sup> - all rights reserved
                <?php if (session()->get('admin_role') == 'superadmin') : ?>
                    <span class="badge badge-danger text-white px-1">Super Admin</span>
                <?php else : ?>
                    <span class="badge badge-primary text-white px-1"><?= session()->get('admin_name') ?></span>
                    <span>--</span>
                    <span class="badge badge-danger text-white px-1">role:<?= session()->get('admin_role') ?></span>
                <?php endif; ?>

            </p>
        </div>
        <div class="col-12 col-sm-auto text-center">
            <p class="mb-0 text-600">&nbsp;</p>
        </div>
    </div>
</footer>