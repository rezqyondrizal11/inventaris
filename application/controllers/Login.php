<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model'); // Pastikan model User_model ada
        $this->load->library('form_validation');
        $this->load->library('session');
    }

    public function index()
    {

        // Cek apakah sudah login, jika sudah, redirect ke dashboard
        if ($this->session->userdata('user_id')) {
            redirect('dashboard');
        }

        // Atur validasi form
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() === TRUE) {
            // Ambil data dari form
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            // Cek kredensial
            $user = $this->User_model->get_user_by_email($email);

            if ($user && password_verify($password, $user['password'])) {
                // Set session data
                $this->session->set_userdata('user_id', $user['id']);
                $this->session->set_userdata('role', $user['role']);
                $this->session->set_userdata('name', $user['name']);
                $this->session->set_userdata('logged_in', TRUE);
                // Redirect ke halaman dashboard
                redirect('dashboard');
            } else {

                // Set error flashdata
                $this->session->set_flashdata('error', 'Invalid email or password');
                redirect('login');
            }
        } else {
            $this->load->view('auth/login');
        }
    }

    public function logout()
    {
        $this->session->sess_destroy(); // Hapus session
        redirect('login'); // Redirect ke halaman login
    }

    public function forgot_password()
    {
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

        if ($this->form_validation->run() === TRUE) {
            $email = $this->input->post('email');
            $user = $this->User_model->get_user_by_email($email);

            if ($user) {
                // Simulasi kirim link reset
                // (Di aplikasi nyata, Anda akan generate token dan kirim email berisi link dengan token tsb)
                $this->session->set_flashdata('success', 'Reset link telah dikirim ke email Anda.');
            } else {
                $this->session->set_flashdata('error', 'Email tidak ditemukan.');
            }

            redirect('login/forgot_password');
        }

        $this->load->view('auth/forgot_password');
    }

}