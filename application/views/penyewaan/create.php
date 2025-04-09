<?php $this->load->view('layout/header'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Create New Penyewaan</h1>

    <!-- Tampilkan pesan error jika validasi gagal -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?= $errors ?>
        </div>
    <?php endif; ?>

    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Create Penyewaan Form</h6>
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
                    <label for="id_supir">Nama Supir</label>
                    <select class="form-control" id="id_supir" name="id_supir">
                        <option value="" disabled selected>Pilih Salah Satu</option> <!-- Disabled option -->
                        <?php foreach ($supir as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= $s['nama'] ?> </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="jumlah_keluar">Stok</label>
                    <input type="number" min="0" class="form-control" id="jumlah_keluar" name="jumlah_keluar" value="<?= set_value('jumlah_keluar') ?>" required>
                </div>
                <div class="form-group">
                    <label for="tanggal">Tanggal Sewa</label>
                    <input type="date" min="<?= date('Y-m-d') ?>" class="form-control" id="tanggal" name="tanggal" value="<?= set_value('tanggal') ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Create</button>
            </form>
        </div>

    </div>
</div>
<?php $this->load->view('layout/footer'); ?>
