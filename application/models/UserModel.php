<?php

class UserModel extends CI_Model{

    public function __construct(){
    }

    public function get($where, $table){
        return $this->db->where($where)->get($table)->row();
    }

    public function getApplications($user){
        return $this->db->where('user_id',$user)->where('status!=',3)->get('application')->result();
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

    public function getUsersApplications($user,$status)
    {
        return $this->db->where('user_id',$user)->where('status',$status)->get('application')->result();
    }
    public function getRules($position_id, $department_id)
    {
        return $this->db->where('department_id', $department_id)->where('position_id', $position_id)->get('rules')->row();
    }

    public function getColabMultiplier($colab)
    {
        return $this->db->where('colab', $colab)->get('colab_values')->row()->multiplier;
    }

    public function getCatPoint($cat)
    {
        return $this->db->where('cat', $cat)->get('points')->row()->point;
    }

    public function getExtension($extension)
    {
        if(empty($this->db->where('title', $extension)->get('types')->row()->id))
        {
            $this->db->insert('types',['title'=>$extension]);
        }
        return $this->db->where('title', $extension)->get('types')->row()->id;
    }
}
?>