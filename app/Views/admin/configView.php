<?= $this->extend('layouts/layout') ?>
<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<div class="content-header bg-secondary mb-2">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3 class="m-0">Dashboard Admin</h3>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="col-md-4">
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('pesan')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('pesan') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>
</div>
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    Config Aplikasi
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <form action="<?= base_url("config/save") ?>" method="POST" enctype="multipart/form-data">
                            <?= csrf_field(); ?>
                            <div class="form-group">
                                <label>Nama Aplikasi</label>
                                <input type="text" name="nama_aplikasi" value="<?= $config["nama_aplikasi"] ?>" id="nama_aplikasi" class="form-control">
                            </div>
                            <div class="row form-group">
                                <div class="col-md-3">
                                    <label>No Urut Partai</label>
                                    <input type="text" name="no_urut" value="<?= $config["no_urut_partai"] ?>" id="no_urut" class="form-control">
                                </div>
                                <div class="col-md-9">
                                    <label>Nama Partai</label>
                                    <input type="text" name="nama_partai" value="<?= $config["nama_partai"] ?>" id="nama_partai" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Logo</label>
                            </div>
                            <img src="<?= base_url() ?>/assets/dist/img/<?= $config["logo"] ?>" class="img-thumbnail preview mb-3">
                            <div class="form-group">
                                <input type="hidden" name="logoLama" value="<?= $config['logo'] ?>">
                                <input type="file" name="logo" id="logo" class="form-control">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Sesi Pendaftaran</label>
                                        <select name="sesi_daftar" id="sesi_daftar" class="form-control">
                                            <option value="aktif" <?= ($config['sesi_daftar'] == 'aktif') ? "selected" : ""; ?>>Aktif</option>
                                            <option value="tidak aktif" <?= ($config['sesi_daftar'] == 'tidak aktif') ? "selected" : ""; ?>>Tidak Aktif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Sesi Pemungutan Suara</label>
                                        <select name="sesi_vote" id="sesi_vote" class="form-control">
                                            <option value="aktif" <?= ($config['sesi_vote'] == 'aktif') ? "selected" : ""; ?>>Aktif</option>
                                            <option value="tidak aktif" <?= ($config['sesi_vote'] == 'tidak aktif') ? "selected" : ""; ?>>Tidak Aktif</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection('content') ?>