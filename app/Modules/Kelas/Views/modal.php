<div class="modal fade" id="edit-kelas-modal" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Edit Data kelas</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <form action="<?= route_to('kelas.update') ?>" method="POST" id="update-kelas-form">
                <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" class="ci_csrf_data">
                <input type="hidden" name="id">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="nama_kelas">Nama Kelas</label>
                        <input type="text" name="nama_kelas" class="form-control mt-1" placeholder="Nama Kelas">
                        <span class="text-danger error-text nama_kelas_error"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="jurusan">Jurusan</label>
                        <select name="id_jurusan" id="" class="form-select">
                            <option value="" hidden></option>
                            <?php foreach ($choice_of_major as $major) : ?>
                                <option value="<?= $major->id; ?>" <?= $kelas->id_jurusan == $major->id ? 'selected' : null ?>><?= $major->nama_jurusan; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="text-danger error-text id_jurusan_error"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="nama_guru">Wali Kelas</label>
                        <select name="id_guru" id="" class="form-select">
                            <option value="">Pilih Wali Kelas</option>
                            <?php foreach ($choice_of_teacher as $teacher) : ?>
                                <option value="<?= $teacher->id; ?>" <?= $kelas->id_guru == $teacher->id ? 'selected' : null ?>><?= $teacher->nama_guru; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="text-danger error-text id_guru_error"></span>
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

<div class="modal fade" id="import-kelas-modal" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="text-gray-800">Import Kelas</h3>
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
                    <form action="<?= route_to('kelas.import') ?>" method="POST" id="import-kelas-form" enctype="multipart/form-data">
                        <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" class="ci_csrf_data">
                        <div class="form-group mb-5">
                            <label for="file">Pilih File</label>
                            <input type="file" class="form-control mt-2" name="file">
                            <span class="text-danger error_text file-error"></span>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-sm btn-light me-2" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>