<div class="modal fade" tabindex="-1" id="add-siswa-modal" data-backdrop="false">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content" style="width: 800px; left: 50%; transform: translateX(-50%)">
            <div class="modal-header">
                <h3 class="modal-title">Tambah Data Siswa</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <form action="<?= route_to('siswa.store') ?>" method="POST" id="add-siswa-form">
                <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" class="ci_csrf_data">
                <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="nisn">NISN</label>
                                        <input type="number" class="form-control mt-2" name="nisn" placeholder="NISN">
                                        <span class="text-danger error_text nisn-error"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="nama_siswa">Nama Siswa</label>
                                        <input type="text" class="form-control mt-2" name="nama_siswa" placeholder="Nama Siswa">
                                        <span class="text-danger error_text nama_siswa-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="id_kelas">Nama Kelas</label>
                                        <select name="id_kelas" id="kelas" class="form-select mt-2">
                                            <option value="">Pilih Kelas</option>
                                            <?php foreach ($kelas as $kls) : ?>
                                                <option value="<?= $kls->id ?>"><?= $kls->nama_kelas ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <span class="text-danger error_text id_kelas-error"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="jenis_kelamin">Jenis Kelamin</label>
                                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-select mt-2">
                                            <option value="">Jenis Kelamin</option>
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                        <span class="text-danger error_text jenis_kelamin-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="tempat_lahir">Tempat Lahir</label>
                                        <input type="text" class="form-control mt-2" name="tempat_lahir" placeholder="Tempat Lahir">
                                        <span class="text-danger error_text tempat_lahir-error"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="tanggal_lahir">Tanggal Lahir</label>
                                        <input type="date" class="form-control mt-2" name="tanggal_lahir" placeholder="Tanggal Lahir">
                                        <span class="text-danger error_text tanggal_lahir-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="alamat">Alamat</label>
                                        <textarea name="alamat" id="" cols="30" rows="2" class="form-control mt-2"></textarea>
                                        <span class="text-danger error_text alamat-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="agama" id="agama">Agama</label>
                                        <select name="agama" class="form-select mt-2">
                                            <option value="">Agama</option>
                                            <option value="Islam">Islam</option>
                                            <option value="Kristen">Kristen</option>
                                            <option value="Katolik">Katolik</option>
                                            <option value="Hindu">Hindu</option>
                                            <option value="Budha">Budha</option>
                                            <option value="Konghucu">Konghucu</option>
                                        </select>
                                        <span class="text-danger error_text agama-error"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="status_keluarga" id="status_keluarga">Status Keluarga</label>
                                        <select name="status_keluarga" class="form-select mt-2">
                                            <option value="">Status Keluarga</option>
                                            <option value="Kandung">Kandung</option>
                                            <option value="Tiri">Tiri</option>
                                            <option value="Angkat">Angkat</option>
                                        </select>
                                        <span class="text-danger error_text status_keluarga-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="nama_ayah">Nama Ayah</label>
                                        <input type="text" class="form-control mt-2" name="nama_ayah" placeholder="Nama Ayah">
                                        <span class="text-danger error_text nama_ayah-error"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="nama_ibu">Nama Ibu</label>
                                        <input type="text" class="form-control mt-2" name="nama_ibu" placeholder="Nama Ibu">
                                        <span class="text-danger error_text nama_ibu-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="no_handphone">No. Handphone</label>
                                        <input type="number" class="form-control mt-2" name="no_handphone" placeholder="No. Handphone">
                                        <span class="text-danger error_text no_handphone-error"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
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

<div class="modal fade" tabindex="-1" id="edit-siswa-modal" data-backdrop="false">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content" style="width: 800px; left: 50%; transform: translateX(-50%)">
            <div class="modal-header">
                <h3 class="modal-title">Edit Data Siswa</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <form action="<?= route_to('siswa.update') ?>" method="POST" id="update-siswa-form">
                <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" class="ci_csrf_data">
                <input type="hidden" name="id">
                <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="nisn">NISN</label>
                                        <input type="number" class="form-control mt-2" name="nisn" placeholder="NISN">
                                        <span class="text-danger error_text nisn-error"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="nama_siswa">Nama Siswa</label>
                                        <input type="text" class="form-control mt-2" name="nama_siswa" placeholder="Nama Siswa">
                                        <span class="text-danger error_text nama_siswa-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="id_kelas">Nama Kelas</label>
                                        <select name="id_kelas" class="form-select mt-2">
                                            <option value="">Kelas</option>
                                            <?php foreach ($kelas as $kls) : ?>
                                                <option value="<?= $kls->id ?>"><?= $kls->nama_kelas ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <span class="text-danger error_text id_kelas-error"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="jenis_kelamin">Jenis Kelamin</label>
                                        <select name="jenis_kelamin" class="form-select mt-2">
                                            <option value="">Jenis Kelamin</option>
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                        <span class="text-danger error_text jenis_kelamin-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="tempat_lahir">Tempat Lahir</label>
                                        <input type="text" class="form-control mt-2" name="tempat_lahir" placeholder="Tempat Lahir">
                                        <span class="text-danger error_text tempat_lahir-error"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="tanggal_lahir">Tanggal Lahir</label>
                                        <input type="date" class="form-control mt-2" name="tanggal_lahir" placeholder="Tanggal Lahir">
                                        <span class="text-danger error_text tanggal_lahir-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="alamat">Alamat</label>
                                        <textarea name="alamat" id="" cols="30" rows="2" class="form-control mt-2"></textarea>
                                        <span class="text-danger error_text alamat-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="agama">Agama</label>
                                        <select name="agama" class="form-select mt-2">
                                            <option value="">Agama</option>
                                            <option value="Islam">Islam</option>
                                            <option value="Kristen">Kristen</option>
                                            <option value="Katolik">Katolik</option>
                                            <option value="Hindu">Hindu</option>
                                            <option value="Budha">Budha</option>
                                            <option value="Konghucu">Konghucu</option>
                                        </select>
                                        <span class="text-danger error_text agama-error"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="status_keluarga">Status Keluarga</label>
                                        <select name="status_keluarga" class="form-select mt-2">
                                            <option value="">Status Keluarga</option>
                                            <option value="Kandung">Kandung</option>
                                            <option value="Tiri">Tiri</option>
                                            <option value="Angkat">Angkat</option>
                                        </select>
                                        <span class="text-danger error_text status_keluarga-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="nama_ayah">Nama Ayah</label>
                                        <input type="text" class="form-control mt-2" name="nama_ayah" placeholder="Nama Ayah">
                                        <span class="text-danger error_text nama_ayah-error"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="nama_ibu">Nama Ibu</label>
                                        <input type="text" class="form-control mt-2" name="nama_ibu" placeholder="Nama Ibu">
                                        <span class="text-danger error_text nama_ibu-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="no_handphone">No. Handphone</label>
                                        <input type="number" class="form-control mt-2" name="no_handphone" placeholder="No. Handphone">
                                        <span class="text-danger error_text no_handphone-error"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
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

<div class="modal fade" id="detail-siswa-modal" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Detail Siswa</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                <table class="table table-condensed">
                    <tbody id="siswa-details">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="import-siswa-modal" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="text-gray-800">Import Siswa</h3>
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
                    <form action="<?= route_to('siswa.import') ?>" method="POST" id="import-siswa-form" enctype="multipart/form-data">
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