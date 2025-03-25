<?= $this->section('scripts') ?>
<script>
    var tablePending, tableProcessed, tableRejected, tableDone;

    $(function() {
        tablePending = $('#data_laporan_pending').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?= route_to('laporan.listdata') ?>",
                "type": "POST",
                "data": function(d) {
                    d.status = 'pending';
                }
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
                },
                {
                    "targets": 5,
                    "orderable": false,
                },
                {
                    "targets": 6,
                    "orderable": false,
                }
            ],
            "language": {
                "url": "<?= base_url('stisla') ?>/assets/lang/indonesia.json"
            }
        });

        $('a[href="#table_processed"]').on('shown.bs.tab', function() {
            if (!$.fn.DataTable.isDataTable('#data_laporan_processed')) {
                tableProcessed = $('#data_laporan_processed').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "order": [],
                    "ajax": {
                        "url": "<?= route_to('laporan.listdata') ?>",
                        "type": "POST",
                        "data": function(d) {
                            d.status = 'processed';
                        }
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
                        },
                        {
                            "targets": 5,
                            "orderable": false,
                        },
                        {
                            "targets": 6,
                            "orderable": false,
                        }
                    ],
                    "language": {
                        "url": "<?= base_url('stisla') ?>/assets/lang/indonesia.json"
                    }
                });
            } else {
                tableProcessed.ajax.reload(null, false);
            }
        });

        $('a[href="#table_rejected"]').on('shown.bs.tab', function() {
            if (!$.fn.DataTable.isDataTable('#data_laporan_rejected')) {
                tableRejected = $('#data_laporan_rejected').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "order": [],
                    "ajax": {
                        "url": "<?= route_to('laporan.listdata') ?>",
                        "type": "POST",
                        "data": function(d) {
                            d.status = 'rejected';
                        }
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
                        },
                        {
                            "targets": 5,
                            "orderable": false,
                        },
                        {
                            "targets": 6,
                            "orderable": false,
                        }
                    ],
                    "language": {
                        "url": "<?= base_url('stisla') ?>/assets/lang/indonesia.json"
                    }
                });
            } else {
                tableRejected.ajax.reload(null, false);
            }
        });

        $('a[href="#table_done"]').on('shown.bs.tab', function() {
            if (!$.fn.DataTable.isDataTable('#data_laporan_done')) {
                tableDone = $('#data_laporan_done').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "order": [],
                    "ajax": {
                        "url": "<?= route_to('laporan.listdata') ?>",
                        "type": "POST",
                        "data": function(d) {
                            d.status = 'done';
                        }
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
                        },
                        {
                            "targets": 5,
                            "orderable": false,
                        },
                        {
                            "targets": 6,
                            "orderable": false,
                        }
                    ],
                    "language": {
                        "url": "<?= base_url('stisla') ?>/assets/lang/indonesia.json"
                    }
                });
            } else {
                tableDone.ajax.reload(null, false);
            }
        });
    });

    $(document).on('click', '.approve-laporan-btn', function(e) {
        let id = $(this).data('id');

        Swal.fire({
            title: 'Apakah anda yakin?',
            html: 'Anda ingin menyetujui laporan ini?',
            showCancelButton: true,
            cancelButtonText: 'Batal',
            confirmButtonText: 'Iya',
            cancelButtonColor: '#E1E3EA',
            confirmButtonColor: '#50CD89',
            allowOutsideClick: false
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    method: 'POST',
                    url: '<?= route_to('laporan.approve') ?>',
                    data: {
                        id: id
                    },
                    success: function(response) {
                        if (response.status == 1) {
                            tablePending.ajax.reload(null, false);
                            tableProcessed.ajax.reload(null, false);
                            tableRejected.ajax.reload(null, false);
                            tableDone.ajax.reload(null, false);
                            toastr.success(response.msg);
                        } else {
                            toastr.error(response.msg);
                        }
                    }
                })
            }
        });
    });

    $(document).on('click', '.unapprove-laporan-btn', function(e) {
        let id = $(this).data('id');

        Swal.fire({
            title: 'Apakah anda yakin?',
            html: 'Anda ingin menolak laporan ini?',
            showCancelButton: true,
            cancelButtonText: 'Batal',
            confirmButtonText: 'Iya',
            cancelButtonColor: '#E1E3EA',
            confirmButtonColor: '#d33',
            allowOutsideClick: false
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    method: 'POST',
                    url: '<?= route_to('laporan.unapprove') ?>',
                    data: {
                        id: id
                    },
                    success: function(response) {
                        if (response.status == 1) {
                            tablePending.ajax.reload(null, false);
                            tableProcessed.ajax.reload(null, false);
                            tableRejected.ajax.reload(null, false);
                            tableDone.ajax.reload(null, false);
                            toastr.success(response.msg);
                        } else {
                            toastr.error(response.msg);
                        }
                    }
                })
            }
        });
    });

    $(document).on('click', '.detail-laporan-btn', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var url = "<?= route_to('laporan.getdetail') ?>";

        $.get(url, {
            id: id
        }, function(response) {
            if (response.data) {
                var modal_title = 'Detail Laporan';
                var modal = $('body').find('div#detail-laporan-modal');
                modal.find('.modal-title').html(modal_title);

                var statusHtml = '';
                if (response.data.status == 0) {
                    statusHtml = '<span class="badge badge-warning" style="padding: 6px 8px; border-radius: 25px;">Menunggu</span>';
                } else if (response.data.status == 1) {
                    statusHtml = '<span class="badge badge-success" style="padding: 6px 8px; border-radius: 25px;">Diproses</span>';
                } else if (response.data.status == 2) {
                    statusHtml = '<span class="badge badge-danger" style="padding: 6px 8px; border-radius: 25px;">Ditolak</span>';
                }

                var fotoUrl = response.data.foto == null ? '<?= base_url() ?>/img/bukti_laporan/sample.jpg' : '<?= base_url() ?>/img/bukti_laporan/' + response.data.foto;

                // Struktur tabel dengan nilai diisi dari response data
                modal.find('tbody#laporan-details').html(
                    '<tr>' +
                    '<td width="30%">Pelapor</td>' +
                    '<td width="10%">:</td>' +
                    '<td>' + response.data.nama_siswa + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td width="30%">Nama Terlapor</td>' +
                    '<td width="10%">:</td>' +
                    '<td>' + response.data.nama_terlapor + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td width="30%">Pelanggaran</td>' +
                    '<td width="10%">:</td>' +
                    '<td>' + response.data.judul + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td width="30%">Tanggal</td>' +
                    '<td width="10%">:</td>' +
                    '<td>' + response.data.tanggal + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td width="30%">Lokasi</td>' +
                    '<td width="10%">:</td>' +
                    '<td>' + response.data.lokasi + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td width="30%">Deskripsi</td>' +
                    '<td width="10%">:</td>' +
                    '<td>' + response.data.deskripsi + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td width="30%">Status</td>' +
                    '<td width="10%">:</td>' +
                    '<td>' + statusHtml + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td width="30%">Foto Bukti</td>' +
                    '<td width="10%">:</td>' +
                    '<td><img src="' + fotoUrl + '" style="width: 250px; border-radius: 2px;" alt="Bukti" /></td>' +
                    '</tr>'
                );
                modal.modal('show');
            } else {
                toastr.error('Data tidak ditemukan');
            }
        }, 'json');
    });

    $(document).on('click', '#export', function(e) {
        e.preventDefault();
        window.location.href = '<?= route_to('laporan.export') ?>';
    });

    $(document).on('click', '#add_laporan_btn', function(e) {
        e.preventDefault();
        var modal = $('body').find('div#add-laporan-modal');
        var modal_title = 'Buat Laporan';
        var modal_btn_text = 'Simpan';
        modal.find('.modal-title').html(modal_title);
        modal.find('.modal-footer > button.action').html(modal_btn_text);
        modal.find('input.error-text').html('');
        modal.find('input[type="text"][name="nama_terlapor"]').val('');
        modal.find('input[type="text"][name="judul"]').val('');
        modal.find('input[type="date"][name="tanggal"]').val('');
        modal.find('input[type="text"][name="lokasi"]').val('');
        modal.find('textarea[name="deskripsi"]').val('');
        modal.find('input[type="file"][name="foto"]').val('');
        modal.modal('show');
    });

    $('#add-laporan-form').on('submit', function(e) {
        e.preventDefault();

        // Pengecekan file sebelum dikirim
        var fileInput = $(this).find('input[type="file"][name="foto"]');
        if (fileInput.get(0).files.length === 0) {
            toastr.error('Silakan pilih foto bukti terlebih dahulu.');
            return;
        }

        var csrfName = $('.ci_csrf_data').attr('name');
        var csrfHash = $('.ci_csrf_data').val();
        var form = this;
        var modal = $('body').find('div#add-laporan-modal');
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
                        $('#data_laporan_pending').DataTable().ajax.reload(null, false);
                        $('#data_laporan_approve').DataTable().ajax.reload(null, false);
                        $('#data_laporan_unapprove').DataTable().ajax.reload(null, false);
                        $('#data_laporan_done').DataTable().ajax.reload(null, false);
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

    $(document).on('click', '.delete-laporan-btn', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var url = "<?= route_to('laporan.delete') ?>";

        Swal.fire({
            title: 'Apakah anda yakin?',
            html: 'Anda ingin menghapus laporan ini?',
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
                        $('#data_laporan_pending').DataTable().ajax.reload(null, false);
                        $('#data_laporan_approve').DataTable().ajax.reload(null, false);
                        $('#data_laporan_unapprove').DataTable().ajax.reload(null, false);
                        $('#data_laporan_done').DataTable().ajax.reload(null, false);
                        toastr.success(response.msg);
                    } else {
                        toastr.error(response.msg);
                    }
                }, 'json');
            }
        });
    });

    $(document).on('click', '#filter_tanggal_laporan', function(e) {
        e.preventDefault();
        var modal = $('body').find('div#filter-tanggal-laporan-modal');
        var modal_title = 'Filter Tanggal';
        var modal_btn_text = 'Apply';
        modal.find('.modal-title').html(modal_title);
        modal.find('.modal-footer > button.action').html(modal_btn_text);
        modal.find('input.error-text').html('');
        modal.find('input[type="text"][name="tanggal_awal"]').val('');
        modal.find('input[type="text"][name="tanggal_akhir"]').val('');
        modal.modal('show');
    });

    const linkedPicker1Element = document.getElementById("tanggal_awal");
    const linked1 = new tempusDominus.TempusDominus(linkedPicker1Element);
    const linked2 = new tempusDominus.TempusDominus(document.getElementById("tanggal_akhir"), {
        useCurrent: false,
    });

    linkedPicker1Element.addEventListener(tempusDominus.Namespace.events.change, (e) => {
        linked2.updateOptions({
            restrictions: {
                minDate: e.detail.date,
            },
        });
    });

    const subscription = linked2.subscribe(tempusDominus.Namespace.events.change, (e) => {
        linked1.updateOptions({
            restrictions: {
                maxDate: e.date,
            },
        });
    });
</script>
<?= $this->endSection() ?>