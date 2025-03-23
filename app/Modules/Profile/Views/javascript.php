<?= $this->section('scripts'); ?>
<script>
    $('#detail_profil_form').on('submit', function(e) {
        e.preventDefault();
        var form = this;
        var formdata = new FormData(form);

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
                if ($.isEmptyObject(response.error)) {
                    if (response.status == 1) {
                        $('.ci-user-nama').each(function() {
                            $(this).html(response.user_info.nama);
                        });
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

    $('#user_profile_file').ijaboCropTool({
        preview: '.ci-foto-preview',
        setRatio: 1,
        allowedExtensions: ['jpg', 'jpeg', 'png'],
        processUrl: '<?= route_to('profile.updateprofilepicture'); ?>',
        withCSRF: ['<?= csrf_token() ?>', '<?= csrf_hash() ?>'],
        onSuccess: function(message, element, status) {
            if (status == 1) {
                toastr.success(message);
            } else {
                toastr.error(message);
            }
        },
        onError: function(message, element, status) {
            alert(message);
        }
    });

    $('#change_password_form').on('submit', function(e) {
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
                        $(form)[0].reset();
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
</script>
<?= $this->endSection(); ?>