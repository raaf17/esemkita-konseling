<?= $this->extend('App\Modules\Layouts\Views\app.php') ?>

<?= $this->section('content') ?>
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center pb-3 active" data-toggle="tab" href="#sub_masalah">
                                Sub Masalah
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center pb-3" data-toggle="tab" href="#main_masalah">
                                Main Masalah
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="sub_masalah" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Tambah Sub Jurusan</h4>
                                        </div>
                                        <div class="card-body">
                                            <form action="" method="POST" id="add-sub-masalah-form" autocomplete="off">
                                                <input type="hidden" name="" value="" class="ci_csrf_data">
                                                <div class="form-group mb-3">
                                                    <label for="nama_sub_masalah">Nama Masalah</label>
                                                    <input type="text" name="nama_sub_masalah" class="form-control mt-1" placeholder="Nama Masalah">
                                                    <span class="text-danger error-text nama_sub_masalah_error"></span>
                                                </div>
                                                <div class="form-group">
                                                    <label for="id_main_masalah">Kelompok Masalah</label>
                                                    <select name="id_main_masalah" id="id_main_masalah" class="form-control mt-1"></select>
                                                    <span class="text-danger error-text id_guru_error"></span>
                                                </div>
                                                <div class="d-flex justify-content-end pt-7">
                                                    <button type="reset" class="btn btn-secondary mr-1">Batal</button>
                                                    <button type="button" class="btn btn-primary" onclick="onSave()" id="save">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Data Sub Masalah</h4>
                                            <div class="card-header-action">
                                                <button type="button" class="btn btn-success mr-1" onclick="onExport('sub_masalah')"><i class="fa fa-file-excel"></i> Export</button>
                                                <button type="button" class="btn btn-danger" onclick="onMultipleDelete()"><i class="fa fa-trash"></i> Hapus Banyak</button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-striped" id="data_sub_masalah">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">
                                                                <div class="custom-checkbox custom-control">
                                                                    <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input select_all" id="checkbox-all">
                                                                    <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                                                                </div>
                                                            </th>
                                                            <th>Kelompok Masalah</th>
                                                            <th>Nama Masalah</th>
                                                            <th width="8%">Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="main_masalah" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Tambah Main Masalah</h4>
                                        </div>
                                        <div class="card-body">
                                            <form action="" method="POST" id="add-main-masalah-form" autocomplete="off">
                                                <input type="hidden" name="" value="" class="ci_csrf_data">
                                                <div class="form-group mb-3">
                                                    <label for="nama_main_masalah">Kelompok Masalah</label>
                                                    <input type="text" name="nama_main_masalah" class="form-control mt-1" placeholder="Nama Masalah">
                                                    <span class="text-danger error-text nama_main_masalah_error"></span>
                                                </div>
                                                <div class="d-flex justify-content-end pt-7">
                                                    <button type="reset" class="btn btn-secondary mr-1">Batal</button>
                                                    <button type="button" class="btn btn-primary" onclick="onSave()" id="save">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Data Main Masalah</h4>
                                            <div class="card-header-action">
                                                <button type="button" class="btn btn-success mr-1" onclick="onExport('main_masalah')"><i class="fa fa-file-excel"></i> Export</button>
                                                <button type="button" class="btn btn-danger" onclick="onMultipleDelete()"><i class="fa fa-trash"></i> Hapus Banyak</button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-striped" id="data_main_masalah">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">
                                                                <div class="custom-checkbox custom-control">
                                                                    <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input select_all" id="checkbox-all">
                                                                    <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                                                                </div>
                                                            </th>
                                                            <th>Kelompok Masalah</th>
                                                            <th width="8%">Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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