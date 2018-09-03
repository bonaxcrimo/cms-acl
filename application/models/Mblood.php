<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mblood extends MY_Model {
	protected $insert_id;
	protected $table = 'tblparameter';

    public function getList($conditions = [],$count=false,$limit=0,$offset=0){
    	$table = $this->table;
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
    		return $this->db->get()->result();
    	}
    }
    public function getById($id){
    	$conditions = ['parameter_key'=>$id];
    	$blood = $this->getList($conditions);
    	if(!empty($blood)){
    		$blood = $blood[0];
    		return $blood;
    	}
    	return [];
    }
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
			$new = " where parametergrpid='BLOOD'";
		}else{
			$new = " and parametergrpid='BLOOD'";
		}
		$sql = $this->db->query("SELECT * FROM tblparameter " . $where.$new);
        return $sql;
	}
	function get($where, $sidx, $sord, $limit, $start){
		$query="SELECT *,
		DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedonview
		FROM tblblood " . $where . " ORDER BY $sidx $sord LIMIT $start , $limit";
		$sql = $this->db->query($query);

		return $sql;
	}
	function getJ($where, $sidx, $sord, $limit, $start){
		if(strlen($where)==0){
			$new = " where parametergrpid='BLOOD'";
		}else{
			$new = " and parametergrpid='BLOOD'";
		}
		$query = "select * from tblparameter " . $where.$new." ORDER BY $sidx $sord LIMIT $start , $limit";
		return $this->db->query($query);
	}
	function getM($where, $sidx, $sord, $limit, $start){
		$query = "select * from tblblood1  " . $where . " ORDER BY $sidx $sord LIMIT $start , $limit";
		// echo $query;
		return $this->db->query($query);
	}
	function get_where($where){
		$sql = $this->db->query("SELECT bloodid FROM tblblood " . $where);
		return $sql;
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
	function del($tabel,$id){
		$query = $this->db->where("parameter_key",$id);
		$sql = $this->db->delete($tabel);
		return $sql;
	}

	//controller
	function get_jemaat(){
		$sql = $this->db->get('tblblood');
		return $sql;
	}

	function get_combo(){
		$blood=":All;";
		$sqlblood = $this->db->get('tblblood');
		foreach ($sqlblood->result() as $key) {
			$blood=$blood.$key->bloodid.":".$key->bloodid.";";
		}
		$blood=strrev($blood);
		$blood=substr($blood,1);
		$blood=strrev($blood);
		return $blood;
	}
	function get_combo2(){
		$blood="{value:'',text:'All'},";
		$sqlblood = $this->db->get('tblblood');
		foreach ($sqlblood->result() as $key) {
			$blood .="{value:'".$key->bloodid."',text:'".$key->bloodid."'},";
		}
		$blood=strrev($blood);
		$blood=substr($blood,1);
		$blood=strrev($blood);
		return $blood;
	}
}