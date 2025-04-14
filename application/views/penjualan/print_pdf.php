<!DOCTYPE html>
<html>

<head>
    <title>Laporan Penjualan</title>
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
    <h1>Laporan Penjualan <?= $jenis['name'] ?></h1>
    <?php if ($start_date && $end_date): ?>
    <h3>Periode: <?= date('d-M-Y', strtotime($start_date)) ?> s/d <?= date('d-M-Y', strtotime($end_date)) ?></h3>
    <?php else: ?>
    <h3>Semua Data Penjualan</h3>
    <?php endif; ?>
    <table>
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
            <?php if ($penjualan):
                $no = 1;
                foreach ($penjualan as $d): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($d['barang_kode'] ?? '') ?> / <?= htmlspecialchars($d['barang_nama'] ?? '') ?>
                </td>
                <td><?= htmlspecialchars($d['customer_nama'] ?? 'Unknown') ?></td>
                <td><?= htmlspecialchars($d['supir_nama'] ?? 'Unknown') ?></td>
                <td><?= htmlspecialchars($d['jumlah_awal'] ?? 0) ?></td>
                <td><?= htmlspecialchars($d['jumlah_keluar'] ?? 0) ?></td>
                <td><?= htmlspecialchars($d['stok'] ?? 0) ?></td>
                <td><?= date('d-M-Y', strtotime($d['tanggal'] ?? '')) ?></td>

            </tr>
            <?php endforeach;
            else: ?>
            <tr>
                <td colspan="9" class="text-center">Tidak ada data</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>


</body>

</html>