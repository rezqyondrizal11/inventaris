<?php

$this->load->view('layout/header'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Proses Penyewaan</h1>
    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Proses Penyewaan Form</h6>
        </div>
        <div class="card-body">
            <form method="post">

                <div class="form-group">
                    <label for="tanggal_selesai">Tanggal Selesai</label>
                    <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" value="<?= $penyewaan['tanggal_selesai'] ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">proses</button>
            </form>
        </div>

    </div>
</div>
<?php $this->load->view('layout/footer'); ?>