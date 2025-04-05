<?= $this->extend('App\Modules\Layouts\Views\app.php') ?>

<?= $this->section('content') ?>
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Rekapitulasi Data Mutasi Masuk</h4>
                    <div class="card-header-action">
                        <a href="#" class="btn btn-info mr-1"><i class="fa fa-file"></i> Import</a>
                        <a href="#" class="btn btn-success mr-1"><i class="fa fa-file-excel"></i> Export</a>
                        <button type="button" class="btn btn-primary mr-1" onclick="onAdd()"><i class="fa fa-plus-circle"></i> Tambah</button>
                        <button type="button" class="btn btn-danger"><i class="fa fa-trash"></i> Hapus Banyak</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                    <?= form_open('mutasi/multipledelete', ['id' => 'bulk']) ?>
                        <table class="table table-striped" id="data_mutasi">
                            <thead>
                                <tr>
                                    <th width="5%">
                                        <div class="custom-checkbox custom-control">
                                            <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input select_all" id="checkbox-all">
                                            <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                                        </div>
                                    </th>
                                    <th>Tanggal Diterima</th>
                                    <th width="15%">Asal Sekolah</th>
                                    <th>No. Surat</th>
                                    <th>Nama</th>
                                    <th>JK</th>
                                    <th width="15%">Alasan</th>
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