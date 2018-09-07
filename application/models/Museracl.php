<?php
class Museracl extends MY_Model{
    protected $table = 'tbluseracl';
    protected $alias = 'ua';
    public function count($where){
        $sql = $this->db->query("SELECT * FROM tbluseracl " . $where);
        return $sql;
    }
    function get($where, $sidx, $sord, $limit, $start){
        $query = "select * from tbluseracl " . $where." ORDER BY $sidx $sord LIMIT $start , $limit";
        return $this->db->query($query);
    }
}