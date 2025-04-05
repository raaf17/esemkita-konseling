<?= $this->extend('App\Modules\Layouts\Views\app.php') ?>

<?= $this->section('content') ?>
<div class="section-body">
    <div class="row">
        <div class="col-lg-5">
            <div class="card card-primary">
                <div class="card-header">
                    <h4>Tambah Pengguna</h4>
                </div>
                <div class="card-body">
                    <form action="<?= route_to('users.store') ?>" method="POST" id="add-users-form" autocomplete="off">
                        <input type="hidden" name="" value="" class="ci_csrf_data">
                        <div class="form-group">
                            <label for="nama">Nama Pengguna</label>
                            <input type="text" name="nama" class="form-control mt-1" placeholder="Nama Pengguna">
                            <span class="text-danger error-text nama_error"></span>
                        </div>
                        <div class="form-group mt-3">
                            <label for="username">Username</label>
                            <input type="text" name="username" class="form-control mt-1" placeholder="Username">
                            <span class="text-danger error-text username_error"></span>
                        </div>
                        <div class="form-group mt-3">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control mt-1" placeholder="Email">
                            <span class="text-danger error-text email_error"></span>
                        </div>
                        <div class="form-group mt-3">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control mt-1" placeholder="Password">
                            <span class="text-danger error-text password_error"></span>
                        </div>
                        <div class="form-group mt-3">
                            <label for="role">Role</label>
                            <select name="role" id="role" class="form-control"></select>
                            <span class="text-danger error-text role_error"></span>
                        </div>
                        <div class="d-flex justify-content-end pt-7">
                            <button type="reset" class="btn btn-secondary mr-1">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card card-primary">
                <div class="card-header">
                    <h4>Data Pengguna</h4>
                    <div class="card-header-action">
                        <a href="#" class="btn btn-info mr-1"><i class="fa fa-file"></i> Import</a>
                        <a href="#" class="btn btn-success mr-1"><i class="fa fa-file-excel"></i> Export</a>
                        <button type="button" class="btn btn-danger"><i class="fa fa-trash"></i> Hapus Banyak</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <?= form_open('users/multipledelete', ['id' => 'bulk']) ?>
                        <table class="table table-striped" id="data_users">
                            <thead>
                                <tr>
                                    <th width="5%">
                                        <div class="custom-checkbox custom-control">
                                            <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad" class="custom-control-input select_all" id="checkbox-all">
                                            <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                                        </div>
                                    </th>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
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