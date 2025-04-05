<?= $this->extend('App\Modules\Layouts\Views\app.php') ?>

<?= $this->section('content') ?>
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Data Pengguna Login</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <?= form_open('loginactivity/multipledelete', ['id' => 'bulk']) ?>
                        <table class="table table-striped" id="data_log">
                            <thead>
                                <tr>
                                    <th width="5%">
                                        <div class="custom-checkbox custom-control">
                                            <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input select_all" id="checkbox-all">
                                            <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                                        </div>
                                    </th>
                                    <th>Nama Pengguna</th>
                                    <th width="15%">Action</th>
                                    <th>IP Address</th>
                                    <th>Browser</th>
                                    <th>Perangkat</th>
                                    <th>Created at</th>
                                    <th width="4%">Aksi</th>
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
<?= $this->endSection() ?>
<?php include('javascript.php') ?>