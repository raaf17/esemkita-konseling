<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h4>Pengaturan Umum</h4>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center pb-3 active" data-toggle="tab" href="#umum">Umum</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center pb-3" data-toggle="tab" href="#instansi">Instansi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center pb-3" data-toggle="tab" href="#logo_favicon">Logo & Favicon</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="umum" role="tabpanel">
                    <form class="form" action="<?= route_to('settings.updategeneralsetting') ?>" method="POST" id="general_settings_form">
                        <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" class="ci_csrf_data">
                        <div class="form-group row">
                            <label for="site-title" class="form-control-label col-lg-2 text-md-right">Meta Title :</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="setting_meta_title" value="<?= get_settings()->setting_meta_title; ?>" />
                                <span class="text-danger error_text setting_meta_title_error"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="site-description" class="form-control-label col-lg-2 text-md-right">Meta Description :</label>
                            <div class="col-lg-10">
                                <textarea class="form-control" name="setting_meta_description" cols="30" rows="3"><?= get_settings()->setting_meta_description; ?></textarea>
                                <span class="text-danger error_text setting_meta_description_error"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="form-control-label col-lg-2 text-md-right">Meta Keywords :</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="setting_meta_keyword" value="<?= get_settings()->setting_meta_keyword; ?>" />
                                <span class="text-danger error_text setting_meta_keyword_error"></span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end pt-7">
                            <button type="reset" class="btn btn-secondary mr-1">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>

                <div class="tab-pane fade" id="instansi" role="tabpanel">
                    <form class="form" action="<?= route_to('settings.updateschoolsetting') ?>" method="POST" id="school_settings_form">
                        <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" class="ci_csrf_data">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label class="form-control-label col-lg-3 text-md-right">
                                                <span>Nama Sekolah:</span>
                                            </label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control form-control" name="setting_nama_sekolah" value="<?= get_settings()->setting_nama_sekolah ?>" />
                                                <span class="text-danger error_text setting_nama_sekolah_error"></span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="form-control-label col-lg-3 text-md-right">
                                                <span>Kepala Sekolah :</span>
                                            </label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control form-control" name="setting_kepala_sekolah" value="<?= get_settings()->setting_kepala_sekolah ?>" />
                                                <span class="text-danger error_text setting_kepala_sekolah_error"></span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="form-control-label col-lg-3 text-md-right">
                                                <span>Alamat :</span>
                                            </label>
                                            <div class="col-lg-9">
                                                <textarea class="form-control form-control" name="setting_alamat"><?= get_settings()->setting_alamat ?></textarea>
                                                <span class="text-danger error_text setting_alamat_error"></span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="form-control-label col-lg-3 text-md-right">
                                                <span>Latitude :</span>
                                            </label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control form-control" name="setting_latitude" value="<?= get_settings()->setting_latitude ?>" />
                                                <span class="text-danger error_text setting_latitude_error"></span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="form-control-label col-lg-3 text-md-right">
                                                <span>Longitude :</span>
                                            </label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control form-control" name="setting_longitude" value="<?= get_settings()->setting_longitude ?>" />
                                                <span class="text-danger error_text setting_longitude_error"></span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="form-control-label col-lg-3 text-md-right">
                                                <span>Email :</span>
                                            </label>
                                            <div class="col-lg-9">
                                                <input type="email" class="form-control form-control" name="setting_email" value="<?= get_settings()->setting_email ?>" />
                                                <span class="text-danger error_text setting_email_error"></span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="form-control-label col-lg-3 text-md-right">
                                                <span>Telepon :</span>
                                            </label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control form-control" name="setting_telepon" value="<?= get_settings()->setting_telepon ?>" />
                                                <span class="text-danger error_text setting_telepon_error"></span>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end pt-7">
                                            <button type="reset" class="btn btn-secondary mr-1">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="card">
                                    <div class="card-body">
                                        <div id="map" style="height: 505px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="tab-pane fade" id="logo_favicon" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-6">
                            <form action="<?= route_to('settings.updatelogosetting') ?>" method="POST" enctype="multipart/form-data" id="update_setting_logo">
                                <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" class="ci_csrf_data">
                                <div class="form-group">
                                    <label for="setting_logo">Logo</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="setting_logo" id="setting_logo">
                                        <label class="custom-file-label" for="setting_logo">Choose file</label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end pt-7">
                                    <button type="reset" class="btn btn-secondary mr-1">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-6">
                            <form action="<?= route_to('settings.updatefaviconsetting') ?>" method="POST" enctype="multipart/form-data" id="update_setting_favicon">
                                <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" class="ci_csrf_data">
                                <div class="form-group">
                                    <label for="setting_favicon">Favicon</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="setting_favicon" id="setting_favicon">
                                        <label class="custom-file-label" for="setting_favicon">Choose file</label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end pt-7">
                                    <button type="reset" class="btn btn-secondary mr-1">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>