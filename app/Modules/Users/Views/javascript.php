<?= $this->section('scripts') ?>
<script>
    $(function() {
        HELPER.createCombo({
            el: ['role'],
            valueField: 'role',
            displayField: 'role',
            url: '<?= route_to('users.comboboxrole') ?>',
            withNull: true,
            grouped: false,
            chosen: true,
            callback: function() {}
        });
    })

    var table = $('#data_users').DataTable({
        "processing": false,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": "<?= route_to('users.listdata') ?>",
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
            },
            {
                "targets": 3,
                "orderable": false,
            },
            {
                "targets": 4,
                "orderable": false,
            }
        ],
        "language": {
            "url": "<?= base_url('stisla') ?>/assets/lang/indonesia.json"
        }
    });

    $(document).on('click', '.edit-users-btn', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var url = "<?= route_to('users.getusers') ?>";
        $.get(url, {
            id: id
        }, function(response) {
            var modal_title = 'Edit Pengguna';
            var modal_btn_text = 'Simpan Perubahan';
            var modal = $('body').find('div#edit-users-modal');
            modal.find('form').find('input[type="hidden"][name="id"]').val(id);
            modal.find('.modal-title').html(modal_title);
            modal.find('.modal-footer > button.action').html(modal_btn_text);
            modal.find('input[name="nama"]').val(response.data.nama);
            modal.find('input[name="username"]').val(response.data.username);
            modal.find('input[type="email"]').val(response.data.email);
            modal.find('form').find('input[type="hidden"][name="password_lama"]').val(response.data.password);
            modal.find('input[type="password"]').html('');
            modal.find('select').val(response.data.role);
            modal.find('span.error_text').html('');
            modal.modal('show');
        }, 'json');
    });

    function onEdit(id) {
        $.ajax({
            url: '<?= route_to('users.getusers') ?>',
            method: 'GET',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('input[name="id"]').val(response.data.id);
                $('input[name="nama"]').val(response.data.nama);
                $('input[name="username"]').val(response.data.username);
                $('input[name="email"]').val(response.data.email);
                $('input[name="password_lama"]').val(response.data.password);
                $('input[name="password"]').html('');
                $('[name=role]').val(response.data.role).change();

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
        var form = document.getElementById('users_form');
        var formData = new FormData(form);
        formData.append(csrfName, csrfHash);

        var url = '<?= route_to('users.store') ?>';
        if (type === 'update') {
            url = '<?= route_to('users.update') ?>';
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
                        $('#data_users').DataTable().ajax.reload(null, false);
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
        var url = "<?= route_to('users.delete') ?>";

        Swal.fire({
            title: 'Apakah anda yakin?',
            html: 'Anda ingin menghapus users ini?',
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
                        $('#data_users').DataTable().ajax.reload(null, false);
                        toastr.success(response.msg);
                    } else {
                        toastr.error(response.msg);
                    }
                }, 'json');
            }
        });
    };

    function onExport() {
        window.location.href = '<?= route_to('users.export') ?>';
    };

    function onImport() {
        var modal = $('#import_users_modal');
        modal.modal('show');

        $('#save').attr('onclick', 'onSave("update")');
    };

    function onMultipleDelete() {
        let jumlahData = $('#data_users tbody tr .check:checked');

        if (jumlahData.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Gagal',
                text: 'Tidak ada data yang dipilih!'
            })
        } else {
            Swal.fire({
                title: 'Apakah anda yakin?',
                html: `Anda ingin menghapus (${jumlahData.length}) pengguna ini?`,
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
                    $('#data_users').DataTable().ajax.reload(null, false);
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