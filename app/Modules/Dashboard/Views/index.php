<?= $this->extend('App\Modules\Layouts\Views\app.php') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-8">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Aktifitas Konsultasi</h4>
                        <div class="card-header-action">
                            <div class="btn-group">
                                <a href="#" class="btn btn-primary">Week</a>
                                <a href="#" class="btn">Month</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="chart" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card card-statistic-2">
            <div class="card-header">
                <h5>Quick Data</h5>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="card-icon shadow-primary bg-primary">
                        <i class="fas fa-archive"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Orders</h4>
                        </div>
                        <div class="card-body">
                            59
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card-icon shadow-primary bg-primary">
                        <i class="fas fa-archive"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Orders</h4>
                        </div>
                        <div class="card-body">
                            59
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card-icon shadow-primary bg-primary">
                        <i class="fas fa-archive"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Orders</h4>
                        </div>
                        <div class="card-body">
                            59
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card-icon shadow-primary bg-primary">
                        <i class="fas fa-archive"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Orders</h4>
                        </div>
                        <div class="card-body">
                            59
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    $(function() {
        $.ajax({
            url: '<?= route_to('dashboard') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var jumlah = [];
                var nama_layanan = [];

                // Cek apakah data dalam response ada dan memiliki elemen
                if (response.data && response.data.length > 0) {
                    $.each(response.data, function(i, row) {
                        jumlah.push(row.jumlah);
                        nama_layanan.push(row.nama_layanan);
                    });

                    var ctx = document.getElementById("chart").getContext("2d");
                    var chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: nama_layanan,
                            datasets: [{
                                label: 'Jumlah',
                                data: jumlah,
                                borderColor: 'rgba(103,119,239,255)',
                                backgroundColor: 'rgba(103,119,239,255)',
                                borderWidth: 2,
                                pointRadius: 5, // Dot size
                                pointBackgroundColor: 'rgba(103,119,239,255)',
                                pointBorderColor: 'white',
                                pointBorderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    // position: 'top'
                                },
                                tooltip: {
                                    enabled: true,
                                    callbacks: {
                                        label: function(tooltipItem) {
                                            let value = tooltipItem.raw || 0;
                                            return value
                                        }
                                    }
                                },
                                datalabels: {
                                    anchor: 'end', // Position on top of the dot
                                    align: 'top',
                                    color: 'black',
                                    font: {
                                        weight: 'bold',
                                        size: 12
                                    },
                                }
                            },
                            scales: {
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Layanan'
                                    }
                                },
                                y: {
                                    title: {
                                        display: true,
                                        text: 'Jumlah'
                                    },
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                } else {
                    console.error("Data tidak ditemukan");
                }
            },
            error: function(xhr, status, error) {
                console.error("Terjadi kesalahan saat mengambil data:", error);
            }
        });
    });

    var table = $('#data_konseling_all').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": "<?= route_to('home.listdataall') ?>",
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
            },
        ],
        "language": {
            "url": "<?= base_url('stisla') ?>/assets/lang/indonesia.json"
        }
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
                    '<td><img src="' + fotoUrl + '" style="width: 200px; border-radius: 2px;" alt="Bukti" /></td>' +
                    '</tr>'
                );
                modal.modal('show');
            } else {
                toastr.error('Data tidak ditemukan');
            }
        }, 'json');
    });
</script>
<?= $this->endSection() ?>