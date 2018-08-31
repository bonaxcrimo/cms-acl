<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mrayon extends CI_Model {
	function __construct() {
        parent::__construct();
    }
	function count($where){
		$sql = $this->db->query("SELECT rayonid FROM tblrayon " . $where);
        return $sql;
	}
	function get($where, $sidx, $sord, $limit, $start){
		$sql = $this->db->query("SELECT *,
		DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedonview
		FROM tblrayon " . $where . " ORDER BY $sidx $sord LIMIT $start , $limit");
		return $sql;
	}
	
	function get_where($where){
		$sql = $this->db->query("SELECT rayonid FROM tblrayon " . $where);
		return $sql;
	}

	function add($tabel,$data){
		$sql = $this->db->insert($tabel,$data);
	}
	function edit($tabel,$data,$id){
		$query = $this->db->where("rayonid",$id);
		$query = $this->db->update($tabel,$data);
	}
	function del($tabel,$id){
		$query = $this->db->where("rayonid",$id);
		$sql = $this->db->delete($tabel);
		return $sql;
	}

	//controller
	function get_jemaat(){
		$sql = $this->db->get('tblrayon');
		return $sql;
	}

	function get_combo(){
		$rayon=":All;";
		$sqlrayon = $this->db->get('tblrayon');
		foreach ($sqlrayon->result() as $key) {
			$rayon=$rayon.$key->rayonid.":".$key->rayonid.";";
		}
		$rayon=strrev($rayon);
		$rayon=substr($rayon,1);
		$rayon=strrev($rayon);
		return $rayon;
	}
}