<!-- Content Header (Page header) -->
<?php
// header("Content-type: application/vnd-ms-excel");
// header("Content-Disposition: attachment; filename=Rekap Data Kegiatan.xls");
?>
<?= $this->extend('layouts/layout') ?>
<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<div class="content-header bg-secondary mb-2">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3 class="m-0">Data Per TPS</h3>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table table id="download" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Saksi</th>
                                    <?php foreach ($pesertakota as $peserta) : ?>
                                        <th><?= $peserta["no_urutkota"] ?>.<?= $peserta["nama_calegkota"] ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($orang as $data) :
                                ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $data["nama"] ?></td>
                                        <?php
                                        $id_saksi = $data["id_orang"];
                                        $result = $suaraKota->getResultKotaByLevel("id_saksi", $id_saksi);
                                        if ($result) { ?>
                                            <?php foreach ($result as $hasil) { ?>
                                                <td><?= $hasil["jumlah_suara"] ?></td>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <?php foreach ($pesertakota as $peserta) { ?>
                                                <td>-</td>
                                            <?php } ?>
                                        <?php } ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.content -->
<script>
    $(document).ready(function() {
        $("#download").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "buttons": [{
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Download',
                className: 'btn-success'
            }],
            "paging": false
        }).buttons().container().appendTo('#download_wrapper .col-md-6:eq(0)');
    });
</script>

<?= $this->endSection('content') ?>