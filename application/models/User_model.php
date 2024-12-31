<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
    protected $table = 'users'; // Nama tabel di database

    public function get_all_users()
    {
        return $this->db->get('users')->result_array();
    }
    public function count_all()
    {

        return $this->db->count_all_results($this->table);
    }
    public function get_user_by_id($id)
    {
        return $this->db->get_where('users', ['id' => $id])->row_array();
    }

    public function create_user($data)
    {
        return $this->db->insert('users', $data);
    }

    public function update_user($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }

    public function delete_user($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('users');
    }
    public function get_user_by_email($email)
    {
        // Ambil user berdasarkan email
        $this->db->where('email', $email);
        $query = $this->db->get('users');
        return $query->row_array();  // Mengembalikan satu baris data pengguna
    }
    public function get_user_by_role($role)
    {
        // Ambil user berdasarkan email
        $this->db->where('role', $role);
        $query = $this->db->get('users');
        return $query->result_array();  // Mengembalikan satu baris data pengguna
    }
}
