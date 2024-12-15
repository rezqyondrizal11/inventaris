<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Permintaan_pemimpin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Permintaan_model');
        $this->load->model('Kat_penyewaan_model');
        $this->load->model('Barang_model');
        $this->load->model('Customer_model');
        // Memuat library form_validation
        $this->load->library('form_validation');
        // Memuat library session
        $this->load->library('session');
        // Memeriksa apakah pengguna sudah login
        if ($this->session->userdata('role') != 'pemimpin') {
            redirect('dashboard');  // Arahkan ke halaman login jika pengguna belum login
        }
    }

    public function index()
    {
        $data['data'] = $this->Permintaan_model->get_all_data();
        $this->load->view('permintaan_pemimpin/index', $data);
    }
}
