<?= $this->extend('App\Modules\Layouts\Views\app.php') ?>

<?= $this->section('content') ?>
<div class="card card-flush">
    <div class="card-body table-responsive">
        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-transparent fs-6 fw-semibold">
            <li class="nav-item">
                <a class="nav-link text-active-primary d-flex align-items-center pb-3 active" data-bs-toggle="tab" href="#table_belum_dikunjungi">
                    Belum Dikunjungi
                    <span class="btn btn-sm btn-light-warning fw-bold ms-2 fs-8 py-1 px-3"><?= $count_status_pending ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary d-flex align-items-center pb-3" data-bs-toggle="tab" href="#tabel_sudah_dikunjungi">
                    Sudah Dikunjungi
                    <span class="btn btn-sm btn-light-primary fw-bold ms-2 fs-8 py-1 px-3"><?= $count_status_done ?></span>
                </a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <!-- Tabel Belum Dikunjungi -->
            <div class="tab-pane fade show active" id="table_belum_dikunjungi" role="tabpanel">
                <?= form_open('kunjunganrumah/getpdf', ['id' => 'selected']) ?>
                <table class="table align-middle table-row-dashed fs-6 gy-4" id="data_kunjungan_rumah_pending">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" name="checkbox" type="checkbox" />
                                </div>
                            </th>
                            <th>Nama Siswa</th>
                            <th>Alamat</th>
                            <th>Yang Dikunjungi</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                    </tbody>
                </table>
                <?= form_close(); ?>
            </div>

            <!-- Tabel Sudah Dikunjungi -->
            <div class="tab-pane fade" id="tabel_sudah_dikunjungi" role="tabpanel">
                <?= form_open('kunjunganrumah/getpdf', ['id' => 'selected']) ?>
                <table class="table align-middle table-row-dashed fs-6 gy-4" id="data_kunjungan_rumah_done">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" name="checkbox" type="checkbox" />
                                </div>
                            </th>
                            <th>Nama Siswa</th>
                            <th>Alamat</th>
                            <th>Yang Dikunjungi</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th width="15%">Aksi</th>
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

<div class="card card-flush mt-3" style="display: none;">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <h2 class="text-gray-800">Surat Panggilan Orang Tua</h2>
        </div>
    </div>
    <div class="card-body" id="pdf">
        <object data="" id="pdf-preview" type="application/pdf" width="100%" height="500"></object>
    </div>
</div>
<?php include('modal.php') ?>
<?= $this->endSection() ?>
<?php include('javascript.php') ?>