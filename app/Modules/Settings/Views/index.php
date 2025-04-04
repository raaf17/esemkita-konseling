<?= $this->extend('App\Modules\Layouts\Views\app.php') ?>

<?= $this->section('content') ?>
<div class="section-body">
    <h2 class="section-title">Overview</h2>
    <p class="section-lead">
        Organize and adjust all settings about this site.
    </p>

    <div class="row" id="container">
        <div class="col-lg-6">
            <div class="card card-large-icons">
                <div class="card-icon bg-primary text-white">
                    <i class="fas fa-cog"></i>
                </div>
                <div class="card-body">
                    <h4>General</h4>
                    <p>General settings such as, site title, site description, address and so on.</p>
                    <button type="button" onclick="generalSettings()" class="badge badge-primary">Change Setting <i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card card-large-icons">
                <div class="card-icon bg-primary text-white">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="card-body">
                    <h4>Email</h4>
                    <p>Email SMTP settings, notifications and others related to email.</p>
                    <button type="button" onclick="generalSettings()" class="badge badge-primary">Change Setting <i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card card-large-icons">
                <div class="card-icon bg-primary text-white">
                    <i class="fas fa-power-off"></i>
                </div>
                <div class="card-body">
                    <h4>System</h4>
                    <p>PHP version settings, time zones and other environments.</p>
                    <button type="button" onclick="generalSettings()" class="badge badge-primary">Change Setting <i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts'); ?>
<script>
    function generalSettings() {
        $('.loader').show();

        $.ajax({
            url: '<?= route_to('settings.general') ?>',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#container').html(response.html);
            },
            error: function(xhr, status, error) {
                console.error('An error occurred:', error);
            },
            complete: function() {
                $('.loader').hide();
            }
        });
    }

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