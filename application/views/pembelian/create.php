<?php $this->load->view('layout/header'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Create New Pembelian</h1>

    <!-- Tampilkan pesan error jika validasi gagal -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?= $errors ?>
        </div>
    <?php endif; ?>

    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Create Pembelian Form</h6>
        </div>
        <div class="card-body">
            <form method="post" id="pembelianForm">
                <div id="pembelian-container">
                    <div class="pembelian-item">
                        <div class="form-group">
                            <label for="no_invoice">No Invoice</label>
                            <input type="text" class="form-control" name="pembelian[0][no_invoice]" value="INV-<?= mt_rand(100, 999) ?>" required readonly>
                        </div>

                        <div class="form-group">
                            <label for="id_barang">Nama Barang</label>
                            <select class="form-control" name="pembelian[0][id_barang]" required>
                                <option value="" disabled selected>Pilih Salah Satu</option>
                                <?php foreach ($barang as $b): ?>
                                    <option value="<?= $b['id'] ?>"><?= $b['name'] ?> - <?= $b['stok'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="id_supplier">Nama Supplier</label>
                            <select class="form-control" name="pembelian[0][id_supplier]" required>
                                <option value="" disabled selected>Pilih Salah Satu</option>
                                <?php foreach ($supplier as $s): ?>
                                    <option value="<?= $s['id'] ?>"><?= $s['nama'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="jumlah_masuk">Stok Masuk</label>
                            <input type="number" class="form-control" min="1" name="pembelian[0][jumlah_masuk]" required>
                        </div>

                        <div class="form-group">
                            <label for="tanggal">Tanggal Beli</label>
                            <input type="date" class="form-control" name="pembelian[0][tanggal]" required>
                        </div>
                    </div>
                </div>

                <div class="float-right">
                    <button type="button" class="btn btn-secondary" id="addItem">Tambah Pembelian</button>
                </div>
                <br><br>

                <div class="float-right">
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>

        </div>

    </div>
</div>
<script>
    let itemIndex = 1;

    document.getElementById('addItem').addEventListener('click', function() {
        const container = document.getElementById('pembelian-container');

        const newItem = document.createElement('div');
        newItem.classList.add('pembelian-item');

        newItem.innerHTML = `
      

            <div class="form-group">
                <label for="id_barang">Nama Barang</label>
                <select class="form-control" name="pembelian[${itemIndex}][id_barang]" required>
                    <option value="" disabled selected>Pilih Salah Satu</option>
                    <?php foreach ($barang as $b): ?>
                        <option value="<?= $b['id'] ?>"><?= $b['name'] ?> - <?= $b['stok'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="id_supplier">Nama Supplier</label>
                <select class="form-control" name="pembelian[${itemIndex}][id_supplier]" required>
                    <option value="" disabled selected>Pilih Salah Satu</option>
                    <?php foreach ($supplier as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= $s['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="jumlah_masuk">Stok Masuk</label>
                <input type="number" class="form-control" min="1" name="pembelian[${itemIndex}][jumlah_masuk]" required>
            </div>

            <div class="form-group">
                <label for="tanggal">Tanggal Beli</label>
                <input type="date" class="form-control" name="pembelian[${itemIndex}][tanggal]" required>
            </div>
        `;

        container.appendChild(newItem);
        itemIndex++;
    });
</script>
<?php $this->load->view('layout/footer'); ?>