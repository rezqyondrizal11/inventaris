<!DOCTYPE html>
<html>

<head>
    <title>Laporan Pembelian</title>
    <style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    table,
    th,
    td {
        border: 1px solid black;
    }

    th,
    td {
        padding: 8px;
        text-align: left;
    }

    h1,
    h3 {
        text-align: center;
    }

    .signature-table {
        width: 100%;
        margin-top: 20px;
    }

    .signature-table th {
        text-align: center;
        width: 50%;
    }
    </style>
</head>

<body>
    <h1>Laporan Pembelian</h1>
    <?php if ($start_date && $end_date): ?>
    <h3>Periode: <?= date('d-M-Y', strtotime($start_date)) ?> s/d <?= date('d-M-Y', strtotime($end_date)) ?></h3>
    <?php else: ?>
    <h3>Semua Data Pembelian</h3>
    <?php endif; ?>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Nama Supplier</th>

                <th>Jumlah Awal</th>
                <th>Jumlah Masuk</th>
                <th>Jumlah Keluar</th>
                <th>Stok</th>
                <th>Tanggal Pembelian</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($data as $d):
                $barang = $this->Barang_model->get_data_by_id($d['id_barang']);
                $barangname = $barang ? $barang['name'] : 'Unknown';
                $supplier = $this->Supplier_model->get_data_by_id($d['id_supplier']);
                $suppliername = $supplier ? $supplier['nama'] : 'Unknown';

                ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($barang['kode'], ENT_QUOTES, 'UTF-8') ?> /
                    <?= htmlspecialchars($barangname, ENT_QUOTES, 'UTF-8') ?>
                </td>
                <td><?= htmlspecialchars($suppliername, ENT_QUOTES, 'UTF-8') ?></td>

                <td><?= htmlspecialchars($d['jumlah_awal'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($d['jumlah_masuk'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($d['jumlah_keluar'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($d['stok'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= date('d-M-Y', strtotime($d['tanggal'])) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Bagian tanda tangan -->
    <!-- <table class="signature-table">
        <tr>
            <th>
                Mengetahui,
                <br>
                Pimpinan
                <br><br>
                <br><br>
                <br><br>
                (...................................)
            </th>
            <th>
                Padang, <?php // date('l d M Y') ?>
                <br>
                Penanggung Jawab
                <br><br>
                <br><br>
                <br><br>
                (...................................)
            </th>
        </tr>
    </table> -->
</body>

</html>