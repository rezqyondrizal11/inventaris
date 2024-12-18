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
        $this->load->model('Permintaan_model');
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

        $data = [
            'barang' => $this->Barang_model->get_all_data(),
            'supplier' => $this->Supplier_model->get_all_data(),
            'pembelian' => $this->Pembelian_model->get_all_data(),
            'permintaan' => $this->Permintaan_model->get_all_data(),
            'user' => $this->User_model->get_user_by_id($user_id),
        ];

        // Muat tampilan dashboard
        if ($this->session->userdata('role') == 'admin') {
            $this->load->view('home/dashboard_admin', $data);
        } elseif ($this->session->userdata('role') == 'pegawai') {
            $this->load->view('home/dashboard_pegawai', $data);
        } elseif ($this->session->userdata('role') == 'customer') {
            $this->load->view('home/dashboard_customer', $data);
        } elseif ($this->session->userdata('role') == 'pemimpin') {
            $this->load->view('home/dashboard_pemimpin', $data);
        }
    }
}
