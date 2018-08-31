<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mpersekutuan extends CI_Model {
	function __construct() {
        parent::__construct();
    }
	function count($where){
		$sql = $this->db->query("SELECT persekutuanid FROM tblpersekutuan " . $where);
        return $sql;
	}
	function get($where, $sidx, $sord, $limit, $start){
		$sql = $this->db->query("SELECT *,
		DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedonview
		FROM tblpersekutuan " . $where . " ORDER BY $sidx $sord LIMIT $start , $limit");
		return $sql;
	}
	
	function get_where($where){
		$sql = $this->db->query("SELECT persekutuanid FROM tblpersekutuan " . $where);
		return $sql;
	}

	function add($tabel,$data){
		$sql = $this->db->insert($tabel,$data);
	}
	function edit($tabel,$data,$id){
		$query = $this->db->where("persekutuanid",$id);
		$query = $this->db->update($tabel,$data);
	}
	function del($tabel,$id){
		$query = $this->db->where("persekutuanid",$id);
		$sql = $this->db->delete($tabel);
		return $sql;
	}

	//controller
	function get_jemaat(){
		$sql = $this->db->get('tblpersekutuan');
		return $sql;
	}

	function get_combo(){
		$persekutuan=":All;";
		$sqlpersekutuan = $this->db->get('tblpersekutuan');
		foreach ($sqlpersekutuan->result() as $key) {
			$persekutuan=$persekutuan.$key->persekutuanid.":".$key->persekutuanid.";";
		}
		$persekutuan=strrev($persekutuan);
		$persekutuan=substr($persekutuan,1);
		$persekutuan=strrev($persekutuan);
		return $persekutuan;
	}
	function get_combo2(){
		$persekutuan="{value:'',text:'All'},";
		$sqlpersekutuan = $this->db->get('tblpersekutuan');
		foreach ($sqlpersekutuan->result() as $key) {
			$persekutuan .="{value:'".$key->persekutuanid."',text:'".$key->persekutuanid."'},";
		}
		$persekutuan=strrev($persekutuan);
		$persekutuan=substr($persekutuan,1);
		$persekutuan=strrev($persekutuan);
		return $persekutuan;
	}
}