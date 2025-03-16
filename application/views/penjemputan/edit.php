<?php

$this->load->view('layout/header'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Penjemputan</h1>
    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Penjemputan Form</h6>
        </div>
        <div class="card-body">
            <form method="post">

                <div class="form-group">
                    <label for="id_kat_barang">Nama Barang</label>
                    <select class="form-control" id="id_barang" name="id_barang" required>
                        <option value="" disabled selected>Pilih Salah Satu</option> <!-- Disabled option -->
                        <?php foreach ($barang as $b): ?>
                            <option value="<?= $b['id'] ?> " <?= $b['id'] == $penjemputan['id_barang'] ? 'selected' : '' ?>><?= $b['name'] ?> - <?= $b['stok'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="id_customer">Nama Customer</label>
                    <select class="form-control" id="id_customer" name="id_customer" required>
                        <option value="" disabled selected>Pilih Salah Satu</option> <!-- Disabled option -->
                        <?php foreach ($customer as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= $c['id'] == $penjemputan['id_customer'] ? 'selected' : '' ?>><?= $c['kode'] ?> || <?= $c['nama'] ?> </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="jumlah_masuk">Stok</label>
                    <input type="number" class="form-control" min="0" id="jumlah_masuk" name="jumlah_masuk" value="<?= $penjemputan['jumlah_masuk'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= $penjemputan['tanggal'] ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>

    </div>
</div>
<?php $this->load->view('layout/footer'); ?>