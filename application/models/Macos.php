<?php
class Macos extends MY_Model{
    protected $table = 'tblacos';
    protected $alias = 'tblacos';

    public function getList($conditions=[],$count=false,$limit=0,$offset=0){
        $table =$this->table;
        $alias = $this->alias;
        $this->db->from($table.' as '.$alias);
        $select = "";
        if(!empty($conditions)){
            $this->db->where($conditions);
        }
        if(!empty($limit)){
            $this->db->limit($limit,$offet);
        }
        if($count===true){
            $this->db->get()->num_rows();
        }else{
            $this->db->get()->result_array();
        }
    }
    public function getByMultiId($ids){
        $acos = $this->db->where_in('id',$ids)
                ->from($this->table)
                ->get()
                ->result_array();
        return $acos;
    }
    public function save($data){
        $conditions = [
            'class' => $data['class'],
            'method' =>$data['method']
        ];
        if($this->getBy($conditions,true) > 0){
            $result = $this->getBy($conditions);
            $id = $result[0]['id'];
            return $this->update($data,$id);
        }else{
            return $this->insert($data);
        }
    }
}