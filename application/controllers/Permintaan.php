<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Permintaan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Permintaan_model');
        $this->load->model('Kat_penyewaan_model');
        $this->load->model('Barang_model');
        $this->load->model('Customer_model');
        // Memuat library form_validation
        $this->load->model('Notifikasi_model');
        $this->load->library('form_validation');
        // Memuat library session
        $this->load->library('session');
        // Memeriksa apakah pengguna sudah login
        if ($this->session->userdata('role') != 'customer') {
            redirect('dashboard');  // Arahkan ke halaman login jika pengguna belum login
        }
    }

    public function index()
    {
        $customer = $this->Customer_model->get_data_by_iduser($this->session->userdata('user_id'));

        $data['data'] = $this->Permintaan_model->get_all_data_by_customer($customer['id']);
        $this->load->view('permintaan/index', $data);
    }
    public function print()
    {
        $customer = $this->Customer_model->get_data_by_iduser($this->session->userdata('user_id'));
        if (!$customer) {
            show_error('Unauthorized access', 403);
        }

        $data['data'] = $this->Permintaan_model->get_all_data_by_customer($customer['id']);
        $this->load->view('permintaan/print', $data);
    }

    public function create()
    {
        $data['barang'] = $this->Barang_model->get_all_data(['stok !=' => 0]);

        if ($this->input->post()) {
            $permintaan = $this->input->post('permintaan');



            $customer = $this->Customer_model->get_data_by_iduser($this->session->userdata('user_id'));

            foreach ($permintaan as $item) {
                $this->form_validation->set_rules("permintaan[{$item['id_barang']}][id_barang]", 'Barang', 'required|trim');
                $this->form_validation->set_rules("permintaan[{$item['stok']}][stok]", 'Stok', 'required|trim|integer');

                // if (!$this->form_validation->run()) {
                //     $data['errors'] = validation_errors();
                //     break;
                // }

                $dataToInsert = [
                    'id_barang' => $item['id_barang'],
                    'no_invoice' => $item['no_invoice'],
                    'id_customer' => $customer['id'],
                    'stok' => $item['stok'],
                    'tanggal' => date('Y-m-d'),
                    'status' => 1,
                ];

                $permintaan = $this->Permintaan_model->create_data($dataToInsert);

                $dataNotif = [
                    'id_permintaan' => $permintaan,
                    'url' => "proses_permintaan/proses/" . $permintaan,
                    'ket' => 'Ada permintaan dari customer ' . $customer['name'] . ' jumlah stok: ' . $item['stok'],
                    'status' => 1,
                    'tanggal' => date('Y-m-d'),
                ];

                $notif = $this->Notifikasi_model->create_data($dataNotif);
            }


            $this->session->set_flashdata('success', 'Permintaan created successfully!');
            redirect('permintaan');
        }

        $this->load->view('permintaan/create', $data);
    }



    public function edit($id)
    {
        // Ambil data  berdasarkan ID
        $data['data'] = $this->Permintaan_model->get_data_by_id($id);

        // Jika data tidak ditemukan, tampilkan halaman error atau redirect
        if (!$data['data']) {
            show_404(); // Atau redirect('user') jika lebih cocok
        }
        if ($data['data']['status'] != 1) {
            redirect('permintaan');
        }
        // Jika form disubmit
        if ($this->input->post()) {
            // Validasi input
            $this->form_validation->set_rules('id_barang', 'Barang', 'required|trim');
            $this->form_validation->set_rules('id_customer', 'Customer', 'required|trim');
            $this->form_validation->set_rules('stok', 'Stok', 'required|trim');

            if ($this->form_validation->run()) {
                // Siapkan data untuk pembaruan
                $update_data = [
                    'id_barang' => $this->input->post('id_barang'),
                    'id_customer' => $customer['id'],

                    'stok' => $this->input->post('stok'),
                    'tanggal' => date('Y-m-d'),
                    'status' => 1,
                    'ket' => $this->input->post('ket'),
                ];



                // Update 
                $this->Permintaan_model->update_data($id, $update_data);

                // Redirect ke halaman 
                $this->session->set_flashdata('success', 'Permintaan updated successfully!');
                redirect('permintaan');
            } else {
                // Jika validasi gagal, kirimkan pesan error ke view
                $data['errors'] = validation_errors();
            }
        }

        // Load view
        $this->load->view('permintaan/edit', $data);
    }


    public function delete($id)
    {
        $this->Permintaan_model->delete_data($id);
        redirect('permintaan');
    }
}
