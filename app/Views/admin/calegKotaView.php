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
                <h3 class="m-0">PESERTA </h3>
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
        <?php if (session()->get("sesi_daftar") == "aktif") : ?>
            <div class="card">
                <div class="card-header">
                    <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal">
                        <i class="fas fa-plus">
                        </i>
                        Tambah Caleg
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
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        PESERTA
                    </div>
                    <div class="card-body">
                        <table class=" table table-bordered">
                            <thead>
                                <tr>
                                    <th>No Urut</th>
                                    <th>Nama Caleg</th>
                                    <th>Image</th>
                                    <?php if (session()->get("sesi_daftar") == "aktif") : ?>
                                        <th>Action</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($calegkota != null) { ?>
                                    <?php foreach ($calegkota as $calegK) { ?>
                                        <tr>
                                            <td><?= $calegK['no_urutkota'] ?></td>
                                            <td><?= $calegK['nama_calegkota'] ?></td>
                                            <td>
                                                <?php if ($calegK['image']) : ?>
                                                    <img src="<?= base_url('assets/calegImg/' . $calegK['image']) ?>" alt="<?= $calegK['nama_calegkota'] ?>" style="width: 100px;" class=" img-thumbnail">
                                                <?php else : ?>
                                                    <img src="<?= base_url('assets/dist/img/avatar5.png') ?>" alt="Default Avatar" style="width: 100px;" class=" img-thumbnail">
                                                <?php endif; ?>
                                            </td>

                                            <?php $id_caleg = $calegK['id_calegkota'];
                                            if (session()->get("sesi_daftar") == "aktif") : ?>
                                                <td>
                                                    <a data-toggle="modal" data-target="#editModal" class='btn btn-primary btn-xs editModal' data-id="<?= $calegK['id_calegkota'] ?>"><i class='fas fa-edit'></i> Edit </a>
                                                    <form action="<?= base_url("caleg/$id_caleg") ?>" method="POST" class="d-inline">
                                                        <?= csrf_field(); ?>
                                                        <input type="hidden" name="_method" value="delete">
                                                        <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Data akan dihapus')"><i class='fas fa-trash'></i>Delete</button>
                                                    </form>
                                                </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <td colspan="2">No Data</td>
                                <?php } ?>
                            </tbody>
                        </table>
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
                <h5 class="modal-title" id="exampleModalLabel">Tambah Caleg</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url("caleg/createCalegkota") ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field(); ?>
                    <div class="form-group">
                        <label>Nomor Urut</label>
                        <input type="number" name="no_urut" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="image">Foto Caleg</label>
                        <img id="image-preview" src="#" alt="Preview" style="max-width: 200px; max-height: 200px; display: none;" class="mb-2">
                        <input type="file" class="form-control-file" id="image" name="image" accept="image/*" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="save" class="btn btn-primary">Simpan</button></form>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data Caleg Kab/Kota</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url("caleg/editCalegKota") ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field(); ?>
                    <div class="form-group">
                        <label>Nomor Urut</label>
                        <input type="text" name="no_urut" id="no_urut" class="form-control" required>
                        <input type="hidden" class="form-control" id="id" name="id">
                        <input type="hidden" class="form-control" id="imageLama" name="imageLama">
                    </div>
                    <div class="form-group">
                        <label>Nama Caleg</label>
                        <input type="text" name="nama" id="nama" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="image">Foto Caleg</label>
                        <input type="file" class="form-control-file" id="image-edit" name="image" accept="image/*">
                        <img id="image-preview-edit" src="<?= base_url("assets/dist/img/avatar5.png") ?>" alt="Preview" style="max-width: 200px; max-height: 200px; display: none;">
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
                url: '<?= base_url("caleg/getCaleg") ?>',
                data: {
                    [csrf_token]: csrf_hash,
                    id: id,
                },
                method: 'POST',
                dataType: 'json',
                success: function(data) {
                    $('#id').val(data.id_calegkota);
                    $('#no_urut').val(data.no_urutkota);
                    $('#nama').val(data.nama_calegkota);
                    $('#imageLama').val(data.image);

                    // check if image exists
                    if (data.image !== "") {
                        imagePreview2.src = '<?= base_url("assets/calegImg/") ?>' + data.image;
                    } else {
                        imagePreview2.src = '<?= base_url("assets/dist/img/avatar5.png") ?>';
                    }
                    imagePreview2.style.display = "block";
                }
            });
        });
    });
    const inputImageEdit = document.getElementById("image-edit");
    const imagePreview2 = document.getElementById("image-preview-edit");
    inputImageEdit.addEventListener("change", () => {
        const file = inputImageEdit.files[0];
        if (file) {
            const reader = new FileReader();
            reader.addEventListener("load", () => {
                imagePreview2.src = reader.result;
                imagePreview2.style.display = "block";
            });
            reader.readAsDataURL(file);
        } else {
            imagePreview2.src = "";
            imagePreview2.style.display = "none";
        }
    });
</script>
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

<?= $this->endSection('content') ?>