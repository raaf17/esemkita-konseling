<?= $this->section('scripts') ?>
<script>
    var tablePending, tableDone;

    $(function() {
        tablePending = $('#data_kunjungan_rumah_pending').DataTable({
            "processing": false,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?= route_to('kunjunganrumah.listdata') ?>",
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
            ],
            "language": {
                "url": "<?= base_url('stisla') ?>/assets/lang/indonesia.json"
            }
        });

        $('a[href="#tabel_sudah_dikunjungi"]').on('shown.bs.tab', function() {
            if (!$.fn.DataTable.isDataTable('#data_kunjungan_rumah_done')) {
                tableDone = $('#data_kunjungan_rumah_done').DataTable({
                    "processing": false,
                    "serverSide": true,
                    "order": [],
                    "ajax": {
                        "url": "<?= route_to('kunjunganrumah.listdata') ?>",
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

    $(document).on('click', '#add_kunjunganrumah_btn', function(e) {
        e.preventDefault();
        var modal = $('body').find('div#add-kunjunganrumah-modal');
        var modal_title = 'Buat Surat';
        var modal_btn_text = 'Simpan';
        modal.find('.modal-title').html(modal_title);
        modal.find('.modal-footer > button.action').html(modal_btn_text);
        modal.find('input.error-text').html('');
        modal.find('select[name="id_siswa"]').val('');
        modal.find('select[name="id_kelas"]').val('');
        modal.find('textarea[name="alamat"]').val('');
        modal.find('input[type="date"][name="tanggal"]').val('');
        modal.find('input[type="time"][name="jam"]').val('');
        modal.find('input[type="text"][name="anggota_keluarga"]').val('');
        modal.find('textarea[name="ringkasan_masalah"]').val('');
        modal.find('textarea[name="hasil_kunjungan"]').val('');
        modal.find('textarea[name="rencana_tindak_lanjut"]').val('');
        modal.find('textarea[name="catatan_khusus"]').val('');
        modal.find('textarea[name="keterangan"]').val('');
        modal.modal('show');
    });

    $('#add-kunjunganrumah-form').on('submit', function(e) {
        e.preventDefault();
        var csrfName = $('.ci_csrf_data').attr('name');
        var csrfHash = $('.ci_csrf_data').val();
        var form = this;
        var modal = $('body').find('div#add-kunjunganrumah-modal');
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
                        $('#data_kunjungan_rumah_pending').DataTable().ajax.reload(null, false);
                        $('#data_kunjungan_rumah_done').DataTable().ajax.reload(null, false);
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

    $(document).on('click', '.done-kunjunganrumah-btn', function(e) {
        let id = $(this).data('id');

        Swal.fire({
            title: 'Apakah anda yakin?',
            html: 'Anda sudah mengunjungi siswa ini?',
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
                    url: '<?= route_to('kunjunganrumah.done') ?>',
                    data: {
                        id: id
                    },
                    success: function(response) {
                        if (response.status == 1) {
                            $('#data_kunjungan_rumah_pending').DataTable().ajax.reload(null, false);
                            $('#data_kunjungan_rumah_done').DataTable().ajax.reload(null, false);
                            toastr.success(response.msg);
                        } else {
                            toastr.error(response.msg);
                        }
                    }
                })
            }
        });
    });

    $(document).on('click', '.detail-kunjunganrumah-btn', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var url = "<?= route_to('kunjunganrumah.getdetail') ?>";

        $.get(url, {
            id: id
        }, function(response) {
            if (response.data) {
                var modal_title = 'Detail Kunjungan Rumah';
                var modal = $('body').find('div#detail-kunjunganrumah-modal');
                modal.find('.modal-title').html(modal_title);

                var statusHtml = '';
                if (response.data.status == 0) {
                    statusHtml = '<span class="badge badge-warning" style="padding: 6px 8px; border-radius: 25px;">Menunggu</span>';
                } else if (response.data.status == 1) {
                    statusHtml = '<span class="badge badge-primary" style="padding: 6px 8px; border-radius: 25px;">Selesai</span>';
                }

                var dateTimeString = response.data.tanggal + ' ' + response.data.jam;
                var dateTime = new Date(dateTimeString);

                var formattedDate = new Intl.DateTimeFormat('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                }).format(dateTime);

                var formattedTime = new Intl.DateTimeFormat('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                }).format(dateTime);

                modal.find('tbody#kunjunganrumah-details').html(
                    '<tr>' +
                    '<td width="30%">Nama Siswa</td>' +
                    '<td width="10%">:</td>' +
                    '<td>' + response.data.nama_siswa + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td width="30%">Kelas</td>' +
                    '<td width="10%">:</td>' +
                    '<td>' + response.data.nama_kelas + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td width="30%">Alamat</td>' +
                    '<td width="10%">:</td>' +
                    '<td>' + response.data.alamat + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td width="30%">Guru yang Mengunjungi</td>' +
                    '<td width="10%">:</td>' +
                    '<td>' + response.data.nama_guru + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td width="30%">Tanggal</td>' +
                    '<td width="10%">:</td>' +
                    '<td>' + formattedDate + ' - ' + formattedTime + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td width="30%">Anggota Keluarga</td>' +
                    '<td width="10%">:</td>' +
                    '<td>' + response.data.anggota_keluarga + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td width="30%">Ringkasan Masalah</td>' +
                    '<td width="10%">:</td>' +
                    '<td>' + (!response.data.ringkasan_masalah ? 'Data tidak tersedia' : response.data.ringkasan_masalah) + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td width="30%">Hasil Kunjungan</td>' +
                    '<td width="10%">:</td>' +
                    '<td>' + (!response.data.hasil_kunjungan ? 'Data tidak tersedia' : response.data.hasil_kunjungan) + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td width="30%">Rencana Tindak Lanjut</td>' +
                    '<td width="10%">:</td>' +
                    '<td>' + (!response.data.rencana_tindak_lanjut ? 'Data tidak tersedia' : response.data.rencana_tindak_lanjut) + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td width="30%">Catatan Khusus</td>' +
                    '<td width="10%">:</td>' +
                    '<td>' + (!response.data.catatan_khusus ? 'Data tidak tersedia' : response.data.catatan_khusus) + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td width="30%">Keterangan</td>' +
                    '<td width="10%">:</td>' +
                    '<td>' + (!response.data.keterangan ? 'Data tidak tersedia' : response.data.keterangan) + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td width="30%">Status</td>' +
                    '<td width="10%">:</td>' +
                    '<td>' + statusHtml + '</td>' +
                    '</tr>'
                );
                modal.modal('show');
            } else {
                toastr.error('Data tidak ditemukan');
            }
        }, 'json');
    });

    // $(document).on('click', '.pdf-kunjunganrumah-btn', function(e) {
    //     e.preventDefault();
    //     var id = $(this).data('id');
    //     var url = "<?= route_to('kunjunganrumah.getpdf') ?>";
    //     window.open(url + '?id=' + id, '_blank');
    // });

    $(document).on('click', '.edit-kunjunganrumah-btn', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var url = "<?= route_to('kunjunganrumah.getkunjunganrumah') ?>";
        $.get(url, {
            id: id
        }, function(response) {
            var modal_title = 'Edit Kunjungan Rumah';
            var modal_btn_text = 'Simpan Perubahan';
            var modal = $('body').find('div#edit-kunjunganrumah-modal');
            modal.find('form').find('input[type="hidden"][name="id"]').val(id);
            modal.find('.modal-title').html(modal_title);
            modal.find('.modal-footer > button.action').html(modal_btn_text);
            modal.find('select[name="id_siswa"]').val(response.data.id_siswa);
            modal.find('select[name="id_kelas"]').val(response.data.id_kelas);
            modal.find('textarea[name="alamat"]').val(response.data.alamat);
            modal.find('select[name="id_guru"]').val(response.data.id_guru);
            modal.find('input[type="date"][name="tanggal"]').val(response.data.tanggal);
            modal.find('input[type="time"][name="jam"]').val(response.data.jam);
            modal.find('input[type="text"][name="anggota_keluarga"]').val(response.data.anggota_keluarga);
            modal.find('textarea[name="ringkasan_masalah"]').val(response.data.ringkasan_masalah);
            modal.find('textarea[name="hasil_kunjungan"]').val(response.data.hasil_kunjungan);
            modal.find('textarea[name="rencana_tindak_lanjut"]').val(response.data.rencana_tindak_lanjut);
            modal.find('textarea[name="catatan_khusus"]').val(response.data.catatan_khusus);
            modal.find('textarea[name="keterangan"]').val(response.data.keterangan);
            modal.find('span.error_text').html('');
            modal.modal('show');
        }, 'json');
    });

    $('#update-kunjunganrumah-form').on('submit', function(e) {
        e.preventDefault();
        // CSRF
        var csrfName = $('.ci_csrf_data').attr('name'); // CSRF Token name
        var csrfHash = $('.ci_csrf_data').val(); // CSRF Hash
        var form = this;
        var modal = $('body').find('div#edit-kunjunganrumah-modal');
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
                        $('#data_kunjungan_rumah_pending').DataTable().ajax.reload(null, false);
                        $('#data_kunjungan_rumah_done').DataTable().ajax.reload(null, false);
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

    $(document).on('click', '.delete-kunjunganrumah-btn', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var url = "<?= route_to('kunjunganrumah.delete') ?>";

        Swal.fire({
            title: 'Apakah anda yakin?',
            html: 'Anda ingin menghapus kunjungan rumah ini?',
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
                        $('#data_kunjungan_rumah_pending').DataTable().ajax.reload(null, false);
                        $('#data_kunjungan_rumah_done').DataTable().ajax.reload(null, false);
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
        window.location.href = '<?= route_to('kunjunganrumah.export') ?>';
    });

    $(document).on('click', '#filter_tanggal_kunjungan_rumah', function(e) {
        e.preventDefault();
        var modal = $('body').find('div#filter-tanggal-kunjungan-rumah-modal');
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

    function getPdf() {
        let jumlahData = $('.check:checked');

        if (jumlahData.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Gagal',
                text: 'Tidak ada data yang dipilih!'
            });
        } else if (jumlahData.length > 1) {
            Swal.fire({
                icon: 'warning',
                title: 'Gagal',
                text: 'Pilih 1 data!'
            });
        } else {
            $("#selected").submit();
        }
    }

    $("#selected").on("submit", function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        var id = $(this).data('id');
        var url = "<?= route_to('kunjunganrumah.getpdf') ?>";

        $.ajax({
            url: $(this).attr("action"),
            data: $(this).serialize(),
            type: "POST",
            success: function(response) {
                if (response.status == 1) {
                    window.open(url + '?id=' + id, '_blank');
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