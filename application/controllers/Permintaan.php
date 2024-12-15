<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Permintaan extends CI_Controller
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
        if ($this->session->userdata('role') != 'customer') {
            redirect('dashboard');  // Arahkan ke halaman login jika pengguna belum login
        }
    }

    public function index()
    {
        $data['data'] = $this->Permintaan_model->get_all_data();
        $this->load->view('permintaan/index', $data);
    }

    public function create()
    {

        $data['barang'] = $this->Barang_model->get_all_data(['stok !=' => 0]);
        // Jika form disubmit
        if ($this->input->post()) {
            // Validasi input
            $this->form_validation->set_rules('id_barang', 'Barang', 'required|trim');

            $this->form_validation->set_rules('stok', 'Stok', 'required|trim');

            $customer = $this->Customer_model->get_data_by_iduser($this->session->userdata('user_id'));
            if ($this->form_validation->run()) {
                // Siapkan data untuk pembuatan 
                $data = [
                    'id_barang' => $this->input->post('id_barang'),
                    'id_customer' => $customer['id'],

                    'stok' => $this->input->post('stok'),
                    'tanggal' => date('Y-m-d'),
                    'status' => 1,
                    'ket' => $this->input->post('ket'),
                ];

                // Panggil model untuk membuat 
                $this->Permintaan_model->create_data($data);

                // Set flash message untuk notifikasi sukses
                $this->session->set_flashdata('success', 'Permintaan created successfully!');
                redirect('permintaan');
            } else {
                // Jika validasi gagal, kirimkan pesan error ke view
                $data['errors'] = validation_errors();
            }
        }

        // Load view
        $this->load->view('permintaan/create', $data);
    }


    public function edit($id)
    {
        // Ambil data  berdasarkan ID
        $data['data'] = $this->Permintaan_model->get_data_by_id($id);

        // Jika data tidak ditemukan, tampilkan halaman error atau redirect
        if (!$data['data']) {
            show_404(); // Atau redirect('user') jika lebih cocok
        }
        if ($data['data']['status'] != 1) {
            redirect('permintaan');
        }
        // Jika form disubmit
        if ($this->input->post()) {
            // Validasi input
            $this->form_validation->set_rules('id_barang', 'Barang', 'required|trim');
            $this->form_validation->set_rules('id_customer', 'Customer', 'required|trim');
            $this->form_validation->set_rules('stok', 'Stok', 'required|trim');

            if ($this->form_validation->run()) {
                // Siapkan data untuk pembaruan
                $update_data = [
                    'id_barang' => $this->input->post('id_barang'),
                    'id_customer' => $customer['id'],

                    'stok' => $this->input->post('stok'),
                    'tanggal' => date('Y-m-d'),
                    'status' => 1,
                    'ket' => $this->input->post('ket'),
                ];



                // Update 
                $this->Permintaan_model->update_data($id, $update_data);

                // Redirect ke halaman 
                $this->session->set_flashdata('success', 'Permintaan updated successfully!');
                redirect('permintaan');
            } else {
                // Jika validasi gagal, kirimkan pesan error ke view
                $data['errors'] = validation_errors();
            }
        }

        // Load view
        $this->load->view('permintaan/edit', $data);
    }


    public function delete($id)
    {
        $this->Permintaan_model->delete_data($id);
        redirect('permintaan');
    }
}