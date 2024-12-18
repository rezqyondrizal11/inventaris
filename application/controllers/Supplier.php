<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Supplier extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Supplier_model');
        $this->load->model('Kat_penyewaan_model');
        // Memuat library form_validation
        $this->load->model('Notifikasi_model');
        $this->load->library('form_validation');
        // Memuat library session
        $this->load->library('session');
        // Memeriksa apakah pengguna sudah login
        if ($this->session->userdata('role') != 'admin') {
            redirect('dashboard');  // Arahkan ke halaman login jika pengguna belum login
        }
    }

    public function index()
    {
        $data['data'] = $this->Supplier_model->get_all_data();
        $this->load->view('supplier/index', $data);
    }

    public function create()
    {
        $data = [];
        // Jika form disubmit
        if ($this->input->post()) {
            // Validasi input
            $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[supplier.email]');
            $this->form_validation->set_rules('kode', 'Kode', 'required|trim');
            $this->form_validation->set_rules('telepon', 'Telepon', 'required|trim');
            $this->form_validation->set_rules('alamat', 'Alamat', 'required|trim');


            if ($this->form_validation->run()) {
                // Siapkan data untuk pembuatan 
                $data = [
                    'nama' => $this->input->post('nama'),
                    'email' => $this->input->post('email'),
                    'kode' => $this->input->post('kode'),
                    'telepon' => $this->input->post('telepon'),
                    'alamat' => $this->input->post('alamat'),

                ];

                // Panggil model untuk membuat 
                $this->Supplier_model->create_data($data);

                // Set flash message untuk notifikasi sukses
                $this->session->set_flashdata('success', 'Supplier created successfully!');
                redirect('supplier');
            } else {
                // Jika validasi gagal, kirimkan pesan error ke view
                $data['errors'] = validation_errors();
            }
        }

        // Load view
        $this->load->view('supplier/create', $data);
    }


    public function edit($id)
    {
        // Ambil data  berdasarkan ID
        $data['data'] = $this->Supplier_model->get_data_by_id($id);

        // Jika data tidak ditemukan, tampilkan halaman error atau redirect
        if (!$data['data']) {
            show_404();
        }

        // Jika form disubmit
        if ($this->input->post()) {
            // Validasi input
            $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
            $this->form_validation->set_rules('kode', 'Kode', 'required|trim');
            $this->form_validation->set_rules('telepon', 'Telepon', 'required|trim');
            $this->form_validation->set_rules('alamat', 'Alamat', 'required|trim');
            if ($this->form_validation->run()) {
                // Siapkan data untuk pembaruan
                $update_data = [
                    'nama' => $this->input->post('nama'),
                    'email' => $this->input->post('email'),
                    'kode' => $this->input->post('kode'),
                    'telepon' => $this->input->post('telepon'),
                    'alamat' => $this->input->post('alamat'),
                ];

                // Update 
                $this->Supplier_model->update_data($id, $update_data);

                // Redirect ke halaman 
                $this->session->set_flashdata('success', 'Supplier updated successfully!');
                redirect('supplier');
            } else {
                // Jika validasi gagal, kirimkan pesan error ke view
                $data['errors'] = validation_errors();
            }
        }

        // Load view
        $this->load->view('supplier/edit', $data);
    }


    public function delete($id)
    {
        $this->Supplier_model->delete_data($id);
        redirect('supplier');
    }
}
