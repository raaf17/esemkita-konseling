<?= $this->extend('App\Modules\Layouts\Views\app.php') ?>

<?= $this->section('content') ?>
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h4>Data Kunjungan Rumah</h4>
                    <div class="card-header-action">
                        <a href="#" class="btn btn-success mr-1"><i class="fa fa-file-excel"></i> Export</a>
                        <button type="button" class="btn btn-danger"><i class="fa fa-trash"></i> Hapus Banyak</button>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center pb-3 active" data-toggle="tab" href="#table_belum_dikunjungi">
                                Belum Dikunjungi <span class="btn btn-sm btn-warning fw-bold ms-2 fs-8 py-2 px-2 ml-1"><?= $count_status_pending ?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center pb-3" data-toggle="tab" href="#tabel_sudah_dikunjungi">
                                Sudah Dikunjungi <span class="btn btn-sm btn-success fw-bold ms-2 fs-8 py-2 px-2 ml-1"><?= $count_status_done ?></span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <!-- Tabel Masuk -->
                        <div class="tab-pane fade show active" id="table_belum_dikunjungi" role="tabpanel">
                            <?= form_open('kunjunganrumah/getpdf', ['id' => 'selected']) ?>
                            <table class="table table-striped" id="data_kunjungan_rumah_pending">
                                <thead>
                                    <tr>
                                        <th width="5%">
                                            <div class="custom-checkbox custom-control">
                                                <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input" id="checkbox-all">
                                                <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                                            </div>
                                        </th>
                                        <th>Nama Siswa</th>
                                        <th>Alamat</th>
                                        <th>Yang Dikunjungi</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th width="12%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                </tbody>
                            </table>
                            <?= form_close(); ?>
                        </div>

                        <!-- Tabel Disetujui -->
                        <div class="tab-pane fade" id="tabel_sudah_dikunjungi" role="tabpanel">
                            <?= form_open('kunjunganrumah/getpdf', ['id' => 'selected']) ?>
                            <table class="table table-striped" id="data_kunjungan_rumah_done">
                                <thead>
                                    <tr>
                                        <th width="5%">
                                            <div class="custom-checkbox custom-control">
                                                <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input" id="checkbox-all">
                                                <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                                            </div>
                                        </th>
                                        <th>Nama Siswa</th>
                                        <th>Alamat</th>
                                        <th>Yang Dikunjungi</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th width="12%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                </tbody>
                            </table>
                            <?= form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card card-primary mt-3 pdf-card" style="display: none;">
    <div class="card-header">
        <h4>Surat Panggilan Orang Tua</h4>
    </div>
    <div class="card-body" id="pdf_preview">
        <object data="" type="application/pdf" width="100%" height="500"></object>
    </div>
</div>
<?php include('modal.php') ?>
<?= $this->endSection() ?>
<?php include('javascript.php') ?>