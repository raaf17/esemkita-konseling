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
                    <form action="<?= route_to('quiz.store') ?>" method="POST" id="add-quiz-form" autocomplete="off">
                        <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" class="ci_csrf_data">
                        <div class="form-group">
                            <label for="quiz">Quiz</label>
                            <input type="text" name="quiz" class="form-control mt-1" placeholder="Quiz">
                            <span class="text-danger error-text quiz_error"></span>
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
                    <h4>Data Quesioner</h4>
                    <div class="card-header-action">
                        <a href="#" class="btn btn-info mr-1"><i class="fa fa-file"></i> Import</a>
                        <a href="#" class="btn btn-success mr-1"><i class="fa fa-file-excel"></i> Export</a>
                        <button type="button" class="btn btn-primary mr-1" onclick="onAdd()"><i class="fa fa-plus-circle"></i> Tambah</button>
                        <button type="button" class="btn btn-danger"><i class="fa fa-trash"></i> Hapus Banyak</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <?= form_open('quiz/multipledelete', ['id' => 'bulk']) ?>
                        <table class="table table-striped" id="data_quiz">
                            <thead>
                                <tr>
                                    <th width="5%">
                                        <div class="custom-checkbox custom-control">
                                            <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input" id="checkbox-all">
                                            <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                                        </div>
                                    </th>
                                    <th>Quiz</th>
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