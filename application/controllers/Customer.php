<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Customer extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Customer_model');
        $this->load->model('Jenis_customer_model');
        $this->load->model('User_model');
        $this->load->model('Kat_penyewaan_model');


        // Memuat library form_validation
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
        $data['data'] = $this->Customer_model->get_all_data();
        $this->load->view('customer/index', $data);
    }

    public function create()
    {
        $data = [];
        $data['jenis'] = $this->Jenis_customer_model->get_all_data();
        $data['user'] = $this->User_model->get_user_by_role('customer');
        // Jika form disubmit
        if ($this->input->post()) {
            // Validasi input
            $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[customer.email]');
            $this->form_validation->set_rules('kode', 'Kode', 'required|trim');
            $this->form_validation->set_rules('telepon', 'Telepon', 'required|trim');
            $this->form_validation->set_rules('alamat', 'Alamat', 'required|trim');
            $this->form_validation->set_rules('id_jc', 'Jenis Customer', 'required|trim');
            $this->form_validation->set_rules('id_user', 'User', 'trim');

            if ($this->form_validation->run()) {
                // Siapkan data untuk pembuatan 
                $data = [
                    'nama' => $this->input->post('nama'),
                    'email' => $this->input->post('email'),
                    'kode' => $this->input->post('kode'),
                    'telepon' => $this->input->post('telepon'),
                    'alamat' => $this->input->post('alamat'),
                    'id_jc' => $this->input->post('id_jc'),
                    'id_user' => $this->input->post('id_user'),

                ];

                // Panggil model untuk membuat 
                $this->Customer_model->create_data($data);

                // Set flash message untuk notifikasi sukses
                $this->session->set_flashdata('success', 'Customer created successfully!');
                redirect('customer');
            } else {
                // Jika validasi gagal, kirimkan pesan error ke view
                $data['errors'] = validation_errors();
            }
        }

        // Load view
        $this->load->view('customer/create', $data);
    }


    public function edit($id)
    {
        $data['jenis'] = $this->Jenis_customer_model->get_all_data();
        $data['user'] = $this->User_model->get_user_by_role('customer');
        // Ambil data  berdasarkan ID
        $data['data'] = $this->Customer_model->get_data_by_id($id);

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
            $this->form_validation->set_rules('id_jc', 'Jenis Customer', 'required|trim');
            $this->form_validation->set_rules('id_user', 'User', 'trim');
            if ($this->form_validation->run()) {
                // Siapkan data untuk pembaruan
                $update_data = [
                    'nama' => $this->input->post('nama'),
                    'email' => $this->input->post('email'),
                    'kode' => $this->input->post('kode'),
                    'telepon' => $this->input->post('telepon'),
                    'alamat' => $this->input->post('alamat'),
                    'id_jc' => $this->input->post('id_jc'),
                    'id_user' => $this->input->post('id_user'),
                ];

                // Update 
                $this->Customer_model->update_data($id, $update_data);

                // Redirect ke halaman 
                $this->session->set_flashdata('success', 'Customer updated successfully!');
                redirect('customer');
            } else {
                // Jika validasi gagal, kirimkan pesan error ke view
                $data['errors'] = validation_errors();
            }
        }

        // Load view
        $this->load->view('customer/edit', $data);
    }


    public function delete($id)
    {
        $this->Customer_model->delete_data($id);
        redirect('customer');
    }
}
