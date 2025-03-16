<?php $this->load->view('layout/header'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Create New Barang</h1>

    <!-- Tampilkan pesan error jika validasi gagal -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?= $errors ?>
        </div>
    <?php endif; ?>

    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Create Barang Form</h6>
        </div>
        <div class="card-body">
            <form method="post">
                <div class="form-group">
                    <label for="kode"><strong>Kode</strong></label>
                    <input type="text" name="kode" placeholder="Masukkan Kode" autocomplete="off" class="form-control" required value="BRG<?= mt_rand(100, 999) ?>" maxlength="8" readonly>
                </div>
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" class="form-control" id="nama" name="name" value="<?= set_value('name') ?>" required>
                </div>
                <div class="form-group">
                    <label for="kondisi">Kondisi</label>
                    <select class="form-control" id="kondisi" name="kondisi" required>
                        <option value="" disabled selected>Pilih Salah Satu</option> <!-- Disabled option -->
                        <option value="baik">Baik</option>
                        <option value="rusak">Rusak</option>

                    </select>
                </div>
                <div class="form-group">
                    <label for="satuan">Volume</label>
                    <input type="text" class="form-control" id="satuan" name="satuan" value="<?= set_value('satuan') ?>" required>
                </div>
                <div class="form-group">
                    <label for="id_kat_barang">Kategori Barang</label>
                    <select class="form-control" id="id_kat_barang" name="id_kat_barang" required>
                        <option value="" disabled selected>Pilih Salah Satu</option> <!-- Disabled option -->
                        <?php foreach ($kategori as $kat): ?>
                            <option value="<?= $kat['id'] ?>"><?= $kat['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="id_penyewaan">Penyewaan</label>
                    <select class="form-control" id="id_penyewaan" name="id_penyewaan" required>
                        <option value="" disabled selected>Pilih Salah Satu</option> <!-- Disabled option -->
                        <?php foreach ($penyewaan as $pen): ?>
                            <option value="<?= $pen['id'] ?>"><?= $pen['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="stok">Stok</label>
                    <input type="number" class="form-control" min="1" id="stok" name="stok" value="<?= set_value('stok') ?>" required>
                </div>

                <button type="submit" class="btn btn-primary">Create</button>
            </form>
        </div>

    </div>
</div>
<?php $this->load->view('layout/footer'); ?>