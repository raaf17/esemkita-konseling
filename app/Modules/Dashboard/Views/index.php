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
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Aktifitas Konsultasi</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="doughnutChart" style="height: 300px;"></canvas>
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

                if (response.data && response.data.length > 0) {
                    $.each(response.data, function(i, row) {
                        jumlah.push(row.jumlah);
                        nama_layanan.push(row.nama_layanan);
                    });

                    // BAR CHART
                    var ctxBar = document.getElementById("chart").getContext("2d");
                    var barChart = new Chart(ctxBar, {
                        type: 'bar',
                        data: {
                            labels: nama_layanan,
                            datasets: [{
                                label: 'Jumlah',
                                data: jumlah,
                                backgroundColor: 'rgba(103,119,239,1)',
                                borderColor: 'rgba(103,119,239,1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(tooltipItem) {
                                            return tooltipItem.raw;
                                        }
                                    }
                                },
                            },
                            scales: {
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Layanan'
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Jumlah'
                                    }
                                }
                            }
                        }
                    });

                    // DOUGHNUT CHART
                    var ctxDoughnut = document.getElementById("doughnutChart").getContext("2d");
                    var doughnutChart = new Chart(ctxDoughnut, {
                        type: 'doughnut',
                        data: {
                            labels: nama_layanan,
                            datasets: [{
                                data: jumlah,
                                backgroundColor: generateColors(jumlah.length),
                                borderColor: '#fff',
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(tooltipItem) {
                                            let label = tooltipItem.label || '';
                                            let value = tooltipItem.raw || 0;
                                            return `${label}: ${value}`;
                                        }
                                    }
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

        // Fungsi bantu: generate warna random untuk doughnut
        function generateColors(count) {
            const colors = [
                '#6777ef', '#fc544b', '#63ed7a', '#ffa426',
                '#3abaf4', '#47c363', '#e83e8c', '#6f42c1'
            ];
            let result = [];
            for (let i = 0; i < count; i++) {
                result.push(colors[i % colors.length]);
            }
            return result;
        }
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