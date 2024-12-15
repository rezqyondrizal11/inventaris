<?php $this->load->view('layout/header'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Proses Permintaan</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Proses Permintaan List</h6>
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
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Tanggal Permintaan</th>
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


                        $customer = $this->Customer_model->get_data_by_id($d['id_customer']);
                        $customername = $customer ? $customer['nama'] : 'Unknown';
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>

                            <td><?= htmlspecialchars($barangname, ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($customername, ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($d['stok'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <?php if ($d['status'] == 1) {
                                    echo 'Process';
                                } elseif ($d['status'] == 2) {
                                    echo 'Accepted';
                                } else {
                                    echo 'Decline';
                                }
                                ?>
                            </td>
                            <td><?= date('d-M-Y', strtotime($d['tanggal'])) ?></td>

                            <td>
                                <?php if ($d['status'] == 1) { ?>
                                    <a href="<?= site_url('proses_permintaan/proses/' . $d['id']) ?>" class="btn btn-warning btn-sm">Proses</a>

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