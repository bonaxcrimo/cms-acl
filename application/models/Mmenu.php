<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mmenu extends MY_Model
{
	function count($where){
		$sql = $this->db->query("SELECT menuid FROM tblmenu " . $where);
        return $sql;
	}
	function get($where, $sidx, $sord, $limit, $start){
		$sql = $this->db->query("SELECT *,
		DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedon
		FROM tblmenu " . $where . " ORDER BY $sidx $sord, menuseq ASC LIMIT $start , $limit");
		return $sql;
	}

	function get_where($where){
		$sql = $this->db->query("SELECT menuid FROM tblmenu " . $where);
		return $sql;
	}

	function add($tabel,$data){
		$sql = $this->db->insert($tabel,$data);
	}
	function edit($tabel,$data,$id){
		$query = $this->db->where("menuid",$id);
		$query = $this->db->update($tabel,$data);
	}
	function del($tabel,$id){
		$query = $this->db->where("menuid",$id);
		$sql = $this->db->delete($tabel);
		return $sql;
	}

	function reseq(){
		$sql = $this->db->query("SELECT DISTINCT(menuparent) FROM tblmenu ORDER BY menuid ASC");
		foreach ($sql->result() as $key) {
			$sql = $this->db->query("SELECT menuid FROM tblmenu WHERE menuparent='$key->menuparent' ORDER BY menuseq ASC");
			$i=0;
			foreach ($sql->result() as $key) {
				$i=$i+10;
				$this->db->query("UPDATE tblmenu SET menuseq='$i' WHERE menuid='$key->menuid'");
			}
		}
		return 1;
	}

	//controller
	function get_jemaat(){
		$sql = $this->db->get('tblmenu');
		return $sql;
	}

	function get_combo(){
		$menu=":All;";
		$sqlmenu = $this->db->get('tblmenu');
		foreach ($sqlmenu->result() as $key) {
			$menu=$menu.$key->menuid.":".$key->menuid.";";
		}
		$menu=strrev($menu);
		$menu=substr($menu,1);
		$menu=strrev($menu);
		return $menu;

	}
}