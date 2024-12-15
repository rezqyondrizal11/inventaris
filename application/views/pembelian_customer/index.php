<?php $this->load->view('layout/header'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Pembelian Barang</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Pembelian Barang List</h6>
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
                        <th>Jumlah Masuk</th>
                        <th>Jumlah Keluar</th>
                        <th>Sisa</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($data as $d):
                        $penjualan = $this->Penjualan_model->get_data_by_id($d['id_penjualan']);

                        $barang = $this->Barang_model->get_data_by_id($penjualan['id_barang']);
                        $barangname = $barang ? $barang['name'] : 'Unknown';
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>

                            <td><?= htmlspecialchars($barangname, ENT_QUOTES, 'UTF-8') ?></td>

                            <td><?= htmlspecialchars($d['jumlah_masuk'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($d['jumlah_keluar'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($d['sisa'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <?php if ($d['status'] == 1) {

                                    echo 'Diproses';
                                } elseif ($d['status'] == 2) {

                                    echo 'Barang Dikembalian';
                                } elseif ($d['status'] == 3) {

                                    echo 'Masih ada Sisa';
                                } elseif ($d['status'] == 0) {

                                    echo 'Pengembalian Ditolak';
                                }
                                ?>
                            </td>
                            <td> <?php if (!$d['status'] || $d['status'] == 0) { ?>

                                    <a href="<?= site_url('pembelian_customer/pengembalian/' . $d['id']) ?>" class="btn btn-warning btn-sm">Pengembalian</a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $this->load->view('layout/footer'); ?>