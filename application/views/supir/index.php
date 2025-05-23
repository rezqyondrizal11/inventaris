<?php $this->load->view('layout/header'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Supir</h1>
    <a href="<?= site_url('supir/create') ?>" class="btn btn-primary mb-4">Add New Supir</a>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Supir List</h6>
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
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Alamat</th>

                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($data as $d): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $d['kode'] ?></td>
                            <td><?= $d['nama'] ?></td>
                            <td><?= $d['email'] ?></td>
                            <td><?= $d['telepon'] ?></td>
                            <td><?= $d['alamat'] ?></td>
                            <td>
                                <a href="<?= site_url('supir/edit/' . $d['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="<?= site_url('supir/delete/' . $d['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $this->load->view('layout/footer'); ?>