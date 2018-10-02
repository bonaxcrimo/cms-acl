<?php
class Museracl extends MY_Model{
    protected $table = 'tbluseracl';
    protected $alias = 'ua';
    public function count($where){
        $sql = $this->db->query("SELECT * FROM tbluseracl " . $where);
        return $sql;
    }
    public function getList($conditions=[],$count=false,$limit=0,$offset=0){
        $table= "tbluser";
        $alias = "u";
        $this->db->from($table.' as '.$alias);
        $select = "$alias.userpk, (select GROUP_CONCAT(tbluseracl.acoid SEPARATOR ',') from tbluseracl where tbluseracl.userpk= $alias.userpk) as acos";
        if(!empty($conditions)){
            $this->db->where($conditions);
        }
        if(!empty($limit)){
            $this->db->limit($limit,$offset);
        }
        if($count===true){
            return $this->db->get()->num_rows();
        }else{
            $this->db->select($select);
            return $this->db->get()->result();
        }
    }
    public function getByUserPK($userpk){
       if(empty($userpk)){
            return [];
        }
        $alias = $this->alias;
        $acl  = $this->db->distinct('acos.class,acos.method,acos.display_name')
                    ->where_in('userpk',$userpk)
                    ->from($this->table.' as '.$this->alias)
                    ->join('tblacos','tblacos.acosid = '.$this->alias.'.acoid','inner')
                    ->get()
                    ->result_array();

        return $acl;
    }
    public function getByIdUser($id){
        $conditions = [
            'userpk' =>$id
        ];
        $data = $this->getList($conditions);
        if(!empty($data)){
            $data = $data[0];
        }
        return $data;
    }
    public function save($data){
        $this->db->trans_start();
        if(isset($data['userpk']) && !empty($data['userpk'])){
            $id = $data['userpk'];
            $this->saveRolePermission($id,$data['role_permission']);
        }
        if($this->db->trans_status() === false){
            $this->db->trans_rollback();
            return false;
        }else{
            $this->db->trans_commit();
            return true;
        }
    }
    public function saveRolePermission($userpk,$acos){
        $result = $this->saveBatch(['userpk'=>$userpk,'acos'=>$acos]);
        return $result;
    }
    public function saveBatch($data){
        $insert= [];
        if(isset($data['acos'])){
            $records = $this->getListAll('tbluseracl',['userpk'=>$data['userpk']],true);
            if(empty($records)){
                foreach($data['acos'] as $aco){
                    $insert[] = [
                        'userpk'=>$data['userpk'],
                        'acoid'=>$aco,
                        'modifiedby'=>$_SESSION['username'],
                        'modifiedon'=>date("Y-m-d H:i:s")
                    ];
                }
                return $this->insertBatch($insert);
            }else{
                $records = $this->getListByGroup(['userpk'=>$data['userpk']]);
                $acos = strpos($records[0]['acos'],',')===false?[$records[0]['acos']]:explode(', ',$records[0]['acos']);
                $inserts = array_diff($data['acos'],$acos);
                $removes = array_diff($acos,$data['acos']);
                if(!empty($inserts)){
                    $insert= [];
                    foreach($inserts as $val){
                        $insert[]=[
                            'userpk'=>$data['userpk'],
                            'acoid'=>$val,
                            'modifiedby'=>$_SESSION['username'],
                            'modifiedon'=>date("Y-m-d H:i:s")
                        ];
                    }
                    $this->insertBatch($insert);
                }
                if(!empty($removes)){
                    $remove =[];
                    foreach($removes as $val){
                        $remove[] = [
                            'userpk' =>$data['userpk'],
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
    public function get($where, $sidx, $sord, $limit, $start){
        $query = "select * from tbluseracl " . $where." ORDER BY $sidx $sord LIMIT $start , $limit";
        return $this->db->query($query);
    }
}