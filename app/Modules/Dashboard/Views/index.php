<?= $this->extend('App\Modules\Layouts\Views\app.php') ?>

<?= $this->section('content') ?>
<div class="main-content">
    <section class="section">
        <div class="row">
            <div class="col-lg-7">
                <div class="section-header">
                    <h1>Dashboard</h1>
                </div>
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
                                <div id="chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-12">
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
    </section>
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

                    var options = {
                        series: [{
                            name: 'Jumlah',
                            type: 'column',
                            data: jumlah
                        }],
                        chart: {
                            height: 300,
                            type: 'line'
                        },
                        stroke: {
                            width: [0, 4]
                        },
                        dataLabels: {
                            enabled: true,
                            enabledOnSeries: [1]
                        },
                        labels: nama_layanan,
                        yaxis: [{
                            title: {
                                text: 'Jumlah'
                            }
                        }]
                    };

                    var chart = new ApexCharts(document.querySelector("#chart"), options);
                    chart.render();
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
            "url": "<?= base_url() ?>/datatable/lang/indonesia.json"
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

    $(document).ready(function() {
        var greetElem = document.getElementById("greetings");

        if (!greetElem) {
            console.error("Element with ID 'greetings' not found.");
            return;
        }

        var curHr = new Date().getHours();
        var userName = "<?= addslashes(get_user()->nama ?? 'User') ?>"; // Handle if $user is not set
        var greetMes = [
            `Wow! Masih Begadang? ${userName}`,
            `Selamat Pagi, ${userName}`,
            `Selamat Siang, ${userName}`,
            `Selamat Sore, ${userName}`,
            `Selamat Malam, ${userName}`,
            `Belum Tidur ya? ${userName}`
        ];

        let greetText = "";

        if (curHr < 4) greetText = greetMes[0];
        else if (curHr < 10) greetText = greetMes[1];
        else if (curHr < 16) greetText = greetMes[2];
        else if (curHr < 18) greetText = greetMes[3];
        else if (curHr < 22) greetText = greetMes[4];
        else greetText = greetMes[5];

        greetElem.textContent = greetText;
    });
</script>
<?= $this->endSection() ?>