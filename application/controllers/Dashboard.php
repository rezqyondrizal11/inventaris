<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Memuat library session
        $this->load->library('session');
        $this->load->model('Kat_penyewaan_model');
        $this->load->model('Barang_model');
        $this->load->model('Supplier_model');
        $this->load->model('Pembelian_model');
        $this->load->model('Notifikasi_model');
        $this->load->model('Kat_barang_model');
        $this->load->model('kat_penyewaan_model');
        $this->load->model('jenis_customer_model');
        $this->load->model('supir_model');
        $this->load->model('supplier_model');
        $this->load->model('customer_model');
        $this->load->model('barang_model');
        $this->load->model('penjualan_model');
        $this->load->model('pembelian_model');
        $this->load->model('permintaan_model');
        $this->load->model('pengembalian_barang_model');
        $this->load->model('penyewaan_model');
        $this->load->model('Customer_model');
        $this->load->model('Permintaan_model');
        $this->load->model('pembelian_customer_model');


        // Memeriksa apakah pengguna sudah login
        if (!$this->session->userdata('user_id')) {
            redirect('login');  // Arahkan ke halaman login jika pengguna belum login
        }
    }

    public function index()
    {
        // Ambil data pengguna berdasarkan ID
        $user_id = $this->session->userdata('user_id');
        $this->load->model('User_model');


        // Muat tampilan dashboard
        if ($this->session->userdata('role') == 'admin') {

            $data = [
                'user' => $this->User_model->get_user_by_id($user_id),
                'total_user' => $this->User_model->count_all(),
                'total_kat_barang' => $this->Kat_barang_model->count_all(),
                'total_kat_penyewaan' => $this->kat_penyewaan_model->count_all(),
                'total_jenis_custumer' => $this->jenis_customer_model->count_all(),
                'total_supir' => $this->supir_model->count_all(),
                'total_supplier' => $this->supplier_model->count_all(),
                'total_customer' => $this->customer_model->count_all(),
                'total_barang' => $this->barang_model->count_all(),

            ];

            $this->load->view('home/dashboard_admin', $data);
        } elseif ($this->session->userdata('role') == 'pegawai') {
            $data = [
                'user' => $this->User_model->get_user_by_id($user_id),
                'total_penjualan' => $this->penjualan_model->count_all(),
                'total_pembelian' => $this->pembelian_model->count_all(),
                'total_permintaan' => $this->permintaan_model->count_all(),
                'total_pengembalian' => $this->pengembalian_barang_model->count_all(),
                'katpenyewaan' => $this->kat_penyewaan_model->get_all_data(['name !=' => "Tidak Termasuk", 'status' => 1]),
            ];
            $this->load->view('home/dashboard_pegawai', $data);
        } elseif ($this->session->userdata('role') == 'customer') {
            $customer = $this->Customer_model->get_data_by_iduser($this->session->userdata('user_id'));

            $data = [
                'user' => $this->User_model->get_user_by_id($user_id),
                'total_permintaan' => $this->Permintaan_model->count_all_data_by_customer($customer['id']),
                'total_pembelian' => $this->pembelian_customer_model->count_all_data_by_customer($customer['id']),

            ];
            $this->load->view('home/dashboard_customer', $data);
        } elseif ($this->session->userdata('role') == 'pemimpin') {
            $data = [
                'user' => $this->User_model->get_user_by_id($user_id),
                'total_penjualan' => $this->penjualan_model->count_all(),
                'total_pembelian' => $this->pembelian_model->count_all(),
                'total_permintaan' => $this->permintaan_model->count_all(),
                'total_pengembalian' => $this->pengembalian_barang_model->count_all(),
                'katpenyewaan' => $this->kat_penyewaan_model->get_all_data(['name !=' => "Tidak Termasuk", 'status' => 1]),
            ];

            $this->load->view('home/dashboard_pemimpin', $data);
        }
    }
}
