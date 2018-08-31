<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mparameter extends CI_Model {
	function __construct() {
        parent::__construct();
    }
	function count($where){
		$sql = $this->db->query("SELECT parameterpk FROM tblparameter " . $where);
        return $sql;
	}
	function get($where, $sidx, $sord, $limit, $start){
		$sql = $this->db->query("SELECT *,
		DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedonview
		FROM tblparameter " . $where . " ORDER BY $sidx $sord LIMIT $start , $limit");
		return $sql;
	}
	function get_where($where){
		$sql = $this->db->query("SELECT parameterpk FROM tblparameter " . $where);
		return $sql;
	}

	function add($tabel,$data){
		$sql = $this->db->insert($tabel,$data);
	}
	function edit($tabel,$data,$id){
		$query = $this->db->where("parameterpk",$id);
		$query = $this->db->update($tabel,$data);
	}
	function del($tabel,$id){
		$query = $this->db->where("parameterpk",$id);
		$sql = $this->db->delete($tabel);
		return $sql;
	}

	//controller
	function get_bgfilter(){
		$bgfilter="";
		$sqlbgfilter = $this->db->query('SELECT * FROM tblparameter WHERE parametergrpid="BACKGROUND" AND parameterid="FILTER" ');
		foreach ($sqlbgfilter->result() as $key) {
			$bgfilter="#".$key->parametertext;
		}
		return $bgfilter;
	}

	function get_bgsortira(){
		$bgsortira="";
		$sqlbgsortir = $this->db->query('SELECT * FROM tblparameter WHERE parametergrpid="BACKGROUND" AND parameterid="SORTIRA" ');
		foreach ($sqlbgsortir->result() as $key) {
			$bgsortira="#".$key->parametertext;
		}
		return $bgsortira;
	}

	function get_bgsortird(){
		$bgsortird="";
		$sqlbgsortir = $this->db->query('SELECT * FROM tblparameter WHERE parametergrpid="BACKGROUND" AND parameterid="SORTIRD" ');
		foreach ($sqlbgsortir->result() as $key) {
			$bgsortird="#".$key->parametertext;
		}
		return $bgsortird;
	}

	function get_jemaat(){
		$sql = $this->db->query('SELECT * FROM tblparameter WHERE parametergrpid="STATUS"');
		return $sql;
	}

	function get_combo(){
		$parameter=":All;";
		$sqlparameter = $this->db->query('SELECT * FROM tblparameter WHERE parametergrpid="STATUS"');
		foreach ($sqlparameter->result() as $key) {
			$parameter=$parameter.$key->parameterid.":".$key->parametertext.";";
		}
		$parameter=strrev($parameter);
		$parameter=substr($parameter,1);
		$parameter=strrev($parameter);
		return $parameter;
	}

	function get_combo_all(){
		$parameter=":All;";
		$sqlparameter = $this->db->query('SELECT * FROM tblparameter WHERE parametergrpid="STATUS" and parameterid<>"M" and parameterid<>"TB" ');
		foreach ($sqlparameter->result() as $key) {
			$parameter=$parameter.$key->parameterid.":".$key->parametertext.";";
		}
		$parameter=strrev($parameter);
		$parameter=substr($parameter,1);
		$parameter=strrev($parameter);
		return $parameter;
	}


	function get_combo_tb(){
		$parameter=":All;";
		$sqlparameter = $this->db->query('SELECT * FROM tblparameter WHERE parametergrpid="STATUS" and parameterid="TB"');
		foreach ($sqlparameter->result() as $key) {
			$parameter=$parameter.$key->parameterid.":".$key->parametertext.";";
		}
		$parameter=strrev($parameter);
		$parameter=substr($parameter,1);
		$parameter=strrev($parameter);
		return $parameter;
	}

}