<?php
Class Mjemaat extends CI_Model{

	function count($where){
		$sql = $this->db->query("SELECT member_key FROM tblmember " . $where);
        return $sql;
	}
	function get($where, $sidx, $sord, $limit, $start){
		$sql = $this->db->query("SELECT *,
		DATE_FORMAT(dob,'%d-%m-%Y') dobview,
		DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(dob, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(dob, '00-%m-%d')) AS umur,
		DATE_FORMAT(tglbesuk,'%d-%m-%Y') tglbesukview,
		DATE_FORMAT(baptismdate,'%d-%m-%Y') baptismdateview,
		DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedonview
		FROM tblmember " . $where . " ORDER BY $sidx $sord LIMIT $start , $limit");
		return $sql;
	}
	function getM($where, $sidx, $sord, $limit, $start){
	
		$query = "select * ,DATE_FORMAT(dob,'%d-%m-%Y') dob,
		DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(dob, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(dob, '00-%m-%d')) AS umur,
		DATE_FORMAT(tglbesuk,'%d-%m-%Y') tglbesuk,
		DATE_FORMAT(baptismdate,'%d-%m-%Y') baptismdate,
		DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedon
		
		from tblmember  " . $where . " ORDER BY $sidx $sord LIMIT $start , $limit";
		// echo $query;
		return $this->db->query($query);
	}
	function getJ($where, $sidx, $sord, $limit, $start){
		$query = "select * from tblmember  " . $where . " ORDER BY $sidx $sord LIMIT $start , $limit";
		return $this->db->query($query);
	}
	function count_relasi($where){
		$sql = $this->db->query("SELECT * FROM tblmember t1, tbltemp".$_SESSION['userpk']." t2 WHERE t1.member_key=t2.member_key " . $where);
        if($sql){
        	$data = $sql;
        }
        else{
        	$data = 0;
        }
        return $data;
	}
	function get_relasi($where, $sidx, $sord, $limit, $start){
		$sql = $this->db->query("SELECT *,
		DATE_FORMAT(dob,'%d-%m-%Y') dobview,
		DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(dob, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(dob, '00-%m-%d')) AS umur,
		DATE_FORMAT(tglbesuk,'%d-%m-%Y') tglbesukview,
		DATE_FORMAT(baptismdate,'%d-%m-%Y') baptismdateview,
		DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedonview
		FROM tblmember t1, tbltemp".$_SESSION['userpk']." t2 WHERE t1.recno=t2.recno " . $where . " ORDER BY $sidx $sord LIMIT $start , $limit");
		if($sql){
        	$data = $sql;
        }
        else{
        	$data = 0;
        }
        return $data;
	}
	function get_relasiM($where, $sidx, $sord, $limit, $start){
		$sql = $this->db->query("SELECT *,
		DATE_FORMAT(dob,'%d-%m-%Y') dobview,
		DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(dob, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(dob, '00-%m-%d')) AS umur,
		DATE_FORMAT(tglbesuk,'%d-%m-%Y') tglbesukview,
		DATE_FORMAT(baptismdate,'%d-%m-%Y') baptismdateview,
		DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedonview
		FROM tblmember t1, tbltemp".$_SESSION['userpk']." t2 WHERE t1.recno=t2.recno " . $where . " ORDER BY t1.$sidx $sord LIMIT $start , $limit");
		if($sql){
        	$data = $sql;
        }
        else{
        	$data = 0;
        }
        return $data;
	}

	function add($tabel,$data){
		$sql = $this->db->insert($tabel,$data);
	}
	function edit($tabel,$data,$id){
		$query = $this->db->where("member_key",$id);
		$query = $this->db->update($tabel,$data);
	}

	function creat(){
		$tbl = "tbltemp".$_SESSION['userpk'];
		$sql = $this->db->get($tbl);
		$recno="";
		$rel = "R-".substr(str_shuffle("123456789"),0,1).substr(str_shuffle("123456789"),0,1).substr(str_shuffle("123456789"),0,1).substr(str_shuffle("123456789"),0,1).substr(str_shuffle("123456789"),0,1);

		foreach ($sql->result() as $key) {
			$recno = $key->recno;
			$this->db->query("UPDATE tblmember SET relationno='$rel' WHERE recno='$recno'");
			$this->db->query("DELETE FROM $tbl WHERE recno='$recno'");
		}
		return 1;
	}

	function deletetabel(){
		$tbl = "tbltemp".$_SESSION['userpk'];
		$this->db->query("DROP TABLE ".$tbl);
		return 1;
	}

	function deletecreat($recno){
		$tbl = "tbltemp".$_SESSION['userpk'];
		$sql = $this->db->where("recno",$recno);
		$sql = $this->db->delete($tbl);
		return 1;
	}

	function deletecreatall(){
		$tbl = "tbltemp".$_SESSION['userpk'];
		$sql = $this->db->query("DELETE FROM ".$tbl);
	}

	function getwhere($member_key){
		$sql = $this->db->query("SELECT *,
		DATE_FORMAT(dob,'%d-%m-%Y') dob,
		DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(dob, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(dob, '00-%m-%d')) AS umur,
		DATE_FORMAT(tglbesuk,'%d-%m-%Y') tglbesuk,
		DATE_FORMAT(baptismdate,'%d-%m-%Y') baptismdate,
		DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedon
		FROM tblmember WHERE member_key ='$member_key' LIMIT 0,1");
		return $sql;
	}
	function del($tabel,$id){
		$query = $this->db->where("member_key",$id);
		$sql = $this->db->delete($tabel);
		return $sql;
	}

	function jlhbesuk($member_key){
		$sql = $this->db->where('member_key',$member_key)->count_all_results('tblbesuk');
		return $sql;
	}

	function tglbesukterakhir($member_key){
		$sql = $this->db->query("SELECT DATE_FORMAT(besukdate,'%d-%m-%Y') besukdate FROM tblbesuk WHERE member_key='$member_key' ORDER BY besukdate ASC");
		if($sql->num_rows>=1){
			foreach ($sql->result() as $key) {
				$tgl = $key->besukdate;
			}
		}
		else{
			$tgl="";
		}
		return $tgl;
	}

	function creat_relation($recno){
		$sql = $this->db->query("CREATE TABLE tbltemp".$_SESSION['userpk']." (recno VARCHAR(30),UNIQUE KEY recno (recno))");
	}
	function simpan_relation($recno){
		$sql = $this->db->query("INSERT INTO tbltemp".$_SESSION['userpk']." (recno) VALUES('$recno')");
	}
}
?>
