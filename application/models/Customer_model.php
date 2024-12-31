<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Customer_model extends CI_Model
{
    protected $table = 'customer'; // Nama tabel di database

    public function count_all()
    {
        return $this->db->count_all($this->table);
    }
    public function get_all_data()
    {
        return $this->db->get('customer')->result_array();
    }

    public function get_data_by_id($id)
    {
        return $this->db->get_where('customer', ['id' => $id])->row_array();
    }

    public function get_data_by_iduser($id)
    {
        return $this->db->get_where('customer', ['id_user' => $id])->row_array();
    }
    public function create_data($data)
    {
        return $this->db->insert('customer', $data);
    }

    public function update_data($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('customer', $data);
    }

    public function delete_data($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('customer');
    }
}
