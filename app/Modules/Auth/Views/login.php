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
                        <?php if (!empty(session()->getFlashdata('message'))) : ?>
                            <div class="alert alert-danger alert-dismissible show fade">
                                <div class="alert-body">
                                    <button class="close" data-dismiss="alert">
                                        <span>&times;</span>
                                    </button>
                                    <span><?= session()->getFlashdata('message') ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                        <form action="<?= route_to('admin.login.handler'); ?>" method="POST">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input id="email" type="email" class="<?= ($validation->hasError('login_id')) ? 'is-invalid' : '' ?> form-control" name="login_id" id="email" value="<?= set_value('login_id'); ?>" placeholder="Username Or Email">
                            </div>

                            <div class="form-group">
                                <div class="d-block">
                                    <label for="password" class="control-label">Password</label>
                                    <div class="float-right">
                                        <a href="<?= route_to('forgot.form') ?>" class="text-small">
                                            Lupa Password?
                                        </a>
                                    </div>
                                </div>
                                <div class="input-group">
                                    <input type="password" class="<?= ($validation->hasError('password')) ? 'is-invalid' : '' ?> form-control" name="password" id="password" value="<?= set_value('password'); ?>" placeholder="Password">
                                    <div class="input-group-append">
                                        <span class="input-group-text toggle-password" style="cursor: pointer;">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="customCheck1" id="customCheck1" class="custom-control-input">
                                    <label class="custom-control-label" for="remember-me">Ingatkan Saya</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                    Login
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
<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        $(".toggle-password").click(function() {
            let passwordField = $("#password");
            let icon = $(this).find("i");

            if (passwordField.attr("type") === "password") {
                passwordField.attr("type", "text");
                icon.removeClass("fa-eye").addClass("fa-eye-slash");
            } else {
                passwordField.attr("type", "password");
                icon.removeClass("fa-eye-slash").addClass("fa-eye");
            }
        });
    });
</script>
<?= $this->endSection(); ?>