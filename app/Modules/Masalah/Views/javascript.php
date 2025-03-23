<?= $this->section('scripts') ?>
<script>
    $('#add-main-masalah-form').on('submit', function(e) {
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
                        $('#data_sub_masalah').DataTable().ajax.reload(null, false);
                        $('#data_main_masalah').DataTable().ajax.reload(null, false);
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

    $('#add-sub-masalah-form').on('submit', function(e) {
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
                        $('#data_sub_masalah').DataTable().ajax.reload(null, false);
                        $('#data_main_masalah').DataTable().ajax.reload(null, false);
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

    var table = $('#data_sub_masalah').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": "<?= route_to('masalah.listdatasubmasalah') ?>",
            "type": "POST"
        },
        "columnDefs": [{
                "targets": 0,
                "orderable": false
            },
            {
                "targets": 1,
                "orderable": true,
            },
            {
                "targets": 2,
                "orderable": false,
            }
        ],
        "language": {
            "url": "<?= base_url() ?>/datatable/lang/indonesia.json"
        }
    });

    var table = $('#data_main_masalah').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": "<?= route_to('masalah.listdatamainmasalah') ?>",
            "type": "POST"
        },
        "columnDefs": [{
                "targets": 0,
                "orderable": false
            },
            {
                "targets": 1,
                "orderable": true,
            },
        ],
        "language": {
            "url": "<?= base_url() ?>/datatable/lang/indonesia.json"
        }
    });

    $(document).on('click', '.edit-sub-masalah-btn', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var url = "<?= route_to('masalah.getsubmasalah') ?>";
        $.get(url, {
            id: id
        }, function(response) {
            var modal_title = 'Edit Sub Masalah';
            var modal_btn_text = 'Simpan Perubahan';
            var modal = $('body').find('div#edit-sub-masalah-modal');
            modal.find('form').find('input[type="hidden"][name="id"]').val(id);
            modal.find('.modal-title').html(modal_title);
            modal.find('.modal-footer > button.action').html(modal_btn_text);
            modal.find('input[type="text"][name="nama_sub_masalah"]').val(response.data.sub_masalah.nama_sub_masalah);
            modal.find('select[name="id_main_masalah"]').val(response.data.sub_masalah.id_main_masalah);
            modal.find('span.error_text').html('');
            modal.modal('show');
        }, 'json');
    });

    $(document).on('click', '.edit-main-masalah-btn', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var url = "<?= route_to('masalah.getmainmasalah') ?>";
        $.get(url, {
            id: id
        }, function(response) {
            var modal_title = 'Edit Main Masalah';
            var modal_btn_text = 'Simpan Perubahan';
            var modal = $('body').find('div#edit-main-masalah-modal');
            modal.find('form').find('input[type="hidden"][name="id"]').val(id);
            modal.find('.modal-title').html(modal_title);
            modal.find('.modal-footer > button.action').html(modal_btn_text);
            modal.find('input[type="text"][name="nama_main_masalah"]').val(response.data.nama_main_masalah);
            modal.find('span.error_text').html('');
            modal.modal('show');
        }, 'json');
    });

    $('#update-sub-masalah-form').on('submit', function(e) {
        e.preventDefault();
        // CSRF
        var csrfName = $('.ci_csrf_data').attr('name'); // CSRF Token name
        var csrfHash = $('.ci_csrf_data').val(); // CSRF Hash
        var form = this;
        var modal = $('body').find('div#edit-sub-masalah-modal');
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
                        modal.modal('hide');
                        toastr.success(response.msg);
                        $('#data_sub_masalah').DataTable().ajax.reload(null, false);
                        $('#data_main_masalah').DataTable().ajax.reload(null, false);
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

    $('#update-main-masalah-form').on('submit', function(e) {
        e.preventDefault();
        // CSRF
        var csrfName = $('.ci_csrf_data').attr('name'); // CSRF Token name
        var csrfHash = $('.ci_csrf_data').val(); // CSRF Hash
        var form = this;
        var modal = $('body').find('div#edit-main-masalah-modal');
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
                        modal.modal('hide');
                        toastr.success(response.msg);
                        $('#data_sub_masalah').DataTable().ajax.reload(null, false);
                        $('#data_main_masalah').DataTable().ajax.reload(null, false);
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

    $(document).on('click', '.delete-sub-masalah-btn', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var url = "<?= route_to('masalah.delete') ?>";

        Swal.fire({
            title: 'Apakah anda yakin?',
            html: 'Anda ingin menghapus masalah ini?',
            showCancelButton: true,
            cancelButtonText: 'Batal',
            confirmButtonText: 'Hapus',
            cancelButtonColor: '#d33',
            confirmButtonColor: '#3085d6',
            allowOutsideClick: false
        }).then(function(result) {
            if (result.value) {
                $.get(url, {
                    id: id
                }, function(response) {
                    if (response.status == 1) {
                        $('#data_sub_masalah').DataTable().ajax.reload(null, false);
                        $('#data_main_masalah').DataTable().ajax.reload(null, false);
                        toastr.success(response.msg);
                    } else {
                        toastr.error(response.msg);
                    }
                }, 'json');
            }
        });
    });

    $(document).on('click', '.delete-main-masalah-btn', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var url = "<?= route_to('masalah.deletemain') ?>";

        Swal.fire({
            title: 'Apakah anda yakin?',
            html: 'Anda ingin menghapus masalah ini?',
            showCancelButton: true,
            cancelButtonText: 'Batal',
            confirmButtonText: 'Hapus',
            cancelButtonColor: '#d33',
            confirmButtonColor: '#3085d6',
            allowOutsideClick: false
        }).then(function(result) {
            if (result.value) {
                $.get(url, {
                    id: id
                }, function(response) {
                    if (response.status == 1) {
                        $('#data_sub_masalah').DataTable().ajax.reload(null, false);
                        $('#data_main_masalah').DataTable().ajax.reload(null, false);
                        toastr.success(response.msg);
                    } else {
                        toastr.error(response.msg);
                    }
                }, 'json');
            }
        });
    });

    $(document).on('click', '#export-sub-masalah', function(e) {
        e.preventDefault();
        window.location.href = '<?= route_to('masalah.exportsubmasalah') ?>';
    });
    $(document).on('click', '#export-main-masalah', function(e) {
        e.preventDefault();
        window.location.href = '<?= route_to('masalah.exportmainmasalah') ?>';
    });
</script>
<?= $this->endSection() ?>