<?php $this->load->view('layout/header'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Barang</h1>
    <?php if ($this->session->userdata('role') == 'admin'): ?>
        <a href="<?= site_url('barang/create') ?>" class="btn btn-primary mb-4">Add New Barang</a>
    <?php endif; ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Barang List</h6>
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
                        <th>ID</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Volume</th>
                        <th>kondisi</th>
                        <th>Kategori</th>
                        <th>Penyewaan</th>

                        <th>Jumlah Masuk</th>
                        <th>Jumlah Keluar</th>
                        <th>Stok</th>
                        <?php if ($this->session->userdata('role') == 'admin'): ?>

                            <th>Actions</th>
                        <?php endif; ?>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($data as $d):
                        // Periksa apakah data kategori ada, jika tidak beri nilai default
                        $kat = $this->Kat_barang_model->get_data_by_id($d['id_kat_barang']);
                        $kategori_name = $kat ? $kat['name'] : 'Unknown';
                        $katp = $this->Kat_penyewaan_model->get_data_by_id($d['id_penyewaan']);
                        $kategori_p = $kat ? $katp['name'] : 'Unknown';
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($d['kode'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($d['name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($d['satuan'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($d['kondisi'], ENT_QUOTES, 'UTF-8') ?></td>

                            <td><?= htmlspecialchars($kategori_name, ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($kategori_p, ENT_QUOTES, 'UTF-8') ?></td>

                            <td><?= htmlspecialchars($d['jumlah_masuk'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($d['jumlah_keluar'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($d['stok'], ENT_QUOTES, 'UTF-8') ?></td>

                            <?php if ($this->session->userdata('role') == 'admin'): ?>

                                <td>
                                    <a href="<?= site_url('barang/edit/' . $d['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="<?= site_url('barang/delete/' . $d['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
                                </td>
                            <?php endif; ?>

                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $this->load->view('layout/footer'); ?>