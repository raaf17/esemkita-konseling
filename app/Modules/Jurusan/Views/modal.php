<div class="modal fade" id="import_jurusan_modal" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog">
        <div class="card">
            <div class="card-header">
                <h4 class="text-gray-800">Import Jurusan</h4>
                <div class="card-header-action">
                    <button class="btn btn-success"><i class="fa fa-file-excel"></i> Template</button>
                </div>
            </div>
            <div class="card-body">
                <form action="<?= route_to('jurusan.import') ?>" method="POST" id="import-jurusan-form" enctype="multipart/form-data">
                    <input type="hidden" name="" value="" class="ci_csrf_data">
                    <div class="form-group mb-5">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="file">
                            <label class="custom-file-label" for="setting_logo">Pilih file</label>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end pt-7">
                        <button type="reset" class="btn btn-secondary mr-1" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" onclick="onSave()" id="save">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>