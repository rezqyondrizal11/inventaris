<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends CI_Controller
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
        // Pastikan pengguna sudah login
        if (!$this->session->userdata('logged_in')) {
            redirect('login');  // Jika tidak, arahkan ke halaman login
        }
        // Memuat model pengguna
        $this->load->model('User_model');
    }

    // Halaman profil pengguna
    public function index()
    {
        // Ambil data pengguna berdasarkan ID yang ada di session
        $data['user'] = $this->User_model->get_user_by_id($this->session->userdata('user_id'));

        // Tampilkan view profil
        $this->load->view('profile/index', $data);
    }

    // Halaman untuk update profil
    public function edit()
    {
        // Ambil data dari form
        $password = $this->input->post('password');
        $password_confirm = $this->input->post('password_confirm');

        // Validasi jika ada perubahan password dan pastikan konfirmasi password cocok
        if (!empty($password)) {
            if ($password !== $password_confirm) {
                $this->session->set_flashdata('error', 'Password and Confirm Password do not match.');
                redirect('profile'); // Redirect kembali ke halaman profile
            }

            // Hash password baru jika cocok
            $password = password_hash($password, PASSWORD_DEFAULT);
        }

        // Lanjutkan update data pengguna (termasuk password jika ada perubahan)
        $data = [
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),
            'password' => $password
        ];

        $this->User_model->update_user($this->session->userdata('user_id'), $data);

        // Set flash message dan redirect
        $this->session->set_flashdata('success', 'Profile updated successfully.');
        redirect('profile');
    }
}
