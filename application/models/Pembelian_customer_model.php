<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pembelian_customer_model extends CI_Model
{
    public function get_all_data($conditions = [])
    {
        // Check if there are conditions
        if (!empty($conditions)) {
            $this->db->where($conditions);
        }
        return $this->db->get('pembelian_customer')->result_array();
    }

    public function get_data_by_id($id)
    {
        // Use the dynamic where method
        return $this->get_all_data(['id' => $id])[0] ?? null;
    }

    public function create_data($data)
    {
        return $this->db->insert('pembelian_customer', $data);
    }

    public function update_data($conditions, $data)
    {
        // Support dynamic conditions for update
        if (!empty($conditions)) {
            $this->db->where($conditions);
        }
        return $this->db->update('pembelian_customer', $data);
    }

    public function delete_data($conditions)
    {
        // Support dynamic conditions for delete
        if (!empty($conditions)) {
            $this->db->where($conditions);
        }
        return $this->db->delete('pembelian_customer');
    }
}
