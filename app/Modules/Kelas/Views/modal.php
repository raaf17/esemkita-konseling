<div class="modal fade" id="modal_kelas" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST" id="add-kelas-form" autocomplete="off">
                <input type="hidden" name="" value="" class="ci_csrf_data">
                <input type="hidden" name="id">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="nama_kelas">Nama Kelas</label>
                        <input type="text" name="nama_kelas" class="form-control mt-1" placeholder="Nama Kelas">
                    </div>
                    <div class="form-group mb-3">
                        <label for="jurusan">Jurusan</label>
                        <select name="id_jurusan" id="id_jurusan" class="form-control"></select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="nama_guru">Wali Kelas</label>
                        <select name="id_guru" id="id_guru" class="form-control"></select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" onclick="onSave()" id="save">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="import_kelas_modal" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog">
        <div class="card">
            <div class="card-header">
                <h4 class="text-gray-800">Import Kelas</h4>
                <div class="card-header-action">
                    <button class="btn btn-success"><i class="fa fa-file-excel"></i> Template</button>
                </div>
            </div>
            <div class="card-body">
                <form action="" method="POST" id="import-jurusan-form" enctype="multipart/form-data">
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