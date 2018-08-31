<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mkebaktian extends CI_Model {
	function __construct() {
        parent::__construct();
    }
	function count($where){
		$sql = $this->db->query("SELECT kebaktianid FROM tblkebaktian " . $where);
        return $sql;
	}
	function get($where, $sidx, $sord, $limit, $start){
		$sql = $this->db->query("SELECT *,
		DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedonview
		FROM tblkebaktian " . $where . " ORDER BY $sidx $sord LIMIT $start , $limit");
		return $sql;
	}
	
	function get_where($where){
		$sql = $this->db->query("SELECT kebaktianid FROM tblkebaktian " . $where);
		return $sql;
	}

	function add($tabel,$data){
		$sql = $this->db->insert($tabel,$data);
	}
	function edit($tabel,$data,$id){
		$query = $this->db->where("kebaktianid",$id);
		$query = $this->db->update($tabel,$data);
	}
	function del($tabel,$id){
		$query = $this->db->where("kebaktianid",$id);
		$sql = $this->db->delete($tabel);
		return $sql;
	}

	//controller
	function get_jemaat(){
		$sql = $this->db->get('tblkebaktian');
		return $sql;
	}

	function get_combo(){
		$kebaktian=":All;";
		$sqlkebaktian = $this->db->get('tblkebaktian');
		foreach ($sqlkebaktian->result() as $key) {
			$kebaktian=$kebaktian.$key->kebaktianid.":".$key->kebaktianid.";";
		}
		$kebaktian=strrev($kebaktian);
		$kebaktian=substr($kebaktian,1);
		$kebaktian=strrev($kebaktian);
		return $kebaktian;
	}
	function get_combo2(){
		$kebaktian="{value:'',text:'All'},";
		$sqlkebaktian = $this->db->get('tblkebaktian');
		foreach ($sqlkebaktian->result() as $key) {
			$kebaktian .="{value:'".$key->kebaktianid."',text:'".$key->kebaktianid."'},";
		}
		$kebaktian=strrev($kebaktian);
		$kebaktian=substr($kebaktian,1);
		$kebaktian=strrev($kebaktian);
		return $kebaktian;
	}
}