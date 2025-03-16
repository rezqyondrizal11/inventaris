<?php $this->load->view('layout/header'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Penjualan</h1>

    <!-- Filter Form -->
    <form method="GET" action="<?= site_url('penjualan_pemimpin') ?>" class="mb-4">
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
                <a href="<?= site_url('penjualan_pemimpin') ?>" class="btn btn-secondary">Reset</a>
            </div>
        </div>
    </form>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Penjualan List</h6>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <?php foreach ($jeniscustomer as $index => $jenis) : ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $index == 0 ? 'active' : '' ?>" id="tab-<?= $jenis["id"] ?>" data-toggle="tab" href="#content-<?= $jenis["id"] ?>" role="tab">
                            <?= $jenis["name"] ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="tab-content mt-3" id="myTabContent">
                <?php foreach ($jeniscustomer as $index => $jenis) : ?>
                    <?php
                    $penjualan = $this->Penjualan_model->get_all_data_jc($jenis['id'], $start_date, $end_date);
                    ?>
                    <div class="tab-pane fade <?= $index == 0 ? 'show active' : '' ?>" id="content-<?= $jenis["id"] ?>" role="tabpanel">

                        <a href="<?= site_url('penjualan_pemimpin/print_pdf?jenis=' . $jenis["id"] . '&start_date=' . $this->input->get('start_date') . '&end_date=' . $this->input->get('end_date')) ?>" class="btn btn-danger mb-4" target="_blank">Print PDF</a>
                        <a href="<?= site_url('penjualan_pemimpin/export_excel') ?>?jenis=<?= $jenis["id"] ?>&start_date=<?= $this->input->get('start_date') ?>&end_date=<?= $this->input->get('end_date') ?>"
                            class="btn btn-success mb-4">Export Excel</a>
                        <table class="table table-bordered" width="100%" cellspacing="0">
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

                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($penjualan) :
                                    $no = 1;
                                    foreach ($penjualan as $d) : ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= htmlspecialchars($d['barang_kode'] ?? '') ?> / <?= htmlspecialchars($d['barang_nama'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($d['customer_nama'] ?? 'Unknown') ?></td>
                                            <td><?= htmlspecialchars($d['supir_nama'] ?? 'Unknown') ?></td>
                                            <td><?= htmlspecialchars($d['jumlah_awal'] ?? 0) ?></td>
                                            <td><?= htmlspecialchars($d['jumlah_keluar'] ?? 0) ?></td>
                                            <td><?= htmlspecialchars($d['stok'] ?? 0) ?></td>
                                            <td><?= date('d-M-Y', strtotime($d['tanggal'] ?? '')) ?></td>

                                        </tr>
                                    <?php endforeach;
                                else : ?>
                                    <tr>
                                        <td colspan="9" class="text-center">Tidak ada data</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>
            </div>


        </div>
    </div>
</div>

</div>
<?php $this->load->view('layout/footer'); ?>