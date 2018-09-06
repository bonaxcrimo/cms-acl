<?php
class Macl extends MY_Model{
    protected $table = 'tblacl';
    protected $alias = 'a';
    public function getList($conditions = [],$count = false,$limit=0,$offset=0){
        $table = $this->table;
        $alias = $this->alias;
        $this->db->from($table.' as '.$alias);
        if(!empty($conditions))
            $this->db->where($conditions);
        if(!empty($limit))
            $this->db->limit($limit,$offset);
        if($count===true){
            return $this->db->get()->num_rows();
        }else{
            return $this->db->get()->result_array();
        }
    }
    public function getByRole($role_id){
        return $this->getList(['roleid'=>$role_id]);
    }
    public function saveBatch($data){
        $insert= [];
        if(isset($data['acos'])){
            $records = $this->getList(['roleid'=>$data['roleid']],true);
            if(empty($records)){
                foreach($data['acos'] as $aco){
                    $insert[] = [
                        'roleid'=>$data['roleid'],
                        'acoid'=>$aco,
                        'modifiedby'=>$this->session->userdata('username')
                    ];
                }
                return $this->insertBatch($insert);
            }else{
                $records = $this->getListByGroup(['roleid'=>$data['roleid']]);
                $acos = strpos($records[0]['acos'],',')===false?[$records[0]['acos']]:explode(', ',$records[0]['acos']);
                $inserts = array_diff($data['acos'],$acos);
                $removes = array_diff($acos,$data['acos']);
                if(!empty($inserts)){
                    $insert= [];
                    foreach($inserts as $val){
                        $insert[]=[
                            'roleid'=>$data['roleid'],
                            'acoid'=>$val,
                            'modifiedby'=>$this->session->userdata('username')
                        ];
                    }
                    $this->insertBatch($insert);
                }
                if(!empty($removes)){
                    $remove =[];
                    foreach($removes as $val){
                        $remove[] = [
                            'roleid' =>$data['roleid'],
                            'acoid'=>$val
                        ];
                    }
                    $this->removeBatch($remove);
                }
                return true;
            }
        }
        return false;
    }
    protected function getListByGroup($conditions){
        $this->db->select('GROUP_CONCAT('.$this->alias.'.acoid SEPARATOR "," ) as acos ')
            ->where($conditions)
            ->from($this->table.' as '.$this->alias);

        return $this->db->get()->result_array();
    }
    public function getByRoles($roles){
        if(empty($roles)){
            return [];
        }
        $alias = $this->alias;
        $acl  = $this->db->distinct('acos.class,acos.method,acos.display_name')
                    ->where_in('roleid',$roles)
                    ->from($this->table.' as '.$this->alias)
                    ->join('tblacos','tblacos.acosid = '.$this->alias.'.acoid','inner')
                    ->get()
                    ->result_array();

        return $acl;
    }
    public function deleteByRole($role_id){
        $this->db->where(['roleid'=>$role_id])->delete($this->table);
        return true;
    }
}