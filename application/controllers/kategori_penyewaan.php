<?php
defined('BASEPATH') or exit('No direct script access allowed');

class kategori_penyewaan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
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
        $data['data'] = $this->Kat_penyewaan_model->get_all_data();
        $this->load->view('katpenyewaan/index', $data);
    }

    public function create()
    {
        $data = [];
        // Jika form disubmit
        if ($this->input->post()) {
            // Validasi input
            $this->form_validation->set_rules('name', 'Name', 'required|trim');


            if ($this->form_validation->run()) {

                $data = [
                    'name' => $this->input->post('name'),
                    'status' => 1,
                ];

                $this->Kat_penyewaan_model->create_data($data);

                // Set flash message untuk notifikasi sukses
                $this->session->set_flashdata('success', 'Kategori Penyewaan created successfully!');
                redirect('kategori_penyewaan');
            } else {
                // Jika validasi gagal, kirimkan pesan error ke view
                $data['errors'] = validation_errors();
            }
        }

        // Load view
        $this->load->view('katpenyewaan/create', $data);
    }


    public function edit($id)
    {
        // Ambil data  berdasarkan ID
        $data['data'] = $this->Kat_penyewaan_model->get_data_by_id($id);

        // Jika data user tidak ditemukan, tampilkan halaman error atau redirect
        if (!$data['data']) {
            show_404(); // Atau redirect('user') jika lebih cocok
        }

        // Jika form disubmit
        if ($this->input->post()) {
            // Validasi input
            $this->form_validation->set_rules('name', 'Name', 'required|trim');

            if ($this->form_validation->run()) {
                // Siapkan data untuk pembaruan
                $update_data = [
                    'name' => $this->input->post('name'),

                ];
                // Update 
                $this->Kat_penyewaan_model->update_data($id, $update_data);

                // Redirect ke halaman 
                $this->session->set_flashdata('success', 'Kategori Penyewaan updated successfully!');
                redirect('kategori_penyewaan');
            } else {
                // Jika validasi gagal, kirimkan pesan error ke view
                $data['errors'] = validation_errors();
            }
        }

        // Load view
        $this->load->view('katpenyewaan/edit', $data);
    }
    public function show($id)
    {
        // Ambil data  berdasarkan ID
        $data['data'] = $this->Kat_penyewaan_model->get_data_by_id($id);

        // Jika data user tidak ditemukan, tampilkan halaman error atau redirect
        if (!$data['data']) {
            show_404(); // Atau redirect('user') jika lebih cocok
        }
        // Siapkan data untuk pembaruan
        $update_data = [
            'status' => $data['data']['status'] == 1 ? 0 : 1,
        ];

        // Update 
        $this->Kat_penyewaan_model->update_data($id, $update_data);
        redirect('kategori_penyewaan');
    }
    public function delete($id)
    {
        $this->Kat_penyewaan_model->delete_data($id);
        redirect('kategori_penyewaan');
    }
}
