<?php $this->load->view('layout/header'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Pengembalian Barang</h1>

    <!-- Tampilkan pesan error jika validasi gagal -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?= $errors ?>
        </div>
    <?php endif; ?>

    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Pengembalian Barang Form</h6>
        </div>
        <div class="card-body">
            <form method="post">
                <div class="form-group">
                    <label for="name">Stok Keluar</label>
                    <input type="text" class="form-control" id="jumlah_keluar" name="jumlah_keluar" value="<?= set_value('jumlah_keluar') ?>" required>
                </div>

                <button type="submit" class="btn btn-primary">Proses</button>
            </form>
        </div>
    </div>
</div>
<?php $this->load->view('layout/footer'); ?>