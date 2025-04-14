<!DOCTYPE html>
<html>

<head>
    <title>Laporan Penyewaan</title>
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
    <h1>Laporan Penyewaan (<?= $kategori['name'] ?>) </h1>
    <?php if ($start_date && $end_date): ?>
    <h3>Periode: <?= date('d-M-Y', strtotime($start_date)) ?> s/d <?= date('d-M-Y', strtotime($end_date)) ?></h3>
    <?php else: ?>
    <h3>Semua Data Penyewaan</h3>
    <?php endif; ?>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Nama Customer</th>
                <th>Nama Supir</th>
                <th>Jumlah Awal</th>
                <th>Jumlah Masuk</th>
                <th>Jumlah Keluar</th>
                <th>Stok</th>
                <th>Tanggal Sewa</th>
                <th>Status</th>
                <th>Tanggal Selesai</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($data as $d):
                $barang = $this->Barang_model->get_data_by_id($d['id_barang']);
                $barangname = $barang ? $barang['name'] : 'Unknown';
                $supir = $this->Supir_model->get_data_by_id($d['id_supir']);
                $supirname = $supir ? $supir['nama'] : 'Unknown';
                $customer = $this->Customer_model->get_data_by_id($d['id_customer']);
                $customername = $customer ? $customer['nama'] : 'Unknown';
                ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($barang['kode'], ENT_QUOTES, 'UTF-8') ?> /
                    <?= htmlspecialchars($barangname, ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($supirname, ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($customername, ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($d['jumlah_awal'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($d['jumlah_masuk'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($d['jumlah_keluar'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($d['stok'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= date('d-M-Y', strtotime($d['tanggal'])) ?></td>

                <td>
                    <?php if ($d['status'] == 1) {
                            echo 'Disewa';
                        } elseif ($d['status'] == 2) {
                            echo 'Selesai Sewa';
                        }
                        ?>
                </td>
                <td>
                    <?php if ($d['tanggal_selesai']) { ?>
                    <?= date('d-M-Y', strtotime($d['tanggal_selesai'])) ?>
                    <?php }
                        ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


</body>

</html>