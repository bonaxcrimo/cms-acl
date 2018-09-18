<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mpersekutuan extends MY_Model {
    protected $table = 'tblparameter';
	 public function save($data){
    	if(isset($data['parameter_key']) && !empty($data['parameter_key'])){
    		$id = $data['parameter_key'];
    		unset($data['parameter_key']);
    		$this->db->where("parameter_key",$id);
    		$result = $this->db->update($this->table,$data);
    	}else{
	    	$result = $this->db->insert($this->table,$data);
    	}
    	return $result;
    }
    public function delete($id){
    	$this->db->where(['parameter_key'=>$id]);
    	return $this->db->delete($this->table);
    }
	function count($where){
		if(strlen($where)==0){
			$new = " where parametergrpid='PERSEKUTUAN'";
		}else{
			$new = " and parametergrpid='PERSEKUTUAN'";
		}
		$sql = $this->db->query("SELECT * FROM tblparameter " . $where.$new);
        return $sql;
	}
	function get($where, $sidx, $sord, $limit, $start){
		if(strlen($where)==0){
            $new = " where parametergrpid='PERSEKUTUAN'";
        }else{
            $new = " and parametergrpid='PERSEKUTUAN'";
        }
        $query = "select * from tblparameter " . $where.$new." ORDER BY $sidx $sord LIMIT $start , $limit";
        return $this->db->query($query);
	}

	function add($tabel,$data){
		$sql = $this->db->insert($tabel,$data);
		return $sql;
	}
	function edit($tabel,$data,$id){
		$query = $this->db->where("parameter_key",$id);
		$query = $this->db->update($tabel,$data);
		return $query;
	}
}