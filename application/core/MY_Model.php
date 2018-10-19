<?php

class MY_Model extends CI_Model{
    // public function getList($table,$conditions = [],$count=false,$limit=0,$offset=0){
    //     $this->db->from($table);
    //     if(!empty($conditions)){
    //         $this->db->where($conditions);
    //     }
    //     if(!empty($limit)){
    //         $this->db->limit($limit,$offset);
    //     }
    //     if($count===true){
    //         return $this->db->get()->num_rows();
    //     }else{
    //         return $this->db->get()->result();
    //     }
    // }
    public function getById($table,$field,$id){
        $conditions = [$field=>$id];
        $data = $this->getListAll($table,$conditions);
        if(!empty($data)){
            $data = $data[0];
            return $data;
        }
        return [];
    }
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
    protected function update($update,$id,$field){
        $condition = [$field=>$id];
        print_r($condition);
        if($this->db->update($this->table,$update,$condition)){
            return true;
        }else{
            return false;
        }
    }
    public function getListAll($table,$conditions=[],$count=false,$limit=0,$offset=0,$object=true){
        $this->db->from($table);
        if(!empty($conditions)){
            $this->db->where($conditions);
        }
        if(!empty($limit)){
            $this->db->limit($limit,$offset);
        }
        if($count===true){
            return $this->db->get()->num_rows();
        }else{
            if($object===false){
                return $this->db->get()->result_array();
            }else{
                return $this->db->get()->result_object();
            }
        }
    }
    protected function removeBatch($conditions){
        foreach($conditions as $condition){
            $this->db->delete($this->table,$condition);
            $this->db->reset_query();
        }
    }
}