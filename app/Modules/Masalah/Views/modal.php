<div class="modal fade" id="edit-sub-masalah-modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Edit Masalah</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <form action="<?= route_to('masalah.update') ?>" method="POST" id="update-sub-masalah-form">
                <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" class="ci_csrf_data">
                <input type="hidden" name="id">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="nama_sub_masalah">Nama Masalah</label>
                        <input type="text" name="nama_sub_masalah" class="form-control mt-1" placeholder="Nama Masalah">
                        <span class="text-danger error-text nama_sub_masalah_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="id_main_masalah">Kelompok Masalah</label>
                        <select name="id_main_masalah" class="form-select mt-1">
                            <option value="" hidden></option>
                            <?php foreach ($option_main_masalah as $mm) : ?>
                                <option value="<?= $mm->id; ?>" <?= isset($sub_masalah) && $sub_masalah->id_main_masalah == $mm->id ? 'selected' : null ?>><?= $mm->nama_main_masalah; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="text-danger error-text id_main_masalah_error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="edit-main-masalah-modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Edit Masalah</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <form action="<?= route_to('masalah.updatemain') ?>" method="POST" id="update-main-masalah-form">
                <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" class="ci_csrf_data">
                <input type="hidden" name="id">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="nama_main_masalah">Kelompok Masalah</label>
                        <input type="text" name="nama_main_masalah" class="form-control mt-1" placeholder="Nama Masalah">
                        <span class="text-danger error-text nama_main_masalah_error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>