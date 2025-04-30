<?php

class JuryModel extends CI_Model{

    public function __construct(){
    }

    public function get($where, $table){
        return $this->db->where($where)->get($table)->row();
    }

    public function getAll($table) {
        return $this->db->get($table)->result();
    }

    public function getAllPositions() {
        return $this->db->get('positions')->result();
    }

    public function getAllDepartments() {
        return $this->db->get('deps')->result();
    }

    public function selectFromRules($select) {
        return $this->db->select($select)->get('rules')->result();
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

    public function getRules($position_id, $department_id){
        return $this->db->where('department_id', $department_id)->get('rules')->row();
    }

    public function getExtension($extension){
        return $this->db->where('title', $extension)->get('types')->row()->id ?? -1;
    }
}
?>