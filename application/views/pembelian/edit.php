<?php

$this->load->view('layout/header'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Pembelian</h1>
    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Pembelian Form</h6>
        </div>
        <div class="card-body">
            <form method="post">
                <div class="form-group">
                    <label for="stok">No Invoice</label>
                    <input type="text" class="form-control" value="<?= $pembelian['no_invoice'] ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="id_kat_barang">Nama Barang</label>
                    <select class="form-control" id="id_barang" name="id_barang" required>
                        <option value="" disabled selected>Pilih Salah Satu</option> <!-- Disabled option -->
                        <?php foreach ($barang as $b): ?>
                            <option value="<?= $b['id'] ?> " <?= $b['id'] == $pembelian['id_barang'] ? 'selected' : '' ?>><?= $b['name'] ?> - <?= $b['stok'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="id_supplier">Nama Supplier</label>
                    <select class="form-control" id="id_supplier" name="id_supplier" required>
                        <option value="" disabled selected>Pilih Salah Satu</option> <!-- Disabled option -->
                        <?php foreach ($supplier as $s): ?>
                            <option value="<?= $s['id'] ?>" <?= $s['id'] == $pembelian['id_supplier'] ? 'selected' : '' ?>> <?= $s['nama'] ?> </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="jumlah_masuk">Stok Masuk</label>
                    <input type="number" class="form-control" min="1" id="jumlah_masuk" name="jumlah_masuk" value="<?= $pembelian['jumlah_masuk'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="tanggal">Tanggal Pembelian</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= $pembelian['tanggal'] ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>

    </div>
</div>
<?php $this->load->view('layout/footer'); ?>