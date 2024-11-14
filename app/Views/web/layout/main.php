<!DOCTYPE html>
<html lang="en-US" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>Website Real Count</title>

    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url() ?>assets/favicon/img/logo/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url() ?>assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url() ?>assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url() ?>assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="<?= base_url() ?>favicon/site.webmanifest">
    <meta name="theme-color" content="#ffffff">
    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link href="<?= base_url() ?>assets/lib/flatpickr/flatpickr.min.css" rel="stylesheet">

    <script src="<?= base_url() ?>assets/js/config.navbar-vertical.js"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="<?= base_url() ?>assets/lib/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/lib/prismjs/prism-okaidia.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/lib/datatables-bs4/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/lib/datatables.net-responsive-bs4/responsive.bootstrap4.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/lib/fancybox/jquery.fancybox.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/lib/select2/select2.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/lib/toastr/toastr.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/css/theme.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="<?= base_url() ?>assets/js/jquery.min.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>



    <!-- datatables -->
    <script src="<?= base_url() ?>assets/lib/datatables/js/jquery.dataTables.min.js"></script>
    <script src="<?= base_url() ?>assets/lib/datatables-bs4/dataTables.bootstrap4.min.js"></script>
    <script src="<?= base_url() ?>assets/lib/datatables.net-responsive/dataTables.responsive.js"></script>
    <script src="<?= base_url() ?>assets/lib/datatables.net-responsive-bs4/responsive.bootstrap4.js"></script>
    <style>
        .tps-form {
            border: 1px solid #ccc;
            /* Tambahkan border pada TPS form */
            padding: 20px;
            /* Tambahkan padding */
            margin-bottom: 20px;
            /* Tambahkan jarak antar TPS form */
            border-radius: 5px;
            /* Tambahkan sudut melengkung */
        }

        .is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            color: #dc3545;
        }

        .has-error {
            border: 2px solid #dc3545;
            padding: 1px;
            border-radius: 5px;
            height: 309px;
            /* Tinggi default untuk tampilan desktop */
        }

        /* Media query untuk tampilan mobile */
        @media (max-width: 768px) {

            /* Kamu bisa sesuaikan 768px dengan batas lebar yang diinginkan */
            .has-error {
                height: auto;
                /* Mengubah tinggi menjadi otomatis di tampilan mobile */
                min-height: 150px;
                /* Menentukan tinggi minimum agar terlihat lebih baik */
            }
        }
    </style>
    <style>
        /* Center the chart and reduce white space */
        .card-center {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 5px;
            /* Reduce padding inside the card */
            padding-top: 10px;
            height: auto;
            width: 100%;
            /* Atur lebar sesuai kebutuhan */
            /* max-width: 350px; */
            background-color: #ffffff;
            /* Keeps the white background */
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            /* Optional: subtle shadow for separation */
        }

        /* Fixed size for charts */
        #pie,
        #chart-pie-gubernur {
            width: 450px !important;
            height: 450px !important;
            transform: translateY(-89px);
            max-width: 100%;
            max-height: 100%;
        }

        /* Adjust card container for smaller screens */
        @media (max-width: 768px) {
            .card-center {
                flex-direction: column;
                padding: 8px;
                /* Reduce padding for smaller screens */
                margin: 5px;
                /* Optional: add margin for spacing */
            }

            #pie,
            #chart-pie-gubernur {
                transform: translateY(-50px);
            }
        }

        @media (max-width: 576px) {
            .card-center {
                padding: 5px;
                /* Further reduce padding */
                margin: 5px;
            }

            #pie,
            #chart-pie-gubernur {
                transform: translateY(-30px);
            }
        }
    </style>
</head>

<body>

    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">

        <div class="container-fluid" data-layout="container">
            <?= $this->include('web/layout/left_menu') ?>
            <div class="content">

                <?= $this->include('web/layout/top_menu') ?>
                <!-- Render the content section -->
                <?= $this->renderSection('content') ?>

                <?= $this->include('web/layout/footer') ?>
                <?= $this->include('web/layout/modal-lg') ?>
                <?= $this->include('web/layout/modal') ?>
            </div>
        </div>
    </main>
    <!-- ===============================================-->
    <!--    End of Main Content-->
    <!-- ===============================================-->

    <!-- ===============================================-->
    <!--    JavaScripts-->
    <!-- ===============================================-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@latest"></script>
    <script src="<?= base_url() ?>assets/js/popper.min.js"></script>
    <script src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>
    <script src="<?= base_url() ?>assets/lib/@fortawesome/all.min.js"></script>
    <script src="<?= base_url() ?>assets/lib/stickyfilljs/stickyfill.min.js"></script>
    <script src="<?= base_url() ?>assets/lib/sticky-kit/sticky-kit.min.js"></script>
    <script src="<?= base_url() ?>assets/lib/is_js/is.min.js"></script>
    <script src="<?= base_url() ?>assets/lib/lodash/lodash.min.js"></script>
    <script src="<?= base_url() ?>assets/lib/perfect-scrollbar/perfect-scrollbar.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700%7cPoppins:100,200,300,400,500,600,700,800,900&amp;display=swap" rel="stylesheet">
    <script src="<?= base_url() ?>assets/lib/prismjs/prism.js"></script>

    <script src="<?= base_url() ?>assets/lib/datatables/js/jquery.dataTables.min.js"></script>
    <script src="<?= base_url() ?>assets/lib/datatables-bs4/dataTables.bootstrap4.min.js"></script>
    <script src="<?= base_url() ?>assets/lib/datatables.net-responsive/dataTables.responsive.js"></script>
    <script src="<?= base_url() ?>assets/lib/datatables.net-responsive-bs4/responsive.bootstrap4.js"></script>
    <script src="<?= base_url() ?>assets/lib/flatpickr/flatpickr.min.js"></script>
    <script src="<?= base_url() ?>assets/lib/fancybox/jquery.fancybox.min.js"></script>
    <script src="<?= base_url() ?>assets/lib/select2/select2.min.js"></script>
    <script src="<?= base_url() ?>assets/lib/tinymce/tinymce.min.js"></script>
    <script src="<?= base_url() ?>assets/lib/toastr/toastr.min.js"></script>
    <script src="<?= base_url() ?>assets/js/theme.js"></script>
    <script src="<?= base_url() ?>assets/lib/echarts/echarts.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



    <script>
        function showToast(type, message, title = null) {
            var defaultOptions = {
                closeButton: true,
                newestOnTop: false,
                positionClass: 'toast-bottom-right'
            };
            var vTitle = (title != null) ? title : type;
            toastr.options = defaultOptions;

            switch (type) {
                case 'success':
                    toastr.success(message, vTitle);
                    break;

                case 'warning':
                    toastr.warning(message, vTitle);
                    break;

                case 'error':
                    toastr.error(message, vTitle);
                    break;

                default:
                    toastr.info(message, vTitle);
                    break;
            }
        }

        function showToastError(error, eJson = null) {
            var defaultOptions = {
                closeButton: true,
                newestOnTop: false,
                positionClass: 'toast-bottom-right'
            };
            toastr.options = defaultOptions;

            if (eJson && eJson.errors) {
                // Menggunakan for...in loop
                for (var key in eJson.errors) {
                    toastr.error(eJson.errors[key], key);
                }
            } else {
                toastr.error(error, "Error");
            }
        }
    </script>

    <?= $this->renderSection('script') ?>
</body>

</html>