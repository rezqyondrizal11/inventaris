<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Penjualan_pemimpin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Barang_model');
        $this->load->model('Customer_model');
        $this->load->model('Penjualan_model');
        $this->load->model('Jenis_customer_model');


        $this->load->model('Supir_model');
        $this->load->model('Kat_penyewaan_model');
        // Memuat library form_validation
        $this->load->model('Notifikasi_model');
        $this->load->library('form_validation');
        // Memuat library session
        $this->load->library('session');
        // Memeriksa apakah pengguna sudah login
        if ($this->session->userdata('role') != 'pemimpin') {
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

        $this->load->view('penjualan_pemimpin/index', $data);
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
        $html = $this->load->view('penjualan_pemimpin/print_pdf', $data, TRUE);

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
            $row++;
        }

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
