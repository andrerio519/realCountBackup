<?= $this->extend('layouts/layout') ?>
<?= $this->section('content') ?>
<script src="<?= base_url() ?>/assets/dist/js/jam.js"></script>
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
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    Perolehan Suara
                </div>
            </div>
            <div class="card-body">
                <div class="row justify-content-center align-items-center">
                    <div class="col-md-4">
                        <h2><?= tanggal_indo(date("Y-m-d")) ?></h2>
                    </div>
                </div>
                <div class="row justify-content-center align-items-lg-center mt-3">
                    <div class="col-md-8 bg-navy text-center card mx-auto">
                        <!-- Hitung Persentasi -->
                        <div class="easy-pie main-pie" data-percent="">
                            <div class="percent"></div>
                        </div>
                        <h4 class="text-white"></h4>
                    </div>
                </div>
                <div class="row justify-content-center align-items-center">
                    <div class="col-md-4">
                        <div class="clock"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.content -->
<script type="text/javascript">
    $(document).ready(function() {
        loadData();
        setInterval(function() {
            loadData();
        }, 20000);
    });

    function loadData() {
        $.ajax({
            url: '<?= base_url("allTpsData") ?>',
            success: function(data) {
                var obj = JSON.parse(data);
                var persen = obj.persen;
                var tps_masuk = obj.tps_masuk;
                var tps_total = obj.tps_total;
                $('.easy-pie.main-pie').data('easyPieChart').update(persen);
                $('.percent').text(persen);
                $('h4').text("TPS : " + tps_masuk + "/" + tps_total);
            }
        });
    }
</script>
<?= $this->endSection('content') ?>