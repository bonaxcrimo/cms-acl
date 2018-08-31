<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mserving extends CI_Model {
	function __construct() {
        parent::__construct();
    }
	function count($where){
		$sql = $this->db->query("SELECT servingid FROM tblserving " . $where);
        return $sql;
	}
	function get($where, $sidx, $sord, $limit, $start){
		$sql = $this->db->query("SELECT *,
		DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedonview
		FROM tblserving " . $where . " ORDER BY $sidx $sord LIMIT $start , $limit");
		return $sql;
	}
	
	function get_where($where){
		$sql = $this->db->query("SELECT servingid FROM tblserving " . $where);
		return $sql;
	}

	function add($tabel,$data){
		$sql = $this->db->insert($tabel,$data);
	}
	function edit($tabel,$data,$id){
		$query = $this->db->where("servingid",$id);
		$query = $this->db->update($tabel,$data);
	}
	function del($tabel,$id){
		$query = $this->db->where("servingid",$id);
		$sql = $this->db->delete($tabel);
		return $sql;
	}

	//controller
	function get_jemaat(){
		$sql = $this->db->get('tblserving');
		return $sql;
	}

	function get_combo(){
		$serving=":All;";
		$sqlserving = $this->db->get('tblserving');
		foreach ($sqlserving->result() as $key) {
			$serving=$serving.$key->servingid.":".$key->servingid.";";
		}
		$serving=strrev($serving);
		$serving=substr($serving,1);
		$serving=strrev($serving);
		return $serving;
	}
}