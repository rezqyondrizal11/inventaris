<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pembelian_customer extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Kat_penyewaan_model');
        $this->load->model('Penjualan_model');
        $this->load->model('Barang_model');

        $this->load->model('Pembelian_customer_model');
        // Memuat library form_validation
        $this->load->library('form_validation');
        // Memuat library session
        $this->load->library('session');
        // Memeriksa apakah pengguna sudah login
        if ($this->session->userdata('role') != 'customer') {
            redirect('dashboard');  // Arahkan ke halaman login jika pengguna belum login
        }
    }

    public function index()
    {
        $data['data'] = $this->Pembelian_customer_model->get_all_data();
        $this->load->view('pembelian_customer/index', $data);
    }


    public function pengembalian($id)
    {
        $data = [];


        $data['pengembalian'] = $this->Pembelian_customer_model->get_data_by_id($id);

        if (!$data['pengembalian']) {
            $this->session->set_flashdata('error', 'Barang not found!');
            redirect('pembelian_customer');
        }

        if ($this->input->post()) {
            // Validasi input

            $this->form_validation->set_rules('jumlah_keluar', 'Jumlah Keluar', 'required|trim');



            if ($this->form_validation->run()) {
                $update_data = [
                    'jumlah_keluar' => $this->input->post('jumlah_keluar'),
                    'status' => 1,
                    'sisa' => $data['pengembalian']['sisa'] - $this->input->post('jumlah_keluar'),
                ];

                $id = ['id' => $id];
                $this->Pembelian_customer_model->update_data($id, $update_data);

                $this->session->set_flashdata('success', 'Pengembalian Barang updated successfully!');
                redirect('pembelian_customer');
            } else {
                $data['errors'] = validation_errors();
            }
        }

        $this->load->view('pembelian_customer/pengembalian', $data);
    }




    public function delete($id)
    {
        $id = ['id' => $id];
        $this->Barang_model->delete_data($id);
        redirect('Barang');
    }
}
