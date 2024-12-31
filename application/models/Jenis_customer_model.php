<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jenis_customer_model extends CI_Model
{
    protected $table = 'jenis_customer'; // Nama tabel di database

    public function count_all()
    {
        return $this->db->count_all($this->table);
    }
    public function get_all_data()
    {
        return $this->db->get('jenis_customer')->result_array();
    }

    public function get_data_by_id($id)
    {
        return $this->db->get_where('jenis_customer', ['id' => $id])->row_array();
    }

    public function create_data($data)
    {
        return $this->db->insert('jenis_customer', $data);
    }

    public function update_data($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('jenis_customer', $data);
    }

    public function delete_data($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('jenis_customer');
    }
}
