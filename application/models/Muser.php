<?php
Class Muser extends CI_Model{

	function count($where){
		$sql = $this->db->query("SELECT userpk FROM tbluser " . $where);
        return $sql;
	}
	function get($where, $sidx, $sord, $limit, $start){
		$sql = $this->db->query("SELECT *,
		DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedonview
		FROM tbluser " . $where . " ORDER BY $sidx $sord LIMIT $start , $limit");
		return $sql;
	}
	function add($tabel,$data){
		$this->db->insert($tabel,$data);
		$userpk="";
		$sql = $this->db->query("SELECT userpk FROM tbluser ORDER BY userpk DESC LIMIT 0,1");
		foreach ($sql->result() as $key) {
			$userpk .= $key->userpk;
		}
		return $userpk;
	}
	function edit($tabel,$data,$id){
		$query = $this->db->where("userpk",$id);
		$query = $this->db->update($tabel,$data);
	}
	function getwhere($userpk){
		$sql = $this->db->query("SELECT *,
		DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedon
		FROM tbluser WHERE userpk ='$userpk' LIMIT 0,1");
		return $sql;
	}
	function del($tabel,$id){
		$query = $this->db->where("userpk",$id);
		$sql = $this->db->delete($tabel);
		return $sql;
	}
}
?>
