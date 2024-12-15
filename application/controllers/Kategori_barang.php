<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kategori_barang extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Kat_barang_model');
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
        $data['data'] = $this->Kat_barang_model->get_all_data();
        $this->load->view('katbarang/index', $data);
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

                ];

                $this->Kat_barang_model->create_data($data);

                // Set flash message untuk notifikasi sukses
                $this->session->set_flashdata('success', 'Kategori Barang created successfully!');
                redirect('kategori_barang');
            } else {
                // Jika validasi gagal, kirimkan pesan error ke view
                $data['errors'] = validation_errors();
            }
        }

        // Load view
        $this->load->view('katbarang/create', $data);
    }


    public function edit($id)
    {
        // Ambil data  berdasarkan ID
        $data['data'] = $this->Kat_barang_model->get_data_by_id($id);

        // Jika data user tidak ditemukan, tampilkan halaman error atau redirect
        if (!$data['data']) {
            show_404();
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
                $this->Kat_barang_model->update_data($id, $update_data);

                // Redirect ke halaman 
                $this->session->set_flashdata('success', 'Kategori Barang updated successfully!');
                redirect('kategori_barang');
            } else {
                // Jika validasi gagal, kirimkan pesan error ke view
                $data['errors'] = validation_errors();
            }
        }

        // Load view
        $this->load->view('katbarang/edit', $data);
    }


    public function delete($id)
    {
        $this->Kat_barang_model->delete_data($id);
        redirect('kategori_barang');
    }
}
