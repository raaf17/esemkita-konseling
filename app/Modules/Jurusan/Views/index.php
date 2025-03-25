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
                    <form action="<?= route_to('jurusan.store') ?>" method="POST" id="add-jurusan-form" autocomplete="off">
                        <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" class="ci_csrf_data">
                        <div class="form-group mb-3">
                            <label for="nama_jurusan">Nama Jurusan</label>
                            <input type="text" name="nama_jurusan" class="form-control mt-1" placeholder="Nama Jurusan">
                            <span class="text-danger error-text nama_jurusan_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="id_guru">Kepala Jurusan</label>
                            <select name="id_guru" class="form-control mt-1"></select>
                            <span class="text-danger error-text id_guru_error"></span>
                        </div>
                        <div class="d-flex justify-content-end pt-7">
                            <button type="reset" class="btn btn-secondary mr-1">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
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
                        <button type="button" class="btn btn-primary mr-1" onclick="onAdd()"><i class="fa fa-plus-circle"></i> Tambah</button>
                        <button type="button" class="btn btn-danger"><i class="fa fa-trash"></i> Hapus Banyak</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="data_jurusan">
                            <thead>
                                <tr>
                                    <th width="5%">
                                        <div class="custom-checkbox custom-control">
                                            <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input" id="checkbox-all">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('modal.php') ?>
<?= $this->endSection() ?>
<?php include('javascript.php') ?>