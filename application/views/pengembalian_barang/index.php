<?php $this->load->view('layout/header'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Pengembalian Barang</h1>
    <a href="<?= site_url('pengembalian_barang/create/') ?>" class="btn btn-primary mb-4">Add New Pengembalian</a>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Pengembalian Barang List</h6>
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
                        <th>Stok Dikembalian</th>
                        <th>Sisa</th>
                        <th>Supir</th>
                        <th>Tanggal Pengembalian</th>
                        <th>Status</th>
                        <th>Volume</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($data as $d):
                        $pembelianc =  $this->Pembelian_customer_model->get_data_by_id($d['id_pc']);


                        $barang = $this->Barang_model->get_data_by_id($d['id_barang']);
                        $barangname = $barang ? $barang['name'] : 'Unknown';

                        $customer = $this->Customer_model->get_data_by_id($d['id_customer']);
                        $customername = $customer ? $customer['nama'] : 'Unknown';



                        $supir = $this->Supir_model->get_data_by_id($d['id_supir']);
                        $supirname = $supir ? $supir['nama'] : '';

                    ?>
                        <tr>
                            <td><?= $no++ ?></td>

                            <td><?= htmlspecialchars($barangname, ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($customername, ENT_QUOTES, 'UTF-8') ?></td>

                            <td><?= htmlspecialchars($d['stok_dikembalikan'], ENT_QUOTES, 'UTF-8') ?></td>

                            <td><?= htmlspecialchars($d['sisa'], ENT_QUOTES, 'UTF-8') ?></td>

                            <td><?= htmlspecialchars($supirname, ENT_QUOTES, 'UTF-8') ?></td>

                            <td><?= $d['tanggal'] ? date('d-M-Y', strtotime($d['tanggal'])) : '' ?></td>
                            <td>
                                <?php if ($d['status'] == 1) {

                                    echo 'Dipakai';
                                } elseif ($d['status'] == 2) {

                                    echo 'Barang Diterima';
                                } elseif ($d['status'] == 0) {

                                    echo 'Pengembalian Ditolak';
                                }
                                ?>
                            </td>
                            <td><?= $d['volume'] ?></td>
                            <td>
                                <?php
                                if ($d['status'] != 2) { ?>
                                    <a href="<?= site_url('pengembalian_barang/proses/' . $d['id']) ?>" class="btn btn-warning btn-sm">Proses</a>

                                <?php   }
                                ?>

                            </td>
                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $this->load->view('layout/footer'); ?>