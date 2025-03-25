<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= get_settings()->setting_favicon == null ? '/image/logo-smea.png' : '/img/icons/' . get_settings()->setting_favicon; ?>">
    <title><?= isset($title) ? $title : 'Halaman Baru'; ?> &mdash; <?= isset(get_settings()->setting_meta_title) ? get_settings()->setting_meta_title : 'Website BK'; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= base_url('stisla') ?>/node_modules/jqvmap/dist/jqvmap.min.css">
    <link rel="stylesheet" href="<?= base_url('stisla') ?>/node_modules/weathericons/css/weather-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('stisla') ?>/node_modules/weathericons/css/weather-icons-wind.min.css">
    <link rel="stylesheet" href="<?= base_url('stisla') ?>/node_modules/summernote/dist/summernote-bs4.css">
    <link rel="stylesheet" href="<?= base_url('stisla') ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= base_url('stisla') ?>/assets/css/custom.css">
    <link rel="stylesheet" href="<?= base_url('stisla') ?>/assets/css/components.css">
    <link rel="stylesheet" href="<?= base_url('stisla') ?>/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= base_url('stisla') ?>/node_modules/datatables.net-select-bs4/css/select.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= base_url('stisla') ?>/node_modules/bootstrap-daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="<?= base_url('stisla') ?>/node_modules/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="<?= base_url('stisla') ?>/node_modules/selectric/public/selectric.css">
    <link rel="stylesheet" href="<?= base_url('stisla') ?>/node_modules/bootstrap-timepicker/css/bootstrap-timepicker.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/extra-assets/ijaboCropTool/ijaboCropTool.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/apexcharts/dist/apexcharts.css">
    </link>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://unpkg.com/nprogress@0.2.0/nprogress.css">
    <script src="https://unpkg.com/nprogress@0.2.0/nprogress.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <?= $this->renderSection('styles') ?>
</head>

<body>
    <div id="app">
        <div class="main-wrapper">
            <div class="navbar-bg"></div>
            <?= $this->include('App\Modules\Layouts\Views\partials\navbar.php') ?>
            <?= $this->include('App\Modules\Layouts\Views\partials\sidebar.php') ?>
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1><?= $title ?></h1>
                        <div class="section-header-breadcrumb">
                            <div class="breadcrumb-item"><?= $title ?></div>
                        </div>
                    </div>
                    <?= $this->renderSection('content') ?>
                </section>
            </div>
            <?= $this->include('App\Modules\Layouts\Views\partials\footer.php') ?>
        </div>
    </div>

    <div class="loading-bar" id="loadingBar"></div>

    <!-- General JS Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="<?= base_url('stisla') ?>/assets/js/stisla.js"></script>
    <script src="<?= base_url('stisla') ?>/node_modules/simpleweather/jquery.simpleWeather.min.js"></script>
    <script src="<?= base_url('stisla') ?>/node_modules/chart.js/dist/Chart.min.js"></script>
    <script src="<?= base_url('stisla') ?>/node_modules/jqvmap/dist/jquery.vmap.min.js"></script>
    <script src="<?= base_url('stisla') ?>/node_modules/jqvmap/dist/maps/jquery.vmap.world.js"></script>
    <script src="<?= base_url('stisla') ?>/node_modules/summernote/dist/summernote-bs4.js"></script>
    <script src="<?= base_url('stisla') ?>/node_modules/chocolat/dist/js/jquery.chocolat.min.js"></script>
    <script src="<?= base_url('stisla') ?>/assets/js/scripts.js"></script>
    <script src="<?= base_url('stisla') ?>/assets/js/custom.js"></script>
    <script src="<?= base_url('stisla') ?>/node_modules/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="<?= base_url('stisla') ?>/node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?= base_url('stisla') ?>/node_modules/datatables.net-select-bs4/js/select.bootstrap4.min.js"></script>
    <script src="<?= base_url('stisla') ?>/node_modules/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="<?= base_url('stisla') ?>/node_modules/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
    <script src="<?= base_url('stisla') ?>/node_modules/select2/dist/js/select2.full.min.js"></script>
    <script src="<?= base_url('stisla') ?>/node_modules/selectric/public/jquery.selectric.min.js"></script>
    <script src="<?= base_url() ?>/sweetalert2/sweetalert2.min.js"></script>
    <script src="<?= base_url() ?>/extra-assets/ijaboCropTool/ijaboCropTool.min.js"></script>
    <script src="<?= base_url() ?>/apexcharts/dist/apexcharts.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>

</html>