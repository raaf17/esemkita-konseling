<?= $this->extend('App\Modules\Layouts\Views\auth.php') ?>

<?= $this->section('content'); ?>
<section class="section">
    <div class="container d-flex flex-row-fluid flex-column justify-content-center" style="min-height: 100vh;">
        <div class="d-flex justify-content-center">
            <div class="card card-primary d-flex flex-column bg-primary" style="width: 400px; background-image: url('<?= base_url() ?>/image/counseling.jpg'); background-size: cover; background-position: center; border-top-right-radius: 0; border-bottom-right-radius: 0;">
                <div class="card-body">
                    <div class="d-flex flex-row-fluid flex-column justify-content-between p-10">
                        <h4 class="text-light text-right">Website Bimbingan Konseling<br /><b><?= isset(get_settings()->setting_nama_sekolah) ? get_settings()->setting_nama_sekolah : ''; ?></b></h4>
                    </div>
                </div>
            </div>
            <div class="card card-primary d-flex flex-column" style="width: 400px; border-top-left-radius: 0; border-bottom-left-radius: 0;">
                <div class="d-flex flex-row-fluid flex-column justify-content-between p-10">
                    <div class="card-header d-flex justify-content-center align-items-center">
                        <h5 class="font-weight-bolder text-dark" style="margin-bottom: -2px;">Masuk Akun Anda</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty(session()->getFlashdata('success'))) : ?>
                            <div class="alert alert-success alert-dismissible show fade">
                                <div class="alert-body">
                                    <button class="close" data-dismiss="alert">
                                        <span>&times;</span>
                                    </button>
                                    <span><?= session()->getFlashdata('success') ?></span>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty(session()->getFlashdata('fail'))) : ?>
                            <div class="alert alert-danger alert-dismissible show fade">
                                <div class="alert-body">
                                    <button class="close" data-dismiss="alert">
                                        <span>&times;</span>
                                    </button>
                                    <span><?= session()->getFlashdata('fail') ?></span>
                                </div>
                            </div>
                        <?php endif; ?>

                        <p class="text-muted">We will send a link to reset your password</p>
                        <form action="<?= route_to('reset-password-handler', $token); ?>" method="POST">
                            <?= csrf_field(); ?>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input id="email" type="email" class="form-control" name="email">
                            </div>

                            <div class="form-group">
                                <label for="password">Password Baru</label>
                                <input id="password" type="password" class="form-control pwstrength" placeholder="New Password" name="new_password" value="<?= set_value('reset_password'); ?>">
                                <?php if ($validation->getError('new_password')) : ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('new_password'); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="password-confirm">Konfirmasi Password</label>
                                <input id="password-confirm" type="password" class="form-control" placeholder="Confirm New Password" name="confirm_new_password" value="<?= set_value('confirm_new_password'); ?>">
                                <?php if ($validation->getError('confirm_new_password')) : ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('confirm_new_password'); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                    Reset Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection(); ?>