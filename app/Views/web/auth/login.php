<?php $this->extend('web/layout/main') ?>

<?php $this->section('content') ?>
    <main class="main" id="top">


        <div class="container" data-layout="container">
            <div class="row flex-center min-vh-100 py-6">
                <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
                    <div class="card">
                        <div class="card-body p-4 p-sm-5">
                            <!-- <img src="<?php echo base_url() ?>assets/img/logos/logos.png" alt="" /> -->
                            <a class="d-flex flex-center mb-4" href="<?= site_url('/login') ?>"><img class="mr-2" src="<?php echo base_url() ?>assets/img/logos/golo.png" alt="" width="345px" /></a>

                            <?php
                            $session = \Config\Services::session();
                            if ($session->getFlashdata('warning')) {
                            ?>
                                <?php
                                foreach ($session->getFlashdata('warning') as $val) {
                                ?><div class="alert alert-danger alert-dismissible mb-1 fade show" role="alert"><?= $val ?>
                                        <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span class="font-weight-light" aria-hidden="true">×</span></button>
                                    </div>
                                <?php
                                }
                                ?>
                            <?php
                            }
                            if ($session->getFlashdata('success')) {
                            ?>
                                <div class="alert alert-success"><?php echo $session->getFlashdata('success') ?></div>
                            <?php
                            }
                            ?>

                            <div class="row text-left justify-content-between align-items-center mb-2">
                                <div class="col-auto">
                                    <h5>Log in</h5>
                                </div>
                            </div>
                            <form method="POST" action="<?php echo site_url('suara24/login') ?>">
                                <div class="form-group">
                                    <input class="form-control" type="text" name="username" placeholder="Username" value="<?php if ($session->getFlashdata('username')) echo $session->getFlashdata('username') ?>" />
                                </div>
                                <div class="form-group">
                                    <input class="form-control" type="password" name="password" placeholder="Password" />
                                </div>
                                <div class="row justify-content-between align-items-center">
                                    <div class="col-auto">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="basic-checkbox" name="remember_me" value="1" />
                                            <label class="custom-control-label" for="basic-checkbox">Remember me</label>
                                        </div>
                                    </div>
                                    <div class="col-auto"><a class="fs--1" href="<?php echo site_url('/forgotpassword') ?>">Forgot Password?</a></div>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary btn-block mt-3" type="submit" name="submit">Log in</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php $this->endSection()?>
<?php $this->section('script')?>
<script>
     function adddata() {
        $('#editor_add').load('<?= site_url('suara24/data/add') ?>', function() {
            $('#add').modal({
                show: true
            });
        });
    }
</script>
<?php $this->endSection()?>