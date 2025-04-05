<?= $this->section('scripts') ?>
<script>
    $(function() {
        HELPER.createCombo({
            el: ['id_kelas'],
            valueField: 'id',
            displayField: 'nama_kelas',
            url: '<?= route_to('siswa.comboboxkelas') ?>',
            withNull: true,
            grouped: false,
            chosen: true,
            callback: function() {}
        });
    })

    var table = $('#data_siswa').DataTable({
        "processing": false,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": "<?= route_to('siswa.listdata') ?>",
            "type": "POST"
        },
        "columnDefs": [{
                "targets": 0,
                "orderable": false
            },
            {
                "targets": 1,
                "orderable": false,
            },
            {
                "targets": 2,
                "orderable": true,
            },
            {
                "targets": 3,
                "orderable": false,
            },
            {
                "targets": 4,
                "orderable": false,
            },
            {
                "targets": 5,
                "orderable": false,
            },
            {
                "targets": 6,
                "orderable": false,
            },
        ],
        "language": {
            "url": "<?= base_url('stisla') ?>/assets/lang/indonesia.json"
        }
    });

    function onAdd() {
        var modal = $('#modal_siswa');
        var modal_title = 'Tambah siswa';
        var modal_btn_text = 'Simpan';
        modal.find('.modal-title').html(modal_title);
        modal.find('.modal-footer > button.action').html(modal_btn_text);
        modal.find('input.error-text').html('');
        modal.modal('show');
    };

    function onDetail(id) {
        $.ajax({
            url: '<?= route_to('siswa.getdetail') ?>',
            method: 'GET',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                if (response.data) {
                    var modal_title = 'Detail Siswa';
                    var modal = $('body').find('div#detail-siswa-modal');
                    modal.find('.modal-title').html(modal_title);

                    var jenis_kelamin = '';
                    if (response.data.jenis_kelamin == 'L') {
                        jenis_kelamin = 'Laki-laki';
                    } else if (response.data.jenis_kelamin == 'P') {
                        jenis_kelamin = 'Perempuan';
                    }

                    // Struktur tabel dengan nilai diisi dari response data
                    modal.find('tbody#siswa-details').html(
                        '<tr>' +
                        '<td width="30%">NISN</td>' +
                        '<td width="10%">:</td>' +
                        '<td>' + response.data.nisn + '</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td width="30%">Nama Siswa</td>' +
                        '<td width="10%">:</td>' +
                        '<td>' + response.data.nama_siswa + '</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td width="30%">Nama Kelas</td>' +
                        '<td width="10%">:</td>' +
                        '<td>' + response.data.nama_kelas + '</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td width="30%">Jenis Kelamin</td>' +
                        '<td width="10%">:</td>' +
                        '<td>' + jenis_kelamin + '</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td width="30%">TTL</td>' +
                        '<td width="10%">:</td>' +
                        '<td>' + response.data.tempat_lahir + ', ' + response.data.tanggal_lahir + '</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td width="30%">Alamat</td>' +
                        '<td width="10%">:</td>' +
                        '<td>' + response.data.alamat + '</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td width="30%">Agama</td>' +
                        '<td width="10%">:</td>' +
                        '<td>' + response.data.agama + '</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td width="30%">Status Keluarga</td>' +
                        '<td width="10%">:</td>' +
                        '<td>' + response.data.status_keluarga + '</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td width="30%">Nama Ayah</td>' +
                        '<td width="10%">:</td>' +
                        '<td>' + response.data.nama_ayah + '</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td width="30%">Nama Ibu</td>' +
                        '<td width="10%">:</td>' +
                        '<td>' + response.data.nama_ibu + '</td>' +
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
            url: '<?= route_to('siswa.getsiswa') ?>',
            method: 'GET',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                var modal = $('#modal_siswa');
                var modal_title = 'Edit Siswa';
                var modal_btn_text = 'Simpan';

                modal.find('.modal-title').html(modal_title);
                modal.find('.modal-footer > button.action').html(modal_btn_text);
                modal.find('input[name="id"]').val(response.data.id);
                modal.find('input[type="number"][name="nisn"]').val(response.data.nisn);
                modal.find('input[type="text"][name="nama_siswa"]').val(response.data.nama_siswa);
                modal.find('select[name="id_kelas"]').val(response.data.id_kelas);
                modal.find('select[name="jenis_kelamin"]').val(response.data.jenis_kelamin);
                modal.find('input[type="text"][name="tempat_lahir"]').val(response.data.tempat_lahir);
                modal.find('input[type="date"][name="tanggal_lahir"]').val(response.data.tanggal_lahir);
                modal.find('textarea[name="alamat"]').val(response.data.alamat);
                modal.find('input[type="number"][name="no_handphone"]').val(response.data.no_handphone);
                modal.find('select[name="agama"]').val(response.data.agama);
                modal.find('input[type="text"][name="nama_ayah"]').val(response.data.nama_ayah);
                modal.find('input[type="text"][name="nama_ibu"]').val(response.data.nama_ibu);
                modal.find('select[name="status_keluarga"]').val(response.data.status_keluarga);
                modal.find('input.error-text').html('');
                modal.modal('show');

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
        var form = document.getElementById('siswa_form');
        var formData = new FormData(form);
        formData.append(csrfName, csrfHash);

        var url = '<?= route_to('siswa.store') ?>';
        if (type === 'update') {
            url = '<?= route_to('siswa.update') ?>';
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
                        $('#data_siswa').DataTable().ajax.reload(null, false);
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
        var url = "<?= route_to('siswa.delete') ?>";

        Swal.fire({
            title: 'Apakah anda yakin?',
            html: 'Anda ingin menghapus siswa ini?',
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
                        $('#data_siswa').DataTable().ajax.reload(null, false);
                        toastr.success(response.msg);
                    } else {
                        toastr.error(response.msg);
                    }
                }, 'json');
            }
        });
    };

    function onExport() {
        window.location.href = '<?= route_to('siswa.export') ?>';
    };

    function onImport() {
        var modal = $('#import_siswa_modal');
        modal.modal('show');

        $('#save').attr('onclick', 'onSave("update")');
    };

    function onMultipleDelete() {
        let jumlahData = $('#data_siswa tbody tr .check:checked');

        if (jumlahData.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Gagal',
                text: 'Tidak ada data yang dipilih!'
            })
        } else {
            Swal.fire({
                title: 'Apakah anda yakin?',
                html: `Anda ingin menghapus (${jumlahData.length}) siswa ini?`,
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
                    $('#data_siswa').DataTable().ajax.reload(null, false);
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