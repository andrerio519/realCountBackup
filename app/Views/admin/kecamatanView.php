<?= $this->extend('layouts/layout') ?>
<?= $this->section('content') ?>

<?php

$nama_calegkota = [];
$jumlah_suarakota = [];
foreach ($kota as $rowkota) {
    $nama_calegkota[] = $rowkota['nama_calegkota'];
    $jumlah_suarakota[] = $rowkota['jumlah_suara'];
}
$rowkota['nama_calegkota'] = $nama_calegkota;
$rowkota['jumlah_suara'] = $jumlah_suarakota;


$no = 1;
?>
<!-- Content Header (Page header) -->
<div class="content-header bg-secondary mb-2">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3 class="m-0">Kecamatan</h3>
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
            <?php elseif (session()->getFlashdata('gagal')) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('gagal') ?>
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
        <div class="col-md-12 col-sm-12">
            <div class="card card-primary card-tabs">
                <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Wilayah</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Perolehan Suara</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                            <div class="card-header">
                                <?php if (session()->get("sesi_daftar") == "aktif") : ?>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <a class="btn btn-primary btn-sm " data-toggle="modal" data-target="#kotaModal">
                                                <i class="fas fa-plus">
                                                </i>
                                                Tambah Kecamatan
                                            </a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kecamatan</th>
                                            <th>Jumlah Saksi</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($kecamatan as $kcmtn) : ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $kcmtn['nama_kecamatan'] ?></td>
                                                <td><?php
                                                    $id_kecamatan = $kcmtn['id_kecamatan'];
                                                    $result = $orang->where("id_kecamatan", $id_kecamatan)->where("level", 'saksi')->countAllResults();
                                                    echo $result;
                                                    ?>
                                                </td>
                                                <td>
                                                    <a href='<?= base_url() ?>/kecamatan/detail/<?= $kcmtn['id_kecamatan'] ?>' class='btn btn-success btn-xs'><i class='fas fa-eye'></i> Detail </a>
                                                    <?php if (session()->get("sesi_daftar") == "aktif") : ?>
                                                        <a data-toggle="modal" data-target="#editmodal" class='btn btn-primary btn-xs editModal' data-id="<?= $id_kecamatan ?>"><i class='fas fa-edit'></i> Edit </a>
                                                        <form action="<?= base_url("kecamatan/detail/$id_kecamatan") ?>" method="POST" class="d-inline">
                                                            <?= csrf_field(); ?>
                                                            <input type="hidden" name="_method" value="delete">
                                                            <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Data akan dihapus')"><i class='fas fa-trash'></i>Delete</button>
                                                        </form>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>PESERTA</h4>
                                    <table class="table table-bordered ">
                                        <thead>
                                            <tr class="table-warning">
                                                <th>No </th>
                                                <th>Nama </th>
                                                <th>Jumlah Suara</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $totalSuarakota = 0;
                                            foreach ($kota as $suara) :
                                                $totalSuarakota += $suara['jumlah_suara'];
                                            ?>
                                                <tr>
                                                    <td><?= $suara['no_urutkota']; ?></td>
                                                    <td><?= $suara['nama_calegkota']; ?></td>
                                                    <td><?= $suara['jumlah_suara']; ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr>
                                                <td colspan="2">Coblos Gambar Partai</td>
                                                <td>
                                                    <?= $partaikota->suara_partaikota ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">Total Suara Partai</td>
                                                <td>
                                                    <?= $totalSuarakota + $partaikota->suara_partaikota ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <p>Chart</p>
                                        </div>
                                        <canvas id="myChartKota"></canvas>
                                        <h5 class="text-center">Total Suara : <?= $totalSuarakota + $partaikota->suara_partaikota ?>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="card">

        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="kotaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Dapil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url("kota/kecamatanCreate") ?>" method="POST">
                    <?= csrf_field(); ?>
                    <div class="form-group">
                        <label>Nama Kecamatan</label>
                        <input type="text" name="nama_kecamatan" class="form-control" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="save" class="btn btn-primary">Simpan</button></form>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="exampleModalLabel2">Edit Kecamatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="<?= base_url("kota/kecamatanEdit"); ?>">
                    <?= csrf_field(); ?>
                    <div class="form-group">
                        <label for="nama_kecamatan" class="form-label">Nama Kecamatan</label>
                        <input type="text" class="form-control" id="nama_kecamatan" name="nama_kecamatan">
                        <input type="hidden" class="form-control" id="id" name="id">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Edit</button></form>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url() ?>/assets/plugins/chart.js/Chart.min.js"></script>
<script>
    var ctx = document.getElementById('myChartKota').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_merge($nama_calegkota, ["coblos Partai"])) ?>,
            datasets: [{
                label: 'Jumlah Suara',
                data: <?= json_encode(array_merge($jumlah_suarakota, [$partaikota->suara_partaikota])) ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    // tambahkan warna lain di sini
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    // tambahkan warna lain di sini
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
    var ctx = document.getElementById('myChartProv').getContext('2d');
</script>
<script>
    $(function() {
        $('.editModal').on('click', function() {
            const id = $(this).data('id');
            const csrf_token = "<?= csrf_token() ?>"
            const csrf_hash = "<?= csrf_hash() ?>"

            $.ajax({
                url: '<?= base_url("kota/getKecamatan") ?>',
                data: {
                    [csrf_token]: csrf_hash,
                    id: id,
                },
                method: 'POST',
                dataType: 'json',
                success: function(data) {
                    $('#id').val(data.id_kecamatan);
                    $('#nama_kecamatan').val(data.nama_kecamatan);
                }
            });
        });

    });
</script>
<?= $this->endSection('content') ?>