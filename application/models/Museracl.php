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
    function get($where, $sidx, $sord, $limit, $start){
        $query = "select * from tbluseracl " . $where." ORDER BY $sidx $sord LIMIT $start , $limit";
        return $this->db->query($query);
    }
}