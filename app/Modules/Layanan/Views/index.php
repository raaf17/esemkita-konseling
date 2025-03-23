<?= $this->extend('App\Modules\Layouts\Views\app.php') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-5 mb-3">
        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <h2 class="text-gray-800">Tambah Data</h2>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0 pt-3">
                <form action="<?= route_to('layanan.store') ?>" method="POST" id="add-layanan-form" autocomplete="off">
                    <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" class="ci_csrf_data">
                    <div class="form-group">
                        <label for="nama_layanan">Nama Layanan</label>
                        <input type="text" name="nama_layanan" class="form-control mt-1" placeholder="Nama Layanan">
                        <span class="text-danger error-text nama_layanan_error"></span>
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
                        <h2 class="text-gray-800"><?= $title ?></h2>
                        <span class="text-muted text-sm" style="font-size: small; font-weight: 400;">Data per tanggal : <?= date('d/m/Y - H:i') ?></span>
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-sm btn-light-success me-3 btn-flex btn-center" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            <i class="ki-outline ki-some-files fs-3"></i> Excel <i class="ki-duotone ki-down fs-5 ms-1"></i>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                <div class="menu-item px-3">
                                    <a href="" class="menu-link px-3" id="export">
                                        Export
                                    </a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#import-layanan-modal">
                                        Import
                                    </a>
                                </div>
                            </div>
                        </button>
                        <button type="button" class="btn btn-sm btn-light-danger" onclick="onMultipleDelete()">
                            <i class="ki-outline ki-basket fs-3"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0 table-responsive">
                <?= form_open('layanan/multipledelete', ['id' => 'bulk']) ?>
                <table class="table align-middle table-row-dashed fs-6 gy-4" id="data_layanan">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input select_all" name="checkbox" type="checkbox" />
                                </div>
                            </th>
                            <th>Nama Layanan</th>
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
<?php include('modal.php') ?>
<?= $this->endSection() ?>
<?php include('javascript.php') ?>