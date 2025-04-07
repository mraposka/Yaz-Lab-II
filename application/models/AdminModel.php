<?php

class AdminModel extends CI_Model{

    public function __construct(){
    }

    public function get($where, $table){
        return $this->db->where($where)->get($table)->row();
    }

    public function getAll($table) {
        return $this->db->get($table)->result();
    }

    public function add($table, $data)
    {
        return $this->db->insert($table, $data);
    }

    public function delete($table,$id) {
        $this->db->where('id', $id);
        return $this->db->delete($table);
    }

    public function update($table, $data, $where)
    {
        $this->db->where($where);
        return $this->db->update($table, $data);
    }

    public function removeRole($user_id) {
        $data = array(
            'role' => NULL
        );
        $this->db->where('id', $user_id);
        return $this->db->update('users', $data);
    }

    public function getUsersWithRole() {
        $this->db->where('role IS NOT NULL');
        $query = $this->db->get('users');
        return $query->result();
    }
    
    public function insertUser($data)
    {
        if ($this->db->where('tc', $data['tc'])->get('users')->row()) {
            return false;
        }
        $data['pass'] = md5($data['pass']);
        $data['role'] = -1;
        return $this->db->insert('users', $data);
    }

    public function loginUser($tc, $password)
    {
        $user = $this->db
            ->where('tc', $tc)
            ->where('pass', md5($password))
            ->get('users')
            ->row();

        if (!$user) {
            return false;
        }
        return $user;
    }
    
}
?>