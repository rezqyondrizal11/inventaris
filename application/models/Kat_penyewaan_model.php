<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kat_penyewaan_model extends CI_Model
{

    public function get_all_data($conditions = [])
    {
        // Check if there are conditions
        if (!empty($conditions)) {
            $this->db->where($conditions);
        }
        return $this->db->get('kat_penyewaan')->result_array();
    }

    public function get_data_by_id($id)
    {
        return $this->db->get_where('kat_penyewaan', ['id' => $id])->row_array();
    }

    public function create_data($data)
    {
        return $this->db->insert('kat_penyewaan', $data);
    }

    public function update_data($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('kat_penyewaan', $data);
    }

    public function delete_data($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('kat_penyewaan');
    }
}
