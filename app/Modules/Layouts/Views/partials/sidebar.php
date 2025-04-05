<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html"><?= isset(get_settings()->setting_nama_sekolah) ? get_settings()->setting_nama_sekolah : 'CODEIGNITER'; ?></a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">WBK</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Main Menu</li>
            <?php foreach (get_main_menu() as $mm) :
                $isMainActive = false;
                foreach (get_sub_menu() as $sm) {
                    if ($sm->main_menu == $mm->kode_menu && current_route_name() == $sm->url) {
                        $isMainActive = true;
                        break;
                    }
                }
            ?>
                <li class="nav-item dropdown <?= $isMainActive ? 'active' : '' ?>">
                    <a href="#" class="nav-link has-dropdown"><i class="<?= $mm->icon ?>"></i><span><?= $mm->nama_menu ?></span></a>
                    <ul class="dropdown-menu">
                        <?php foreach (get_sub_menu() as $sm) :
                            if ($sm->main_menu == $mm->kode_menu) :
                        ?>
                                <li class="<?= current_route_name() == $sm->url ? 'active' : '' ?>"><a class="nav-link" href="<?= route_to($sm->url) ?>"><?= $sm->nama_menu ?></a></li>
                        <?php endif;
                        endforeach; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
            <a href="https://getstisla.com/docs" class="btn btn-info btn-lg btn-block btn-icon-split">
                <i class="fas fa-rocket"></i> Dokumentasi
            </a>
        </div>
    </aside>
</div>