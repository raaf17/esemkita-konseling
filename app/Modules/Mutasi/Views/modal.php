<div class="modal fade" id="add-mutasi-modal" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Tambah Data Mutasi</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <form action="<?= route_to('mutasi.store') ?>" method="POST" id="add-mutasi-form">
                <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" class="ci_csrf_data">
                <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                    <div class="form-group mb-3">
                        <label for="tanggal_diterima">Tanggal Diterima</label>
                        <input type="date" class="form-control mt-2" name="tanggal_diterima">
                        <span class="text-danger error_text tanggal_diterima-error"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="asal_sekolah">Asal Sekolah</label>
                        <input type="text" class="form-control mt-2" name="asal_sekolah" placeholder="Asal Sekolah">
                        <span class="text-danger error_text asal_sekolah-error"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="no_surat">No. Surat</label>
                        <input type="text" class="form-control mt-2" name="no_surat" placeholder="No. Surat">
                        <span class="text-danger error_text no_surat-error"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="id_siswa">Nama Siswa</label>
                        <select name="id_siswa" class="form-select">
                            <option value="">Pilih Siswa</option>
                            <?php foreach ($pilihan_siswa as $siswa) : ?>
                                <option value="<?= $siswa->id ?>"><?= $siswa->nisn ?> - <?= $siswa->nama_siswa ?></option>
                            <?php endforeach ?>
                        </select>
                        <span class="text-danger error_text id_siswa-error"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="jenis_kelamin">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                        <span class="text-danger error_text jenis_kelamin-error"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="alasan">Alasan</label>
                        <textarea name="alasan" cols="30" rows="5" class="form-control mt-2" placeholder="Alasan"></textarea>
                        <span class="text-danger error_text alasan-error"></span>
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

<div class="modal fade" id="edit-mutasi-modal" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Edit Data Mutasi</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <form action="<?= route_to('mutasi.update') ?>" method="POST" id="update-mutasi-form">
                <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" class="ci_csrf_data">
                <input type="hidden" name="id">
                <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                    <div class="form-group mb-3">
                        <label for="tanggal_diterima">Tanggal Diterima</label>
                        <input type="date" class="form-control mt-2" name="tanggal_diterima">
                        <span class="text-danger error_text tanggal_diterima-error"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="asal_sekolah">Asal Sekolah</label>
                        <input type="text" class="form-control mt-2" name="asal_sekolah">
                        <span class="text-danger error_text asal_sekolah-error"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="no_surat">No. Surat</label>
                        <input type="text" class="form-control mt-2" name="no_surat">
                        <span class="text-danger error_text no_surat-error"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="id_siswa">Nama Siswa</label>
                        <select name="id_siswa" class="form-select">
                            <?php foreach ($pilihan_siswa as $siswa) : ?>
                                <option value="<?= $siswa->id; ?>" <?= isset($mutasi) && $mutasi->id_siswa == $siswa->id ? 'selected' : null ?>><?= $siswa->nisn ?> - <?= $siswa->nama_siswa; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="text-danger error_text nama_siswa-error"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="jenis_kelamin">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                        <span class="text-danger error_text jenis_kelamin-error"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="alasan">Alasan</label>
                        <textarea name="alasan" cols="30" rows="5" class="form-control mt-2"></textarea>
                        <span class="text-danger error_text alasan-error"></span>
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

<div class="modal fade" id="import-mutasi-modal" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="text-gray-800">Import mutasi</h3>
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
                    <form action="<?= route_to('mutasi.import') ?>" method="POST" id="import-mutasi-form" enctype="multipart/form-data">
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

<div class="modal fade" tabindex="-1" id="filter-tanggal-mutasi-modal" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <label for="tanggal_awal" class="form-label">From</label>
                        <div class="input-group log-event" id="tanggal_awal">
                            <input id="tanggal_awal" type="text" class="form-control" />
                            <span class="input-group-text" data-td-toggle="datetimepicker">
                                <i class="ki-duotone ki-calendar fs-2"><span class="path1"></span><span class="path2"></span></i>
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label for="tanggal_akhir" class="form-label">To</label>
                        <div class="input-group log-event" id="tanggal_akhir">
                            <input id="tanggal_akhir" type="text" class="form-control" />
                            <span class="input-group-text" data-td-toggle="datetimepicker">
                                <i class="ki-duotone ki-calendar fs-2"><span class="path1"></span><span class="path2"></span></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-sm btn-primary">Apply</button>
            </div>
        </div>
    </div>
</div>