<?php
Class Musermenu extends CI_Model{

	function count($where){
		$sql = $this->db->query("SELECT usermenuid FROM tblusermenu " . $where);
        return $sql;
	}
	function get($where, $sidx, $sord, $limit, $start){
		$sql = $this->db->query("SELECT *,
		DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedonview
		FROM tblusermenu " . $where . " ORDER BY $sidx $sord LIMIT $start , $limit");
		return $sql;
	}
	function add($tabel,$data){
		$sql = $this->db->insert($tabel,$data);
	}
	function edit($tabel,$data,$id){
		$query = $this->db->where("usermenuid",$id);
		$query = $this->db->update($tabel,$data);
	}
	function getwhere($usermenuid){
		$sql = $this->db->query("SELECT *,
		DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedon
		FROM tblusermenu WHERE usermenuid ='$usermenuid' LIMIT 0,1");
		return $sql;
	}
	function del($tabel,$id){
		$query = $this->db->where("usermenuid",$id);
		$sql = $this->db->delete($tabel);
		return $sql;
	}

	function get2($menuid){
		$sql = $this->db->query("SELECT * FROM tblmenu WHERE menuid ='$menuid'");
		foreach ($sql->result() as $key) {
			$sql = $this->db->query("SELECT * FROM tblmenu WHERE menuid ='$menuid'");
		}
		return $menuname;
	}


	function addusermenu($userpk){
		$sql = $this->db->query("SELECT menuid FROM tblmenu");
		$menuid="";
		foreach ($sql->result() as $key) {
			$data = array(
				'userpk' => $userpk,
				'menuid' => $key->menuid,
				'acl' => '1111111',
				'modifiedby' =>$_SESSION['userid'],
				'modifiedon' => date("Y-m-d H:i:s")
				);
			$this->db->insert('tblusermenu',$data);
		}
		return 1;
	}
	function delusermenu($userpk){
		$query = $this->db->where("userpk",$userpk);
		$sql = $this->db->delete('tblusermenu');
	}

	//controller
	function get_form(){
		$sql = $this->db->get('tblmenu');
		return $sql;
	}

	function get_combo(){
		$usermenu=":All;";
		$sqlserving = $this->db->get('tblusermenu');
		foreach ($sqlserving->result() as $key) {
			$usermenu=$usermenu.$key->usermenuid.":".$key->usermenuid.";";
		}
		$usermenu=strrev($usermenu);
		$usermenu=substr($usermenu,1);
		$usermenu=strrev($usermenu);
		return $usermenu;
	}
}
?>
