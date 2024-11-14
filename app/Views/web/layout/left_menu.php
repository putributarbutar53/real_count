<nav class="navbar navbar-vertical navbar-expand-xl navbar-light navbar-vibrant">
    <div class="d-flex align-items-center">
        <div class="toggle-icon-wrapper">

            <button class="btn navbar-toggler-humburger-icon navbar-vertical-toggle" data-toggle="tooltip" data-placement="left" title="Toggle Navigation"><span class="navbar-toggle-icon"><span class="toggle-line"></span></span></button>

        </div><a class="navbar-brand" href="<?php echo site_url('admin2053/dashboard') ?>">
            <div class="d-flex align-items-center py-3"><img class="mr-2" src="<?= base_url() ?>assets/img/logos/logo.svg" alt="" height="38" /></div>
        </a>
    </div>
    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
        <div class="navbar-vertical-content perfect-scrollbar scrollbar">
            <ul class="navbar-nav flex-column">
                <li class="nav-item<?php if (current_url() === site_url('chart')) { ?> active<?php } ?>">
                    <a class="nav-link" href="<?php echo site_url('chart') ?>">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-chart-pie"></span></span><span class="nav-link-text">Dashboard</span>
                        </div>
                    </a>
                </li>
            </ul>
            <div class="navbar-vertical-divider">
                <hr class="navbar-vertical-hr my-2" />
            </div>
            <ul class="navbar-nav flex-column">
                <li class="nav-item<?php if (current_url() === site_url('suara24/suara')) { ?> active<?php } ?>">
                    <a class="nav-link" href="<?php echo site_url('suara24/suara') ?>">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-plus-circle"></span></span><span class="nav-link-text">Input Suara</span>
                        </div>
                    </a>
                </li>
            </ul>
            <div class="navbar-vertical-divider">
                <hr class="navbar-vertical-hr my-2" />
            </div>
            <ul class="navbar-nav flex-column">
                <li class="nav-item<?php if (current_url() === site_url('hasil')) { ?> active<?php } ?>">
                    <a class="nav-link" href="<?php echo site_url('hasil') ?>">
                        <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-chart-line"></span></span><span class="nav-link-text">Hasil</span>
                        </div>
                    </a>
                </li>
            </ul>

            

            <?php if ((session()->get('admin_role') == 'superadmin') || (session()->get('admin_role') == 'admin')) { ?>
                <div class="navbar-vertical-divider">
                    <hr class="navbar-vertical-hr my-2" />
                </div>
                <ul class="navbar-nav flex-column">
                    <li class="nav-item<?php if (current_url() === site_url('suara24/data')) { ?> active<?php } ?>">
                        <a class="nav-link" href="<?php echo site_url('suara24/data') ?>">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-layer-group"></span></span><span class="nav-link-text">Data</span>
                            </div>
                        </a>
                    </li>
                </ul>
            <?php } ?>
            <div class="navbar-vertical-divider">
                <hr class="navbar-vertical-hr my-2" />
            </div>
            <?php if ((session()->get('admin_role') == 'superadmin') || (session()->get('admin_role') == 'admin')) { ?>
                <div class="navbar-vertical-divider">
                    <hr class="navbar-vertical-hr my-2" />
                </div>
                <ul class="navbar-nav flex-column">
                    <li class="nav-item<?php if (current_url() === site_url('suara24/dataProv')) { ?> active<?php } ?>">
                        <a class="nav-link" href="<?php echo site_url('suara24/dataProv') ?>">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-layer-group"></span></span><span class="nav-link-text">Data Prov</span>
                            </div>
                        </a>
                    </li>
                </ul>
            <?php } ?>
            <div class="navbar-vertical-divider">
                <hr class="navbar-vertical-hr my-2" />
            </div>
            <?php if (session()->has('admin_username')): ?>
                <ul class="navbar-nav flex-column">
                    <li class="nav-item<?php if (current_url() === site_url('suara24/login/logout')) { ?> active<?php } ?>">
                        <a class="nav-link" href="<?php echo site_url('suara24/login/logout') ?>">
                            <div class="d-flex align-items-center"><span class="nav-link-icon"><span class="fas fa-sign-out-alt"></span></span><span class="nav-link-text">Logout</span>
                            </div>
                        </a>
                    </li>
                </ul>
            <?php endif; ?>
            

        </div>
    </div>
</nav>