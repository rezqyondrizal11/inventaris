<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Supir extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Supir_model');
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
        $data['data'] = $this->Supir_model->get_all_data();
        $this->load->view('supir/index', $data);
    }

    public function create()
    {
        $data = [];
        // Jika form disubmit
        if ($this->input->post()) {
            // Validasi input
            $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[supir.email]');
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
                $this->Supir_model->create_data($data);

                // Set flash message untuk notifikasi sukses
                $this->session->set_flashdata('success', 'Supir created successfully!');
                redirect('supir');
            } else {
                // Jika validasi gagal, kirimkan pesan error ke view
                $data['errors'] = validation_errors();
            }
        }

        // Load view
        $this->load->view('supir/create', $data);
    }


    public function edit($id)
    {
        // Ambil data  berdasarkan ID
        $data['data'] = $this->Supir_model->get_data_by_id($id);

        // Jika data tidak ditemukan, tampilkan halaman error atau redirect
        if (!$data['data']) {
            show_404(); // Atau redirect('user') jika lebih cocok
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
                $this->Supir_model->update_data($id, $update_data);

                // Redirect ke halaman 
                $this->session->set_flashdata('success', 'Supir updated successfully!');
                redirect('supir');
            } else {
                // Jika validasi gagal, kirimkan pesan error ke view
                $data['errors'] = validation_errors();
            }
        }

        // Load view
        $this->load->view('supir/edit', $data);
    }


    public function delete($id)
    {
        $this->Supir_model->delete_data($id);
        redirect('supir');
    }
}
