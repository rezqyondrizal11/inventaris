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
        $this->load->model('penyewaan_model');
        $this->load->model('Customer_model');
        $this->load->model('Supir_model');
        $this->load->model('Pengembalian_barang_model');
        $this->load->model('Notifikasi_model');
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

        $customer = $this->Customer_model->get_data_by_iduser($this->session->userdata('user_id'));

        // Memanggil fungsi dari model
        $conditions2 = array(
            'id_customer' => $customer['id'] // Mengambil ID customer dari objek $customer
        );
        $data['data'] = $this->Pembelian_customer_model->get_all_data($conditions2); // Kondisi langsung

        $this->load->view('pembelian_customer/index', $data);
    }


    public function pengembalian($id)
    {
        $data = [];


        $data['pembelian'] = $this->Pembelian_customer_model->get_data_by_id($id);

        $conditions2 = array(
            'id_pc' =>  $data['pembelian']['id'] // Mengambil ID customer dari objek $customer
        );
        $data['pengembalian'] = $this->Pengembalian_barang_model->get_all_data($conditions2);;


        if (!$data['pembelian']) {
            $this->session->set_flashdata('error', 'Barang not found!');
            redirect('pembelian_customer');
        }

        if ($this->input->post()) {
            // Validasi input

            $this->form_validation->set_rules('jumlah_keluar', 'Jumlah Keluar', 'required|trim');



            if ($this->form_validation->run()) {

                $penjualan = $this->Penjualan_model->get_data_by_id($data['pembelian']['id_penjualan']);
                if ($penjualan) {
                    $barang = $this->Barang_model->get_data_by_id($penjualan['id_barang']);
                } else {
                    $penyewaan = $this->penyewaan_model->get_data_by_id($data['pembelian']['id_penyewaan']);
                    $barang = $this->Barang_model->get_data_by_id($penyewaan['id_barang']);
                }

                $update_datap = [
                    'id_pc' => $id,
                    'stok_dikembalikan' => $this->input->post('jumlah_keluar'),
                    'sisa' => $data['pembelian']['sisa'] - $this->input->post('jumlah_keluar'),
                    'id_barang' => $barang['id'],
                    'id_customer' => $data['pembelian']['id_customer'],
                    'status' => 1,
                ];

                $this->Pengembalian_barang_model->create_data($update_datap);


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
