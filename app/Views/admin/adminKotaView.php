<?= $this->extend('layouts/layout') ?>
<?= $this->section('content') ?>

<?php

$no = 1;
?>
<!-- Content Header (Page header) -->
<div class="content-header bg-secondary mb-2">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3 class="m-0">Admin Kecamatan</h3>
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
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-4">
                        <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal">
                            <i class="fas fa-user-plus">
                            </i>
                            Tambah Admin
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Kontak</th>
                            <th>Kecamatan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $user['nama'] ?></td>
                                <td><?= $user['nope'] ?></td>
                                <td>Kecamatan <?= $user['nama_kecamatan'] ?></td>
                                <td>
                                    <a data-toggle="modal" data-target="#editModal" class='btn btn-primary btn-xs editModal' data-id="<?= $user['id_orang'] ?>"><i class='fas fa-edit'></i> Edit </a>
                                    <form action="<?= base_url() ?>/users/<?= $user['id_orang'] ?>" method="POST" class="d-inline">
                                        <?= csrf_field(); ?>
                                        <input type="hidden" name="_method" value="delete">
                                        <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Data akan dihapus')"><i class='fas fa-trash'></i>Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal Tambah admintk1 -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Admin Kecamatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url("kota/adminKecamatanCreate") ?>" method="POST">
                    <?= csrf_field(); ?>
                    <div class="form-group">
                        <label>Kecamatan</label>
                        <select name="id_kecamatan" id="id_kecamatan" class="form-control select2">
                            <?php foreach ($wilayah as $w) : ?>
                                <option value="<?= $w['id_kecamatan'] ?>"><?= $w['nama_kecamatan'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nama Admin</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Telepon/Wa</label>
                        <input type="text" name="nope" class="form-control" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="submit" class="btn btn-primary">Simpan</button></form>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="exampleModalLabel">Edit Admin Kota</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url("users/userEdit") ?>" method="POST">
                    <?= csrf_field(); ?>
                    <div class="form-group">
                        <label>Nama Admin</label>
                        <input type="text" name="nama" id="nama" class="form-control" required>
                        <input type="hidden" class="form-control" id="id" name="id">
                    </div>
                    <div class="form-group">
                        <label>Telepon/Wa</label>
                        <input type="text" name="nope" id="nope" class="form-control" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="submit" class="btn btn-primary">Simpan</button></form>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('.editModal').on('click', function() {
            const id = $(this).data('id');
            const csrf_token = "<?= csrf_token() ?>"
            const csrf_hash = "<?= csrf_hash() ?>"

            $.ajax({
                url: '<?= base_url("users/getUser") ?>',
                data: {
                    [csrf_token]: csrf_hash,
                    id: id,
                },
                method: 'POST',
                dataType: 'json',
                success: function(data) {
                    $('#id').val(data.id_orang);
                    $('#nama').val(data.nama);
                    $('#nope').val(data.nope);
                }
            });
        });
    });
</script>
<?= $this->endSection('content') ?>