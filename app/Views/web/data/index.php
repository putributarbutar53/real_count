<?php $this->extend('web/layout/main') ?>

<?php $this->section('content') ?>
<div class="card mb-3">
    <div class="bg-holder d-none d-lg-block bg-card" style="background-image:url(<?= base_url() ?>/assets/img/illustrations/corner-4.png);">
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-8">
                <h4 class="mb-0">Data
                </h4>
            </div>
        </div>
    </div>
</div>
<div class="card mb-3">
    <div class="card-header">
        <div class="row flex-between-center">
        </div>
    </div>
        <div class="card-body bg-light">
            <div class="row list">
                <div class="col">
                    <table id="table_index" width="100%" class="table mb-0 table-striped table-dashboard data-table border-bottom border-200">
                        <thead class="bg-200">
                            <tr>
                                <th><b>nama paslon</b></th>
                                <th><b>kecamatan</b></th>
                                <th><b>desa</b></th>
                                <th><b>tps</b></th>
                                <th><b>suara sah</b></th>
                                <th data-orderable="false"><b>#</b></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php $this->endSection() ?>
    <?php $this->section('script') ?>
    <script>
        function dataindex() {
            $('#table_index').DataTable({
                'processing': true,
                'serverSide': true,
                'scrollX': true,
                'serverMethod': 'post',
                'searchDelay': '350',
                'responsive': false,
                'lengthChange': true,
                'autoWidth': true,
                'sWrapper': 'falcon-data-table-wrapper',

                'ajax': {
                    'url': '<?= site_url('suara24/data/loaddata') ?>',
                    'headers': {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                },
                'columns': [{
                        data: 'nama_paslon',
                    },
                    {
                        data: 'nama_kec'
                    },
                    {
                        data: 'nama_desa'
                    },
                    {
                        data: 'tps'
                    },
                    {
                        data: 'suara_sah'
                    },
                    {
                        data: 'navButton',
                        render: function(data, type, row) {
                            if (row.username != 'admin')
                                return '<button onclick="editdata(' + row.id + ')" class="btn btn-sm btn-falcon-warning mb-1"><i class="fas fa-pen-square"></i></button>&nbsp;<button onclick="deletedata(' + row.id + ')" class="btn btn-sm btn-falcon-danger mb-1"><i class="fas fa-trash-alt"></i></button>';
                            else return "";
                        }
                    },
                ],
                // 'dom':'Bfrtip',
                // 'buttons':[
                // 'copy','csv','excel','pdf','print'
                // ],
                'order': [
                    [2, 'desc']
                ],
                'language': {
                    'emptyTable': 'Belum ada data'
                },
                'destroy': true,
            });
        }

    $(document).ready(function() {
        $('#table_index').DataTable().columns.adjust();
        setTimeout(function() {
            dataindex();
        }, 100);
    });

    function deletedata(iddata) {
        $('#alert_modal').modal('show');
        $("#click_yes").off("click").on("click", function() {
            $.ajax({
                type: 'DELETE',
                url: "<?= site_url('admin2011/admin/delete') ?>/" + iddata,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#alert_modal').modal('hide');
                    // Tanggapan sukses
                    showToast('success', response.message);
                    // Refresh tabel setelah penghapusan data
                    dataindex();
                },
                error: function(xhr, status, error) {
                    // Tanggapan error
                    showToastError(error, xhr.responseJSON);
                }
            });
        });
    }

    function editdata(iddata) {
        $.get("<?= site_url('suara24/data/edit') ?>/" + iddata, function(data, status) {
            $("#editor_add").html(data);
            $('#add').modal('toggle');
        });
    }

    function adddata() {
        $('#editor_add').load('<?= site_url('suara24/data/add') ?>', function() {
            $('#add').modal({
                show: true
            });
        });
    }
</script>

<?php $this->endSection() ?>