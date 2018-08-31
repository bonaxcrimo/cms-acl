<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mpstatus extends CI_Model {
	function __construct() {
        parent::__construct();
    }
	function count($where){
		$sql = $this->db->query("SELECT pstatusid FROM tblpstatus " . $where);
        return $sql;
	}
	function get($where, $sidx, $sord, $limit, $start){
		$sql = $this->db->query("SELECT *,
		DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedonview
		FROM tblpstatus " . $where . " ORDER BY $sidx $sord LIMIT $start , $limit");
		return $sql;
	}
	
	function get_where($where){
		$sql = $this->db->query("SELECT pstatusid FROM tblpstatus " . $where);
		return $sql;
	}

	function add($tabel,$data){
		$sql = $this->db->insert($tabel,$data);
	}
	function edit($tabel,$data,$id){
		$query = $this->db->where("pstatusid",$id);
		$query = $this->db->update($tabel,$data);
	}
	function del($tabel,$id){
		$query = $this->db->where("pstatusid",$id);
		$sql = $this->db->delete($tabel);
		return $sql;
	}

	//controller
	function get_jemaat(){
		$sql = $this->db->get('tblpstatus');
		return $sql;
	}

	function get_combo(){
		$pstatus=":All;";
		$sqlpstatus = $this->db->get('tblpstatus');
		foreach ($sqlpstatus->result() as $key) {
			$pstatus=$pstatus.$key->pstatusid.":".$key->pstatusid.";";
		}
		$pstatus=strrev($pstatus);
		$pstatus=substr($pstatus,1);
		$pstatus=strrev($pstatus);
		return $pstatus;
	}
}