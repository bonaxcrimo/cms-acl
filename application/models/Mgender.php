<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mgender extends CI_Model {
	function __construct() {
        parent::__construct();
    }
	function count($where){
		$sql = $this->db->query("SELECT genderid FROM tblgender " . $where);
        return $sql;
	}
	function get($where, $sidx, $sord, $limit, $start){
		$sql = $this->db->query("SELECT *,
		DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedonview
		FROM tblgender " . $where . " ORDER BY $sidx $sord LIMIT $start , $limit");
		return $sql;
	}

	function get_where($where){
		$sql = $this->db->query("SELECT genderid FROM tblgender " . $where);
		return $sql;
	}

	function add($tabel,$data){
		$sql = $this->db->insert($tabel,$data);
	}
	function edit($tabel,$data,$id){
		$query = $this->db->where("genderid",$id);
		$query = $this->db->update($tabel,$data);
	}
	function del($tabel,$id){
		$query = $this->db->where("genderid",$id);
		$sql = $this->db->delete($tabel);
		return $sql;
	}

	//controller


	function get_combo(){
		$gender=":All;";
		$sqlgender = $this->db->get('tblgender');
		foreach ($sqlgender->result() as $key) {
			$gender=$gender.$key->genderid.":".$key->genderid.";";
		}
		$gender=strrev($gender);
		$gender=substr($gender,1);
		$gender=strrev($gender);
		return $gender;
	}
	function get_combo2(){
		$gender="{value:'',text:'All'},";
		$sqlgender = $this->db->get('tblgender');
		foreach ($sqlgender->result() as $key) {
			$gender .="{value:'".$key->genderid."',text:'".$key->genderid."'},";
		}
		$gender=strrev($gender);
		$gender=substr($gender,1);
		$gender=strrev($gender);
		return $gender;
	}
}