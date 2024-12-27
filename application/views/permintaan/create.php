<?php $this->load->view('layout/header'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Create New Permintaan</h1>

    <!-- Tampilkan pesan error jika validasi gagal -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?= $errors ?>
        </div>
    <?php endif; ?>

    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Create Permintaan Form</h6>
        </div>
        <div class="card-body">
            <form method="post" id="permintaanForm">
                <div id="permintaan-container">
                    <div class="permintaan-item">
                        <div class="form-group">
                            <label for="stok">No Invoice</label>
                            <input type="text" class="form-control" name="permintaan[0][no_invoice]" required>
                        </div>

                        <div class="form-group">
                            <label for="id_barang">Nama Barang</label>
                            <select class="form-control" name="permintaan[0][id_barang]" required>
                                <option value="" disabled selected>Pilih Salah Satu</option>
                                <?php foreach ($barang as $b): ?>
                                    <option value="<?= $b['id'] ?>"><?= $b['name'] ?> - <?= $b['stok'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="stok">Stok</label>
                            <input type="number" class="form-control" name="permintaan[0][stok]" required>
                        </div>

                    </div>
                </div>
                <div class="float-right">
                    <button type="button" class="btn btn-secondary" id="addItem">Tambah Permintaan</button>

                </div> <br><br>
                <div class="float-right">

                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $this->load->view('layout/footer'); ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let container = document.getElementById('permintaan-container');
        let addItemButton = document.getElementById('addItem');
        let itemIndex = 1;

        addItemButton.addEventListener('click', function() {
            let newItem = document.createElement('div');
            newItem.classList.add('permintaan-item');
            newItem.innerHTML = `
                <div class="form-group">
                    <label for="id_barang">Nama Barang</label>
                    <select class="form-control" name="permintaan[${itemIndex}][id_barang]" required>
                        <option value="" disabled selected>Pilih Salah Satu</option>
                        <?php foreach ($barang as $b): ?>
                            <option value="<?= $b['id'] ?>"><?= $b['name'] ?> - <?= $b['stok'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="stok">Stok</label>
                    <input type="number" class="form-control" name="permintaan[${itemIndex}][stok]" required>
                </div>
                <button type="button" class="btn btn-danger remove-item">Hapus</button>
            `;
            container.appendChild(newItem);
            itemIndex++;
        });

        container.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-item')) {
                e.target.closest('.permintaan-item').remove();
            }
        });
    });
</script>