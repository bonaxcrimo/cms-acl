<?php
class Muserroles extends MY_Model{
    protected $table = 'tbluserroles';
    protected $alias = 'ur';
    public function getList($conditions =[],$count = false,$limit=0,$offset=0){
        $table= $this->table;
        $alias = $this->alias;
        $this->db->from($table.' as '.$alias);
        if(!empty($conditions)){
            $this->db->where($conditions);
        }
        if(!empty($limit)){
            $this->db->limit($limit,$offset);
        }
        if($count===true){
            $this->db->get()->num_rows();
        }else{
            $this->db->get()->result_array();
        }
    }
    public function getByUserID($user_id){
        return $this->getList(['userpk'=>$user_id]);
    }
    protected function getListByGroup($conditions){
        $this->db->select('GROUP CONCAT('.$this->alias.'.roleid SEPARATOR ",") as roles')
            ->where($conditions)
            ->from($this->table.' as '.$this->alias);
        return $this->db->get()->result_array();
    }
    public function deleteByRole($role_id){
        $this->db->where(['roleid'=>$role_id])->delete($this->table);
        return true;
    }
}