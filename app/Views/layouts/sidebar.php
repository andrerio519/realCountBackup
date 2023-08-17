<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="<?= base_url() ?>assets/dist/img/<?= getConfig()['logo'] ?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light"><?= getConfig()['nama_aplikasi'] ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= base_url() ?>assets/dist/img/admin.PNG" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?= strtoupper(session()->get('level')); ?></a>
            </div>
        </div>



        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="<?= base_url() ?>/admin" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <?php if (session()->get('level') == "master admin") : ?>
                    <li class="nav-item">
                        <a href="" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>
                                Data Wilayah
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url("kecamatan") ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Kecamatan</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>
                                Super Admin
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url("users") ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Admin List</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url("caleg") ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Caleg Peserta</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url("data") ?>" class="nav-link" onclick="return confirm('Load seluruh data akan memakan waktu.Lanjutkan?')">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Data </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url("config") ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Config</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php $id_kecamatan = session()->get('id_kecamatan');
                if (session()->get('level') == "admin_kecamatan") : ?>
                    <li class="nav-item">
                        <a href="<?= base_url("kecamatan/detail/$id_kecamatan") ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Kelurahan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url("users") ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Admin List</p>
                        </a>
                    </li>
                <?php endif; ?>
                <?php $id_kelurahan = session()->get('id_kelurahan');
                if (session()->get('level') == "admin_kelurahan") : ?>
                    <li class="nav-item">
                        <a href="<?= base_url("kelurahan/detail/$id_kelurahan") ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>TPS </p>
                        </a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a href="<?= base_url() ?>logout" onclick="return confirm('yakin ingin logout?')" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>