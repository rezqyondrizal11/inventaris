<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pembelian_model extends CI_Model
{
    protected $table = 'pembelian'; // Nama tabel di database

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
        return $this->db->get('pembelian')->result_array();
    }
    public function get_filtered_data($start_date, $end_date)
    {
        $this->db->where('tanggal >=', $start_date);
        $this->db->where('tanggal <=', $end_date);
        return $this->db->get('pembelian')->result_array();
    }
    public function get_data_by_id($id)
    {
        // Use the dynamic where method
        return $this->get_all_data(['id' => $id])[0] ?? null;
    }

    public function create_data($data)
    {
        return $this->db->insert('pembelian', $data);
    }

    public function update_data($conditions, $data)
    {
        // Support dynamic conditions for update
        if (!empty($conditions)) {
            $this->db->where($conditions);
        }


        // echo "<pre>";
        // print_r($conditions);
        // echo "<br>";

        // print_r($data);
        // echo "<br>";

        // print_r($this->db->where($conditions));
        // die;
        return $this->db->update('pembelian', $data);
    }

    public function delete_data($conditions)
    {
        // Support dynamic conditions for delete
        if (!empty($conditions)) {
            $this->db->where($conditions);
        }
        return $this->db->delete('pembelian');
    }

    public function get_data_after_id($id, $idbarang)
    {
        $this->db->where('id_barang', $idbarang);

        $this->db->where('id >', $id);
        $this->db->order_by('id', 'ASC');
        return $this->db->get('pembelian')->result_array();
    }
}
