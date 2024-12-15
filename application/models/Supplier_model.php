<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Supplier_model extends CI_Model
{

    public function get_all_data()
    {
        return $this->db->get('supplier')->result_array();
    }

    public function get_data_by_id($id)
    {
        return $this->db->get_where('supplier', ['id' => $id])->row_array();
    }

    public function create_data($data)
    {
        return $this->db->insert('supplier', $data);
    }

    public function update_data($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('supplier', $data);
    }

    public function delete_data($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('supplier');
    }
}
