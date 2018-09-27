<?php
Class Mtb extends MY_Model{
	protected $table = 'tblmember';
	public function save($data) {
        $this->db->trans_start();
        @$extphotofile=@$_POST['extphotofile'];
	    @$editphotofile=@$_POST['editphotofile'];
	    if($extphotofile!=""){
	    	if($editphotofile!=""){
	    		if (file_exists("uploads/medium_".$editphotofile)) {
					unlink("uploads/medium_".$editphotofile);
				}
				if (file_exists("uploads/small_".$editphotofile)) {
					unlink("uploads/small_".$editphotofile);
				}
				@$namephotofile = date("d-m-Y-h").substr(str_shuffle("abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz"), 0, 10) . substr(str_shuffle("abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz"), 0, 10);
	    		@$photofile = @$namephotofile.".".@$extphotofile;
		    }
	    	else{
				@$namephotofile = date("d-m-Y-h").substr(str_shuffle("abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz"), 0, 10) . substr(str_shuffle("abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz"), 0, 10);
	    		@$photofile = @$namephotofile.".".@$extphotofile;
	    	}
	    }
	    else{
	    	if($editphotofile!=""){
	    		if($editphotofile=="clearfoto"){
	    			@$photofile = "";
	    		}
	    		else{
	    			@$photofile = $editphotofile;
		    	}
	    	}
	    	else{
	    		@$photofile = "";
	    	}
	    }
	    $servingid="";
		if(!empty($_POST['servingid'])){
		    foreach ($_POST['servingid'] as $selectedOption){
	    		$servingid=$servingid.$selectedOption."/";
	    	}
	    }
	    @$dob = $data['dob'];
	    @$exp1 = explode('-',$dob);
		@$dob = $exp1[2]."-".$exp1[1]."-".$exp1[0];
		$data['dob'] = $dob;


		@$baptismdate = $data['baptismdate'];
		@$exp2 = explode('-',$baptismdate);
		@$baptismdate = $exp2[2]."-".$exp2[1]."-".$exp2[0];
		$data['baptismdate'] = $baptismdate;

		@$tglbesuk = $data['tglbesuk'];
		@$exp3 = explode('-',$tglbesuk);
		@$tglbesuk = $exp3[2]."-".$exp3[1]."-".$exp3[0];
		$data['tglbesuk']=$tglbesuk;


        $data['modifiedon'] =  date("Y-m-d H:i:s");
        $data['modifiedby'] = $this->session->userdata('username');
        if (isset($data['besukid']) && !empty($data['besukid'])) {
            $id = $data['besukid'];
            unset($data['besukid']);
            $save = $this->_preFormat($data); //format the fields

            $result = $this->update($save, $id,'besukid');
            if($result === true ){
            } else {
                $this->db->trans_rollback();
            }
        } else {
        	$save = $this->_preFormat($data);//format untuk field
            $result = $this->insert($save);
            if($result === true){

            } else {
                $this->db->trans_rollback();
            }
        }
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }
    private function _preFormat($data){
    	$fields = ['grp_pi','relationno','memberno','membername','chinesename','phoneticname','aliasname','tel_h','tel_o','handphone','address','add2','city','gender_key','pstatus_key','pob','dob','blood_key','kebaktian_key','persekutuan_key','rayon_key','status_key','serving','fax','email','website','baptismdocno','baptis','baptismdate','remark','relation','oldgrp','kebaktian','tglbesuk','teambesuk','description','photofile','modifiedby','modifiedon'];
    	$save = [];
    	foreach($fields as $val){
    		if(isset($data[$val])){
    			$save[$val] = $data[$val];
    		}
    	}
    	return $save;
    }
	function count($where){
		$where2="";
		if($where!="")
			$where2=" and status_key='18' ";
		else
			$where2=" where status_key = '18' ";
		$sql = $this->db->query("SELECT member_key FROM tblmember " . $where.$where2);
        return $sql;
	}

	function get($where, $sidx, $sord, $limit, $start){
		$sql = $this->db->query("SELECT *,
		DATE_FORMAT(tgl_hadir,'%d-%m-%Y') tgl_hadirview,
		DATE_FORMAT(dob,'%d-%m-%Y') dobview,
		DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(dob, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(dob, '00-%m-%d')) AS umur,
		DATE_FORMAT(tglbesuk,'%d-%m-%Y') tglbesukview,
		DATE_FORMAT(baptismdate,'%d-%m-%Y') baptismdateview,
		DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedonview
		FROM tblmember " . $where . " and statusid='TB' ORDER BY $sidx $sord LIMIT $start , $limit");
		return $sql;
	}
	function getM($where, $sidx, $sord, $limit, $start){
		$where2="";
		if($where!="")
			$where2=" and status_key='18' ";
		else
			$where2=" where status_key = '18' ";
		$query = "select * ,DATE_FORMAT(dob,'%d-%m-%Y') dob,
		DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(dob, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(dob, '00-%m-%d')) AS umur,
		DATE_FORMAT(tglbesuk,'%d-%m-%Y') tglbesuk,
		DATE_FORMAT(baptismdate,'%d-%m-%Y') baptismdate,
		DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedon

		from tblmember  " . $where.$where2."  ORDER BY $sidx $sord LIMIT $start , $limit";

		return $this->db->query($query);
	}

	function count_relasi($where){
		$sql = $this->db->query("SELECT * FROM tblmember t1, tbltemp".$_SESSION['userpk']." t2 WHERE t1.recno=t2.recno " . $where);
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
