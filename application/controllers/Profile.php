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
        $this->load->model('Notifikasi_model');
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
	
		// Data awal tanpa password
		$data = [
			'name' => $this->input->post('name'),
			'email' => $this->input->post('email')
		];
	
		// Validasi dan proses password jika diisi
		if (!empty($password)) {
			if ($password !== $password_confirm) {
				$this->session->set_flashdata('error', 'Password dan Konfirmasi Password tidak cocok.');
				redirect('profile');
			}
	
			// Hash dan tambahkan ke data jika password valid
			$data['password'] = password_hash($password, PASSWORD_DEFAULT);
		}
	
		// Update data
		$this->User_model->update_user($this->session->userdata('user_id'), $data);
	
		// Flash message
		$this->session->set_flashdata('success', 'Profil berhasil diperbarui.');
		redirect('profile');
	}
	
}
