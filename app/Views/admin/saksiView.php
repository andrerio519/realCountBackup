<!DOCTYPE html>
<html lang="en">

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url() ?>/assets/dist/css/adminlte.min.css">
    <!-- jQuery -->
    <script src="<?= base_url() ?>/assets/dist/jquery/jquery-3.4.1.min.js"></script>


</head>

<body>

    <div class="wrapper">

        <?php

        $no = 1;
        $image = $tps["image"];
        $id_orang = $tps['id_orang'];
        ?>
        <!-- Content Header (Page header) -->
        <div class="content-header bg-secondary mb-2">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h3 class="m-0">Saksi TPS <?= $tps['tps'] ?> Kelurahan <?= $kelurahan['nama_kelurahan'] ?></h3>
                    </div><!-- /.col -->
                    <div class="col-sm-6 ml-auto text-right">
                        <a class="btn btn-danger btn-sm " href="<?= base_url() ?>/logout" onclick="return confirm('yakin ingin logout?')">
                            <i class="fas fa-sign-out-alt"></i>
                            Keluar
                        </a>
                    </div>
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
                <div class="card">
                    <div class="card-header">
                        <?php if (session()->get("sesi_vote") == "aktif") : ?>
                            <div class="row">
                                <div class="col-md-8">
                                    <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal">
                                        <i class="fas fa-edit"></i>
                                        Input Data
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                DPRD Kabupaten / Kota
                            </div>
                            <div class="card-body">
                                <table class=" table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Caleg</th>
                                            <th>Jumlah Suara</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $totalSuarakota = 0;
                                        foreach ($kota as $kt) {
                                            $totalSuarakota += $kt['jumlah_suara']; ?>
                                            <tr>
                                                <td><?= $kt['no_urutkota'] ?></td>
                                                <td><?= $kt['nama_calegkota'] ?></td>
                                                <td><?= $kt['jumlah_suara'] ?></td>
                                            </tr>
                                        <?php } ?>
                                        <tr>
                                            <td colspan="2">Coblos Partai</td>
                                            <td>
                                                <?= $suarapartaikota['suara_partaikota'] ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">Total Partai</td>
                                            <td>
                                                <?= $totalSuarakota + $suarapartaikota['suara_partaikota'] ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <?php if ($image == "") { ?>
                                    <h3>No File</h3>
                                <?php } else { ?>
                                    <img src="<?= base_url("assets/tps/$image") ?>" alt="" class=" card-img">
                                <?php } ?>
                            </div>
                        </div>
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
                                    <!-- Jika data pesertari tidak kosong, tampilkan data pesertari yang ada -->
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
                                <img id="image-preview" src="#" alt="Preview" style="max-width: 200px; max-height: 200px; display: none;" class="mb-2">
                                <input type="file" class="form-control-file" id="image" name="image" accept="image/*">
                                <input type="hidden" name="imageLama" value="<?= $image ?>">
                                <input type="hidden" name="no_tps" value="<?= $tps["tps"] ?>">
                                <input type="hidden" name="nama_kelurahan" value="<?= $kelurahan['nama_kelurahan'] ?>">
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Kirim</button> </form>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- REQUIRED SCRIPTS -->

        <!-- Bootstrap 4 -->
        <script src="<?= base_url() ?>/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- AdminLTE App -->
        <script src="<?= base_url() ?>/assets/dist/js/adminlte.min.js"></script>
        <script>
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


</body>

</html>