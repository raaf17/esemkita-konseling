<div class="modal fade" id="edit-materi-modal" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Edit <?= $title ?></h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <form action="<?= route_to('materi.update') ?>" method="POST" id="update-materi-form">
                <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" class="ci_csrf_data">
                <input type="hidden" name="id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="judul_materi">Judul Materi</label>
                        <input type="text" class="form-control mt-2" name="judul_materi">
                        <span class="text-danger error_text judul_materi-error"></span>
                    </div>
                    <div class="form-group mt-3">
                        <label for="isi_materi">Isi Materi</label>
                        <textarea name="isi_materi" id="" cols="30" rows="10" class="form-control"></textarea>
                        <span class="text-danger error_text isi_materi-error"></span>
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

<div class="modal fade" id="import-materi-modal" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="text-gray-800">Import Materi</h3>
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
                    <form action="<?= route_to('materi.import') ?>" method="POST" id="import-materi-form" enctype="multipart/form-data">
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