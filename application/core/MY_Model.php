<?php

class MY_Model extends CI_Model{

    public function getBy($conditions=[],$count=false){
        $query = $this->db->where($conditions)->get($this->table);
        if($count==true){
            return $query->num_rows();
        }
        return $query->result_array();
    }
    protected function insert($insert){
        return $this->insertBatch([$insert]);
    }
    public function insertBatch($insert){
        if($this->db->insert_batch($this->table,$insert)){
            $this->insert_id = $this->db->insert_id();
            return true;
        }else{
            return false;
        }
    }
    protected function update($update,$id){
        $condition = ['ID'=>$id];
        if($this->db->update($this->table,$update,$condition)){
            return true;
        }else{
            return false;
        }
    }
    protected function removeBatch($conditions){
        foreach($conditions as $condition){
            $this->db->delete($this->table,$condition);
            $this->db->reset_query();
        }
    }
}