<?php $this->load->view('layout/header'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Create New Permintaan</h1>

    <!-- Tampilkan pesan error jika validasi gagal -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?= $errors ?>
        </div>
    <?php endif; ?>

    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Create Permintaan Form</h6>
        </div>
        <div class="card-body">
            <form method="post">
                <div class="form-group">
                    <label for="id_barang">Nama Barang</label>
                    <select class="form-control" id="id_barang" name="id_barang" required>
                        <option value="" disabled selected>Pilih Salah Satu</option> <!-- Disabled option -->
                        <?php foreach ($barang as $b): ?>
                            <option value="<?= $b['id'] ?>"><?= $b['name'] ?> - <?= $b['stok'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="stok">Stok</label>
                    <input type="number" class="form-control" id="stok" name="stok" value="<?= set_value('stok') ?>" required>
                </div>

                <button type="submit" class="btn btn-primary">Create</button>
            </form>
        </div>
    </div>
</div>
<?php $this->load->view('layout/footer'); ?>