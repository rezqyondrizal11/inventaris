<?php

$this->load->view('layout/header'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Penjualan</h1>
    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Penjualan Form</h6>
        </div>
        <div class="card-body">
            <form method="post">

                <div class="form-group">
                    <label for="id_kat_barang">Nama Barang</label>
                    <select class="form-control" id="id_barang" name="id_barang" required>
                        <option value="" disabled selected>Pilih Salah Satu</option> <!-- Disabled option -->
                        <?php foreach ($barang as $b): ?>
                            <option value="<?= $b['id'] ?> " <?= $b['id'] == $penjualan['id_barang'] ? 'selected' : '' ?>><?= $b['name'] ?> - <?= $b['stok'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="id_customer">Nama Customer</label>
                    <select class="form-control" id="id_customer" name="id_customer" required>
                        <option value="" disabled selected>Pilih Salah Satu</option> <!-- Disabled option -->
                        <?php foreach ($customer as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= $c['id'] == $penjualan['id_customer'] ? 'selected' : '' ?>><?= $c['kode'] ?> || <?= $c['nama'] ?> </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="id_kat_barang">Nama Supir</label>
                    <select class="form-control" id="id_supir" name="id_supir" required>
                        <option value="" disabled selected>Pilih Salah Satu</option> <!-- Disabled option -->
                        <?php foreach ($supir as $s): ?>
                            <option value="<?= $s['id'] ?>" <?= $s['id'] == $penjualan['id_supir'] ? 'selected' : '' ?>><?= $s['nama']  ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="jumlah_keluar">Stok Keluar</label>
                    <input type="number" class="form-control" id="jumlah_keluar" name="jumlah_keluar" value="<?= $penjualan['jumlah_keluar'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="tanggal">Tanggal Jual</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= $penjualan['tanggal'] ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>

    </div>
</div>
<?php $this->load->view('layout/footer'); ?>