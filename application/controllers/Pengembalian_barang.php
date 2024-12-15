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

            $data['data'] = $this->Pembelian_customer_model->get_filtered_data($start_date, $end_date);
        } else {
            $data['data'] = $this->Pembelian_customer_model->get_all_data();
        }


        $this->load->view('pengembalian_barang/index', $data);
    }

    public function proses($id)
    {
        $data = [

            'supir' => $this->Supir_model->get_all_data(),
            'pembelian' => $this->Pembelian_customer_model->get_data_by_id($id),
        ];

        if (!$data['pembelian']) {
            $this->session->set_flashdata('error', 'Pembelian not found!');
            redirect('pembelian');
        }

        if ($this->input->post()) {
            // Validasi input

            $this->form_validation->set_rules('id_supir', 'Supir', 'required|trim');
            $this->form_validation->set_rules('status', 'status', 'required|trim');
            $this->form_validation->set_rules('tanggal', 'Tanggal', 'required|trim');
            $barang = $this->Barang_model->get_data_by_id($this->input->post('id_barang'));


            if ($this->form_validation->run()) {

                // Data untuk update pembelian
                $update_data = [
                    'id_supir' => $this->input->post('id_supir'),
                    'tanggal' => $this->input->post('tanggal'),


                    'status' => $this->input->post('status'),
                ];


                // print_r($stok);

                // die;
                $this->Pembelian_customer_model->update_data(['id' => $id], $update_data);
                if ($update_data['status'] == 2) {
                    $penjualan = $this->Penjualan_model->get_data_by_id($data['pembelian']['id_penjualan']);

                    $barang = $this->Barang_model->get_data_by_id($penjualan['id_barang']);
                    $barangmasuk = $data['pembelian']['jumlah_keluar'] + $barang['jumlah_masuk'];
                    $stok_baru = $barang['jumlah_awal'] +  $barangmasuk - $barang['jumla_keluar'];
                    $barang_update = [
                        'stok' => $stok_baru,  // Update stok
                        'jumlah_masuk' => $barangmasuk,  // Update jumlah_keluar jika perlu
                    ];

                    // Mengupdate data barang di database
                    $conditions = ['id' => $this->input->post('id_barang')];
                    $this->Barang_model->update_data($conditions, $barang_update);
                }



                $this->session->set_flashdata('success', 'Pengembalian updated successfully!');
                redirect('pengembalian_barnag');
            } else {
                $data['errors'] = validation_errors();
            }
        }

        $this->load->view('pengembalian_barang/proses', $data);
    }
}
