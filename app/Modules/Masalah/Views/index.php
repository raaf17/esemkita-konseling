<?= $this->extend('App\Modules\Layouts\Views\app.php') ?>

<?= $this->section('content') ?>
<ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-6 fw-semibold mb-5">
    <li class="nav-item">
        <a class="nav-link text-active-primary pb-1 active" data-bs-toggle="tab" href="#sub_masalah">Sub Masalah</a>
    </li>
    <li class="nav-item">
        <a class="nav-link text-active-primary pb-1" data-bs-toggle="tab" href="#main_masalah">Main Masalah</a>
    </li>
</ul>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade active show" id="sub_masalah" role="tabpanel">
        <div class="row">
            <div class="col-lg-5 mb-3">
                <div class="card">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1">
                                <h2 class="text-gray-800">Tambah Sub Masalah</h2>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0 pt-3">
                        <form action="<?= route_to('daftar_cek_masalah.store') ?>" method="POST" id="add-sub-masalah-form" autocomplete="off">
                            <input type="hidden" name="" value="" class="ci_csrf_data">
                            <div class="form-group mb-3">
                                <label for="nama_sub_masalah">Nama Masalah</label>
                                <input type="text" name="nama_sub_masalah" class="form-control mt-1" placeholder="Nama Masalah">
                                <span class="text-danger error-text nama_sub_masalah_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="id_main_masalah">Kelompok Masalah</label>
                                <select name="id_main_masalah" class="form-select mt-1">
                                    <option value="">Pilih Kelompok Masalah</option>
                                    <?php foreach ($option_main_masalah as $main_masalah) : ?>
                                        <option value="<?= $main_masalah->id ?>"><?= $main_masalah->nama_main_masalah ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="text-danger error-text id_guru_error"></span>
                            </div>
                            <div class="d-flex justify-content-end pt-7">
                                <button type="reset" class="btn btn-sm btn-light fw-bold btn-active-light-primary me-2">Batal</button>
                                <button type="submit" class="btn btn-sm fw-bold btn-primary" id="kt_button_submit">
                                    <span class="indicator-label">
                                        Simpan
                                    </span>
                                    <span class="indicator-progress">
                                        Tolong tunggu... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <div>
                                <h2 class="text-gray-800">Data Sub Masalah</h2>
                                <span class="text-muted text-sm" style="font-size: small; font-weight: 400;">Data per tanggal : <?= date('d/m/Y - H:i') ?></span>
                            </div>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-sm btn-light-danger me-3">
                                    <i class="ki-outline ki-document fs-3"></i> PDF
                                </button>
                                <button type="button" class="btn btn-sm btn-light-success btn-flex btn-center" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                    <i class="ki-outline ki-some-files fs-3"></i> Excel <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                        <div class="menu-item px-3">
                                            <a href="" class="menu-link px-3" id="export-sub-masalah">
                                                Export
                                            </a>
                                        </div>
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#import-daftarcekmasalah-modal">
                                                Import
                                            </a>
                                        </div>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0 table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-4" id="data_sub_masalah">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="w-10px pe-2">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                            <input class="form-check-input" name="checkbox" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_customers_table .form-check-input" value="1" />
                                        </div>
                                    </th>
                                    <th>Nama Masalah</th>
                                    <th>Kelompok Masalah</th>
                                    <th width="15%">Aksi</th>
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
    <div class="tab-pane fade" id="main_masalah" role="tabpanel">
        <div class="row">
            <div class="col-lg-5 mb-3">
                <div class="card">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1">
                                <h2 class="text-gray-800">Tambah Main Masalah</h2>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0 pt-3">
                        <form action="<?= route_to('daftar_cek_masalah.storemain') ?>" method="POST" id="add-main-masalah-form" autocomplete="off">
                            <input type="hidden" name="" value="" class="ci_csrf_data">
                            <div class="form-group mb-3">
                                <label for="nama_main_masalah">Kelompok Masalah</label>
                                <input type="text" name="nama_main_masalah" class="form-control mt-1" placeholder="Nama Masalah">
                                <span class="text-danger error-text nama_main_masalah_error"></span>
                            </div>
                            <div class="d-flex justify-content-end pt-7">
                                <button type="reset" class="btn btn-sm btn-light fw-bold btn-active-light-primary me-2">Batal</button>
                                <button type="submit" class="btn btn-sm fw-bold btn-primary" id="kt_button_submit">
                                    <span class="indicator-label">
                                        Simpan
                                    </span>
                                    <span class="indicator-progress">
                                        Tolong tunggu... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <div>
                                <h2 class="text-gray-800">Data Main Masalah</h2>
                                <span class="text-muted text-sm" style="font-size: small; font-weight: 400;">Data per tanggal : <?= date('d/m/Y - H:i') ?></span>
                            </div>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-sm btn-light-danger me-3">
                                    <i class="ki-outline ki-document fs-3"></i> PDF
                                </button>
                                <button type="button" class="btn btn-sm btn-light-success btn-flex btn-center" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                    <i class="ki-outline ki-some-files fs-3"></i> Excel <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                        <div class="menu-item px-3">
                                            <a href="" class="menu-link px-3" id="export-main-masalah">
                                                Export
                                            </a>
                                        </div>
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#import-daftarcekmasalah-modal">
                                                Import
                                            </a>
                                        </div>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0 table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-4" id="data_main_masalah">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="w-10px pe-2">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                            <input class="form-check-input" name="checkbox" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_customers_table .form-check-input" value="1" />
                                        </div>
                                    </th>
                                    <th>Kelompok Masalah</th>
                                    <th width="15%">Aksi</th>
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
<?php include('modal.php') ?>
<?= $this->endSection() ?>
<?php include('javascript.php') ?>