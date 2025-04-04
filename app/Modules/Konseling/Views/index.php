<?= $this->extend('App\Modules\Layouts\Views\app.php') ?>

<?= $this->section('content') ?>
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h4>Data Konseling</h4>
                    <div class="card-header-action">
                        <a href="#" class="btn btn-success mr-1"><i class="fa fa-file-excel"></i> Export</a>
                        <button type="button" class="btn btn-danger"><i class="fa fa-trash"></i> Hapus Banyak</button>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center pb-3 active" data-toggle="tab" href="#table_masuk">
                                Masuk <span class="btn btn-sm btn-warning fw-bold ms-2 fs-8 py-2 px-2 ml-1"><?= $count_status_pending ?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center pb-3" data-toggle="tab" href="#tabel_disetujui">
                                Disetujui <span class="btn btn-sm btn-success fw-bold ms-2 fs-8 py-2 px-2 ml-1"><?= $count_status_approve ?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center pb-3" data-toggle="tab" href="#tabel_ditolak">
                                Ditolak <span class="btn btn-sm btn-danger fw-bold ms-2 fs-8 py-2 px-2 ml-1"><?= $count_status_unapprove ?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center pb-3" data-toggle="tab" href="#tabel_selesai">
                                Selesai <span class="btn btn-sm btn-primary fw-bold ms-2 fs-8 py-2 px-2 ml-1"><?= $count_status_done ?></span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <!-- Tabel Masuk -->
                        <div class="tab-pane fade show active" id="table_masuk" role="tabpanel">
                            <table class="table table-striped" id="data_konseling_pending">
                                <thead>
                                    <tr>
                                        <th width="5%">
                                            <div class="custom-checkbox custom-control">
                                                <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input" id="checkbox-all">
                                                <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                                            </div>
                                        </th>
                                        <th>Nama/Kelompok</th>
                                        <th>Kelas</th>
                                        <th>Jenis Konseling</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Deskripsi</th>
                                        <th width="12%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                </tbody>
                            </table>
                        </div>

                        <!-- Tabel Disetujui -->
                        <div class="tab-pane fade" id="tabel_disetujui" role="tabpanel">
                            <table class="table table-striped" id="data_konseling_approve">
                                <thead>
                                    <tr>
                                        <th width="5%">
                                            <div class="custom-checkbox custom-control">
                                                <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input" id="checkbox-all">
                                                <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                                            </div>
                                        </th>
                                        <th>Nama/Kelompok</th>
                                        <th>Kelas</th>
                                        <th>Jenis Konseling</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Deskripsi</th>
                                        <th width="12%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                </tbody>
                            </table>
                        </div>

                        <!-- Tabel Ditolak -->
                        <div class="tab-pane fade" id="tabel_ditolak" role="tabpanel">
                            <table class="table table-striped" id="data_konseling_unapprove">
                                <thead>
                                    <tr>
                                        <th width="5%">
                                            <div class="custom-checkbox custom-control">
                                                <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input" id="checkbox-all">
                                                <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                                            </div>
                                        </th>
                                        <th>Nama/Kelompok</th>
                                        <th>Kelas</th>
                                        <th>Jenis Konseling</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Deskripsi</th>
                                        <th width="12%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                </tbody>
                            </table>
                        </div>

                        <!-- Tabel Selesai -->
                        <div class="tab-pane fade" id="tabel_selesai" role="tabpanel">
                            <table class="table table-striped" id="data_konseling_done">
                                <thead>
                                    <tr>
                                        <th width="5%">
                                            <div class="custom-checkbox custom-control">
                                                <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input" id="checkbox-all">
                                                <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                                            </div>
                                        </th>
                                        <th>Nama/Kelompok</th>
                                        <th>Kelas</th>
                                        <th>Jenis Konseling</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Deskripsi</th>
                                        <th width="12%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('modal.php') ?>
<?= $this->endSection() ?>
<?php include('javascript.php') ?>