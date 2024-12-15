<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Pembelian extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Barang_model');
        $this->load->model('Pembelian_model');
        $this->load->model('Supplier_model');
        $this->load->model('Kat_penyewaan_model');
        // Memuat library form_validation
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

        if ($start_date && $end_date) {

            $data['data'] = $this->Pembelian_model->get_filtered_data($start_date, $end_date);
        } else {
            $data['data'] = $this->Pembelian_model->get_all_data();
        }


        $this->load->view('pembelian/index', $data);
    }

    public function create()
    {
        $data = [];
        $data['barang'] = $this->Barang_model->get_all_data(['stok !=' => 0]);
        $data['supplier'] = $this->Supplier_model->get_all_data();

        if ($this->input->post()) {
            // Validasi input
            $this->form_validation->set_rules('id_barang', 'Barang', 'required|trim');
            $this->form_validation->set_rules('id_supplier', 'Supplier', 'required|trim');
            $this->form_validation->set_rules('jumlah_masuk', 'Jumlah Masuk', 'required|trim');
            $this->form_validation->set_rules('tanggal', 'Tanggal', 'required|trim');
            $barang = $this->Barang_model->get_data_by_id($this->input->post('id_barang'));


            if ($this->form_validation->run()) {
                $barangmasuk = $barang['jumlah_masuk'] + $this->input->post('jumlah_masuk');
                $stok_baru = $barang['stok'] + $this->input->post('jumlah_masuk');

                $data = [
                    'id_barang' => $this->input->post('id_barang'),
                    'id_supplier' => $this->input->post('id_supplier'),
                    'jumlah_awal' =>  $barang['stok'],
                    'jumlah_masuk' =>  $this->input->post('jumlah_masuk'),
                    'jumlah_keluar' => 0,
                    'stok' =>  $barang['stok'] + $this->input->post('jumlah_masuk'),
                    'tanggal' => $this->input->post('tanggal'),
                ];

                // Menyimpan data pembelian
                $this->Pembelian_model->create_data($data);

                // Update stok barang di tabel barang
                $barang_update = [
                    'stok' => $stok_baru,  // Update stok
                    'jumlah_masuk' => $barangmasuk,  // Update jumlah_keluar jika perlu
                ];

                // Mengupdate data barang di database
                $conditions = ['id' => $this->input->post('id_barang')];
                $this->Barang_model->update_data($conditions, $barang_update);

                $this->session->set_flashdata('success', 'Pembelian created successfully!');
                redirect('pembelian');
            } else {
                $data['errors'] = validation_errors();
            }
        }

        $this->load->view('pembelian/create', $data);
    }


    public function edit($id)
    {
        $data = [
            'barang' => $this->Barang_model->get_all_data(['stok !=' => 0]),
            'supplier' => $this->Supplier_model->get_all_data(),
            'pembelian' => $this->Pembelian_model->get_data_by_id($id),
        ];

        if (!$data['pembelian']) {
            $this->session->set_flashdata('error', 'Pembelian not found!');
            redirect('pembelian');
        }

        if ($this->input->post()) {
            // Validasi input
            $this->form_validation->set_rules('id_barang', 'Barang', 'required|trim');
            $this->form_validation->set_rules('id_supplier', 'Supplier', 'required|trim');
            $this->form_validation->set_rules('jumlah_masuk', 'Jumlah Masuk', 'required|trim');
            $this->form_validation->set_rules('tanggal', 'Tanggal', 'required|trim');
            $barang = $this->Barang_model->get_data_by_id($this->input->post('id_barang'));

            $jumlah_masuk_post = $this->input->post('jumlah_masuk');
            $jumlah_masuk_awal = $data['pembelian']['jumlah_masuk'];

            if ($jumlah_masuk_post > $jumlah_masuk_awal) {
                // Jumlah masuk bertambah
                $masuk = $jumlah_masuk_post - $jumlah_masuk_awal;
                $stok_baru = $barang['stok'] + $masuk;
                $barangmasuk = $barang['jumlah_masuk'] + $masuk;
            } elseif ($jumlah_masuk_post < $jumlah_masuk_awal) {
                // Jumlah masuk berkurang
                $masuk = $jumlah_masuk_awal - $jumlah_masuk_post;
                $stok_baru = $barang['stok'] - $masuk;
                $barangmasuk = $barang['jumlah_masuk'] - $masuk;
            } else {
                // Jumlah masuk tidak berubah
                $stok_baru = $barang['stok'];
                $barangmasuk = $barang['jumlah_masuk'];
            }

            if ($this->form_validation->run()) {
                $stok = $data['pembelian']['jumlah_awal'] + $jumlah_masuk_post;

                // Data untuk update pembelian
                $update_data = [
                    'id_barang' => $this->input->post('id_barang'),
                    'id_supplier' => $this->input->post('id_supplier'),

                    'jumlah_awal' => $data['pembelian']['jumlah_awal'],
                    'jumlah_keluar' => 0,
                    'jumlah_masuk' => $jumlah_masuk_post,
                    'stok' => $stok,
                    'tanggal' => $this->input->post('tanggal'),
                ];

                // print_r($stok);
                // die;
                $this->Pembelian_model->update_data(['id' => $id], $update_data);

                // Update stok barang
                $barang_update = [
                    'stok' => $stok_baru,
                    'jumlah_masuk' => $barangmasuk,
                ];
                $this->Barang_model->update_data(['id' => $this->input->post('id_barang')], $barang_update);

                // Update data terkait
                $this->update_related_data($id, $barang['id'], $stok);

                $this->session->set_flashdata('success', 'Pembelian updated successfully!');
                redirect('pembelian');
            } else {
                $data['errors'] = validation_errors();
            }
        }

        $this->load->view('pembelian/edit', $data);
    }

    public function delete($id)
    {
        $pembelian = $this->Pembelian_model->get_data_by_id($id);
        $barang = $this->Barang_model->get_data_by_id($pembelian['id_barang']);
        $masuk = $barang['jumlah_masuk'] - $pembelian['jumlah_masuk'];

        // Update stok barang
        $barang_update = [
            'jumlah_masuk' =>  $masuk,
            'stok' => $barang['jumlah_awal'] + $masuk - $barang['jumlah_akhir'],
        ];

        $this->Barang_model->update_data(['id' =>  $pembelian['id_barang']], $barang_update);

        $related_data = $this->Pembelian_model->get_data_after_id($id, $pembelian['id_barang']);
        $awal = $pembelian['jumlah_awal'];
        foreach ($related_data as $data) {
            $jumlah_awal =  $awal;
            $stok_baru = $jumlah_awal + $data['jumlah_masuk'];

            $this->Pembelian_model->update_data(
                ['id' => $data['id']],
                ['jumlah_awal' => $jumlah_awal, 'stok' => $stok_baru]
            );

            $awal = $stok_baru;
        }

        $this->Pembelian_model->delete_data(['id' => $id]);
        redirect('pembelian');
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
        $html = $this->load->view('pembelian/print_pdf', $data, TRUE);

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
            $data = $this->Penjualan_model->get_filtered_data($start_date, $end_date);
            $date_range = 'Periode_' . date('d-M-Y', strtotime($start_date)) . '_sampai_' . date('d-M-Y', strtotime($end_date));
        } else {
            $data = $this->Penjualan_model->get_all_data();
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
        $sheet->setCellValue('B4', 'Nama Barang');
        $sheet->setCellValue('C4', 'Nama Supplier');
        $sheet->setCellValue('D4', 'Jumlah Awal');
        $sheet->setCellValue('E4', 'Jumlah Masuk');
        $sheet->setCellValue('F4', 'Jumlah Keluar');
        $sheet->setCellValue('G4', 'Stok');
        $sheet->setCellValue('H4', 'Tanggal Jual');

        // Fill Data
        $row = 5;
        $no = 1;
        foreach ($data as $d) {
            $barang = $this->Barang_model->get_data_by_id($d['id_barang']);
            $barangname = $barang ? $barang['name'] : 'Unknown';
            $supplier = $this->Supplier_model->get_data_by_id($d['id_supplier']);
            $suppliername = $supplier ? $supir['nama'] : 'Unknown';


            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, htmlspecialchars($barang['kode'], ENT_QUOTES, 'UTF-8') . ' / ' . htmlspecialchars($barangname, ENT_QUOTES, 'UTF-8'));
            $sheet->setCellValue('C' . $row, htmlspecialchars($suppliername, ENT_QUOTES, 'UTF-8'));
            $sheet->setCellValue('D' . $row, htmlspecialchars($d['jumlah_awal'], ENT_QUOTES, 'UTF-8'));
            $sheet->setCellValue('E' . $row, htmlspecialchars($d['jumlah_masuk'], ENT_QUOTES, 'UTF-8'));
            $sheet->setCellValue('F' . $row, htmlspecialchars($d['jumlah_keluar'], ENT_QUOTES, 'UTF-8'));
            $sheet->setCellValue('G' . $row, htmlspecialchars($d['stok'], ENT_QUOTES, 'UTF-8'));
            $sheet->setCellValue('H' . $row, date('d-M-Y', strtotime($d['tanggal'])));
            $row++;
        }

        // Set Auto Size for columns
        foreach (range('A', 'H') as $col) {
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
    private function update_related_data($id, $idbarang, $stok_awal_baru)
    {
        $related_data = $this->Pembelian_model->get_data_after_id($id, $idbarang);

        foreach ($related_data as $data) {
            $jumlah_awal_baru = $stok_awal_baru;
            $stok_baru = $jumlah_awal_baru + $data['jumlah_masuk'];

            $this->Pembelian_model->update_data(
                ['id' => $data['id']],
                ['jumlah_awal' => $jumlah_awal_baru, 'stok' => $stok_baru]
            );

            $stok_awal_baru = $stok_baru;
        }
    }
}
