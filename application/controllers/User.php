<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
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
        $data['users'] = $this->User_model->get_all_users();
        $this->load->view('user/index', $data);
    }

    public function create()
    {
        $data = [];
        // Jika form disubmit
        if ($this->input->post()) {
            // Validasi input
            $this->form_validation->set_rules('name', 'Name', 'required|trim');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[users.email]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|trim');
            $this->form_validation->set_rules('role', 'Role', 'required');

            if ($this->form_validation->run()) {
                // Siapkan data untuk pembuatan user
                $data = [
                    'name' => $this->input->post('name'),
                    'email' => $this->input->post('email'),
                    'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                    'role' => $this->input->post('role'),
                ];

                // Panggil model untuk membuat user
                $this->User_model->create_user($data);

                // Set flash message untuk notifikasi sukses
                $this->session->set_flashdata('success', 'User created successfully!');
                redirect('user');
            } else {
                // Jika validasi gagal, kirimkan pesan error ke view
                $data['errors'] = validation_errors();
            }
        }

        // Load view
        $this->load->view('user/create', $data);
    }


    public function edit($id)
    {
        // Ambil data user berdasarkan ID
        $data['user'] = $this->User_model->get_user_by_id($id);

        // Jika data user tidak ditemukan, tampilkan halaman error atau redirect
        if (!$data['user']) {
            show_404(); // Atau redirect('user') jika lebih cocok
        }

        // Jika form disubmit
        if ($this->input->post()) {
            // Validasi input
            $this->form_validation->set_rules('name', 'Name', 'required|trim');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'min_length[6]|trim');
            $this->form_validation->set_rules('role', 'Role', 'required');
            if ($this->form_validation->run()) {
                // Siapkan data untuk pembaruan
                $update_data = [
                    'name' => $this->input->post('name'),
                    'email' => $this->input->post('email'),
                    'role' => $this->input->post('role'), // Menambahkan role
                ];

                // Jika password diisi, tambahkan ke data pembaruan
                if (!empty($this->input->post('password'))) {
                    $update_data['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
                }

                // Update user
                $this->User_model->update_user($id, $update_data);

                // Redirect ke halaman user
                $this->session->set_flashdata('success', 'User updated successfully!');
                redirect('user');
            } else {
                // Jika validasi gagal, kirimkan pesan error ke view
                $data['errors'] = validation_errors();
            }
        }

        // Load view
        $this->load->view('user/edit', $data);
    }


    public function delete($id)
    {
        $this->User_model->delete_user($id);
        redirect('user');
    }
}
