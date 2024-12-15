<?php $this->load->view('layout/header'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Permintaan</h1>
    <a href="<?= site_url('permintaan/create') ?>" class="btn btn-primary mb-4">Add New Permintaan</a>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Permintaan List</h6>
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
                        <th>Nama Barang</th>
                        <th>Stok</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Keterangan</th>

                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($data as $d):
                        $barang = $this->Barang_model->get_data_by_id($d['id_barang']);
                        $barangname = $barang ? $barang['name'] : 'Unknown';
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $barangname ?></td>
                            <td><?= $d['stok'] ?></td>
                            <td><?= date('d-m-Y', strtotime($d['tanggal'])) ?></td>
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
                            <td><?= $d['ket'] ?></td>
                            <td>
                                <?php if ($d['status'] == 1) { ?>
                                    <a href="<?= site_url('permintaan/edit/' . $d['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="<?= site_url('permintaan/delete/' . $d['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                                <?php }
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