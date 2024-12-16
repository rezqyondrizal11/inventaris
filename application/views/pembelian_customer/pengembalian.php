<?php $this->load->view('layout/header'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Pengembalian Barang</h1>

    <!-- Tampilkan pesan error jika validasi gagal -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?= $errors ?>
        </div>
    <?php endif; ?>

    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Pengembalian Barang Form</h6>
        </div>
        <div class="card-body">
            <?php if ($pembelian['sisa'] != 0) { ?>
                <form method="post">
                    <div class="form-group">
                        <label for="name">Stok Dikembalikan</label>
                        <input type="number" max="<?= $pembelian['sisa'] ?>" class="form-control" id="jumlah_keluar" name="jumlah_keluar" value="<?= set_value('jumlah_keluar') ?>" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Proses</button>
                </form>
                <br><br>
            <?php } ?>
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Stok Dikembalian</th>
                        <th>Sisa</th>
                        <th>Supir</th>
                        <th>Tanggal Pengembalian</th>
                        <th>Status</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($pengembalian as $d):
                        $pembelianc =  $this->Pembelian_customer_model->get_data_by_id($d['id_pc']);
                        $penjualan = $this->Penjualan_model->get_data_by_id($pembelianc['id_penjualan']);

                        if ($penjualan) {
                            $barang = $this->Barang_model->get_data_by_id($penjualan['id_barang']);
                            $barangname = $barang ? $barang['name'] : 'Unknown';
                        } else {
                            $penyewaan = $this->penyewaan_model->get_data_by_id($d['id_penyewaan']);

                            $barang = $this->Barang_model->get_data_by_id($penyewaan['id_barang']);
                            $barangname = $barang ? $barang['name'] : 'Unknown';
                        }



                        $supir = $this->Supir_model->get_data_by_id($d['id_supir']);
                        $supirname = $supir ? $supir['nama'] : '';


                    ?>
                        <tr>
                            <td><?= $no++ ?></td>

                            <td><?= htmlspecialchars($barangname, ENT_QUOTES, 'UTF-8') ?></td>

                            <td><?= htmlspecialchars($d['stok_dikembalikan'], ENT_QUOTES, 'UTF-8') ?></td>

                            <td><?= htmlspecialchars($d['sisa'], ENT_QUOTES, 'UTF-8') ?></td>

                            <td><?= htmlspecialchars($supirname, ENT_QUOTES, 'UTF-8') ?></td>

                            <td><?= $d['tanggal'] ? date('d-M-Y', strtotime($d['tanggal'])) : '' ?></td>
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

                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $this->load->view('layout/footer'); ?>