<?php $this->load->view('layout/header'); ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>

    <!-- Menampilkan informasi pengguna -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Welcome, <?= $user['name']; ?>!</h6>
        </div>
        <div class="card-body">
            <p>Email: <?= $user['email']; ?></p>
            <p>Role: <?= ucfirst($user['role']); ?></p>
        </div>
    </div>

    <!-- Menampilkan beberapa statistik atau informasi lainnya -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <a href="<?= base_url('permintaan') ?>">

                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Permintaan</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_permintaan ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-box fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <a href="<?= base_url('pembelian_customer') ?>">

                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Pembelian</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_pembelian ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-box fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>



        <!-- Tambahkan lebih banyak statistik atau informasi lain sesuai kebutuhan -->
    </div>
</div>

<?php $this->load->view('layout/footer'); ?>