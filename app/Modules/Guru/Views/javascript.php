<?= $this->section('scripts') ?>
<script>
    $('#add-guru-form').on('submit', function(e) {
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
                        $('#data_guru').DataTable().ajax.reload(null, false);
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

    var table = $('#data_guru').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": "<?= route_to('guru.listdata') ?>",
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

    $(document).on('click', '.detail-guru-btn', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var url = "<?= route_to('guru.getdetail') ?>";

        $.get(url, {
            id: id
        }, function(response) {
            if (response.data) {
                var modal_title = 'Detail Guru';
                var modal = $('body').find('div#detail-guru-modal');
                modal.find('.modal-title').html(modal_title);

                // Struktur tabel dengan nilai diisi dari response data
                modal.find('tbody#guru-details').html(
                    '<tr>' +
                    '<td width="30%">Nama Guru</td>' +
                    '<td width="10%">:</td>' +
                    '<td>' + response.data.nama_guru + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td width="30%">NIP</td>' +
                    '<td width="10%">:</td>' +
                    '<td>' + response.data.nip + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td width="30%">Tanggal Lahir</td>' +
                    '<td width="10%">:</td>' +
                    '<td>' + response.data.tanggal_lahir + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td width="30%">No. Handphone</td>' +
                    '<td width="10%">:</td>' +
                    '<td>' + response.data.no_handphone + '</td>' +
                    '</tr>'
                );
                modal.modal('show');
            } else {
                toastr.error('Data tidak ditemukan');
            }
        }, 'json');
    });

    $(document).on('click', '.edit-guru-btn', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var url = "<?= route_to('guru.getguru') ?>";
        $.get(url, {
            id: id
        }, function(response) {
            var modal_title = 'Edit guru';
            var modal_btn_text = 'Simpan Perubahan';
            var modal = $('body').find('div#edit-guru-modal');
            modal.find('form').find('input[type="hidden"][name="id"]').val(id);
            modal.find('.modal-title').html(modal_title);
            modal.find('.modal-footer > button.action').html(modal_btn_text);
            modal.find('input[type="text"][name="nama_guru"]').val(response.data.guru.nama_guru);
            modal.find('input[type="number"][name="nip"]').val(response.data.guru.nip);
            modal.find('input[type="date"][name="tanggal_lahir"]').val(response.data.guru.tanggal_lahir);
            modal.find('input[type="number"][name="no_handphone"]').val(response.data.guru.no_handphone);
            modal.find('span.error_text').html('');
            modal.modal('show');
        }, 'json');
    });

    $('#update-guru-form').on('submit', function(e) {
        e.preventDefault();
        // CSRF
        var csrfName = $('.ci_csrf_data').attr('name'); // CSRF Token name
        var csrfHash = $('.ci_csrf_data').val(); // CSRF Hash
        var form = this;
        var modal = $('body').find('div#edit-guru-modal');
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
                        $('#data_guru').DataTable().ajax.reload(null, false);
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

    $(document).on('click', '.delete-guru-btn', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var url = "<?= route_to('guru.delete') ?>";

        Swal.fire({
            title: 'Apakah anda yakin?',
            html: 'Anda ingin menghapus guru ini?',
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
                        $('#data_guru').DataTable().ajax.reload(null, false);
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
        window.location.href = '<?= route_to('guru.export') ?>';
    });

    $('#import-guru-form').on('submit', function(e) {
        e.preventDefault();
        var csrfName = $('.ci_csrf_data').attr('name');
        var csrfHash = $('.ci_csrf_data').val();
        var modal = $('body').find('div#import-guru-modal');
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
                        modal.modal('hide');
                        toastr.success(response.msg);
                        $('#data_guru').DataTable().ajax.reload(null, false);
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
        let jumlahData = $('#data_guru tbody tr .check:checked');

        if (jumlahData.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Gagal',
                text: 'Tidak ada data yang dipilih!'
            })
        } else {
            Swal.fire({
                title: 'Apakah anda yakin?',
                html: `Anda ingin menghapus (${jumlahData.length}) guru ini?`,
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
                    $('#data_guru').DataTable().ajax.reload(null, false);
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