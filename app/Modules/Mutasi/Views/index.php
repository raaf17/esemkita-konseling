<?= $this->extend('App\Modules\Layouts\Views\app.php') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div>
                    <h2 class="text-gray-800">Rekapitulasi <?= $title ?> Masuk</h2>
                    <span class="text-muted text-sm" style="font-size: small; font-weight: 400;">Data per tanggal : <?= date('d/m/Y - H:i') ?></span>
                </div>
            </div>
            <div class="card-toolbar">
                <div class="d-flex justify-content-end">
                    <form action="<?= route_to('konseling.listdata') ?>" method="POST">
                        <button type="button" class="btn btn-sm btn-light-primary me-3" id="filter_tanggal_mutasi">
                            <i class="ki-outline ki-filter fs-3"></i> Filter
                        </button>
                    </form>
                    <button type="button" class="btn btn-sm btn-light-success me-3 btn-flex btn-center" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                        <i class="ki-outline ki-some-files fs-3"></i> Excel <i class="ki-duotone ki-down fs-5 ms-1"></i>
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                            <div class="menu-item px-3">
                                <a href="" class="menu-link px-3" id="export">
                                    Export
                                </a>
                            </div>
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#import-mutasi-modal">
                                    Import
                                </a>
                            </div>
                        </div>
                    </button>
                    <button type="button" class="btn btn-sm btn-light-danger me-3" onclick="onMultipleDelete()">
                        <i class="ki-outline ki-basket fs-3"></i> Hapus
                    </button>
                    <button type="button" class="btn btn-sm btn-light-primary" id="add_mutasi_btn">
                        <i class="ki-outline ki-plus-square fs-3"></i> Tambah
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body pt-0 table-responsive">
            <?= form_open('mutasi/multipledelete', ['id' => 'bulk']) ?>
            <table class="table align-middle table-row-dashed fs-6 gy-4" id="data_mutasi">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th class="w-10px pe-2">
                            <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                <input class="form-check-input select_all" name="checkbox" type="checkbox" />
                            </div>
                        </th>
                        <th>Tanggal Diterima</th>
                        <th width="15%">Asal Sekolah</th>
                        <th>No. Surat</th>
                        <th>Nama</th>
                        <th>JK</th>
                        <th width="15%">Alasan</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                </tbody>
            </table>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<?php include('modal.php') ?>
<?= $this->endSection() ?>
<?php include('javascript.php') ?>