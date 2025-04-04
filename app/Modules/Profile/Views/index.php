<?= $this->extend('App\Modules\Layouts\Views\app.php') ?>

<?= $this->section('content') ?>
<div class="section-body">
    <h2 class="section-title">Hi, Ujang!</h2>
    <p class="section-lead">
        Change information about yourself on this page.
    </p>

    <div class="row mt-sm-4">
        <div class="col-12 col-md-12 col-lg-5">
            <div class="card profile-widget">
                <div class="profile-widget-header">
                    <img alt="image" src="<?= get_user()->foto == null ? '/image/users/2.jpg' : '/image/users/' . get_user()->foto; ?>" class="rounded-circle profile-widget-picture">
                </div>
                <div class="profile-widget-description">
                    <div class="profile-widget-name"><?= $user->nama ?> <div class="text-muted d-inline font-weight-normal">
                            <div class="slash"></div> <?= $user->role ?>
                        </div>
                    </div>
                    <?= $user->nama ?> is a superhero name in <b>Indonesia</b>, especially in my family. He is not a fictional character but an original hero in my family, a hero for his children and for his wife. So, I use the name as a user in this template. Not a tribute, I'm just bored with <b>'John Doe'</b>.
                </div>
            </div>
        </div>
        <div class="col-12 col-md-12 col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Profile</h4>
                </div>
                <div class="card-body">
                    <form class="form" action="<?= route_to('profile.updateprofile') ?>" method="POST" id="detail_profil_form">
                        <?= csrf_field(); ?>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label required">Nama Lengkap</label>
                            <div class="col-lg-8">
                                <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" value="<?= $user->nama ?>" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Username</label>
                            <div class="col-lg-8">
                                <input type="text" name="username" class="form-control" placeholder="Username" value="<?= $user->username != null ? $user->username : str_replace(' ', '-', strtolower($user->nama)); ?>" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Email</label>
                            <div class="col-lg-8">
                                <input type="text" name="email" class="form-control" placeholder="Email" value="<?= $user->email ?>" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Role</label>
                            <div class="col-lg-8">
                                <input type="text" name="role" class="form-control" placeholder="Role" value="<?= $user->role ?>" disabled />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Instansi</label>
                            <div class="col-lg-8">
                                <input type="text" name="setting_nama_sekolah"
                                    class="form-control"
                                    placeholder="Instansi" value="<?= get_settings()->setting_nama_sekolah == null ? 'Sekolah Saya' : get_settings()->setting_nama_sekolah ?>" disabled />
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?php include('javascript.php') ?>