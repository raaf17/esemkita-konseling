<div class="modal fade" id="detail-konseling-modal" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Detail Konseling</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                <table class="table table-condensed">
                    <tbody id="konseling-details">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add-konseling-modal" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Buat Konseling</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <form action="<?= route_to('konseling.store') ?>" method="POST" id="add-konseling-form">
                <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" class="ci_csrf_data">
                <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                    <div class="form-group mb-3">
                        <label for="id_guru">Nama Guru</label>
                        <select name="id_guru" class="form-select mt-2">
                            <option value="">Pilih Guru</option>
                            <?php foreach ($guru_option as $guru) : ?>
                                <option value="<?= $guru->id ?>"><?= $guru->nama_guru ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="text-danger error_text id_guru-error"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="id_layanan">Jenis Konseling</label>
                        <select name="id_layanan" class="form-select mt-2">
                            <option value="">Pilih Jenis Konseling</option>
                            <?php foreach ($layanan_option as $layanan) : ?>
                                <option value="<?= $layanan->id ?>"><?= $layanan->nama_layanan ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="text-danger error_text id_layanan-error"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" class="form-control mt-2" name="tanggal" placeholder="Tanggal">
                        <span class="text-danger error_text tanggal-error"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="jam">Jam</label>
                        <input type="time" class="form-control mt-2" name="jam" placeholder="Jam">
                        <span class="text-danger error_text jam-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" id="" cols="30" rows="2" class="form-control mt-2" placeholder="Deskripsi"></textarea>
                        <span class="text-danger error_text deskripsi-error"></span>
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

<div class="modal fade" tabindex="-1" id="filter-tanggal-konseling-modal" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-dismiss="modal" aria-label="Close">
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
                <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-sm btn-primary">Apply</button>
            </div>
        </div>
    </div>
</div>