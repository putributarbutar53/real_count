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
        <button class="btn btn-falcon-success mr-1 mb-1" id="export" type="button" style="margin-left: 15px;">
            <i class="fas fa-file-excel"></i>
            Export
        </button>

    </div>
    <div class="card-body bg-light">
        <div class="row list">
            <div class="col">
                <table id="table_index" width="100%" class="table mb-0 table-striped table-dashboard data-table border-bottom border-200">
                    <thead class="bg-200">
                        <tr>

                            <th><b>No</b></th>
                            <th><b>Nama paslon</b></th>
                            <th><b>kecamatan</b></th>
                            <th><b>desa</b></th>
                            <th><b>tps</b></th>
                            <th><b>suara sah</b></th>
                            <th><b>Operator</b></th>
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
                'url': '<?= site_url('suara24/dataProv/loaddata') ?>',
                'headers': {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            },
            'columns': [
                {
                    data:'id'
                },
                {
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
                    data: 'username'
                },
                {
                    data: 'navButton',
                    render: function(data, type, row) {
                        if (row.username != 'superadmin')
                            return '<button onclick="editdata(' + row.id + ')" class="btn btn-sm btn-falcon-warning mb-1"><i class="fas fa-pen-square"></i></button>';
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
                url: "<?= site_url('suara24/suara/delete') ?>/" + iddata,
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
        $.get("<?= site_url('suara24/dataProv/edit') ?>/" + iddata, function(data, status) {
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

    document.getElementById('export').addEventListener('click', function() {
        window.location.href = '<?= base_url('suara24/dataProv/exportExcel') ?>';
    });
</script>
</script>

<?php $this->endSection() ?>