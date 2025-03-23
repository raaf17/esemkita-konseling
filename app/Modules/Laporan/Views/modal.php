<div class="modal fade" id="add-laporan-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Buat Laporan</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <form action="<?= route_to('laporan.store') ?>" method="POST" id="add-laporan-form" enctype="multipart/form-data">
                <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" class="ci_csrf_data">
                <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                    <div class="form-group mb-3">
                        <label for="nama_terlapor">Nama Terlapor</label>
                        <input type="text" class="form-control mt-2" name="nama_terlapor" placeholder="Nama Terlapor">
                        <span class="text-danger error_text nama_terlapor-error"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="judul">Pelanggaran</label>
                        <input type="text" class="form-control mt-2" name="judul" placeholder="Pelanggaran">
                        <span class="text-danger error_text judul-error"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" class="form-control mt-2" name="tanggal" placeholder="Tanggal">
                        <span class="text-danger error_text tanggal-error"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="lokasi">Lokasi</label>
                        <input type="text" class="form-control mt-2" name="lokasi" placeholder="Lokasi">
                        <span class="text-danger error_text lokasi-error"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" id="" cols="30" rows="2" class="form-control mt-2" placeholder="Deskripsi"></textarea>
                        <span class="text-danger error_text deskripsi-error"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="foto">Foto Bukti</label>
                        <input type="file" class="form-control mt-2" name="foto" placeholder="Foto">
                        <span class="text-danger error_text foto-error"></span>
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

<div class="modal fade" id="detail-laporan-modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Detail Laporan</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body">
                <table class="table table-condensed">
                    <tbody id="laporan-details">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="filter-tanggal-laporan-modal">
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