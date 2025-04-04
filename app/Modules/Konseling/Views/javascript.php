<?= $this->section('scripts') ?>
<script>
    var tablePending, tableApproved, tableUnapproved, tableDone;

    $(function() {
        tablePending = $('#data_konseling_pending').DataTable({
            "processing": false,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?= route_to('konseling.listdata') ?>",
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

        $('a[href="#tabel_disetujui"]').on('shown.bs.tab', function() {
            if (!$.fn.DataTable.isDataTable('#data_konseling_approve')) {
                tableApproved = $('#data_konseling_approve').DataTable({
                    "processing": false,
                    "serverSide": true,
                    "order": [],
                    "ajax": {
                        "url": "<?= route_to('konseling.listdata') ?>",
                        "type": "POST",
                        "data": function(d) {
                            d.status = 'approved';
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
                tableApproved.ajax.reload(null, false);
            }
        });

        $('a[href="#tabel_ditolak"]').on('shown.bs.tab', function() {
            if (!$.fn.DataTable.isDataTable('#data_konseling_unapprove')) {
                tableUnapproved = $('#data_konseling_unapprove').DataTable({
                    "processing": false,
                    "serverSide": true,
                    "order": [],
                    "ajax": {
                        "url": "<?= route_to('konseling.listdata') ?>",
                        "type": "POST",
                        "data": function(d) {
                            d.status = 'unapproved';
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
                tableUnapproved.ajax.reload(null, false);
            }
        });

        $('a[href="#tabel_selesai"]').on('shown.bs.tab', function() {
            if (!$.fn.DataTable.isDataTable('#data_konseling_done')) {
                tableDone = $('#data_konseling_done').DataTable({
                    "processing": false,
                    "serverSide": true,
                    "order": [],
                    "ajax": {
                        "url": "<?= route_to('konseling.listdata') ?>",
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

    $(document).on('click', '.approve-konseling-btn', function(e) {
        let id = $(this).data('id');

        Swal.fire({
            title: 'Apakah anda yakin?',
            html: 'Anda ingin menyetujui konseling ini?',
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
                    url: '<?= route_to('konseling.approve') ?>',
                    data: {
                        id: id
                    },
                    success: function(response) {
                        if (response.status == 1) {
                            tablePending.ajax.reload(null, false);
                            tableApproved.ajax.reload(null, false);
                            tableUnapproved.ajax.reload(null, false);
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

    $(document).on('click', '.unapprove-konseling-btn', function(e) {
        let id = $(this).data('id');

        Swal.fire({
            title: 'Apakah anda yakin?',
            html: 'Anda ingin menolak konseling ini?',
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
                    url: '<?= route_to('konseling.unapprove') ?>',
                    data: {
                        id: id
                    },
                    success: function(response) {
                        if (response.status == 1) {
                            tablePending.ajax.reload(null, false);
                            tableApproved.ajax.reload(null, false);
                            tableUnapproved.ajax.reload(null, false);
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

    $(document).on('click', '.detail-konseling-btn', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var url = "<?= route_to('konseling.getdetail') ?>";

        $.get(url, {
            id: id
        }, function(response) {
            if (response.data) {
                var modal_title = 'Detail Konseling';
                var modal = $('body').find('div#detail-konseling-modal');
                modal.find('.modal-title').html(modal_title);

                var statusHtml = '';
                if (response.data.status == 0) {
                    statusHtml = '<span class="badge badge-warning" style="padding: 6px 8px; border-radius: 25px;">Menunggu</span>';
                } else if (response.data.status == 1) {
                    statusHtml = '<span class="badge badge-success" style="padding: 6px 8px; border-radius: 25px;">Disetujui</span>';
                } else if (response.data.status == 2) {
                    statusHtml = '<span class="badge badge-danger" style="padding: 6px 8px; border-radius: 25px;">Ditolak</span>';
                }

                // Struktur tabel dengan nilai diisi dari response data
                modal.find('tbody#konseling-details').html(
                    '<tr>' +
                    '<td width="30%">Nama/Kelompok</td>' +
                    '<td width="10%">:</td>' +
                    '<td>' + response.data.nama_siswa + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td width="30%">Kelas</td>' +
                    '<td width="10%">:</td>' +
                    '<td>' + response.data.nama_kelas + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td width="30%">Guru Konseling</td>' +
                    '<td width="10%">:</td>' +
                    '<td>' + response.data.nama_guru + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td width="30%">Jenis Konseling</td>' +
                    '<td width="10%">:</td>' +
                    '<td>' + response.data.nama_layanan + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td width="30%">Tanggal</td>' +
                    '<td width="10%">:</td>' +
                    '<td>' + response.data.tanggal + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td width="30%">Jam</td>' +
                    '<td width="10%">:</td>' +
                    '<td>' + response.data.jam + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td width="30%">Status</td>' +
                    '<td width="10%">:</td>' +
                    '<td>' + statusHtml + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td width="30%">Deskripsi</td>' +
                    '<td width="10%">:</td>' +
                    '<td>' + response.data.deskripsi + '</td>' +
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
        window.location.href = '<?= route_to('konseling.export') ?>';
    });

    $(document).on('click', '#add_konseling_btn', function(e) {
        e.preventDefault();
        var modal = $('body').find('div#add-konseling-modal');
        var modal_title = 'Buat Konseling';
        var modal_btn_text = 'Simpan';
        modal.find('.modal-title').html(modal_title);
        modal.find('.modal-footer > button.action').html(modal_btn_text);
        modal.find('input.error-text').html('');
        modal.find('select[name="id_guru"]').val('');
        modal.find('select[name="id_layanan"]').val('');
        modal.find('input[type="date"][name="tanggal"]').val('');
        modal.find('input[type="time"][name="jam"]').val('');
        modal.find('textarea[name="deskripsi"]').val('');
        modal.modal('show');
    });

    $('#add-konseling-form').on('submit', function(e) {
        e.preventDefault();
        var csrfName = $('.ci_csrf_data').attr('name');
        var csrfHash = $('.ci_csrf_data').val();
        var form = this;
        var modal = $('body').find('div#add-konseling-modal');
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
                        $('#data_konseling_pending').DataTable().ajax.reload(null, false);
                        $('#data_konseling_approve').DataTable().ajax.reload(null, false);
                        $('#data_konseling_unapprove').DataTable().ajax.reload(null, false);
                        $('#data_konseling_done').DataTable().ajax.reload(null, false);
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

    $(document).on('click', '.delete-konseling-btn', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var url = "<?= route_to('konseling.delete') ?>";

        Swal.fire({
            title: 'Apakah anda yakin?',
            html: 'Anda ingin menghapus pengajuan konseling ini?',
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
                        $('#data_konseling_pending').DataTable().ajax.reload(null, false);
                        $('#data_konseling_approve').DataTable().ajax.reload(null, false);
                        $('#data_konseling_unapprove').DataTable().ajax.reload(null, false);
                        $('#data_konseling_done').DataTable().ajax.reload(null, false);
                        toastr.success(response.msg);
                    } else {
                        toastr.error(response.msg);
                    }
                }, 'json');
            }
        });
    });

    $(document).on('click', '#filter_tanggal_konseling', function(e) {
        e.preventDefault();
        var modal = $('body').find('div#filter-tanggal-konseling-modal');
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