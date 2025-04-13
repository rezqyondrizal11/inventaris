<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barang extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Barang_model');
        $this->load->model('Kat_barang_model');
        $this->load->model('Kat_penyewaan_model');
        $this->load->model('Notifikasi_model');

        // Memuat library form_validation
        $this->load->library('form_validation');
        // Memuat library session
        $this->load->library('session');
        // Memeriksa apakah pengguna sudah login
        if ($this->session->userdata('role') != 'admin' && $this->session->userdata('role') != 'pegawai') {
            redirect('dashboard');  // Arahkan ke halaman login jika pengguna belum login
        }
    }

    public function index()
    {
        $data['data'] = $this->Barang_model->get_all_data();
        $this->load->view('barang/index', $data);
    }

    public function create()
    {
        $data = [];
        $data['penyewaan'] = $this->Kat_penyewaan_model->get_all_data();
        $data['kategori'] = $this->Kat_barang_model->get_all_data();

        if ($this->input->post()) {
            // Validasi input
            $this->form_validation->set_rules('name', 'Name', 'required|trim');
            $this->form_validation->set_rules('kode', 'Kode', 'required|trim');
            $this->form_validation->set_rules('kondisi', 'Kondisi', 'required|trim');
            $this->form_validation->set_rules('satuan', 'Satuan', 'required|trim');
            $this->form_validation->set_rules('id_kat_barang', 'Kategori Barang', 'required|trim');
            $this->form_validation->set_rules('id_penyewaan', 'Penyewaan', 'required|trim');
            $this->form_validation->set_rules('stok', 'Stok', 'required|trim');


            if ($this->form_validation->run()) {
                $data = [
                    'name' => $this->input->post('name'),
                    'kode' => $this->input->post('kode'),
                    'kondisi' => $this->input->post('kondisi'),
                    'satuan' => $this->input->post('satuan'),
                    'id_kat_barang' => $this->input->post('id_kat_barang'),
                    'id_penyewaan' => $this->input->post('id_penyewaan'),
                    'stok' => $this->input->post('stok'),
                    'jumlah_awal' =>  $this->input->post('stok'),
                    'jumlah_masuk' => 0,
                    'jumlah_keluar' => 0,
                ];

                $this->Barang_model->create_data($data);

                $this->session->set_flashdata('success', 'Barang created successfully!');
                redirect('Barang');
            } else {
                $data['errors'] = validation_errors();
            }
        }

        $this->load->view('barang/create', $data);
    }


    public function edit($id)
    {
        $data = [];
        $data['penyewaan'] = $this->Kat_penyewaan_model->get_all_data();
        $data['kategori'] = $this->Kat_barang_model->get_all_data();
        $data['barang'] = $this->Barang_model->get_data_by_id($id);

        if (!$data['barang']) {
            $this->session->set_flashdata('error', 'Barang not found!');
            redirect('Barang');
        }

        if ($this->input->post()) {
            // Validasi input
            $this->form_validation->set_rules('name', 'Name', 'required|trim');
            $this->form_validation->set_rules('kode', 'Kode', 'required|trim');
            $this->form_validation->set_rules('kondisi', 'Kondisi', 'required|trim');
            $this->form_validation->set_rules('satuan', 'Satuan', 'required|trim');
            $this->form_validation->set_rules('id_kat_barang', 'Kategori Barang', 'required|trim');
            $this->form_validation->set_rules('id_penyewaan', 'Penyewaan', 'required|trim');
            $this->form_validation->set_rules('stok', 'Stok', 'required|trim');


            if ($this->form_validation->run()) {
                $update_data = [
                    'name' => $this->input->post('name'),
                    'kode' => $this->input->post('kode'),
                    'kondisi' => $this->input->post('kondisi'),
                    'satuan' => $this->input->post('satuan'),
                    'id_kat_barang' => $this->input->post('id_kat_barang'),
                    'id_penyewaan' => $this->input->post('id_penyewaan'),
                    'stok' => $this->input->post('stok'),
                    'jumlah_awal' => $this->input->post('stok'),

                ];

                $id = ['id' => $id];
                $this->Barang_model->update_data($id, $update_data);

                $this->session->set_flashdata('success', 'Barang updated successfully!');
                redirect('Barang');
            } else {
                $data['errors'] = validation_errors();
            }
        }

        $this->load->view('barang/edit', $data);
    }




    public function delete($id)
    {
        $id = ['id' => $id];
        $this->Barang_model->delete_data($id);
        redirect('Barang');
    }
}
