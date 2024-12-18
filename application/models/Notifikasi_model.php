<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notifikasi_model extends CI_Model
{
    public function get_all_data($conditions = [])
    {
        // Check if there are conditions
        if (!empty($conditions)) {
            $this->db->where($conditions);
        }
        return $this->db->get('notifikasi')->result_array();
    }
    public function get_filtered_data($conditions = [], $start_date, $end_date)
    {
        // Add conditions if provided
        if (!empty($conditions)) {
            $this->db->where($conditions);
        }
        // Filter by date range
        $this->db->where('tanggal >=', $start_date);
        $this->db->where('tanggal <=', $end_date);
        $this->db->order_by('id', 'DESC'); // Add order for consistency
        return $this->db->get('notifikasi')->result_array();
    }
    public function get_data_by_id($id)
    {
        // Use the dynamic where method
        return $this->get_all_data(['id_permintaan' => $id])[0] ?? null;
    }

    public function create_data($data)
    {
        return $this->db->insert('notifikasi', $data);
    }

    public function update_data($conditions, $data)
    {
        // Support dynamic conditions for update
        if (!empty($conditions)) {
            $this->db->where($conditions);
        }
        return $this->db->update('notifikasi', $data);
    }
}
