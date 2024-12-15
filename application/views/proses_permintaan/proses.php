<?php

$this->load->view('layout/header');
$barang = $this->Barang_model->get_data_by_id($data['id_barang']);
$barangname = $barang ? $barang['name'] : 'Unknown';


$customer = $this->Customer_model->get_data_by_id($data['id_customer']);
$customername = $customer ? $customer['nama'] : 'Unknown';

?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800 text-center">Konfirmasi Permintaan</h1>

    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 text-center">
            <h6 class="m-0 font-weight-bold">Konfirmasi Permintaan Form</h6>
        </div>
        <div class="card-body">

            <!-- Informasi Permintaan -->
            <div class="row">
                <div class="col-12 text-center">
                    <h4 class="mb-3">Permintaan Information</h4>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <div class="alert alert-info">
                        <strong>Nama Barang:</strong> <?= $barangname ?>
                    </div>
                </div>
                <div class="col-6">
                    <div class="alert alert-info">
                        <strong>Permintaan Stok:</strong> <?= $data['stok'] ?>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <div class="alert alert-info">
                        <strong>Nama Customer:</strong> <?= $customername ?>
                    </div>
                </div>
                <div class="col-6">
                    <div class="alert alert-info">
                        <strong>Tanggal Permintaan:</strong> <?= date('d-M-Y', strtotime($data['tanggal'])) ?>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form method="post">
                <div class="form-group mb-3">
                    <label for="id_supir">Nama Supir</label>
                    <select class="form-control" id="id_supir" name="id_supir" required>
                        <option value="" disabled selected>Pilih Salah Satu</option>
                        <?php foreach ($supir as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= $s['nama'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="" disabled selected>Pilih Salah Satu</option>
                        <option value="2">Terima</option>
                        <option value="0">Tolak</option>
                    </select>
                </div>

                <div class="form-group mb-4">
                    <label for="keterangan">Keterangan</label>
                    <input type="text" class="form-control" id="keterangan" name="keterangan" value="<?= set_value('keterangan') ?>">
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary px-5">Proses</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->load->view('layout/footer'); ?>