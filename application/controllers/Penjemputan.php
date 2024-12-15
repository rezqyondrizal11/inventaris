<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Penjemputan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Barang_model');
        $this->load->model('Customer_model');
        $this->load->model('Penjemputan_model');
        $this->load->model('Supir_model');
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

    public function index($id)
    {
        $data = [
            'id' =>  $id,
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
        $this->load->view('penjemputan/index', $data);
    }
    public function create($id)
    {
        $data = [];
        $data['barang'] = $this->Barang_model->get_all_data(['stok !=' => 0, 'id_penyewaan' => $id]);
        $data['customer'] = $this->Customer_model->get_all_data();
        // $data['supir'] = $this->Supir_model->get_all_data();

        if ($this->input->post()) {
            // Validasi input
            $this->form_validation->set_rules('id_barang', 'Barang', 'required|trim');
            $this->form_validation->set_rules('id_customer', 'Customer', 'required|trim');
            $this->form_validation->set_rules('jumlah_masuk', 'Jumlah Masuk', 'required|trim');
            $this->form_validation->set_rules('tanggal', 'Tanggal', 'required|trim');
            $barang = $this->Barang_model->get_data_by_id($this->input->post('id_barang'));

            if ($this->form_validation->run()) {
                $barangmasuk = $barang['jumlah_masuk'] + $this->input->post('jumlah_masuk');
                $stok_baru = $barang['stok'] + $this->input->post('jumlah_masuk');

                $data = [
                    'id_barang' => $this->input->post('id_barang'),
                    'id_customer' => $this->input->post('id_customer'),
                    'id_cat_sewa' => $id,
                    'jumlah_awal' =>  $barang['stok'],
                    'jumlah_masuk' =>  $this->input->post('jumlah_masuk'),
                    'jumlah_keluar' => 0,
                    'stok' =>  $barang['stok'] + $this->input->post('jumlah_masuk'),
                    'tanggal' => $this->input->post('tanggal'),
                ];

                // Menyimpan data penjemputan
                $this->Penjemputan_model->create_data($data);
                // Update stok barang di tabel barang
                $barang_update = [
                    'stok' => $stok_baru,  // Update stok
                    'jumlah_masuk' => $barangmasuk,  // Update jumlah_keluar jika perlu
                ];

                // Mengupdate data barang di database
                $conditions = ['id' => $this->input->post('id_barang')];
                $this->Barang_model->update_data($conditions, $barang_update);

                $this->session->set_flashdata('success', 'penjemputan created successfully!');
                redirect('penjemputan/index/' . $id);
            } else {
                $data['errors'] = validation_errors();
            }
        }

        $this->load->view('penjemputan/create', $data);
    }


    public function edit($id)
    {
        $data = [
            'barang' =>  $this->Barang_model->get_all_data(['stok !=' => 0, 'id_penyewaan' => $id]),
            'customer' => $this->Customer_model->get_all_data(),
            'supir' => $this->Supir_model->get_all_data(),
            'penjemputan' => $this->Penjemputan_model->get_data_by_id($id),
        ];

        if (!$data['penjemputan']) {
            $this->session->set_flashdata('error', 'Penjemputan not found!');
            redirect('penjemputan');
        }

        if ($this->input->post()) {
            // Validasi input


            $this->form_validation->set_rules('id_barang', 'Barang', 'required|trim');
            $this->form_validation->set_rules('id_customer', 'Customer', 'required|trim');
            $this->form_validation->set_rules('jumlah_masuk', 'Jumlah Masuk', 'required|trim');
            $this->form_validation->set_rules('tanggal', 'Tanggal', 'required|trim');
            $barang = $this->Barang_model->get_data_by_id($this->input->post('id_barang'));
            $jumlah_masuk_post = $this->input->post('jumlah_masuk');
            $jumlah_masuk_awal = $data['penjemputan']['jumlah_masuk'];

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
                $stok = $data['penjemputan']['jumlah_awal'] - $jumlah_keluar_post;

                // Data untuk update penjemputan
                $update_data = [
                    'id_barang' => $this->input->post('id_barang'),
                    'id_customer' => $this->input->post('id_customer'),
                    'jumlah_awal' => $data['pembelian']['jumlah_awal'],
                    'jumlah_keluar' => 0,
                    'jumlah_masuk' => $jumlah_masuk_post,
                    'stok' => $stok,
                    'tanggal' => $this->input->post('tanggal'),
                ];

                $this->Penjemputan_model->update_data(['id' => $id], $update_data);

                // Update stok barang
                $barang_update = [
                    'stok' => $stok_baru,
                    'jumlah_keluar' => $barangkeluar,
                ];
                $this->Barang_model->update_data(['id' => $this->input->post('id_barang')], $barang_update);

                // Update data terkait
                $this->update_related_data($id, $barang['id'], $stok);

                $this->session->set_flashdata('success', 'penjemputan updated successfully!');
                redirect('penjemputan/index/' . $id);
            } else {
                $data['errors'] = validation_errors();
            }
        }

        $this->load->view('penjemputan/edit', $data);
    }




    public function delete($id)
    {
        $penjemputan = $this->Penjemputan_model->get_data_by_id($id);
        $barang = $this->Barang_model->get_data_by_id($penjemputan['id_barang']);
        $masuk = $barang['jumlah_masuk'] - $barang['jumlah_masuk'];

        // Update stok barang
        $barang_update = [
            'jumlah_masuk' =>  $masuk,
            'stok' => $barang['jumlah_awal'] + $masuk - $barang['jumlah_akhir'],
        ];

        $this->Barang_model->update_data(['id' =>  $penjemputan['id_barang']], $barang_update);

        $related_data = $this->Penjemputan_model->get_data_after_id($id, $penjemputan['id_barang']);
        $awal = $penjemputan['jumlah_awal'];
        foreach ($related_data as $data) {
            $jumlah_awal =  $awal;
            $stok_baru = $jumlah_awal + $data['jumlah_masuk'];

            $this->Penjemputan_model->update_data(
                ['id' => $data['id']],
                ['jumlah_awal' => $jumlah_awal, 'stok' => $stok_baru]
            );

            $awal = $stok_baru;
        }

        $this->Penjemputan_model->delete_data(['id' => $id]);
        redirect('penjemputan/index/' . $id);
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
        $html = $this->load->view('penjemputan/print_pdf', $data, TRUE);

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




            $row++;
        }

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
    private function update_related_data($id, $idbarang, $stok_awal_baru)
    {
        $related_data = $this->Penjemputan_model->get_data_after_id($id, $idbarang);

        foreach ($related_data as $data) {
            $jumlah_awal_baru = $stok_awal_baru;
            $stok_baru = $jumlah_awal_baru + $data['jumlah_masuk'];

            $this->Penjemputan_model->update_data(
                ['id' => $data['id']],
                ['jumlah_awal' => $jumlah_awal_baru, 'stok' => $stok_baru]
            );

            $stok_awal_baru = $stok_baru;
        }
    }
}
