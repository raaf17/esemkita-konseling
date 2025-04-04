<?= $this->extend('App\Modules\Layouts\Views\app.php') ?>

<?= $this->section('content') ?>
<ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-6 fw-semibold mb-5">
    <li class="nav-item">
        <a class="nav-link text-active-primary pb-1 active" data-bs-toggle="tab" href="#umum">Umum</a>
    </li>
    <li class="nav-item">
        <a class="nav-link text-active-primary pb-1" data-bs-toggle="tab" href="#sekolah">Sekolah</a>
    </li>
    <li class="nav-item">
        <a class="nav-link text-active-primary pb-1" data-bs-toggle="tab" href="#logo_dan_favicon">Logo & Favicon</a>
    </li>
</ul>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade active show" id="umum" role="tabpanel">
        <form class="form" action="<?= route_to('settings.updategeneralsetting') ?>" method="POST" id="general_settings_form">
            <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" class="ci_csrf_data">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h2 class="text-gray-800">Pengaturan Umum</h2>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-5">
                        <div class="col-md-2 text-md-end">
                            <label class="fs-6 fw-semibold form-label mt-3">
                                <span class="required">Meta Title</span>
                            </label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" class="form-control form-control" name="setting_meta_title" value="<?= get_settings()->setting_meta_title; ?>" />
                            <span class="text-danger error_text setting_meta_title_error"></span>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-md-2 text-md-end">
                            <label class="fs-6 fw-semibold form-label mt-3">
                                <span>Meta Description :</span>
                            </label>
                        </div>
                        <div class="col-md-10">
                            <textarea class="form-control form-control" name="setting_meta_description" cols="30" rows="3"><?= get_settings()->setting_meta_description; ?></textarea>
                            <span class="text-danger error_text setting_meta_description_error"></span>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-md-2 text-md-end">
                            <label class="fs-6 fw-semibold form-label mt-3">
                                <span>Meta Keywords :</span>
                            </label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" class="form-control form-control" name="setting_meta_keyword" value="<?= get_settings()->setting_meta_keyword; ?>" />
                            <span class="text-danger error_text setting_meta_keyword_error"></span>
                        </div>
                    </div>
                    <div class="row py-5">
                        <div class="col-md-10 offset-md-3">
                            <div class="d-flex">
                                <button type="reset" data-kt-ecommerce-settings-type="cancel" class="btn btn-light me-3">
                                    Cancel
                                </button>
                                <button type="submit" class="btn btn-sm fw-bold btn-primary" id="kt_button_submit">
                                    <span class="indicator-label">
                                        Simpan
                                    </span>
                                    <span class="indicator-progress">
                                        Tolong tunggu... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="tab-pane fade" id="sekolah" role="tabpanel">
        <form class="form" action="<?= route_to('settings.updateschoolsetting') ?>" method="POST" id="school_settings_form">
            <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" class="ci_csrf_data">
            <div class="row">
                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-header border-0 pt-6">
                            <div class="card-title">
                                <h2 class="text-gray-800">Pengaturan Sekolah</h2>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-5">
                                <div class="col-md-3 text-md-end">
                                    <label class="fs-6 fw-semibold form-label mt-3">
                                        <span>Nama Sekolah:</span>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control form-control" name="setting_nama_sekolah" value="<?= get_settings()->setting_nama_sekolah ?>" />
                                    <span class="text-danger error_text setting_nama_sekolah_error"></span>
                                </div>
                            </div>
                            <div class="row mb-5">
                                <div class="col-md-3 text-md-end">
                                    <label class="fs-6 fw-semibold form-label mt-3">
                                        <span>Kepala Sekolah :</span>
                                    </label>
                                </div>

                                <div class="col-md-9">
                                    <input type="text" class="form-control form-control" name="setting_kepala_sekolah" value="<?= get_settings()->setting_kepala_sekolah ?>" />
                                    <span class="text-danger error_text setting_kepala_sekolah_error"></span>
                                </div>
                            </div>
                            <div class="row mb-5">
                                <div class="col-md-3 text-md-end">
                                    <label class="fs-6 fw-semibold form-label mt-3">
                                        <span>Alamat :</span>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <textarea class="form-control form-control" name="setting_alamat"><?= get_settings()->setting_alamat ?></textarea>
                                    <span class="text-danger error_text setting_alamat_error"></span>
                                </div>
                            </div>
                            <div class="row mb-5">
                                <div class="col-md-3 text-md-end">
                                    <label class="fs-6 fw-semibold form-label mt-3">
                                        <span>Latitude :</span>
                                    </label>
                                </div>

                                <div class="col-md-9">
                                    <input type="text" class="form-control form-control" name="setting_latitude" value="<?= get_settings()->setting_latitude ?>" />
                                    <span class="text-danger error_text setting_latitude_error"></span>
                                </div>
                            </div>
                            <div class="row mb-5">
                                <div class="col-md-3 text-md-end">
                                    <label class="fs-6 fw-semibold form-label mt-3">
                                        <span>Longitude :</span>
                                    </label>
                                </div>

                                <div class="col-md-9">
                                    <input type="text" class="form-control form-control" name="setting_longitude" value="<?= get_settings()->setting_longitude ?>" />
                                    <span class="text-danger error_text setting_longitude_error"></span>
                                </div>
                            </div>
                            <div class="row mb-5">
                                <div class="col-md-3 text-md-end">
                                    <label class="fs-6 fw-semibold form-label mt-3">
                                        <span>Email :</span>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <input type="email" class="form-control form-control" name="setting_email" value="<?= get_settings()->setting_email ?>" />
                                    <span class="text-danger error_text setting_email_error"></span>
                                </div>
                            </div>
                            <div class="row mb-5">
                                <div class="col-md-3 text-md-end">
                                    <label class="fs-6 fw-semibold form-label mt-3">
                                        <span>Telepon :</span>
                                    </label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control form-control" name="setting_telepon" value="<?= get_settings()->setting_telepon ?>" />
                                    <span class="text-danger error_text setting_telepon_error"></span>
                                </div>
                            </div>
                            <div class="row py-5">
                                <div class="col-md-9 offset-md-3">
                                    <div class="d-flex">
                                        <button type="reset" data-kt-ecommerce-settings-type="cancel" class="btn btn-sm btn-light me-3">
                                            Cancel
                                        </button>
                                        <button type="submit" class="btn btn-sm fw-bold btn-primary" id="kt_button_submit">
                                            <span class="indicator-label">
                                                Simpan
                                            </span>
                                            <span class="indicator-progress">
                                                Tolong tunggu... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-body">
                            <div id="map"></div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="tab-pane fade" id="logo_dan_favicon" role="tabpanel">
        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <h2 class="text-gray-800">Logo & Favicon</h2>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <form action="<?= route_to('settings.updatelogosetting') ?>" method="POST" enctype="multipart/form-data" id="update_setting_logo">
                            <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" class="ci_csrf_data">
                            <div class="form-group">
                                <label for="setting_logo">Logo</label>
                                <input type="file" name="setting_logo" class="form-control mt-1">
                            </div>
                            <div class="d-flex justify-content-end pt-7">
                                <button type="reset" class="btn btn-sm btn-light fw-bold btn-active-light-primary me-2">Batal</button>
                                <button type="submit" class="btn btn-sm fw-bold btn-primary" id="kt_button_submit">
                                    <span class="indicator-label">
                                        Simpan
                                    </span>
                                    <span class="indicator-progress">
                                        Tolong tunggu... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-6">
                        <form action="<?= route_to('settings.updatefaviconsetting') ?>" method="POST" enctype="multipart/form-data" id="update_setting_favicon">
                            <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" class="ci_csrf_data">
                            <div class="form-group">
                                <label for="setting_favicon">Favicon</label>
                                <input type="file" name="setting_favicon" class="form-control mt-1">
                            </div>
                            <div class="d-flex justify-content-end pt-7">
                                <button type="reset" class="btn btn-sm btn-light fw-bold btn-active-light-primary me-2">Batal</button>
                                <button type="submit" class="btn btn-sm fw-bold btn-primary" id="kt_button_submit">
                                    <span class="indicator-label">
                                        Simpan
                                    </span>
                                    <span class="indicator-progress">
                                        Tolong tunggu... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts'); ?>
<script>
    $('#general_settings_form').on('submit', function(e) {
        e.preventDefault();
        var csrfName = $('.ci_csrf_data').attr('name');
        var csrfHash = $('.ci_csrf_data').val();
        var form = this;
        var formdata = new FormData(form);
        formdata.append(csrfName, csrfHash);

        $.ajax({
            url: $(form).attr('action'),
            method: $(form).attr('method'),
            data: formdata,
            processData: false,
            dataType: 'json',
            contentType: false,
            cache: false,
            beforeSend: function() {
                toastr.remove();
                $(form).find('span.error-text').text('');
            },
            success: function(response) {
                // Update CSRF hash
                $('.ci_csrf_data').val(response.token);

                if ($.isEmptyObject(response.error)) {
                    if (response.status == 1) {
                        toastr.success(response.msg);
                    } else {
                        toastr.error(response.msg);
                    }
                } else {
                    $.each(response.error, function(prefix, val) {
                        $(form).find('span.' + prefix + '_error').text(val);
                    });
                }
            }
        });
    });

    $('#school_settings_form').on('submit', function(e) {
        e.preventDefault();
        var csrfName = $('.ci_csrf_data').attr('name');
        var csrfHash = $('.ci_csrf_data').val();
        var form = this;
        var formdata = new FormData(form);
        formdata.append(csrfName, csrfHash);

        $.ajax({
            url: $(form).attr('action'),
            method: $(form).attr('method'),
            data: formdata,
            processData: false,
            dataType: 'json',
            contentType: false,
            cache: false,
            beforeSend: function() {
                toastr.remove();
                $(form).find('span.error-text').text('');
            },
            success: function(response) {
                // Update CSRF hash
                $('.ci_csrf_data').val(response.token);

                if ($.isEmptyObject(response.error)) {
                    if (response.status == 1) {
                        toastr.success(response.msg);
                    } else {
                        toastr.error(response.msg);
                    }
                } else {
                    $.each(response.error, function(prefix, val) {
                        $(form).find('span.' + prefix + '_error').text(val);
                    });
                }
            }
        });
    });

    $('#update_setting_logo').on('submit', function(e) {
        e.preventDefault();
        var csrfName = $('.ci_csrf_data').attr('name');
        var csrfHash = $('.ci_csrf_data').val();
        var form = this;
        var formdata = new FormData(form);
        formdata.append(csrfName, csrfHash);

        var inputFileVal = $(form).find('input[type="file"][name="setting_logo"]').val();

        if (inputFileVal.length > 0) {
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: formdata,
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function() {
                    toastr.remove();
                    $(form).find('span.error-text').text('');
                },
                success: function(response) {
                    // Update CSRF hash
                    $('.ci_csrf_data').val(response.token);

                    if (response.status == 1) {
                        toastr.success(response.msg);
                        $(form)[0].reset();
                    } else {
                        toastr.error(response.msg);
                    }
                }
            });
        } else {
            $(form).find('span.error_text').text('Tolong, pilih file logo. Tipe file PNG sangat direkomendasikan.');
        }

        $.ajax({
            url: $(form).attr('action'),
            method: $(form).attr('method'),
            data: formdata,
            processData: false,
            dataType: 'json',
            contentType: false,
            cache: false,
            beforeSend: function() {
                toastr.remove();
                $(form).find('span.error-text').text('');
            },
            success: function(response) {
                // Update CSRF hash
                $('.ci_csrf_data').val(response.token);

                if ($.isEmptyObject(response.error)) {
                    if (response.status == 1) {
                        toastr.success(response.msg);
                    } else {
                        toastr.error(response.msg);
                    }
                } else {
                    $.each(response.error, function(prefix, val) {
                        $(form).find('span.' + prefix + '_error').text(val);
                    });
                }
            }
        });
    });

    $('#update_setting_favicon').on('submit', function(e) {
        e.preventDefault();
        var csrfName = $('.ci_csrf_data').attr('name');
        var csrfHash = $('.ci_csrf_data').val();
        var form = this;
        var formdata = new FormData(form);
        formdata.append(csrfName, csrfHash);

        var inputFileVal = $(form).find('input[type="file"][name="setting_favicon"]').val();

        if (inputFileVal.length > 0) {
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: formdata,
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function() {
                    toastr.remove();
                    $(form).find('span.error-text').text('');
                },
                success: function(response) {
                    // Update CSRF hash
                    $('.ci_csrf_data').val(response.token);

                    if (response.status == 1) {
                        toastr.success(response.msg);
                        $(form)[0].reset();
                    } else {
                        toastr.error(response.msg);
                    }
                }
            });
        } else {
            $(form).find('span.error_text').text('Tolong, pilih file favicon. Tipe file PNG sangat direkomendasikan');
        }

        $.ajax({
            url: $(form).attr('action'),
            method: $(form).attr('method'),
            data: formdata,
            processData: false,
            dataType: 'json',
            contentType: false,
            cache: false,
            beforeSend: function() {
                toastr.remove();
                $(form).find('span.error-text').text('');
            },
            success: function(response) {
                // Update CSRF hash
                $('.ci_csrf_data').val(response.token);

                if ($.isEmptyObject(response.error)) {
                    if (response.status == 1) {
                        toastr.success(response.msg);
                    } else {
                        toastr.error(response.msg);
                    }
                } else {
                    $.each(response.error, function(prefix, val) {
                        $(form).find('span.' + prefix + '_error').text(val);
                    });
                }
            }
        });
    });

    var map = L.map('map').setView([<?= get_settings()->setting_latitude ?>, <?= get_settings()->setting_longitude ?>], 13);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    var marker = L.marker([<?= get_settings()->setting_latitude ?>, <?= get_settings()->setting_longitude ?>]).addTo(map);
</script>
<?= $this->endSection(); ?>