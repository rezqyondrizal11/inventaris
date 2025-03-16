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
                    <label for="id_barang">Nama Barang</label>
                    <select class="form-control" id="id_barang" name="id_barang" required>
                        <option value="" disabled selected>Pilih Salah Satu</option> <!-- Disabled option -->
                        <?php foreach ($barang as $b): ?>
                            <option value="<?= $b['id'] ?>"><?= $b['name'] ?> - <?= $b['stok'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="id_customer">Nama Customer</label>
                    <select class="form-control" id="id_customer" name="id_customer" required>
                        <option value="" disabled selected>Pilih Salah Satu</option> <!-- Disabled option -->
                        <?php foreach ($customer as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= $c['kode'] ?> || <?= $c['nama'] ?> </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="stok_dikembalikan">Stok Dikembalikan</label>
                    <input type="number" class="form-control" min="1" id="stok_dikembalikan" name="stok_dikembalikan" value="<?= set_value('stok_dikembalikan') ?>" required>
                </div>

                <div class="form-group">
                    <label for="sisa">Sisa</label>
                    <input type="number" class="form-control" min="0" id="sisa" name="sisa" value="<?= set_value('sisa') ?>" required>
                </div>
                <div class="form-group">
                    <label for="volume">Volume</label>
                    <select class="form-control" id="volume" name="volume" required>
                        <option value="" disabled selected>Pilih Salah Satu</option> <!-- Disabled option -->

                        <option value="Berisi">Berisi </option>
                        <option value="Kosong">Kosong </option>

                    </select>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="" disabled selected>Pilih Salah Satu</option> <!-- Disabled option -->

                        <option value="0">Ditolak </option>
                        <option value="2">Diterima </option>

                    </select>
                </div>
                <div class="form-group">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= set_value('tanggal') ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Proses</button>
            </form>
            <br><br>


        </div>
    </div>
</div>
<?php $this->load->view('layout/footer'); ?>