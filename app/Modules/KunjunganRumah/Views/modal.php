<div class="modal fade" tabindex="-1" id="add-kunjunganrumah-modal" data-backdrop="false">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content" style="width: 800px; left: 50%; transform: translateX(-50%)">
            <div class="modal-header">
                <h3 class="modal-title">Tambah Data kunjunganrumah</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <form action="<?= route_to('kunjunganrumah.store') ?>" method="POST" id="add-kunjunganrumah-form">
                <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" class="ci_csrf_data">
                <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="id_siswa">Nama Siswa</label>
                                        <select name="id_siswa" class="form-select">
                                            <option value="">Pilih Siswa</option>
                                            <?php foreach ($pilihan_siswa as $siswa) : ?>
                                                <option value="<?= $siswa->id ?>"><?= $siswa->nama_siswa ?></option>
                                            <?php endforeach ?>
                                        </select>
                                        <span class="text-danger error_text id_siswa-error"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="id_kelas">Nama Kelas</label>
                                        <select name="id_kelas" id="kelas" class="form-select mt-2 select_option">
                                            <option value="">Pilih Kelas</option>
                                            <?php foreach ($kelas as $kls) : ?>
                                                <option value="<?= $kls->id ?>"><?= $kls->nama_kelas ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <span class="text-danger error_text id_kelas-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="alamat">Alamat</label>
                                        <textarea name="alamat" id="" cols="30" rows="1" class="form-control mt-2" placeholder="Alamat"></textarea>
                                        <span class="text-danger error_text alamat-error"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="id_guru">Guru yang Mengunjungi</label>
                                        <select name="id_guru" id="kelas" class="form-select mt-2 select_option">
                                            <option value="">Pilih Guru</option>
                                            <?php foreach ($guru as $g) : ?>
                                                <option value="<?= $g->id ?>"><?= $g->nama_guru ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <span class="text-danger error_text id_guru-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="tanggal">Tanggal</label>
                                        <input type="date" class="form-control mt-2" name="tanggal" placeholder="Tanggal">
                                        <span class="text-danger error_text tanggal-error"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="jam">Jam</label>
                                        <input type="time" class="form-control mt-2" name="jam" placeholder="Jam">
                                        <span class="text-danger error_text jam-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="anggota_keluarga">Anggota Keluarga</label>
                                        <input type="text" class="form-control mt-2" name="anggota_keluarga" placeholder="Anggota Keluarga">
                                        <span class="text-danger error_text anggota_keluarga-error"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="ringkasan_masalah">Ringkasan Masalah</label>
                                        <textarea name="ringkasan_masalah" id="" cols="30" rows="1" class="form-control mt-2" placeholder="Ringkasan Masalah"></textarea>
                                        <span class="text-danger error_text ringkasan_masalah-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="hasil_kunjungan">Hasil Kunjungan</label>
                                        <textarea name="hasil_kunjungan" id="" cols="30" rows="2" class="form-control mt-2" placeholder="Hasil Kunjungan"></textarea>
                                        <span class="text-danger error_text hasil_kunjungan-error"></span>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="rencana_tindak_lanjut">Rencana Tindak Lanjut</label>
                                        <textarea name="rencana_tindak_lanjut" id="" cols="30" rows="2" class="form-control mt-2" placeholder="Rencana Tindak Lanjut"></textarea>
                                        <span class="text-danger error_text rencana_tindak_lanjut-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="catatan_khusus">Catatan Khusus</label>
                                        <textarea name="catatan_khusus" id="" cols="30" rows="2" class="form-control mt-2" placeholder="Catatan Khusus"></textarea>
                                        <span class="text-danger error_text catatan_khusus-error"></span>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="keterangan">Keterangan</label>
                                        <textarea name="keterangan" id="" cols="30" rows="2" class="form-control mt-2" placeholder="Keterangan"></textarea>
                                        <span class="text-danger error_text keterangan-error"></span>
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

<div class="modal fade" tabindex="-1" id="edit-kunjunganrumah-modal" data-backdrop="false">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content" style="width: 800px; left: 50%; transform: translateX(-50%)">
            <div class="modal-header">
                <h3 class="modal-title">Edit Data Kunjungan Rumah</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <form action="<?= route_to('kunjunganrumah.update') ?>" method="POST" id="update-kunjunganrumah-form">
                <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" class="ci_csrf_data">
                <input type="hidden" name="id">
                <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="id_siswa">Nama Siswa</label>
                                        <select name="id_siswa" class="form-select">
                                            <option value="">Pilih Siswa</option>
                                            <?php foreach ($pilihan_siswa as $siswa) : ?>
                                                <option value="<?= $siswa->id ?>"><?= $siswa->nama_siswa ?></option>
                                            <?php endforeach ?>
                                        </select>
                                        <span class="text-danger error_text id_siswa-error"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="id_kelas">Nama Kelas</label>
                                        <select name="id_kelas" id="kelas" class="form-select mt-2 select_option">
                                            <option value="">Pilih Kelas</option>
                                            <?php foreach ($kelas as $kls) : ?>
                                                <option value="<?= $kls->id ?>"><?= $kls->nama_kelas ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <span class="text-danger error_text id_kelas-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="alamat">Alamat</label>
                                        <textarea name="alamat" id="" cols="30" rows="1" class="form-control mt-2" placeholder="Alamat"></textarea>
                                        <span class="text-danger error_text alamat-error"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="id_guru">Guru yang Mengunjungi</label>
                                        <select name="id_guru" class="form-select mt-2">
                                            <option value="">Pilih Guru</option>
                                            <?php foreach ($guru as $g) : ?>
                                                <option value="<?= $g->id ?>"><?= $g->nama_guru ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <span class="text-danger error_text id_guru-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="tanggal">Tanggal</label>
                                        <input type="date" class="form-control mt-2" name="tanggal" placeholder="Tanggal">
                                        <span class="text-danger error_text tanggal-error"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="jam">Jam</label>
                                        <input type="time" class="form-control mt-2" name="jam" placeholder="Jam">
                                        <span class="text-danger error_text jam-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="anggota_keluarga">Anggota Keluarga</label>
                                        <input type="text" class="form-control mt-2" name="anggota_keluarga" placeholder="Anggota Keluarga">
                                        <span class="text-danger error_text anggota_keluarga-error"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="ringkasan_masalah">Ringkasan Masalah</label>
                                        <textarea name="ringkasan_masalah" id="" cols="30" rows="1" class="form-control mt-2" placeholder="Ringkasan Masalah"></textarea>
                                        <span class="text-danger error_text ringkasan_masalah-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="hasil_kunjungan">Hasil Kunjungan</label>
                                        <textarea name="hasil_kunjungan" id="" cols="30" rows="2" class="form-control mt-2" placeholder="Hasil Kunjungan"></textarea>
                                        <span class="text-danger error_text hasil_kunjungan-error"></span>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="rencana_tindak_lanjut">Rencana Tindak Lanjut</label>
                                        <textarea name="rencana_tindak_lanjut" id="" cols="30" rows="2" class="form-control mt-2" placeholder="Rencana Tindak Lanjut"></textarea>
                                        <span class="text-danger error_text rencana_tindak_lanjut-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="catatan_khusus">Catatan Khusus</label>
                                        <textarea name="catatan_khusus" id="" cols="30" rows="2" class="form-control mt-2" placeholder="Catatan Khusus"></textarea>
                                        <span class="text-danger error_text catatan_khusus-error"></span>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="keterangan">Keterangan</label>
                                        <textarea name="keterangan" id="" cols="30" rows="2" class="form-control mt-2" placeholder="Keterangan"></textarea>
                                        <span class="text-danger error_text keterangan-error"></span>
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

<div class="modal fade" id="detail-kunjunganrumah-modal" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Detail Kunjungan Rumah</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                <table class="table table-condensed">
                    <tbody id="kunjunganrumah-details">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="filter-tanggal-kunjungan-rumah-modal" data-backdrop="false">
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