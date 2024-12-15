<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kat_barang_model extends CI_Model
{

    public function get_all_data()
    {
        return $this->db->get('kat_barang')->result_array();
    }

    public function get_data_by_id($id)
    {
        return $this->db->get_where('kat_barang', ['id' => $id])->row_array();
    }

    public function create_data($data)
    {
        return $this->db->insert('kat_barang', $data);
    }

    public function update_data($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('kat_barang', $data);
    }

    public function delete_data($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('kat_barang');
    }
}
