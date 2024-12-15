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
        $this->load->model('Penjualan_model');
        $this->load->model('Supir_model');
        $this->load->model('Pembelian_customer_model');
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
            $data['data'] = $this->Penjualan_model->get_filtered_data($start_date, $end_date);
        } else {
            $data['data'] = $this->Penjualan_model->get_all_data();
        }

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
            $this->form_validation->set_rules('id_supir', 'Supir', 'required|trim');
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
                    'jumlah_awal' =>  $barang['stok'],
                    'jumlah_masuk' => 0,
                    'jumlah_keluar' => $this->input->post('jumlah_keluar'),
                    'stok' =>  $barang['stok'] - $this->input->post('jumlah_keluar'),
                    'tanggal' => $this->input->post('tanggal'),
                ];

                // Menyimpan data penjualan dan mendapatkan ID yang baru disimpan
                $id_penjualan = $this->Penjualan_model->create_data($data);

                $datacustomer = [
                    'id_penjualan' => $id_penjualan, // Gunakan ID penjualan yang baru
                    'id_customer' => $this->input->post('id_customer'),
                    'jumlah_masuk' => $this->input->post('jumlah_keluar'),
                    'jumlah_keluar' =>  0,
                    'sisa' => $this->input->post('jumlah_keluar'),
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
            $this->form_validation->set_rules('id_supir', 'Supir', 'required|trim');
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
            'jumlah_keluar' =>  $keluar,
            'stok' => $barang['jumlah_awal'] + $barang['jumlah_masuk'] - $keluar,
        ];
        $this->Barang_model->update_data(['id' =>  $penjualan['id_barang']], $barang_update);




        $related_data = $this->Penjualan_model->get_data_after_id($id, $penjualan['id_barang']);
        $awal = $penjualan['jumlah_awal'];
        foreach ($related_data as $data) {
            $jumlah_awal =  $awal;
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

        if ($start_date && $end_date) {
            $data['data'] = $this->Penjualan_model->get_filtered_data($start_date, $end_date);
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
        } else {
            $data['data'] = $this->Penjualan_model->get_all_data();
            $data['start_date'] = null;
            $data['end_date'] = null;
        }
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
            ->setTitle("Laporan Penjualan")
            ->setSubject("Laporan Penjualan")
            ->setDescription("Laporan Penjualan");

        // Add Header
        $sheet->setCellValue('A1', 'Laporan Penjualan');
        $sheet->setCellValue('A2', 'Tanggal: ' . ($start_date && $end_date ? "$start_date s/d $end_date" : "Semua Data"));
        $sheet->mergeCells('A1:J1');
        $sheet->mergeCells('A2:J2');
        $sheet->getStyle('A1:A2')->getFont()->setBold(true);

        // Add Table Headers
        $sheet->setCellValue('A4', 'No');
        $sheet->setCellValue('B4', 'Nama Barang');
        $sheet->setCellValue('C4', 'Nama Customer');
        $sheet->setCellValue('D4', 'Nama Supir');
        $sheet->setCellValue('E4', 'Jumlah Awal');
        $sheet->setCellValue('F4', 'Jumlah Masuk');
        $sheet->setCellValue('G4', 'Jumlah Keluar');
        $sheet->setCellValue('H4', 'Stok');
        $sheet->setCellValue('I4', 'Tanggal Jual');

        // Fill Data
        $row = 5;
        $no = 1;
        foreach ($data as $d) {
            $barang = $this->Barang_model->get_data_by_id($d['id_barang']);
            $barangname = $barang ? $barang['name'] : 'Unknown';
            $supir = $this->Supir_model->get_data_by_id($d['id_supir']);
            $supirname = $supir ? $supir['nama'] : 'Unknown';
            $customer = $this->Customer_model->get_data_by_id($d['id_customer']);
            $customername = $customer ? $customer['nama'] : 'Unknown';

            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, htmlspecialchars($barang['kode'], ENT_QUOTES, 'UTF-8') . ' / ' . htmlspecialchars($barangname, ENT_QUOTES, 'UTF-8'));
            $sheet->setCellValue('C' . $row, htmlspecialchars($customername, ENT_QUOTES, 'UTF-8'));
            $sheet->setCellValue('D' . $row, htmlspecialchars($supirname, ENT_QUOTES, 'UTF-8'));
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
        $filename = "Laporan_Penjualan_{$date_range}.xlsx";

        // Output File
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
