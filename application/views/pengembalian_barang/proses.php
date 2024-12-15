<?php $this->load->view('layout/header'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Proses Pengembalian</h1>

    <!-- Tampilkan pesan error jika validasi gagal -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?= $errors ?>
        </div>
    <?php endif; ?>

    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Proses Pengembalian Form</h6>
        </div>
        <div class="card-body">
            <form method="post">


                <div class="form-group">
                    <label for="id_supir">Nama Supr</label>
                    <select class="form-control" id="id_supir" name="id_supir" required>
                        <option value="" disabled selected>Pilih Salah Satu</option> <!-- Disabled option -->
                        <?php foreach ($supir as $s): ?>
                            <option value="<?= $s['id'] ?>"> <?= $s['nama'] ?> </option>
                        <?php endforeach; ?>
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
                    <label for="tanggal">Tanggal Pengembalian</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= set_value('tanggal') ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Create</button>
            </form>
        </div>

    </div>
</div>
<?php $this->load->view('layout/footer'); ?>