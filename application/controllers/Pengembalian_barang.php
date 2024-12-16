<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Pengembalian_barang extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Barang_model');
        $this->load->model('Pembelian_model');
        $this->load->model('Supplier_model');
        $this->load->model('Customer_model');
        $this->load->model('Kat_penyewaan_model');
        $this->load->model('Pembelian_customer_model');
        $this->load->model('Penjualan_model');
        $this->load->model('Supir_model');
        $this->load->model('Pengembalian_barang_model');

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

            $data['data'] = $this->Pengembalian_barang_model->get_filtered_data($start_date, $end_date);
        } else {
            $data['data'] = $this->Pengembalian_barang_model->get_all_data();
        }


        $this->load->view('pengembalian_barang/index', $data);
    }

    public function proses($id)
    {
        $data = [

            'supir' => $this->Supir_model->get_all_data(),
            'pengembalian' => $this->Pengembalian_barang_model->get_data_by_id($id),
        ];

        if (!$data['pengembalian']) {
            $this->session->set_flashdata('error', 'Pengembalian not found!');
            redirect('pengembalian_barang');
        }

        if ($this->input->post()) {
            // Validasi input

            $this->form_validation->set_rules('id_supir', 'Supir', 'trim');
            $this->form_validation->set_rules('status', 'status', 'required|trim');
            $this->form_validation->set_rules('tanggal', 'Tanggal', 'required|trim');
            $this->form_validation->set_rules('volume', 'Volume', 'required|trim');

            $barang = $this->Barang_model->get_data_by_id($this->input->post('id_barang'));


            if ($this->form_validation->run()) {

                // Data untuk update pembelian
                $update_data = [
                    'id_supir' => $this->input->post('id_supir'),
                    'tanggal' => $this->input->post('tanggal'),
                    'volume' => $this->input->post('volume'),
                    'status' => $this->input->post('status'),
                ];

                $this->Pengembalian_barang_model->update_data(['id' => $id], $update_data);
                if ($update_data['status'] == 2) {
                    $pembelian = $this->Pembelian_customer_model->get_data_by_id($data['pengembalian']['id_pc']);

                    if ($data['pengembalian']['sisa'] != 0) {
                        $status = 3;
                    } else {
                        $status = 2;
                    }
                    $update_data = [
                        'jumlah_keluar' => $pembelian['jumlah_keluar'] + $data['pengembalian']['stok_dikembalikan'],
                        'status' =>   $status,
                        'sisa' => $data['pengembalian']['sisa'],
                    ];

                    $ids = ['id' => $data['pengembalian']['id_pc']];

                    $this->Pembelian_customer_model->update_data($ids, $update_data);

                    $penjualan = $this->Penjualan_model->get_data_by_id($pembelian['id_penjualan']);

                    $barang = $this->Barang_model->get_data_by_id($penjualan['id_barang']);
                    $barangmasuk = $data['pengembalian']['stok_dikembalikan'] + $barang['jumlah_masuk'];
                    $stok_baru = $barang['jumlah_awal'] +  $barangmasuk - $barang['jumlah_keluar'];


                    $barang_update = [
                        'stok' => $stok_baru,  // Update stok
                        'jumlah_masuk' => $barangmasuk,  // Update jumlah_keluar jika perlu
                    ];

                    // Mengupdate data barang di database
                    $conditions = ['id' => $penjualan['id_barang']];
                    $this->Barang_model->update_data($conditions, $barang_update);
                }



                $this->session->set_flashdata('success', 'Pengembalian updated successfully!');
                redirect('pengembalian_barang');
            } else {
                $data['errors'] = validation_errors();
            }
        }

        $this->load->view('pengembalian_barang/proses', $data);
    }

    public function create()
    {
        $data = [
            'customer' => $this->Customer_model->get_all_data(),
            'barang' => $this->Barang_model->get_all_data(['stok !=' => 0]),
            'supir' => $this->Supir_model->get_all_data(),

        ];



        if ($this->input->post()) {
            // Validasi input


            $this->form_validation->set_rules('id_barang', 'Barang', 'required|trim');
            $this->form_validation->set_rules('id_customer', 'Customer', 'required|trim');

            $this->form_validation->set_rules('sisa', 'sisa', 'required|trim');
            $this->form_validation->set_rules('stok_dikembalikan', 'Stok Dikembalikan', 'required|trim');

            $this->form_validation->set_rules('status', 'status', 'required|trim');
            $this->form_validation->set_rules('tanggal', 'Tanggal', 'required|trim');
            $this->form_validation->set_rules('volume', 'Volume', 'required|trim');

            $barang = $this->Barang_model->get_data_by_id($this->input->post('id_barang'));


            if ($this->form_validation->run()) {

                // Data untuk update pembelian
                $update_data = [

                    'tanggal' => $this->input->post('tanggal'),
                    'id_barang' => $this->input->post('id_barang'),
                    'id_customer' => $this->input->post('id_customer'),
                    'sisa' => $this->input->post('sisa'),
                    'stok_dikembalikan' => $this->input->post('stok_dikembalikan'),
                    'status' => $this->input->post('status'),
                    'volume' => $this->input->post('volume'),

                ];

                $this->Pengembalian_barang_model->create_data($update_data);
                if ($update_data['status'] == 2) {

                    $barang = $this->Barang_model->get_data_by_id($this->input->post('id_barang'));
                    $barangmasuk = $this->input->post('stok_dikembalikan') + $barang['jumlah_masuk'];
                    $stok_baru = $barang['jumlah_awal'] +  $barangmasuk - $barang['jumla_keluar'];

                    $barang_update = [
                        'stok' => $stok_baru,  // Update stok
                        'jumlah_masuk' => $barangmasuk,  // Update jumlah_keluar jika perlu
                    ];
                    // Mengupdate data barang di database
                    $conditions = ['id' => $barang['id']];
                    $this->Barang_model->update_data($conditions, $barang_update);
                }



                $this->session->set_flashdata('success', 'Pengembalian Create successfully!');
                redirect('pengembalian_barang');
            } else {
                $data['errors'] = validation_errors();
            }
        }

        $this->load->view('pengembalian_barang/create', $data);
    }
}
