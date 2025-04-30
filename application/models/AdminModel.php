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

    public function updateAd($data){
        $id=$data['id'];
        unset($data['id']);
        return $this->db->where('id',$id)->update('ads',$data);
    }

    public function getAdsWithJuries()
    {
        $query = $this->db->get('ads_juries');
        $rows = $query->result();

        $adsMap = [];
        foreach ($rows as $row) {
            $adsId = $row->ads_id;
            $userId = $row->user_id;

            if (!isset($adsMap[$adsId])) {
                $adsMap[$adsId] = [];
            }

            $adsMap[$adsId][] = $userId;
        }

        $finalData = [];

        foreach ($adsMap as $adsId => $userIds) {
            $adsTitleQuery = $this->db->get_where('ads', ['id' => $adsId])->row();
            $adsTitle = isset($adsTitleQuery->title) ? $adsTitleQuery->title : 'N/A';
            $userNames = [];
            foreach ($userIds as $uid) {
                $userRow = $this->db->get_where('users', ['id' => $uid])->row();
                if ($userRow) {
                    $userNames[] = $userRow->name . ' ' . $userRow->surname;
                }
            }

            $finalData[] = [
                'id' => $adsId,
                'title' => $adsTitle,
                'jury' => implode("<br>", $userNames),
            ];
        }

        return $finalData;
    }

    public function deleteJuries($id) {
        $this->db->where('ads_id', $id);
        return $this->db->delete('ads_juries');
    }

    public function getAllJuries() {
        return $this->db->where('role','Juri')->get('users')->result();
    }

    public function getAllAdverts() {
        $subQuery = $this->db->select('ads_id')->from('ads_juries')->get()->result_array();
        $excludedIds = array_column($subQuery, 'ads_id');
    
        if (!empty($excludedIds)) {
            $this->db->where_not_in('id', $excludedIds); // doğru metod bu!
        }
    
        return $this->db->get('ads')->result();
    }    
    
}
?>