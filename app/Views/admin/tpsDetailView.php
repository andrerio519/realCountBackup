<?= $this->extend('layouts/layout') ?>
<?= $this->section('content') ?>


<?php
$image = $tps["image"];
$id_orang = $tps['id_orang'];
$no = 1;
?>
<!-- Content Header (Page header) -->
<div class="content-header bg-secondary mb-2">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3 class="m-0">TPS <?= $tps['tps'] ?> Kelurahan <?= $kelurahan['nama_kelurahan'] ?></h3>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
    <div class="container-fluid">
        <div class="col-md-4">
            <?php if (session()->getFlashdata('pesan')) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('pesan') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php elseif (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="content">
    <div class="container-fluid">
        <?php if (session()->get("sesi_vote") == "aktif" || in_array(session()->get("level"), ["master admin", "admin_kecamatan", "admin_kelurahan"])) : ?>
            <div class="card">
                <div class="card-header">
                    <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal">
                        <i class="fas fa-plus">
                        </i>
                        Input Data
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="card-body">
                <div class="row">
                    <div class="card col-12 col-md-12 col-lg-8 order-2 order-md-1">
                        <div class="row mt-3">
                            <?php $totalSuarakota = 0;
                            foreach ($kota as $kt) {
                                $totalSuarakota += $kt['jumlah_suara'];
                            } ?>
                            <div class="col-12 col-sm-3">
                                <div class="info-box bg-dark">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-white">DPT</span>
                                        <span class="info-box-number text-center text-white mb-0"><?= $tps['dpt'] ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-3">
                                <div class="info-box bg-dark">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-white">Total Suara Partai</span>
                                        <span class="info-box-number text-center text-white mb-0"><?= $totalSuarakota + $suarapartaikota['suara_partaikota'] ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-3">
                                <div class="info-box bg-dark">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-white">Coblos Partai</span>
                                        <span class="info-box-number text-center text-white mb-0"><?= $suarapartaikota['suara_partaikota'] ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h4>Perolehan Suara Calon</h4>
                                <div class="post">
                                </div>
                                <div class="row">
                                    <?php $totalSuarakota = 0;
                                    foreach ($kota as $kt) { ?>
                                        <div class="col-md-4 text-center">
                                            <div class="card bg-gradient-gray">
                                                <img src="<?= base_url() ?>assets/calegImg/<?= $kt['image'] ?>" alt="" class="img-circle img-bordered" height="210">
                                            </div>
                                            <div class="info-box bg-light">
                                                <div class="info-box-content">
                                                    <span class="info-box-text text-center text-muted"><?= $kt['no_urutkota'] ?>.<?= $kt['nama_calegkota'] ?></span>
                                                    <span class="info-box-number text-center text-muted mb-0">
                                                        <?= $kt['jumlah_suara'] ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card col-12 col-md-12 col-lg-4 order-1 order-md-2 mb-3">
                        <div class="card-header">
                            Data TPS
                        </div>
                        <br>
                        <div class="text-muted">
                            <p class="text-sm">Nama Saksi
                                <b class="d-block"><?= $tps['nama'] ?></b>
                            </p>
                            <p class="text-sm">Telepon/Whatsapp Saksi
                                <b class="d-block"><?= $tps['nope'] ?></b>
                            </p>
                            <p class="text-sm">Update Terakhir
                                <b class="d-block">
                                    <?= $tps['updated_at'] ?>
                                </b>
                            </p>
                            <p class="text-sm">Status Laporan Saksi
                                <b class="d-block">
                                    <?php if ($tps['dpt'] == 0) {
                                        echo "Saksi Belum Lapor";
                                    } else {
                                        echo "Selesai";
                                    }
                                    ?>
                                </b>
                            </p>
                            <h5 class="text-muted">Formulir C1</h5>
                            <a href="#" data-target="#file" data-toggle="modal" class="btn-link text-white btn btn-danger mb-3"><i class="far fa-fw fa-image "></i> File</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal File Form C1-->
<div class="modal fade" id="file" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">File TPS <?= $tps['tps'] ?> Kelurahan <?= $kelurahan['nama_kelurahan'] ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php if ($image == "") { ?>
                    <h1>Foto C1 Belum Dikirim....</h1>
                <?php } else { ?>
                    <img src="<?= base_url("assets/tps/$image") ?>" class="img-fluid">
                <?php } ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="exampleModalLabel">Input Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="<?= base_url("tps/insert/$id_orang") ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field(); ?>
                    <table class="table table-bordered">
                        <tr>
                            <th>DPT TPS</th>
                            <td><input type="number" name="dpt" class="form-control" value="<?= $tps['dpt'] ?>"></td>
                        </tr>
                    </table>

                    <h4>DPRD Kab/Kota</h4>
                    <table class="table table-bordered">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Suara</th>
                        </tr>
                        <?php
                        if (empty($kota)) : ?>
                            <?php foreach ($pesertakota as $pk) : ?>
                                <tr>
                                    <td><?= $pk['no_urutkota'] ?></td>
                                    <td><?= $pk['nama_calegkota'] ?></td>
                                    <td>
                                        <input type="hidden" name="id_calegkota[]" value="<?= $pk['id_calegkota'] ?>">
                                        <input type="number" class="form-control" name="jumlah_suarakota[]" value="0">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <!-- Jika data pesertari tidak kosong, tampilkan data peserta yang ada -->
                            <?php foreach ($kota as $pk) : ?>
                                <tr>
                                    <td><?= $pk['no_urutkota'] ?></td>
                                    <td><?= $pk['nama_calegkota'] ?></td>
                                    <td>
                                        <input type="hidden" name="id_calegkota[]" value="<?= $pk['id_calegkota'] ?>">
                                        <input type="number" class="form-control" name="jumlah_suarakota[]" value="<?= $pk['jumlah_suara'] ?>">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <tr>
                            <td colspan="2">Coblos Gambar Partai</td>
                            <td><input type="number" class="form-control" name="suara_partaikota" value="<?= $suarapartaikota['suara_partaikota'] ?>"></td>
                        </tr>
                    </table>
                    <div class="form-group">
                        <label for="image">Form C1</label>
                        <img id="image-preview" src="#" alt="Preview" style="max-width: 200px; max-height: 200px; display: none;" class="mb-2" onchange="validateFileSize(this)">
                        <input type="file" class="form-control-file" id="image" name="image" accept="image/*">
                        <input type="hidden" name="imageLama" value="<?= $image ?>">
                        <input type="hidden" name="no_tps" value="<?= $tps["tps"] ?>">
                        <input type="hidden" name="nama_kelurahan" value="<?= $kelurahan['nama_kelurahan'] ?>">
                        <span id="file-error" style="color: red; display: none;">Ukuran file terlalu besar! Maksimum ukuran file yang diizinkan adalah 5 MB</span>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Kirim</button> </form>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<script>
    function validateFileSize(input) {
        var maxSize = 5 * 1024 * 1024; // ukuran maksimum dalam byte
        var fileSize = input.files[0].size;
        if (fileSize > maxSize) {
            document.getElementById("file-error").style.display = "block";
            input.value = '';
        } else {
            document.getElementById("file-error").style.display = "none";
        }
    }
    const inputImage = document.getElementById("image");
    const imagePreview = document.getElementById("image-preview");
    inputImage.addEventListener("change", () => {
        const file = inputImage.files[0];
        if (file) {
            const reader = new FileReader();
            reader.addEventListener("load", () => {
                imagePreview.src = reader.result;
                imagePreview.style.display = "block";
            });
            reader.readAsDataURL(file);
        } else {
            imagePreview.src = "#";
            imagePreview.style.display = "none";
        }
    });
</script>
<?= $this->endSection('content') ?>