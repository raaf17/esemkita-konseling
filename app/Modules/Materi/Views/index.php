<?= $this->extend('App\Modules\Layouts\Views\app.php') ?>

<?= $this->section('content') ?>
<div class="section-body">
    <div class="row">
        <div class="col-lg-4">
            <div class="card card-primary">
                <div class="card-header">
                    <h4>Tambah Quesioner</h4>
                </div>
                <div class="card-body">
                    <form action="" method="POST" id="materi_form" autocomplete="off">
                        <input type="hidden" name="" value="" class="ci_csrf_data">
                        <div class="form-group">
                            <label for="judul_materi">Judul Materi</label>
                            <input type="text" name="judul_materi" class="form-control mt-1" placeholder="Judul Materi">
                            <span class="text-danger error-text judul_materi_error"></span>
                        </div>
                        <div class="form-group mt-3">
                            <label for="isi_materi">Isi Materi</label>
                            <textarea name="isi_materi" id="" cols="30" rows="10" class="form-control" placeholder="Isi Materi"></textarea>
                            <span class="text-danger error-text isi_materi_error"></span>
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
                    <h4>Data Materi</h4>
                    <div class="card-header-action">
                        <a href="#" class="btn btn-info mr-1"><i class="fa fa-file"></i> Import</a>
                        <a href="#" class="btn btn-success mr-1"><i class="fa fa-file-excel"></i> Export</a>
                        <button type="button" class="btn btn-danger"><i class="fa fa-trash"></i> Hapus Banyak</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <?= form_open('materi/multipledelete', ['id' => 'bulk']) ?>
                        <table class="table table-striped" id="data_materi">
                            <thead>
                                <tr>
                                    <th width="5%">
                                        <div class="custom-checkbox custom-control">
                                            <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input select_all" id="checkbox-all">
                                            <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                                        </div>
                                    </th>
                                    <th>Judul Materi</th>
                                    <th>Isi Materi</th>
                                    <th width="12%">Aksi</th>
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