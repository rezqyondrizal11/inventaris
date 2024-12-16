<?php $this->load->view('layout/header'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Custoemr</h1>
    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Custoemr Form</h6>
        </div>
        <div class="card-body">
            <form method="post">

                <div class="form-group ">
                    <label for="kode"><strong>Kode</strong></label>
                    <input type="text" name="kode" placeholder="Masukkan Kode" autocomplete="off" class="form-control" required value="<?= $data['kode'] ?>" maxlength="8" readonly>
                </div>
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="<?= $data['nama'] ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" id="email" name="email" value="<?= $data['email'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="telepon">Telepon</label>
                    <input type="text" class="form-control" id="telepon" name="telepon" value="<?= $data['telepon'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <input type="text" class="form-control" id="alamat" name="alamat" value="<?= $data['alamat'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="jenis">Jenis Customer</label>
                    <select class="form-control" id="jenis" name="id_jc" required>
                        <option value="" disabled selected>Pilih Salah Satu</option> <!-- Disabled option -->
                        <?php foreach ($jenis as $j): ?>
                            <option value="<?= $j['id'] ?>" <?= $j['id'] == $data['id_jc'] ? 'selected' : '' ?>><?= $j['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="user">User Login</label>
                    <select class="form-control" id="user" name="id_user">
                        <option value="" selected>Pilih Salah Satu</option> <!-- Disabled option -->
                        <?php foreach ($user as $j): ?>
                            <option value="<?= $j['id'] ?>" <?= $j['id'] == $data['id_user'] ? 'selected' : '' ?>><?= $j['email'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
<?php $this->load->view('layout/footer'); ?>