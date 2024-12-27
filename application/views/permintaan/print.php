<!DOCTYPE html>
<html>

<head>
    <title>Invoice Permintaan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #fff;
        }

        .invoice-container {
            width: 800px;
            margin: 20px auto;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
        }

        .invoice-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .invoice-header h1 {
            margin: 0;
            font-size: 24px;
        }

        .invoice-header p {
            margin: 0;
            font-size: 14px;
            color: #555;
        }

        .invoice-details {
            margin-bottom: 20px;
        }

        .invoice-details p {
            margin: 5px 0;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .total {
            text-align: right;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #555;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <!-- Invoice Header -->
        <div class="invoice-header">
            <h1>Invoice Permintaan</h1>
            <p>Generated on: <?= date('d-m-Y') ?></p>
        </div>

        <!-- Invoice Details -->
        <div class="invoice-details">
            <p><strong>Customer Name:</strong> <?= $this->session->userdata('name') ?></p>

        </div>

        <!-- Table of Items -->
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>No Invoice</th>
                    <th>Nama Barang</th>
                    <th>Stok</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Keterangan</th>
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
                        <td><?= $d['no_invoice'] ?></td>

                        <td><?= $barangname ?></td>
                        <td><?= $d['stok'] ?></td>
                        <td><?= date('d-m-Y', strtotime($d['tanggal'])) ?></td>
                        <td>
                            <?php
                            if ($d['status'] == 1) echo 'Process';
                            elseif ($d['status'] == 2) echo 'Accepted';
                            else echo 'Decline';
                            ?>
                        </td>
                        <td><?= $d['ket'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>


    </div>
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>