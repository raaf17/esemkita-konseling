<?= $this->extend('App\Modules\Layouts\Views\app.php') ?>

<?= $this->section('content') ?>
<div class="d-flex flex-column flex-xl-row">
    <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
        <div class="card mb-5 mb-xl-8">
            <div class="card-body pt-15">
                <div class="d-flex flex-center flex-column mb-5">
                    <div class="symbol symbol-150px symbol-circle mb-7">
                        <form class="form" action="<?= route_to('profile.updateprofilepicture') ?>" method="POST" id="userprofilepicture">
                            <div class="image-input image-input-circle ci-foto-preview" data-kt-image-input="true" style="background-image: url(/assets/media/svg/avatars/blank.svg)">
                                <div class="image-input-wrapper w-125px h-125px ci-foto-preview" style="background-image: url('<?= get_user()->foto == null ? '/img/users/2.jpg' : '/img/users/' . get_user()->foto; ?>')"></div>
                                <label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" data-dismiss="click">
                                    <input type="hidden" name="avatar_remove" />
                                    <a href="javascript:;" onclick="event.preventDefault();document.getElementById('user_profile_file').click();"><i class="ki-duotone ki-pencil fs-7"><span class="path1"></span><span class="path2"></span></i></a>
                                    <input type="file" name="user_profile_file" id="user_profile_file" class="d-none" style="opacity: 0;" />
                                </label>
                                <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" data-dismiss="click" title="Cancel avatar">
                                    <i class="ki-outline ki-cross fs-3"></i>
                                </span>
                                <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" data-dismiss="click">
                                    <i class="ki-outline ki-cross fs-3"></i>
                                </span>
                            </div>
                        </form>
                    </div>
                    <a href="#" class="fs-3 text-gray-800 text-hover-primary fw-bold mb-1">
                        <?= $user->nama ?> </a>
                    <span class="mb-2 badge badge-light-primary"><?= $user->role ?></span>
                </div>
                <div class="separator separator-dashed my-3"></div>
                <div class="pb-5 fs-6">
                    <div class="fw-bold mt-5">Username</div>
                    <div class="text-gray-600"><?= $user->username != null ? $user->username : str_replace(' ', '-', strtolower($user->nama)); ?></div>
                    <div class="fw-bold mt-5">Email</div>
                    <div class="text-gray-600"><a href="#" class="text-gray-600 text-hover-primary"><?= $user->email ?></a></div>
                </div>
            </div>
        </div>
    </div>
    <div class="flex-lg-row-fluid ms-lg-15">
        <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-6 fw-semibold mb-5">
            <li class="nav-item">
                <a class="nav-link text-active-primary pb-1 active" data-bs-toggle="tab" href="#kt_settings_tab">Pengaturan Akun</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary pb-1" data-bs-toggle="tab" href="#kt_change_password_tab">Ubah Password</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade active show" id="kt_settings_tab" role="tabpanel">
                <div class="card mb-5 mb-xl-10">
                    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                        data-bs-target="#kt_account_profile_details" aria-expanded="true"
                        aria-controls="kt_account_profile_details">
                        <div class="card-title m-0">
                            <h3 class="fw-bold m-0">Detail Profil</h3>
                        </div>
                    </div>
                    <div id="kt_account_settings_profile_details" class="collapse show">
                        <div class="card-body border-top p-9">
                            <form class="form" action="<?= route_to('profile.updateprofile') ?>" method="POST" id="detail_profil_form">
                                <?= csrf_field(); ?>
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">Nama Lengkap</label>
                                    <div class="col-lg-8 fv-row">
                                        <input type="text" name="nama" class="form-control form-control-lg form-control" placeholder="Nama Lengkap" value="<?= $user->nama ?>" />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-semibold fs-6">Username</label>
                                    <div class="col-lg-8 fv-row">
                                        <input type="text" name="username" class="form-control form-control-lg form-control" placeholder="Username" value="<?= $user->username != null ? $user->username : str_replace(' ', '-', strtolower($user->nama)); ?>" />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-semibold fs-6">Email</label>
                                    <div class="col-lg-8 fv-row">
                                        <input type="text" name="email" class="form-control form-control-lg form-control" placeholder="Email" value="<?= $user->email ?>" />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-semibold fs-6">Role</label>
                                    <div class="col-lg-8 fv-row">
                                        <input type="text" name="role" class="form-control form-control-lg form-control" placeholder="Role" value="<?= $user->role ?>" disabled />
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-semibold fs-6">Instansi</label>
                                    <div class="col-lg-8 fv-row">
                                        <input type="text" name="setting_nama_sekolah"
                                            class="form-control form-control-lg form-control"
                                            placeholder="Instansi" value="<?= get_settings()->setting_nama_sekolah == null ? 'Sekolah Saya' : get_settings()->setting_nama_sekolah ?>" disabled />
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end py-6">
                                    <button type="reset" class="btn btn-sm btn-light fw-bold btn-active-light-primary me-2">Batal</button>
                                    <button type="submit" class="btn btn-sm fw-bold btn-primary" id="kt_button_submit">
                                        <span class="indicator-label">
                                            Simpan
                                        </span>
                                        <span class="indicator-progress">
                                            Tolong tunggu... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="kt_change_password_tab" role="tabpanel">
                <div class="card mb-5 mb-xl-10">
                    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                        data-bs-target="#kt_account_profile_details" aria-expanded="true"
                        aria-controls="kt_account_profile_details">
                        <div class="card-title m-0">
                            <h3 class="fw-bold m-0">Ubah Password</h3>
                        </div>
                    </div>
                    <div id="kt_account_settings_profile_details" class="collapse show">
                        <div class="card-body border-top p-9">
                            <form class="form" action="<?= route_to('profile.updateprofile') ?>" method="POST" id="change_password_form">
                                <input type="hidden" name="" value="" class="ci_csrf_data">
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-semibold fs-6">Password Sekarang</label>
                                    <div class="col-lg-8 fv-row">
                                        <input type="text" name="password_sekarang" class="form-control form-control-lg form-control" placeholder="Password Sekarang" />
                                        <span class="text-danger error_text password_sekarang_error"></span>
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-semibold fs-6">Password Baru</label>
                                    <div class="col-lg-8 fv-row">
                                        <input type="text" name="password_baru" class="form-control form-control-lg form-control" placeholder="Password Baru" />
                                        <span class="text-danger error_text password_baru_error"></span>
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <label class="col-lg-4 col-form-label fw-semibold fs-6">Konfirmasi Password</label>
                                    <div class="col-lg-8 fv-row">
                                        <input type="text" name="konfirmasi_password" class="form-control form-control-lg form-control" placeholder="Email" />
                                        <span class="text-danger error_text konfirmasi_password_error"></span>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end py-6">
                                    <button type="reset" class="btn btn-sm btn-light fw-bold btn-active-light-primary me-2">Batal</button>
                                    <button type="submit" class="btn btn-sm fw-bold btn-primary" id="kt_button_submit">
                                        <span class="indicator-label">
                                            Simpan
                                        </span>
                                        <span class="indicator-progress">
                                            Tolong tunggu... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?php include('javascript.php') ?>