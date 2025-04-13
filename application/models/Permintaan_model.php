<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Permintaan_model extends CI_Model
{
    protected $table = 'permintaan'; // Nama tabel di database

    public function count_all_data_by_customer($id_customer)
    {
        $this->db->where('id_customer', $id_customer);
        return $this->db->count_all_results('permintaan');
    }

    public function count_all()
    {
        return $this->db->count_all_results($this->table);
    }
    public function get_all_data()
    {
        return $this->db->get('permintaan')->result_array();
    }
    public function get_all_data_by_customer($id_customer)
    {
        return $this->db->get_where('permintaan', ['id_customer' => $id_customer])->result_array();
    }

    public function get_data_by_id($id)
    {
        return $this->db->get_where('permintaan', ['id' => $id])->row_array();
    }

    public function create_data($data)
    {
        $this->db->insert('permintaan', $data);
        return $this->db->insert_id();
    }

    public function update_data($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('permintaan', $data);
    }

    public function delete_data($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('permintaan');
    }
}
