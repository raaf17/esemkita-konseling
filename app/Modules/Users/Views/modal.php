<div class="modal fade" id="edit-users-modal" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Edit Data Users</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= route_to('users.update') ?>" method="POST" id="update-users-form">
                <input type="hidden" name="" value="" class="ci_csrf_data">
                <input type="hidden" name="id">
                <div class="modal-body">
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
                        <select name="role" id="role" class="form-select">
                            <?php foreach ($roles as $role) : ?>
                                <option value="<?= $role->role ?>"><?= $role->role ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="text-danger error-text role_error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="import-users-modal" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="text-gray-800">Import Pengguna</h3>
                    </div>
                    <div class="card-toolbar">
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-sm btn-light-success">
                                <i class="ki-duotone ki-exit-down"><span class="path1"></span><span class="path2"></span></i> Template
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="<?= route_to('users.import') ?>" method="POST" id="import-users-form" enctype="multipart/form-data">
                        <input type="hidden" name="" value="" class="ci_csrf_data">
                        <div class="form-group mb-5">
                            <label for="file">Pilih File</label>
                            <input type="file" class="form-control mt-2" name="file">
                            <span class="text-danger error_text file-error"></span>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-sm btn-light me-2" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>