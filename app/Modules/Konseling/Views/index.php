<?= $this->extend('App\Modules\Layouts\Views\app.php') ?>

<?= $this->section('content') ?>
<div class="card card-flush">
    <div class="card-body table-responsive">
        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-transparent fs-6 fw-semibold">
            <li class="nav-item">
                <a class="nav-link text-active-primary d-flex align-items-center pb-3 active" data-bs-toggle="tab" href="#table_masuk">
                    Masuk
                    <span class="btn btn-sm btn-light-warning fw-bold ms-2 fs-8 py-1 px-3"><?= $count_status_pending ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary d-flex align-items-center pb-3" data-bs-toggle="tab" href="#tabel_disetujui">
                    Disetujui
                    <span class="btn btn-sm btn-light-success fw-bold ms-2 fs-8 py-1 px-3"><?= $count_status_approve ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary d-flex align-items-center pb-3" data-bs-toggle="tab" href="#tabel_ditolak">
                    Ditolak
                    <span class="btn btn-sm btn-light-danger fw-bold ms-2 fs-8 py-1 px-3"><?= $count_status_unapprove ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary d-flex align-items-center pb-3" data-bs-toggle="tab" href="#tabel_selesai">
                    Selesai
                    <span class="btn btn-sm btn-light-primary fw-bold ms-2 fs-8 py-1 px-3"><?= $count_status_done ?></span>
                </a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <!-- Tabel Masuk -->
            <div class="tab-pane fade show active" id="table_masuk" role="tabpanel">
                <table class="table align-middle table-row-dashed fs-6 gy-4" id="data_konseling_pending">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" name="checkbox" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_customers_table .form-check-input" value="1" />
                                </div>
                            </th>
                            <th>Nama/Kelompok</th>
                            <th>Kelas</th>
                            <th>Jenis Konseling</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Deskripsi</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                    </tbody>
                </table>
            </div>

            <!-- Tabel Disetujui -->
            <div class="tab-pane fade" id="tabel_disetujui" role="tabpanel">
                <table class="table align-middle table-row-dashed fs-6 gy-4" id="data_konseling_approve">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" name="checkbox" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_customers_table .form-check-input" value="1" />
                                </div>
                            </th>
                            <th>Nama/Kelompok</th>
                            <th>Kelas</th>
                            <th>Jenis Konseling</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Deskripsi</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                    </tbody>
                </table>
            </div>

            <!-- Tabel Ditolak -->
            <div class="tab-pane fade" id="tabel_ditolak" role="tabpanel">
                <table class="table align-middle table-row-dashed fs-6 gy-4" id="data_konseling_unapprove">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" name="checkbox" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_customers_table .form-check-input" value="1" />
                                </div>
                            </th>
                            <th>Nama/Kelompok</th>
                            <th>Kelas</th>
                            <th>Jenis Konseling</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Deskripsi</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                    </tbody>
                </table>
            </div>

            <!-- Tabel Selesai -->
            <div class="tab-pane fade" id="tabel_selesai" role="tabpanel">
                <table class="table align-middle table-row-dashed fs-6 gy-4" id="data_konseling_done">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" name="checkbox" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_customers_table .form-check-input" value="1" />
                                </div>
                            </th>
                            <th>Nama/Kelompok</th>
                            <th>Kelas</th>
                            <th>Jenis Konseling</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Deskripsi</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include('modal.php') ?>
<?= $this->endSection() ?>
<?php include('javascript.php') ?>