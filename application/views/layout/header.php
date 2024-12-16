<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Inventaris</title>
    <link href="<?= base_url('assets/css/sb-admin-2.min.css') ?>" rel="stylesheet">
    <script src="<?= base_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

</head>
<?php
$datapenyewaan = $this->Kat_penyewaan_model->get_all_data(['name !=' => "Tidak Termasuk"]);

?>

<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo base_url('/'); ?>">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Inventaris</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="<?= site_url('dashboard') ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>


            <!-- Divider -->
            <hr class="sidebar-divider">

            <?php if ($this->session->userdata('role') == 'admin'): ?>
                <!-- Heading -->
                <div class="sidebar-heading">
                    Master Data
                </div>
                <!-- Nav Item - Tables -->
                <li class="nav-item active">
                    <a class="nav-link" href="<?= base_url('user') ?>">
                        <i class="fas fa-fw fa-users"></i>
                        <span>User</span></a>
                </li>
                <!-- Nav Item - Tables -->
                <li class="nav-item active">
                    <a class="nav-link" href="<?= base_url('kategori_barang') ?>">
                        <i class="fas fa-fw fa-table"></i>
                        <span>Kategori Barang</span></a>
                </li>
                <!-- Nav Item - Tables -->
                <li class="nav-item active">
                    <a class="nav-link" href="<?= base_url('kategori_penyewaan') ?>">
                        <i class="fas fa-fw fa-table"></i>
                        <span>Kategori Penyewaan</span></a>
                </li>
                <!-- Nav Item - Tables -->
                <li class="nav-item active">
                    <a class="nav-link" href="<?= base_url('jenis_customer') ?>">
                        <i class="fas fa-fw fa-table"></i>
                        <span>Jenis Customer</span></a>
                </li>
                <!-- Nav Item - Tables -->
                <li class="nav-item active">
                    <a class="nav-link" href="<?= base_url('supir') ?>">
                        <i class="fas fa-fw fa-table"></i>
                        <span>Supir</span></a>
                </li>
                <!-- Nav Item - Tables -->
                <li class="nav-item active">
                    <a class="nav-link" href="<?= base_url('supplier') ?>">
                        <i class="fas fa-fw fa-table"></i>
                        <span>Supplier</span></a>
                </li>
                <!-- Nav Item - Tables -->
                <li class="nav-item active">
                    <a class="nav-link" href="<?= base_url('customer') ?>">
                        <i class="fas fa-fw fa-table"></i>
                        <span>Customer</span></a>
                </li>
                <!-- Nav Item - Tables -->
                <li class="nav-item active">
                    <a class="nav-link" href="<?= base_url('barang') ?>">
                        <i class="fas fa-fw fa-table"></i>
                        <span>Barang</span></a>
                </li>


            <?php endif; ?>
            <?php if ($this->session->userdata('role') == 'pegawai'): ?>
                <!-- Heading -->
                <div class="sidebar-heading">
                    Transaksi
                </div>

                <!-- Nav Item - Tables -->
                <li class="nav-item active">
                    <a class="nav-link" href="<?= base_url('penjualan') ?>">
                        <i class="fas fa-store"></i>
                        <span>Penjualan</span></a>
                </li>
                <!-- Nav Item - Tables -->
                <li class="nav-item active">
                    <a class="nav-link" href="<?= base_url('pembelian') ?>">
                        <i class="fas fa-cart-plus"></i>

                        <span>Pembelian</span></a>
                </li>
                <!-- Nav Item - Tables -->
                <li class="nav-item active">
                    <a class="nav-link" href="<?= base_url('proses_permintaan') ?>">
                        <i class="fas fa-inbox"></i>




                        <span>Permintaan</span></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="<?= base_url('pengembalian_barang') ?>">
                        <i class="fas fa-truck fa-flip-horizontal"></i>





                        <span>Pengembalian</span></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOne"
                        aria-expanded="true" aria-controls="collapseOne">
                        <i class="fas fa-truck"></i>


                        <span>Penyewaan</span>
                    </a>
                    <div id="collapseOne" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Details:</h6>
                            <?php foreach ($datapenyewaan as $key => $data) { ?>
                                <a class="collapse-item" href="<?= base_url('penyewaan/index/' .  $data['id']) ?>"><?= $data['name'] ?></a>

                            <?php   } ?>

                        </div>
                    </div>
                </li>

            <?php endif; ?>

            <?php if ($this->session->userdata('role') == 'customer'): ?>
                <!-- Heading -->
                <div class="sidebar-heading">
                    Transaksi
                </div>
                <!-- Nav Item - Tables -->
                <li class="nav-item active">
                    <a class="nav-link" href="<?= base_url('permintaan') ?>">
                        <i class="fas fa-store"></i>
                        <span>permintaan</span></a>
                </li>
                <!-- Nav Item - Tables -->
                <li class="nav-item active">
                    <a class="nav-link" href="<?= base_url('pembelian_customer') ?>">
                        <i class="fas fa-store"></i>
                        <span>Pembelian</span></a>
                </li>
            <?php endif; ?>

            <?php if ($this->session->userdata('role') == 'pemimpin'): ?>
                <!-- Heading -->
                <div class="sidebar-heading">
                    Transaksi
                </div>

                <!-- Nav Item - Tables -->
                <li class="nav-item active">
                    <a class="nav-link" href="<?= base_url('penjualan_pemimpin') ?>">
                        <i class="fas fa-store"></i>
                        <span>Penjualan</span></a>
                </li>
                <!-- Nav Item - Tables -->
                <li class="nav-item active">
                    <a class="nav-link" href="<?= base_url('pembelian_pemimpin') ?>">
                        <i class="fas fa-cart-plus"></i>

                        <span>Pembelian</span></a>
                </li>
                <!-- Nav Item - Tables -->
                <li class="nav-item active">
                    <a class="nav-link" href="<?= base_url('permintaan_pemimpin') ?>">
                        <i class="fas fa-inbox"></i>
                        <span>Permintaan</span></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOne"
                        aria-expanded="true" aria-controls="collapseOne">
                        <i class="fas fa-truck"></i>


                        <span>Penyewaan</span>
                    </a>
                    <div id="collapseOne" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Details:</h6>
                            <?php foreach ($datapenyewaan as $key => $data) { ?>
                                <a class="collapse-item" href="<?= base_url('penyewaan_pemimpin/index/' .  $data['id']) ?>"><?= $data['name'] ?></a>

                            <?php   } ?>

                        </div>
                    </div>
                </li>

            <?php endif; ?>

            <!-- Divider -->
            <hr class=" sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $this->session->userdata('name') ?></span>
                                <img class="img-profile rounded-circle" src="<?= base_url('assets/img/undraw_profile.svg') ?>">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="<?= base_url('profile'); ?>">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <div class="container-fluid">
                    <!-- Content goes here -->