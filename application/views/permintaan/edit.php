<?php $this->load->view('layout/header'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Permintaan</h1>
    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Permintaan Form</h6>
        </div>
        <div class="card-body">
            <form method="post">
                <div class="form-group">
                    <label for="stok">No Invoice</label>
                    <input type="text" class="form-control" value="<?= $data['no_invoice'] ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="id_barang">Nama Barang</label>
                    <select class="form-control" id="id_barang" name="id_barang" required>
                        <option value="" disabled selected>Pilih Salah Satu</option> <!-- Disabled option -->
                        <?php foreach ($barang as $b): ?>
                            <option value="<?= $b['id'] ?>" <?= $data['id_barang'] == $b['id'] ? 'selected' : '' ?>><?= $b['name'] ?> - <?= $b['stok'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="stok">Stok</label>
                    <input type="number" min="0" class="form-control" id="stok" name="stok" value="<?= $data['stok'] ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
<?php $this->load->view('layout/footer'); ?>