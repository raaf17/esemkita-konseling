<?= $this->section('scripts') ?>
<script>
    var table = $('#data_quiz').DataTable({
        "processing": false,
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
            "url": "<?= base_url('stisla') ?>/assets/lang/indonesia.json"
        }
    });

    function onEdit(id) {
        $.ajax({
            url: '<?= route_to('quiz.getquiz') ?>',
            method: 'GET',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('input[name="id"]').val(response.data.id);
                $('input[name="quiz"]').val(response.data.quiz);

                $('#save').text('Update');
                $('#save').attr('onclick', 'onSave("update")');
            },
            error: function(xhr, status, error) {
                console.log('An error occurred:', error);
            }
        });
    }

    function onSave(type) {
        var csrfName = '<?= csrf_token(); ?>';
        var csrfHash = '<?= csrf_hash(); ?>';
        var form = document.getElementById('quiz_form');
        var formData = new FormData(form);
        formData.append(csrfName, csrfHash);

        var url = '<?= route_to('quiz.store') ?>';
        if (type === 'update') {
            url = '<?= route_to('quiz.update') ?>';
        }

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            cache: false,
            success: function(response) {
                $('.ci_csrf_data').val(response.token);
                if ($.isEmptyObject(response.error)) {
                    if (response.status == 1) {
                        form.reset();
                        $('#id_guru').val(null).trigger('change');
                        toastr.success(response.msg);
                        $('#data_quiz').DataTable().ajax.reload(null, false);
                    } else {
                        toastr.error(response.msg);
                    }
                } else {
                    $.each(response.error, function(key, val) {
                        $('span.' + key + '_error').text(val);
                    });
                }
            }
        });
    }

    function onDelete(id) {
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
    };

    function onExport() {
        window.location.href = '<?= route_to('quiz.export') ?>';
    };

    function onImport() {
        var modal = $('#import_quiz_modal');
        modal.modal('show');

        $('#save').attr('onclick', 'onSave("update")');
    };

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

    $(document).on('click', '.select_all', function(e) {
        if ($(this).is(":checked")) {
            $('.check').prop('checked', true);
        } else {
            $('.check').prop('checked', false);
        }
    });

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