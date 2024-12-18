<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Proses_permintaan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Permintaan_model');
        $this->load->model('Penjualan_model');
        $this->load->model('Barang_model');
        $this->load->model('Customer_model');
        $this->load->model('Supir_model');
        $this->load->model('Kat_penyewaan_model');
        $this->load->model('Notifikasi_model');
        $this->load->model('Pembelian_customer_model');
        // Memuat library form_validation
        $this->load->library('form_validation');
        // Memuat library session
        $this->load->library('session');
        // Memeriksa apakah pengguna sudah login
        if ($this->session->userdata('role') != 'pegawai') {
            redirect('dashboard');  // Arahkan ke halaman login jika pengguna belum login
        }
    }

    public function index()
    {
        $data['data'] = $this->Permintaan_model->get_all_data();
        $this->load->view('proses_permintaan/index', $data);
    }




    public function proses($id)
    {


        // Data untuk update penyewaan
        $update_data = [

            'status' => 0,
        ];

        $this->Notifikasi_model->update_data(['id_permintaan' => $id], $update_data);

        // Ambil data  berdasarkan ID
        $data['data'] = $this->Permintaan_model->get_data_by_id($id);



        $data['supir'] = $this->Supir_model->get_all_data();
        // Jika data tidak ditemukan, tampilkan halaman error atau redirect
        if (!$data['data']) {
            show_404();
        }

        // Jika form disubmit
        if ($this->input->post()) {
            // Validasi input
            $this->form_validation->set_rules('id_supir', 'Supir Name', 'required|trim');
            $this->form_validation->set_rules('status', 'Status', 'required|trim');
            $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim');

            if ($this->form_validation->run()) {
                // Siapkan data untuk pembaruan
                $update_data = [
                    'status' => $this->input->post('status'),
                    'ket' => $this->input->post('keterangan'),
                ];

                // Update 
                $this->Permintaan_model->update_data($id, $update_data);
                $permintaan = $this->Permintaan_model->get_data_by_id($id);

                if ($permintaan['status'] == 2) {
                    $barang = $this->Barang_model->get_data_by_id($permintaan['id_barang']);

                    $barangkeluar = $barang['jumlah_keluar'] +  $permintaan['stok'];

                    $stok_baru = $barang['stok'] - $permintaan['stok'];
                    $datap = [
                        'id_barang' => $permintaan['id_barang'],
                        'id_supir' => $this->input->post('id_supir'),
                        'id_customer' => $permintaan['id_customer'],
                        'jumlah_awal' =>  $barang['stok'],
                        'jumlah_masuk' => 0,
                        'jumlah_keluar' => $permintaan['stok'],
                        'stok' =>    $stok_baru,
                        'tanggal' => $permintaan['tanggal'],
                    ];


                    $id_penjualan = $this->Penjualan_model->create_data($datap);

                    $datacustomer = [
                        'id_penjualan' => $id_penjualan, // Gunakan ID penjualan yang baru
                        'id_customer' => $permintaan['id_customer'],
                        'jumlah_masuk' => $permintaan['stok'],
                        'jumlah_keluar' =>  0,
                        'sisa' => $permintaan['stok'],
                        'status' => 1,
                    ];

                    // Menyimpan data ke tabel History_pembelian_customer
                    $this->Pembelian_customer_model->create_data($datacustomer);

                    // Update stok barang di tabel barang
                    $barang_update = [
                        'stok' => $stok_baru,  // Update stok
                        'jumlah_keluar' => $barangkeluar,  // Update jumlah_keluar jika perlu
                    ];

                    // Mengupdate data barang di database
                    $conditions = ['id' =>  $permintaan['id_barang']];
                    $this->Barang_model->update_data($conditions, $barang_update);
                }
                // Redirect ke halaman 
                $this->session->set_flashdata('success', 'Proses Permintaan updated successfully!');
                redirect('proses_permintaan');
            } else {
                // Jika validasi gagal, kirimkan pesan error ke view
                $data['errors'] = validation_errors();
            }
        }

        // Load view
        $this->load->view('proses_permintaan/proses', $data);
    }
}
