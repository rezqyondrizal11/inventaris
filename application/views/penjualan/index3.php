<?php $this->load->view('layout/header'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Penjualan</h1>
    <a href="<?= site_url('penjualan/create') ?>" class="btn btn-primary mb-4">Add New Penjualan</a>
    <a href="<?= site_url('penjualan/print_pdf?start_date=' . $this->input->get('start_date') . '&end_date=' . $this->input->get('end_date')) ?>" class="btn btn-danger mb-4" target="_blank">Print PDF</a>
    <a href="<?= site_url('penjualan/export_excel') ?>?start_date=<?= $this->input->get('start_date') ?>&end_date=<?= $this->input->get('end_date') ?>"
        class="btn btn-success mb-4">Export Excel</a>
    <!-- Filter Form -->
    <form method="GET" action="<?= site_url('penjualan') ?>" class="mb-4">
        <div class="form-row">
            <div class="col-md-4">
                <label for="start_date">Tanggal Awal</label>
                <input type="date" class="form-control" id="start_date" name="start_date"
                    value="<?= htmlspecialchars($this->input->get('start_date'), ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="col-md-4">
                <label for="end_date">Tanggal Akhir</label>
                <input type="date" class="form-control" id="end_date" name="end_date"
                    value="<?= htmlspecialchars($this->input->get('end_date'), ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="col-md-4 align-self-end">
                <button type="submit" class="btn btn-success">Filter</button>
                <a href="<?= site_url('penjualan') ?>" class="btn btn-secondary">Reset</a>
            </div>
        </div>
    </form>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Penjualan List</h6>
        </div>
        <div class="card-body">
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-primary">
                    <?= $this->session->flashdata('success') ?>
                </div>
            <?php endif; ?>

            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Nama Customer</th>
                        <th>Nama Supir</th>
                        <th>Jumlah Awal</th>

                        <th>Jumlah Keluar</th>
                        <th>Sisa Stok</th>
                        <th>Tanggal Jual</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($data as $d):
                        // Periksa apakah data kategori ada, jika tidak beri nilai default
                        $barang = $this->Barang_model->get_data_by_id($d['id_barang']);
                        $barangname = $barang ? $barang['name'] : 'Unknown';
                        $supir = $this->Supir_model->get_data_by_id($d['id_supir']);
                        $supirname = $supir ? $supir['nama'] : 'Unknown';

                        $customer = $this->Customer_model->get_data_by_id($d['id_customer']);
                        $customername = $customer ? $customer['nama'] : 'Unknown';
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>

                            <td> <?= htmlspecialchars($barang['kode'], ENT_QUOTES, 'UTF-8') ?> / <?= htmlspecialchars($barangname, ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($customername, ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($supirname, ENT_QUOTES, 'UTF-8') ?></td>

                            <td><?= htmlspecialchars($d['jumlah_awal'], ENT_QUOTES, 'UTF-8') ?></td>

                            <td><?= htmlspecialchars($d['jumlah_keluar'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($d['stok'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= date('d-M-Y', strtotime($d['tanggal'])) ?></td>
                            <td>
                                <a href="<?= site_url('penjualan/edit/' . $d['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="<?= site_url('penjualan/delete/' . $d['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $this->load->view('layout/footer'); ?>