<?= $this->section('scripts') ?>
<script>
    $('#add-quiz-form').on('submit', function(e) {
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
                        $('#data_quiz').DataTable().ajax.reload(null, false);
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

    var table = $('#data_quiz').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": "<?= route_to('quiz.listdata') ?>",
            "type": "POST"
        },
        "columnDefs": [{
                "targets": 0,
                "orderable": false
            },
            {
                "targets": 1,
                "orderable": true,
            }
        ],
        "language": {
            "url": "<?= base_url() ?>/datatable/lang/indonesia.json"
        }
    });

    $(document).on('click', '.edit-quiz-btn', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var url = "<?= route_to('quiz.getquiz') ?>";
        $.get(url, {
            id: id
        }, function(response) {
            var modal_title = 'Edit Quiz';
            var modal_btn_text = 'Simpan Perubahan';
            var modal = $('body').find('div#edit-quiz-modal');
            modal.find('form').find('input[type="hidden"][name="id"]').val(id);
            modal.find('.modal-title').html(modal_title);
            modal.find('.modal-footer > button.action').html(modal_btn_text);
            modal.find('input[type="text"]').val(response.data.quiz);
            modal.find('span.error_text').html('');
            modal.modal('show');
        }, 'json');
    });

    $('#update-quiz-form').on('submit', function(e) {
        e.preventDefault();
        // CSRF
        var csrfName = $('.ci_csrf_data').attr('name'); // CSRF Token name
        var csrfHash = $('.ci_csrf_data').val(); // CSRF Hash
        var form = this;
        var modal = $('body').find('div#edit-quiz-modal');
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
                        $('#data_quiz').DataTable().ajax.reload(null, false);
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

    $(document).on('click', '.delete-quiz-btn', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var url = "<?= route_to('quiz.delete') ?>";

        Swal.fire({
            title: 'Apakah anda yakin?',
            html: 'Anda ingin menghapus quiz ini?',
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
                        $('#data_quiz').DataTable().ajax.reload(null, false);
                        toastr.success(response.msg);
                    } else {
                        toastr.error(response.msg);
                    }
                }, 'json');
            }
        });
    });

    $(document).on('click', '#export', function(e) {
        e.preventDefault();
        window.location.href = '<?= route_to('quiz.export') ?>';
    });

    $('#import-quiz-form').on('submit', function(e) {
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
                        $('#data_quiz').DataTable().ajax.reload(null, false);
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

    $(document).on('click', '.select_all', function(e) {
        if ($(this).is(":checked")) {
            $('.check').prop('checked', true);
        } else {
            $('.check').prop('checked', false);
        }
    });

    function onMultipleDelete() {
        let jumlahData = $('#data_quiz tbody tr .check:checked');

        if (jumlahData.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Gagal',
                text: 'Tidak ada data yang dipilih!'
            })
        } else {
            Swal.fire({
                title: 'Apakah anda yakin?',
                html: `Anda ingin menghapus (${jumlahData.length}) quiz ini?`,
                showCancelButton: true,
                cancelButtonText: 'Batal',
                confirmButtonText: 'Hapus',
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                allowOutsideClick: false
            }).then(function(result) {
                if (result.value) {
                    $("#bulk").submit();
                }
            });
        }
    }

    $("#bulk").on("submit", function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        $.ajax({
            url: $(this).attr("action"),
            data: $(this).serialize(),
            type: "POST",
            success: function(response) {
                if (response.status == 1) {
                    $('#data_quiz').DataTable().ajax.reload(null, false);
                    toastr.success(response.msg);
                } else {
                    toastr.error(response.msg);
                }
            },
            error: function() {
                Swal.fire({
                    title: "Gagal",
                    text: "Ada data yang digunakan",
                    icon: "warning"
                });
            }
        });
    });
</script>
<?= $this->endSection() ?>