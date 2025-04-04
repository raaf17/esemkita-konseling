<div class="modal fade" id="edit-layanan-modal" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Edit Data <?= $title ?></h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <form action="<?= route_to('layanan.update') ?>" method="POST" id="update-layanan-form">
                <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" class="ci_csrf_data">
                <input type="hidden" name="id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_layanan">Nama Layanan</label>
                        <input type="text" class="form-control mt-2" name="nama_layanan">
                        <span class="text-danger error_text nama_layanan-error"></span>
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

<div class="modal fade" id="import-layanan-modal" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="text-gray-800">Import Layanan</h3>
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
                    <form action="<?= route_to('layanan.import') ?>" method="POST" id="import-layanan-form" enctype="multipart/form-data">
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