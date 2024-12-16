<?php $this->load->view('layout/header'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Create New Customer</h1>

    <!-- Tampilkan pesan error jika validasi gagal -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?= $errors ?>
        </div>
    <?php endif; ?>

    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Create Customer Form</h6>
        </div>
        <div class="card-body">
            <form method="post">
                <div class="form-group">
                    <label for="kode"><strong>Kode</strong></label>
                    <input type="text" name="kode" placeholder="Masukkan Kode" autocomplete="off" class="form-control" required value="CUS<?= mt_rand(100, 999) ?>" maxlength="8" readonly>
                </div>
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="<?= set_value('nama') ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" id="email" name="email" value="<?= set_value('email') ?>" required>
                </div>
                <div class="form-group">
                    <label for="telepon">Telepon</label>
                    <input type="text" class="form-control" id="telepon" name="telepon" value="<?= set_value('telepon') ?>" required>
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <input type="text" class="form-control" id="alamat" name="alamat" value="<?= set_value('alamat') ?>" required>
                </div>
                <div class="form-group">
                    <label for="jenis">Jenis Customer</label>
                    <select class="form-control" id="jenis" name="id_jc" required>
                        <option value="" disabled selected>Pilih Salah Satu</option> <!-- Disabled option -->
                        <?php foreach ($jenis as $j): ?>
                            <option value="<?= $j['id'] ?>"><?= $j['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="user">User Login</label>
                    <select class="form-control" id="user" name="id_user">
                        <option value="" selected>Pilih Salah Satu</option> <!-- Disabled option -->
                        <?php foreach ($user as $j): ?>
                            <option value="<?= $j['id'] ?>"><?= $j['email'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Create</button>
            </form>
        </div>
    </div>
</div>
<?php $this->load->view('layout/footer'); ?>