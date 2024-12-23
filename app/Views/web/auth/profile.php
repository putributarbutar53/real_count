<!-- home.php -->
<?php $this->extend('web/layout/main') ?>

<?php $this->section('content') ?>
<div class="row no-gutters">

    <div class="col-lg-4 pl-lg-2">
        <div class="sticky-top sticky-sidebar">
            <div class="card mb-3 overflow-hidden">
                <div class="card-header">
                    <h5 class="mb-0">Change Password</h5>
                </div>
                <div class="card-body bg-light">
                    <form id="change_password">
                        <div class="form-group">
                            <label for="old-password">Old Password</label>
                            <input class="form-control" id="old-password" name="oPassword" type="password">
                        </div>
                        <div class="form-group">
                            <label for="new-password">New Password</label>
                            <input class="form-control" id="new-password" name="nPassword" type="password">
                        </div>
                        <div class="form-group">
                            <label for="confirm-password">Confirm Password</label>
                            <input class="form-control" id="confirm-password" name="cPassword" type="password">
                        </div>
                        <button class="btn btn-primary btn-block" type="submit">Update Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->endsection() ?>

<?php $this->section('script') ?>
<script>
    $(document).ready(function() {
        $('#change_password').on('submit', function(e) {
            e.preventDefault();
            var form = $(this)[0];
            var formData = new FormData(form);
            $.ajax({
                url: "<?= site_url('suara24/profile/change_password') ?>",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    showToast(response.status, response.message);
                },
                error: function(xhr, status, error) {
                    var response = xhr.responseJSON;
                    showToastError('Error', response);
                }
            });
        });

      });
</script>
<?php $this->endsection() ?>