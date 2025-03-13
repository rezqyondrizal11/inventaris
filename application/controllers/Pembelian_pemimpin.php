<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Pembelian_pemimpin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Barang_model');
        $this->load->model('Pembelian_model');
        $this->load->model('Supplier_model');
        $this->load->model('Kat_penyewaan_model');
        $this->load->model('penyewaan_model');
        $this->load->model('Notifikasi_model');

        // Memuat library form_validation
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

        if ($start_date && $end_date) {

            $data['data'] = $this->Pembelian_model->get_filtered_data($start_date, $end_date);
        } else {
            $data['data'] = $this->Pembelian_model->get_all_data();
        }


        $this->load->view('pembelian_pemimpin/index', $data);
    }


    public function print_pdf()
    {
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');

        if ($start_date && $end_date) {
            $data['data'] = $this->Pembelian_model->get_filtered_data($start_date, $end_date);
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
        } else {
            $data['data'] = $this->Pembelian_model->get_all_data();
            $data['start_date'] = null;
            $data['end_date'] = null;
        }
        // Load the mPDF library
        require_once APPPATH . '../vendor/autoload.php';
        $mpdf = new Mpdf();

        // Load the view for PDF
        $html = $this->load->view('pembelian_pemimpin/print_pdf', $data, TRUE);

        // Set the PDF content
        $mpdf->WriteHTML($html);

        // Output the PDF file (inline in browser or download)
        $mpdf->Output('Pembelian_Report.pdf', 'I'); // 'I' untuk view di browser, 'D' untuk download
    }
    public function export_excel()
    {
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');

        if ($start_date && $end_date) {
            $data = $this->Pembelian_model->get_filtered_data($start_date, $end_date);
            $date_range = 'Periode_' . date('d-M-Y', strtotime($start_date)) . 'sampai' . date('d-M-Y', strtotime($end_date));
        } else {
            $data = $this->Pembelian_model->get_all_data();
            $date_range = 'Semua_Data';
        }

        // Load PhpSpreadsheet
        require_once APPPATH . '../vendor/autoload.php';


        // Create Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator("Your Company")
            ->setLastModifiedBy("Your Company")
            ->setTitle("Laporan Pembelian")
            ->setSubject("Laporan Pembelian")
            ->setDescription("Laporan Pembelian");

        // Add Header
        $sheet->setCellValue('A1', 'Laporan Pembelian');
        $sheet->setCellValue('A2', 'Tanggal: ' . ($start_date && $end_date ? "$start_date s/d $end_date" : "Semua Data"));
        $sheet->mergeCells('A1:J1');
        $sheet->mergeCells('A2:J2');
        $sheet->getStyle('A1:A2')->getFont()->setBold(true);

        // Add Table Headers
        $sheet->setCellValue('A4', 'No');
        $sheet->setCellValue('B4', 'No Invoice');
        $sheet->setCellValue('C4', 'Nama Barang');
        $sheet->setCellValue('D4', 'Nama Supplier');

        $sheet->setCellValue('E4', 'Jumlah Awal');
        $sheet->setCellValue('F4', 'Jumlah Masuk');
        $sheet->setCellValue('G4', 'Jumlah Keluar');
        $sheet->setCellValue('H4', 'Stok');
        $sheet->setCellValue('I4', 'Tanggal Beli');

        // Fill Data
        $row = 5;
        $no = 1;
        foreach ($data as $d) {


            $barang = $this->Barang_model->get_data_by_id($d['id_barang']);
            $barangname = $barang ? $barang['name'] : 'Unknown';
            $supplier = $this->Supplier_model->get_data_by_id($d['id_supplier']);
            $suppliername = $supplier ? $supplier['nama'] : 'Unknown';

            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, htmlspecialchars($d['no_invoice'], ENT_QUOTES, 'UTF-8'));

            $sheet->setCellValue('C' . $row, htmlspecialchars($barangname, ENT_QUOTES, 'UTF-8') . ' / ' . htmlspecialchars($barangname, ENT_QUOTES, 'UTF-8'));
            $sheet->setCellValue('D' . $row, htmlspecialchars($suppliername, ENT_QUOTES, 'UTF-8'));
            $sheet->setCellValue('E' . $row, htmlspecialchars($d['jumlah_awal'], ENT_QUOTES, 'UTF-8'));
            $sheet->setCellValue('F' . $row, htmlspecialchars($d['jumlah_masuk'], ENT_QUOTES, 'UTF-8'));
            $sheet->setCellValue('G' . $row, htmlspecialchars($d['jumlah_keluar'], ENT_QUOTES, 'UTF-8'));
            $sheet->setCellValue('H' . $row, htmlspecialchars($d['stok'], ENT_QUOTES, 'UTF-8'));
            $sheet->setCellValue('I' . $row, date('d-M-Y', strtotime($d['tanggal'])));
            $row++;
        }

        // Set Auto Size for columns
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Generate File
        $writer = new Xlsx($spreadsheet);
        $filename = "Laporan_Pembelian_{$date_range}.xlsx";

        // Output File
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"$filename\"");
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
}
