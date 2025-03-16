<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penjualan_model extends CI_Model
{
    protected $table = 'penjualan'; // Nama tabel di database

    public function count_all()
    {
        return $this->db->count_all_results($this->table);
    }
    public function get_all_data($conditions = [])
    {
        // Check if there are conditions
        if (!empty($conditions)) {
            $this->db->where($conditions);
        }
        $this->db->order_by('id', 'DESC');
        return $this->db->get('penjualan')->result_array();
    }
    public function get_all_data_jc($id_jenis_customer = null, $start_date = null, $end_date = null)
    {
        $this->db->select('
            penjualan.*, 
            customer.nama AS customer_nama, 
            barang.kode AS barang_kode, 
            barang.name AS barang_nama, 
            supir.nama AS supir_nama
        ');
        $this->db->from('penjualan');
        $this->db->join('customer', 'customer.id = penjualan.id_customer', 'left');
        $this->db->join('jenis_customer', 'jenis_customer.id = customer.id_jc', 'left');
        $this->db->join('barang', 'barang.id = penjualan.id_barang', 'left');
        $this->db->join('supir', 'supir.id = penjualan.id_supir', 'left');

        if ($id_jenis_customer) {
            $this->db->where('jenis_customer.id', $id_jenis_customer);
        }

        // Filter berdasarkan rentang tanggal
        if (!empty($start_date) && !empty($end_date)) {
            $this->db->where('penjualan.tanggal >=', $start_date);
            $this->db->where('penjualan.tanggal <=', $end_date);
        }
        $this->db->order_by('penjualan.id', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_filtered_data($start_date, $end_date)
    {
        $this->db->where('tanggal >=', $start_date);
        $this->db->where('tanggal <=', $end_date);
        return $this->db->get('penjualan')->result_array();
    }

    public function get_data_by_id($id)
    {
        // Use the dynamic where method
        return $this->get_all_data(['id' => $id])[0] ?? null;
    }

    public function create_data($data)
    {
        $this->db->insert('penjualan', $data);
        return $this->db->insert_id(); // Mengembalikan ID dari data yang baru saja dimasukkan
    }

    public function update_data($conditions, $data)
    {
        // Support dynamic conditions for update
        if (!empty($conditions)) {
            $this->db->where($conditions);
        }

        return $this->db->update('penjualan', $data);
    }

    public function delete_data($conditions)
    {
        // Support dynamic conditions for delete
        if (!empty($conditions)) {
            $this->db->where($conditions);
        }
        return $this->db->delete('penjualan');
    }

    public function get_data_after_id($id, $idbarang)
    {
        $this->db->where('id_barang', $idbarang);

        $this->db->where('id >', $id);
        $this->db->order_by('id', 'ASC');
        return $this->db->get('penjualan')->result_array();
    }
}
