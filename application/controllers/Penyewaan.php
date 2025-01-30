<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Penyewaan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Barang_model');
        $this->load->model('Customer_model');
        $this->load->model('Penyewaan_model');
        $this->load->model('Supir_model');
        $this->load->model('Kat_penyewaan_model');
        $this->load->model('Pembelian_customer_model');
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

    public function index($id)
    {
        $data = [
            'id' =>  $id,
            'kategori' => $this->Kat_penyewaan_model->get_data_by_id($id),
        ];
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        if ($start_date && $end_date) {
            $data['data'] = $this->Penyewaan_model->get_filtered_data(['id_cat_sewa' => $id], $start_date, $end_date);
        } else {
            $data['data'] = $this->Penyewaan_model->get_all_data(['id_cat_sewa' => $id]);
        }

        $this->load->view('penyewaan/index', $data);
    }

    public function create($id)
    {
        $data = [];
        $data['barang'] = $this->Barang_model->get_all_data(['stok !=' => 0, 'id_penyewaan' => $id]);
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
                    'status' => 1,
                    'id_cat_sewa' => $id,
                    'jumlah_keluar' => $this->input->post('jumlah_keluar'),
                    'stok' =>  $barang['stok'] - $this->input->post('jumlah_keluar'),
                    'tanggal' => $this->input->post('tanggal'),
                ];


                // Menyimpan data penjualan dan mendapatkan ID yang baru disimpan
                $id_penyewaan = $this->Penyewaan_model->create_data($data);

                $datacustomer = [
                    'id_penyewaan' => $id_penyewaan, // Gunakan ID penjualan yang baru
                    'id_customer' => $this->input->post('id_customer'),
                    'jumlah_masuk' => $this->input->post('jumlah_keluar'),
                    'jumlah_keluar' =>  0,
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

                $this->session->set_flashdata('success', 'Penyewaan created successfully!');
                redirect('penyewaan/index/' . $id);
            } else {
                $data['errors'] = validation_errors();
            }
        }

        $this->load->view('penyewaan/create', $data);
    }

    public function edit($id)
    {
        $data = [
            'customer' => $this->Customer_model->get_all_data(),
            'supir' => $this->Supir_model->get_all_data(),
            'penyewaan' => $this->Penyewaan_model->get_data_by_id($id),
        ];

        // Pastikan $data['penyewaan'] tidak null sebelum mengakses properti id_cat_sewa

        $data['barang'] = $this->Barang_model->get_all_data([
            'stok !=' => 0,
            'id_penyewaan' => $data['penyewaan']['id_cat_sewa']
        ]);

        // Debugging untuk memeriksa data 'barang'



        if (!$data['penyewaan']) {
            $this->session->set_flashdata('error', 'Penyewaan not found!');
            redirect('penyewaan');
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
            $jumlah_keluar_awal = $data['penyewaan']['jumlah_keluar'];

            if ($jumlah_keluar_post > $jumlah_keluar_awal) {
                // Jumlah keluar bertambah
                $keluar = $jumlah_keluar_post - $jumlah_keluar_awal;
                $stok_baru = $barang['stok'] - $keluar;

                if ($stok_baru < 0) {
                    $this->session->set_flashdata('error', 'Stok tidak mencukupi!');
                    redirect('penyewaan/edit/' . $id);
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
                $stok = $data['penyewaan']['jumlah_awal'] - $jumlah_keluar_post;

                // Data untuk update penyewaan
                $update_data = [
                    'id_barang' => $this->input->post('id_barang'),
                    'id_supir' => $this->input->post('id_supir'),
                    'id_customer' => $this->input->post('id_customer'),
                    'jumlah_awal' => $data['penyewaan']['jumlah_awal'],
                    'jumlah_masuk' => 0,
                    'jumlah_keluar' => $jumlah_keluar_post,
                    'stok' => $stok,
                    'tanggal' => $this->input->post('tanggal'),
                ];

                $this->Penyewaan_model->update_data(['id' => $id], $update_data);

                // Update stok barang
                $barang_update = [
                    'stok' => $stok_baru,
                    'jumlah_keluar' => $barangkeluar,
                ];
                $this->Barang_model->update_data(['id' => $this->input->post('id_barang')], $barang_update);

                // Update data terkait
                $this->update_related_data($id, $barang['id'], $stok);

                $this->session->set_flashdata('success', 'Penyewaan updated successfully!');
                redirect('penyewaan/index/' .  $data['penyewaan']['id_cat_sewa']);
            } else {
                $data['errors'] = validation_errors();
            }
        }

        $this->load->view('penyewaan/edit', $data);
    }
    public function selesai($id)
    {
        $penyewaan = $this->Penyewaan_model->get_data_by_id($id);
        if (!$penyewaan) {
            $this->session->set_flashdata('error', 'Penyewaan not found!');
            redirect('dashboard');
        }

        if ($this->input->post()) {
            $this->form_validation->set_rules('tanggal_selesai', 'Tanggal', 'required|trim');

            if ($this->form_validation->run()) {
                $barang = $this->Barang_model->get_data_by_id($penyewaan['id_barang']);

                // Update penyewaan
                $this->Penyewaan_model->update_data(['id' => $id], [
                    'tanggal_selesai' => $this->input->post('tanggal_selesai'),
                    'status' => 2,
                ]);

                // Update stok barang
                $this->Barang_model->update_data(['id' => $barang['id']], [
                    'stok' => $barang['stok'] + $penyewaan['jumlah_masuk'],
                    'jumlah_masuk' => $barang['jumlah_masuk'] + $penyewaan['jumlah_masuk'],
                ]);

                $this->session->set_flashdata('success', 'Penyewaan updated successfully!');
                redirect('penyewaan/index/' . $penyewaan['id_cat_sewa']);
            }
        }

        $this->load->view('penyewaan/selesai', compact('penyewaan'));
    }

    public function delete($id)
    {
        $penyewaan = $this->Penyewaan_model->get_data_by_id($id);
        $barang = $this->Barang_model->get_data_by_id($penyewaan['id_barang']);
        $keluar = $barang['jumlah_keluar'] - $penyewaan['jumlah_keluar'];

        // Update stok barang
        $barang_update = [
            'jumlah_keluar' =>  $keluar,
            'stok' => $barang['jumlah_awal'] + $barang['jumlah_masuk'] - $keluar,
        ];
        $this->Barang_model->update_data(['id' =>  $penyewaan['id_barang']], $barang_update);

        $related_data = $this->Penyewaan_model->get_data_after_id($id, $penyewaan['id_barang']);
        $awal = $penyewaan['jumlah_awal'];
        foreach ($related_data as $data) {
            $jumlah_awal =  $awal;
            $stok_baru = $jumlah_awal - $data['jumlah_keluar'];

            $this->Penyewaan_model->update_data(
                ['id' => $data['id']],
                ['jumlah_awal' => $jumlah_awal, 'stok' => $stok_baru]
            );

            $awal = $stok_baru;
        }

        $this->Penyewaan_model->delete_data(['id' => $id]);
        redirect('penyewaan/index/' . $id);
    }
    public function print_pdf($id)
    {
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');

        if ($start_date && $end_date) {
            $data['data'] = $this->Penyewaan_model->get_filtered_data(['id_cat_sewa' => $id], $start_date, $end_date);
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
            $data['kategori'] = $this->Kat_penyewaan_model->get_data_by_id($id);
        } else {
            $data['data'] = $this->Penyewaan_model->get_all_data(['id_cat_sewa' => $id]);
            $data['start_date'] = null;
            $data['end_date'] = null;
            $data['kategori'] = $this->Kat_penyewaan_model->get_data_by_id($id);
        }
        // Load the mPDF library
        require_once APPPATH . '../vendor/autoload.php';
        $mpdf = new Mpdf();

        // Load the view for PDF
        $html = $this->load->view('Penyewaan/print_pdf', $data, TRUE);

        // Set the PDF content
        $mpdf->WriteHTML($html);

        // Output the PDF file (inline in browser or download)
        $mpdf->Output('Penyewaan_Report.pdf', 'I'); // 'I' untuk view di browser, 'D' untuk download
    }
    public function export_excel($id)
    {
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');

        if ($start_date && $end_date) {
            $data['data'] = $this->Penyewaan_model->get_filtered_data(['id_cat_sewa' => $id], $start_date, $end_date);
            $date_range = 'Periode_' . date('d-M-Y', strtotime($start_date)) . '_sampai_' . date('d-M-Y', strtotime($end_date));
            $data['kategori'] = $this->Kat_penyewaan_model->get_data_by_id($id);
        } else {
            $data['data'] = $this->Penyewaan_model->get_all_data(['id_cat_sewa' => $id]);
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
            ->setTitle("Laporan Penyewaan")
            ->setSubject("Laporan Penyewaan")
            ->setDescription("Laporan Penyewaan");

        // Add Header
        $sheet->setCellValue('A1', 'Laporan Penyewaan ' . '(' . $data['kategori']['name'] . ')');
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
        $sheet->setCellValue('I4', 'Tanggal Sewa');
        $sheet->setCellValue('J4', 'Status');
        $sheet->setCellValue('K4', 'Tanggal Selesai');

        // Fill Data
        $row = 5;
        $no = 1;
        foreach ($data['data'] as $d) {
            $barang = $this->Barang_model->get_data_by_id($d['id_barang']);
            $barangname = $barang ? $barang['name'] : 'Unknown';
            $supir = $this->Supir_model->get_data_by_id($d['id_supir']);
            $supirname = $supir ? $supir['nama'] : 'Unknown';
            $customer = $this->Customer_model->get_data_by_id($d['id_customer']);
            $customername = $customer ? $customer['nama'] : 'Unknown';
            if ($d['status'] == 1) {
                $status = 'Disewa';
            } elseif ($d['status'] == 2) {
                $status = 'Selesai Sewa';
            }
            if ($d['tanggal_selesai']) {
                $tanggalselesai = $d['tanggal_selesai'];
            } else {
                $tanggalselesai = null;
            }
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, htmlspecialchars($barang['kode'], ENT_QUOTES, 'UTF-8') . ' / ' . htmlspecialchars($barangname, ENT_QUOTES, 'UTF-8'));
            $sheet->setCellValue('C' . $row, htmlspecialchars($customername, ENT_QUOTES, 'UTF-8'));
            $sheet->setCellValue('D' . $row, htmlspecialchars($supirname, ENT_QUOTES, 'UTF-8'));
            $sheet->setCellValue('E' . $row, htmlspecialchars($d['jumlah_awal'], ENT_QUOTES, 'UTF-8'));
            $sheet->setCellValue('F' . $row, htmlspecialchars($d['jumlah_masuk'], ENT_QUOTES, 'UTF-8'));
            $sheet->setCellValue('G' . $row, htmlspecialchars($d['jumlah_keluar'], ENT_QUOTES, 'UTF-8'));
            $sheet->setCellValue('H' . $row, htmlspecialchars($d['stok'], ENT_QUOTES, 'UTF-8'));
            $sheet->setCellValue('I' . $row, date('d-M-Y', strtotime($d['tanggal'])));
            $sheet->setCellValue('J' . $row, htmlspecialchars($status, ENT_QUOTES, 'UTF-8'));
            $sheet->setCellValue('K' . $row, htmlspecialchars($tanggalselesai, ENT_QUOTES, 'UTF-8'));



            $row++;
        }

        // Set Auto Size for columns
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Generate File
        $writer = new Xlsx($spreadsheet);
        $filename = "Laporan_Penyewaan_{$date_range}.xlsx";

        // Output File
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"$filename\"");
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
    private function update_related_data($id, $idbarang, $stok_awal_baru)
    {
        $related_data = $this->Penyewaan_model->get_data_after_id($id, $idbarang);

        foreach ($related_data as $data) {
            $jumlah_awal_baru = $stok_awal_baru;
            $stok_baru = $jumlah_awal_baru - $data['jumlah_keluar'];

            $this->Penyewaan_model->update_data(
                ['id' => $data['id']],
                ['jumlah_awal' => $jumlah_awal_baru, 'stok' => $stok_baru]
            );

            $stok_awal_baru = $stok_baru;
        }
    }
}
