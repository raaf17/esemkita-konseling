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
                        <h5 class="font-weight-bolder text-dark" style="margin-bottom: -2px;">Lupa Password</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Kami akan mengirimkan tautan untuk reset password anda.</p>
                        <form class="form">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input id="email" type="email" class="form-control" name="email" value="<?= set_value('email'); ?>" placeholder="Email">
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                    Lupa Password
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