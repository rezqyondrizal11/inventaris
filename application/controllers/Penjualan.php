<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Penjualan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Barang_model');
        $this->load->model('Customer_model');
        $this->load->model('Jenis_customer_model');
        $this->load->model('Penjualan_model');
        $this->load->model('Supir_model');
        $this->load->model('Pembelian_customer_model');
        $this->load->model('Kat_penyewaan_model');
        // Memuat library form_validation
        $this->load->model('Notifikasi_model');
        $this->load->library('form_validation');
        // Memuat library session
        $this->load->library('session');
        // Memeriksa apakah pengguna sudah login
        if ($this->session->userdata('role') != 'pegawai') {
            redirect('dashboard');  // Arahkan ke halaman login jika pengguna belum login
        }
    }

    public function index()
    {
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');

        $data = [
            'jeniscustomer' => $this->Jenis_customer_model->get_all_data(),
            'start_date' => $start_date,
            'end_date' => $end_date
        ];

        $this->load->view('penjualan/index', $data);
    }

    public function create()
    {
        $data = [];
        $data['barang'] = $this->Barang_model->get_all_data(['stok !=' => 0]);
        $data['customer'] = $this->Customer_model->get_all_data();
        $data['supir'] = $this->Supir_model->get_all_data();

        if ($this->input->post()) {
            // Validasi input
            $this->form_validation->set_rules('id_barang', 'Barang', 'required|trim');
            $this->form_validation->set_rules('id_supir', 'Supir', 'trim');
            $this->form_validation->set_rules('id_customer', 'Customer', 'required|trim');
            $this->form_validation->set_rules('jumlah_keluar', 'Jumlah Keluar', 'required|trim');
            $this->form_validation->set_rules('tanggal', 'Tanggal', 'required|trim');

            $barang = $this->Barang_model->get_data_by_id($this->input->post('id_barang'));

            // Check if the stock is sufficient
            if ($barang['stok'] < $this->input->post('jumlah_keluar')) {
                $this->form_validation->set_message('stok', 'Stok tidak mencukupi');
            }

            if ($this->form_validation->run()) {
                $barangkeluar = $barang['jumlah_keluar'] + $this->input->post('jumlah_keluar');

                $stok_baru = $barang['stok'] - $this->input->post('jumlah_keluar');
                $data = [
                    'id_barang' => $this->input->post('id_barang'),
                    'id_supir' => $this->input->post('id_supir'),
                    'id_customer' => $this->input->post('id_customer'),
                    'jumlah_awal' => $barang['stok'],
                    'jumlah_masuk' => 0,
                    'jumlah_keluar' => $this->input->post('jumlah_keluar'),
                    'stok' => $barang['stok'] - $this->input->post('jumlah_keluar'),
                    'tanggal' => $this->input->post('tanggal'),
                ];

                // Menyimpan data penjualan dan mendapatkan ID yang baru disimpan
                $id_penjualan = $this->Penjualan_model->create_data($data);

                $datacustomer = [
                    'id_penjualan' => $id_penjualan, // Gunakan ID penjualan yang baru
                    'id_customer' => $this->input->post('id_customer'),
                    'jumlah_masuk' => $this->input->post('jumlah_keluar'),
                    'jumlah_keluar' => 0,
                    'sisa' => $this->input->post('jumlah_keluar'),
                    'status' => 1,
                ];

                // Menyimpan data ke tabel History_pembelian_customer
                $this->Pembelian_customer_model->create_data($datacustomer);

                // Update stok barang di tabel barang
                $barang_update = [
                    'stok' => $stok_baru,  // Update stok
                    'jumlah_keluar' => $barangkeluar,  // Update jumlah_keluar jika perlu
                ];

                // Mengupdate data barang di database
                $conditions = ['id' => $this->input->post('id_barang')];
                $this->Barang_model->update_data($conditions, $barang_update);

                $this->session->set_flashdata('success', 'Penjualan created successfully!');
                redirect('Penjualan');
            } else {
                $data['errors'] = validation_errors();
            }
        }

        $this->load->view('penjualan/create', $data);
    }


    public function edit($id)
    {
        $data = [
            'barang' => $this->Barang_model->get_all_data(['stok !=' => 0]),
            'customer' => $this->Customer_model->get_all_data(),
            'supir' => $this->Supir_model->get_all_data(),
            'penjualan' => $this->Penjualan_model->get_data_by_id($id),
        ];

        if (!$data['penjualan']) {
            $this->session->set_flashdata('error', 'Penjualan not found!');
            redirect('penjualan');
        }

        if ($this->input->post()) {
            // Validasi input
            $this->form_validation->set_rules('id_barang', 'Barang', 'required|trim');
            $this->form_validation->set_rules('id_supir', 'Supir', 'trim');
            $this->form_validation->set_rules('id_customer', 'Customer', 'required|trim');
            $this->form_validation->set_rules('jumlah_keluar', 'Jumlah Keluar', 'required|trim');
            $this->form_validation->set_rules('tanggal', 'Tanggal', 'required|trim');

            $barang = $this->Barang_model->get_data_by_id($this->input->post('id_barang'));
            $jumlah_keluar_post = $this->input->post('jumlah_keluar');
            $jumlah_keluar_awal = $data['penjualan']['jumlah_keluar'];

            if ($jumlah_keluar_post > $jumlah_keluar_awal) {
                // Jumlah keluar bertambah
                $keluar = $jumlah_keluar_post - $jumlah_keluar_awal;
                $stok_baru = $barang['stok'] - $keluar;

                if ($stok_baru < 0) {
                    $this->session->set_flashdata('error', 'Stok tidak mencukupi!');
                    redirect('penjualan/edit/' . $id);
                }

                $barangkeluar = $barang['jumlah_keluar'] + $keluar;
            } elseif ($jumlah_keluar_post < $jumlah_keluar_awal) {
                // Jumlah keluar berkurang
                $keluar = $jumlah_keluar_awal - $jumlah_keluar_post;
                $stok_baru = $barang['stok'] + $keluar;
                $barangkeluar = $barang['jumlah_keluar'] - $keluar;
            } else {
                // Jumlah keluar tidak berubah
                $stok_baru = $barang['stok'];
                $barangkeluar = $barang['jumlah_keluar'];
            }

            if ($this->form_validation->run()) {
                $stok = $data['penjualan']['jumlah_awal'] - $jumlah_keluar_post;

                // Data untuk update penjualan
                $update_data = [
                    'id_barang' => $this->input->post('id_barang'),
                    'id_supir' => $this->input->post('id_supir'),
                    'id_customer' => $this->input->post('id_customer'),
                    'jumlah_awal' => $data['penjualan']['jumlah_awal'],
                    'jumlah_masuk' => 0,
                    'jumlah_keluar' => $jumlah_keluar_post,
                    'stok' => $stok,
                    'tanggal' => $this->input->post('tanggal'),
                ];

                $this->Penjualan_model->update_data(['id' => $id], $update_data);

                // Update stok barang
                $barang_update = [
                    'stok' => $stok_baru,
                    'jumlah_keluar' => $barangkeluar,
                ];
                $this->Barang_model->update_data(['id' => $this->input->post('id_barang')], $barang_update);

                // Update data terkait
                $this->update_related_data($id, $barang['id'], $stok);

                $this->session->set_flashdata('success', 'Penjualan updated successfully!');
                redirect('penjualan');
            } else {
                $data['errors'] = validation_errors();
            }
        }

        $this->load->view('penjualan/edit', $data);
    }




    public function delete($id)
    {
        $penjualan = $this->Penjualan_model->get_data_by_id($id);
        $barang = $this->Barang_model->get_data_by_id($penjualan['id_barang']);
        $keluar = $barang['jumlah_keluar'] - $penjualan['jumlah_keluar'];

        // Update stok barang
        $barang_update = [
            'jumlah_keluar' => $keluar,
            'stok' => $barang['jumlah_awal'] + $barang['jumlah_masuk'] - $keluar,
        ];
        $this->Barang_model->update_data(['id' => $penjualan['id_barang']], $barang_update);




        $related_data = $this->Penjualan_model->get_data_after_id($id, $penjualan['id_barang']);
        $awal = $penjualan['jumlah_awal'];
        foreach ($related_data as $data) {
            $jumlah_awal = $awal;
            $stok_baru = $jumlah_awal - $data['jumlah_keluar'];

            $this->Penjualan_model->update_data(
                ['id' => $data['id']],
                ['jumlah_awal' => $jumlah_awal, 'stok' => $stok_baru]
            );

            $awal = $stok_baru;
        }

        $this->Penjualan_model->delete_data(['id' => $id]);
        redirect('penjualan');
    }
    public function print_pdf()
    {
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $idjenis = $this->input->get('jenis');

        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['jenis'] = $this->Jenis_customer_model->get_data_by_id($idjenis);
        $data['penjualan'] = $this->Penjualan_model->get_all_data_jc($idjenis, $start_date, $end_date);

        // Load the mPDF library
        require_once APPPATH . '../vendor/autoload.php';
        $mpdf = new Mpdf();

        // Load the view for PDF
        $html = $this->load->view('penjualan/print_pdf', $data, TRUE);

        // Set the PDF content
        $mpdf->WriteHTML($html);

        // Output the PDF file (inline in browser or download)
        $mpdf->Output('Penjualan_Report.pdf', 'I'); // 'I' untuk view di browser, 'D' untuk download
    }
    public function export_excel()
    {
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $idjenis = $this->input->get('jenis');

        // Penamaan file berdasarkan periode atau semua data
        if ($start_date && $end_date) {
            $date_range = 'Periode_' . date('d-M-Y', strtotime($start_date)) . '_sampai_' . date('d-M-Y', strtotime($end_date));
        } else {
            $date_range = 'Semua_Data';
        }

        // Ambil data jenis customer jika idjenis ada
        $data['jenis'] = !empty($idjenis) ? $this->Jenis_customer_model->get_data_by_id($idjenis) : null;

        // Ambil data penjualan dengan filter yang sesuai
        $data['penjualan'] = $this->Penjualan_model->get_all_data_jc($idjenis, $start_date, $end_date);

        // Load PhpSpreadsheet
        require_once APPPATH . '../vendor/autoload.php';

        // Buat objek Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set properti dokumen
        $spreadsheet->getProperties()
            ->setCreator("Your Company")
            ->setLastModifiedBy("Your Company")
            ->setTitle("Laporan Penjualan")
            ->setSubject("Laporan Penjualan")
            ->setDescription("Laporan Penjualan");

        // Tambahkan header laporan
        $jenisNama = $data['jenis']['name'] ?? 'Semua Jenis Customer';
        $sheet->setCellValue('A1', 'Laporan Penjualan ' . $jenisNama);
        $sheet->setCellValue('A2', 'Tanggal: ' . ($start_date && $end_date ? "$start_date s/d $end_date" : "Semua Data"));
        $sheet->mergeCells('A1:I1');
        $sheet->mergeCells('A2:I2');
        $sheet->getStyle('A1:A2')->getFont()->setBold(true);

        // Tambahkan header tabel
        $headers = [
            'A4' => 'No',
            'B4' => 'Nama Barang',
            'C4' => 'Nama Customer',
            'D4' => 'Nama Supir',
            'E4' => 'Jumlah Awal',
            'F4' => 'Jumlah Masuk',
            'G4' => 'Jumlah Keluar',
            'H4' => 'Stok',
            'I4' => 'Tanggal Jual'
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
        }

        // Tambahkan data ke dalam tabel
        $row = 5;
        $no = 1;
        $total_stok = 0;
        foreach ($data['penjualan'] as $d) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, ($d['barang_kode'] ?? '') . ' / ' . ($d['barang_nama'] ?? 'Unknown'));
            $sheet->setCellValue('C' . $row, $d['customer_nama'] ?? 'Unknown');
            $sheet->setCellValue('D' . $row, $d['supir_nama'] ?? 'Unknown');
            $sheet->setCellValue('E' . $row, $d['jumlah_awal'] ?? 0);
            $sheet->setCellValue('F' . $row, $d['jumlah_masuk'] ?? 0);
            $sheet->setCellValue('G' . $row, $d['jumlah_keluar'] ?? 0);
            $sheet->setCellValue('H' . $row, $d['stok'] ?? 0);
            $sheet->setCellValue('I' . $row, isset($d['tanggal']) ? date('d-M-Y', strtotime($d['tanggal'])) : '');
            $total_stok += $d['stok'];
            $row++;
        }

        // Add Total Row
        $sheet->setCellValue('G' . $row, 'Total Stok');
        $sheet->setCellValue('H' . $row, $total_stok);
        $sheet->getStyle('G' . $row . ':H' . $row)->getFont()->setBold(true);

        // Set ukuran kolom agar menyesuaikan otomatis
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Simpan file Excel
        $writer = new Xlsx($spreadsheet);
        $filename = "Laporan_Penjualan_{$date_range}.xlsx";

        // Output ke browser
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"$filename\"");
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }


    private function update_related_data($id, $idbarang, $stok_awal_baru)
    {
        $related_data = $this->Penjualan_model->get_data_after_id($id, $idbarang);

        foreach ($related_data as $data) {
            $jumlah_awal_baru = $stok_awal_baru;
            $stok_baru = $jumlah_awal_baru - $data['jumlah_keluar'];

            $this->Penjualan_model->update_data(
                ['id' => $data['id']],
                ['jumlah_awal' => $jumlah_awal_baru, 'stok' => $stok_baru]
            );

            $stok_awal_baru = $stok_baru;
        }
    }
}