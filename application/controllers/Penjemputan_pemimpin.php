<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Penjemputan_pemimpin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Barang_model');
        $this->load->model('Customer_model');
        $this->load->model('Penjemputan_model');
        $this->load->model('Supir_model');
        $this->load->model('Notifikasi_model');
        $this->load->model('Kat_penyewaan_model');
        // Memuat library form_validation
        $this->load->library('form_validation');
        // Memuat library session
        $this->load->library('session');
        // Memeriksa apakah pengguna sudah login
        if ($this->session->userdata('role') != 'pemimpin') {
            redirect('dashboard');  // Arahkan ke halaman login jika pengguna belum login
        }
    }

    public function index($id)
    {
        $data = [
            'id' => $id,
            'kategori' => $this->Kat_penyewaan_model->get_data_by_id($id),
        ];
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        if ($start_date && $end_date) {
            $data['data'] = $this->Penjemputan_model->get_filtered_data(['id_cat_sewa' => $id], $start_date, $end_date);
        } else {
            $data['data'] = $this->Penjemputan_model->get_all_data(['id_cat_sewa' => $id]);
        }
        $data['id'] = $id;
        $this->load->view('penjemputan_pemimpin/index', $data);
    }

    public function print_pdf($id)
    {
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');

        if ($start_date && $end_date) {
            $data['data'] = $this->Penjemputan_model->get_filtered_data(['id_cat_sewa' => $id], $start_date, $end_date);
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
            $data['kategori'] = $this->Kat_penyewaan_model->get_data_by_id($id);
        } else {
            $data['data'] = $this->Penjemputan_model->get_all_data(['id_cat_sewa' => $id]);
            $data['start_date'] = null;
            $data['end_date'] = null;
            $data['kategori'] = $this->Kat_penyewaan_model->get_data_by_id($id);
        }
        // Load the mPDF library
        require_once APPPATH . '../vendor/autoload.php';
        $mpdf = new Mpdf();

        // Load the view for PDF
        $html = $this->load->view('penjemputan_pemimpin/print_pdf', $data, TRUE);

        // Set the PDF content
        $mpdf->WriteHTML($html);

        // Output the PDF file (inline in browser or download)
        $mpdf->Output('Penjemputan_Report.pdf', 'I'); // 'I' untuk view di browser, 'D' untuk download
    }
    public function export_excel($id)
    {
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');

        if ($start_date && $end_date) {
            $data['data'] = $this->Penjemputan_model->get_filtered_data(['id_cat_sewa' => $id], $start_date, $end_date);
            $date_range = 'Periode_' . date('d-M-Y', strtotime($start_date)) . '_sampai_' . date('d-M-Y', strtotime($end_date));
            $data['kategori'] = $this->Kat_penyewaan_model->get_data_by_id($id);
        } else {
            $data['data'] = $this->Penjemputan_model->get_all_data(['id_cat_sewa' => $id]);
            $date_range = 'Semua_Data';
            $data['kategori'] = $this->Kat_penyewaan_model->get_data_by_id($id);
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
            ->setTitle("Laporan Penjemputan")
            ->setSubject("Laporan Penjemputan")
            ->setDescription("Laporan Penjemputan");

        // Add Header
        $sheet->setCellValue('A1', 'Laporan Penjemputan ' . '(' . $data['kategori']['name'] . ')');
        $sheet->setCellValue('A2', 'Tanggal: ' . ($start_date && $end_date ? "$start_date s/d $end_date" : "Semua Data"));
        $sheet->mergeCells('A1:J1');
        $sheet->mergeCells('A2:J2');
        $sheet->getStyle('A1:A2')->getFont()->setBold(true);

        // Add Table Headers
        $sheet->setCellValue('A4', 'No');
        $sheet->setCellValue('B4', 'Nama Barang');
        $sheet->setCellValue('C4', 'Nama Customer');
        $sheet->setCellValue('D4', 'Jumlah Awal');
        $sheet->setCellValue('E4', 'Jumlah Masuk');
        $sheet->setCellValue('F4', 'Jumlah Keluar');
        $sheet->setCellValue('G4', 'Stok');
        $sheet->setCellValue('H4', 'Tanggal Sewa');
        $sheet->setCellValue('I4', 'Status');
        $sheet->setCellValue('J4', 'Tanggal Selesai');

        // Fill Data
        $row = 5;
        $no = 1;
        $total_stok = 0;

        foreach ($data['data'] as $d) {
            $barang = $this->Barang_model->get_data_by_id($d['id_barang']);

            $barangname = $barang ? $barang['name'] : 'Unknown';

            $customer = $this->Customer_model->get_data_by_id($d['id_customer']);
            $customername = $customer ? $customer['nama'] : 'Unknown';

            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, htmlspecialchars($barang['kode'], ENT_QUOTES, 'UTF-8') . ' / ' . htmlspecialchars($barangname, ENT_QUOTES, 'UTF-8'));
            $sheet->setCellValue('C' . $row, htmlspecialchars($customername, ENT_QUOTES, 'UTF-8'));
            $sheet->setCellValue('D' . $row, htmlspecialchars($d['jumlah_awal'], ENT_QUOTES, 'UTF-8'));
            $sheet->setCellValue('E' . $row, htmlspecialchars($d['jumlah_masuk'], ENT_QUOTES, 'UTF-8'));
            $sheet->setCellValue('F' . $row, htmlspecialchars($d['jumlah_keluar'], ENT_QUOTES, 'UTF-8'));
            $sheet->setCellValue('G' . $row, htmlspecialchars($d['stok'], ENT_QUOTES, 'UTF-8'));
            $sheet->setCellValue('H' . $row, date('d-M-Y', strtotime($d['tanggal'])));

            $total_stok += $d['stok'];
            $row++;
        }
        // Add Total Row
        $sheet->setCellValue('F' . $row, 'Total Stok');
        $sheet->setCellValue('G' . $row, $total_stok);
        $sheet->getStyle('F' . $row . ':H' . $row)->getFont()->setBold(true);
        // Set Auto Size for columns
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Generate File
        $writer = new Xlsx($spreadsheet);
        $filename = "Laporan_Penjemputan_{$date_range}.xlsx";

        // Output File
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"$filename\"");
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
}