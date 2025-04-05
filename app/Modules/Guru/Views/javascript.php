<?= $this->section('scripts') ?>
<script>
    var table = $('#data_guru').DataTable({
        "processing": false,
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
            "url": "<?= base_url('stisla') ?>/assets/lang/indonesia.json"
        }
    });

    function onDetail(id) {
        $.ajax({
            url: '<?= route_to('guru.getdetail') ?>',
            method: 'GET',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                if (response.data) {
                    var modal_title = 'Detail Guru';
                    var modal = $('body').find('div#detail-guru-modal');
                    modal.find('.modal-title').html(modal_title);

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
            },
            error: function(xhr, status, error) {
                console.log('An error occurred:', error);
            }
        });
    }

    function onEdit(id) {
        $.ajax({
            url: '<?= route_to('guru.getguru') ?>',
            method: 'GET',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('input[name="id"]').val(response.data.id);
                $('input[type="text"][name="nama_guru"]').val(response.data.nama_guru);
                $('input[type="number"][name="nip"]').val(response.data.nip);
                $('input[type="date"][name="tanggal_lahir"]').val(response.data.tanggal_lahir);
                $('input[type="number"][name="no_handphone"]').val(response.data.no_handphone);

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
        var form = document.getElementById('guru_form');
        var formData = new FormData(form);
        formData.append(csrfName, csrfHash);

        var url = '<?= route_to('guru.store') ?>';
        if (type === 'update') {
            url = '<?= route_to('guru.update') ?>';
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
                        $('#data_guru').DataTable().ajax.reload(null, false);
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
    };

    function onExport() {
        window.location.href = '<?= route_to('guru.export') ?>';
    };

    function onImport() {
        var modal = $('#import_guru_modal');
        modal.modal('show');

        $('#save').attr('onclick', 'onSave("update")');
    };

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