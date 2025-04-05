<?= $this->extend('App\Modules\Layouts\Views\app.php') ?>

<?= $this->section('content') ?>
<div class="section-body">
    <div class="row">
        <div class="col-lg-4">
            <div class="card card-primary">
                <div class="card-header">
                    <h4>Tambah Jurusan</h4>
                </div>
                <div class="card-body">
                    <form action="" method="POST" id="jurusan_form" autocomplete="off">
                        <input type="hidden" name="" value="" class="ci_csrf_data">
                        <input type="text" name="id" hidden>
                        <div class="form-group mb-3">
                            <label for="nama_jurusan">Nama Jurusan</label>
                            <input type="text" name="nama_jurusan" class="form-control mt-1" placeholder="Nama Jurusan">
                        </div>
                        <div class="form-group">
                            <label for="id_guru">Kepala Jurusan</label>
                            <select name="id_guru" id="id_guru" class="form-control mt-1"></select>
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
            <div class="card card-primary">
                <div class="card-header">
                    <h4>Data Jurusan</h4>
                    <div class="card-header-action">
                        <button type="button" class="btn btn-primary mr-1" onclick="onImport()"><i class="fas fa-file-import"></i> Import</button>
                        <button type="button" class="btn btn-success mr-1" onclick="onExport()"><i class="fa fa-file-excel"></i> Export</button>
                        <button type="button" class="btn btn-danger" onclick="onMultipleDelete()"><i class="fa fa-trash"></i> Hapus Banyak</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <?= form_open('jurusan/multipledelete', ['id' => 'bulk']) ?>
                        <table class="table table-striped" id="data_jurusan">
                            <thead>
                                <tr>
                                    <th width="5%">
                                        <div class="custom-checkbox custom-control">
                                            <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input select_all" id="checkbox-all">
                                            <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                                        </div>
                                    </th>
                                    <th>Nama Jurusan</th>
                                    <th>Kepala Jurusan</th>
                                    <th width="8%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <?= form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('modal.php') ?>
<?= $this->endSection() ?>
<?php include('javascript.php') ?>