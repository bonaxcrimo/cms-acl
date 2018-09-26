<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tb extends CI_Controller {

	public function __construct(){
		parent::__construct();

		session_start();
		$this->load->model('mtb');
		$this->load->model('mgender');
		$this->load->model('mpstatus');
		$this->load->model('mparameter');
		$this->load->model('mblood');
		$this->load->model('mkebaktian');
		$this->load->model('mpersekutuan');
		$this->load->model('mrayon');
		$this->load->model('mserving');
		$this->load->model('mmenu');

	}

	function download($filename){
		$this->load->helper('download');
		$data = file_get_contents('uploads/'.$filename);
		force_download($filename,$data);
	}

	function index(){
		$this->view();
	}

	function m(){
		$this->view();
	}

	function pi(){
		$this->view();
	}

	function creatrelation(){
		$this->mtb->creat_relation();
		echo 1;
	}

	function simpan_relation($recno){
		$this->mtb->simpan_relation($recno);
		echo $recno;
	}

	function view(){


		$data['sqlgender'] = getParameter('GENDER');
		$data['sqlpstatus'] =getParameter('PSTATUS');

		$data['sqlstatusidv'] = getParameter('STATUS');
		$data['sqlblood'] =getParameter('BLOOD');
		$data['sqlkebaktian'] =getParameter('KEBAKTIAN');
		$data['sqlpersekutuan'] =getParameter('PERSEKUTUAN');
		$data['sqlrayon'] =getParameter('RAYON');
		$data['listTable'] = $this->db->list_fields('tblmember');

		$data['statusidv'] = getComboParameter('STATUS');
		$data['blood'] = getComboParameter('BLOOD');
		$data['gender'] = getComboParameter('GENDER');
		$data['pstatus'] = getComboParameter('PSTATUS');
		$data['kebaktian'] = getComboParameter('KEBAKTIAN');
		$data['persekutuan'] =getComboParameter('PERSEKUTUAN');
		$data['rayon'] = getComboParameter('RAYON');

		$this->load->view('header');
		$this->load->view('navbar',$data);
		// $this->load->view('tb/gridjemaat',$data);
		$this->load->view('footer');
	}

	function grid(){

		if($this->uri->segment(4)!=false){
			@$acl = $this->uri->segment(4);
		}
		@$status = $this->uri->segment(3);

		@$page = $_POST['page'];
		@$limit = $_POST['rows'];
		@$sidx = $_POST['sidx'];
		@$sord = $_POST['sord'];
		if (!$sidx)
		    $sidx = 1;
		@$totalrows = isset($_POST['totalrows']) ? $_POST['totalrows'] : false;
		if (@$totalrows) {
		   @$limit = $totalrows;
		}
		@$filters = $_POST['filters'];
		@$search = $_POST['_search'];
			$where1="";
			$where2="";
			if($status=="m"){
				$where1 = " WHERE statusid='M' ";
			}
			else if($status=="pi"){
				$where1 = " WHERE grp_pi=1 ";
			}
			else{
				$where1 = " WHERE statusid='TB' ";
			}
       		if($search== "true") {
				$where2 = " AND (".$this->operation($filters).")";
		    }
		    $where = $where1." ".$where2;

		$sql = $this->mtb->count($where);
		$count = $sql->num_rows();
		if ($count > 0) {
		    @$total_pages = ceil($count / $limit);
		} else {
		    $total_pages = 0;
		}
		if ($page > $total_pages)
		    @$page = $total_pages;
		if ($limit < 0)
		    @$limit = 0;
			$start = $limit * $page - $limit;
		if ($start < 0)
		    @$start = 0;
		$data = $this->mtb->get($where, $sidx, $sord, $limit, $start);
		$_SESSION['exceljemaat']= $sord."|".$sidx."|".$where;
		@$responce->page = $page;
		@$responce->total = $total_pages;
		@$responce->records = $count;
		$i=0;

		foreach($data->result() as $row){
			$relation='<a href="#" id="'.$row->relationno.'" title="View Relation" class="relation"><span class="ui-icon ui-icon-note"></span></a>';
			if($row->photofile!=""){
				$photofile="<img style='margin:0 17px;' src='".base_url()."uploads/small_".$row->photofile."' id='btnzoom' fimage='".$row->photofile."' class='dg-picture-zoom'>";
			}
			else{
				$photofile="<img style='margin:0 17px;' src='".base_url()."uploads/small_nofoto.jpg' id='btnzoom' fimage='nofoto.jpg' class='dg-picture-zoom'>";
			}
			if(substr($acl,0,1)==1){
				$view='<a href="#" id='.$row->recno.' title="View" class="btnview" style="float:left"><span class="ui-icon ui-icon-document"></span></a>';
			}
			else{
				$view='<span style="float:left" class="ui-state-disabled ui-icon ui-icon-document"></span>';
			}
			if(substr($acl,2,1)==1){
				$edit='<a href="#" id='.$row->recno.' title="Edit" class="btnedit" style="float:left"><span class="ui-icon ui-icon-pencil"></span></a>';
			}
			else{
				$edit='<span style="float:left" class="ui-state-disabled ui-icon ui-icon-pencil"></span>';
			}
			if(substr($acl,3,1)==1){
				$del='<a href="#" id='.$row->recno.' title="Del" class="btndel" style="float:left"><span class="ui-icon ui-icon-trash"></span></a>';
			}
			else{
				$del='<spans style="float:left" class="ui-state-disabled ui-icon ui-icon-trash"></span>';
			}
			$rel="";
			 $db1 = get_instance()->db->conn_id;
			if(mysqli_num_rows(mysqli_query($db1,"SHOW TABLES LIKE 'tbltemp".$_SESSION['userpk']."'"))==1){
				$tabel = "tbltemp".$_SESSION['userpk'];
				$q = mysqli_query($db1,"SELECT recno FROM $tabel WHERE recno='$row->recno'");
				if($cek = mysqli_fetch_array($q)){
					$rel = "checked";
				}
			}
			else{
			    $rel = "disabled";
			}

			$recno = $row->recno;
			$pembesukdari="";
			$remark="";
			$besukdate="";
			$q = mysqli_query($db1,"SELECT * FROM tblbesuk WHERE recno='$recno' ORDER BY besukdate DESC");
			if($dta = mysqli_fetch_array($q,MYSQLI_ASSOC)){
				//$dta = "checked";
				$pembesukdari=$dta['pembesukdari'];
				$remark=$dta['remark'];
				$besukdate=$dta['besukdate'];
				$d=strtotime($besukdate);
				$besukdate = date("Y-m-d", $d);
			}

			$jlhbesuk = $this->mtb->jlhbesuk($row->recno);
			$tglbesukterakhir = $this->mtb->tglbesukterakhir($row->recno);
			$select="<spans style='float:left;margin-top:3px;margin-left:4px;'><input style='width:11px' $rel type='checkbox' name='selectboxid[]' id='selectboxid' value='".$row->recno."'></span>";
			$responce->rows[$i]['id']   = $row->recno;
			$responce->rows[$i]['cell'] = array(
				$recno,
				$view.$edit.$del.$select,
				$photofile,
				$row->statusid,
				$row->grp_pi,
				$row->relationno,
				$row->tgl_hadir,
				$row->memberno,
				$row->membername,
				$row->chinesename,
				$row->phoneticname,
				$row->aliasname,
				$row->tel_h,
				$row->tel_o,
				$row->handphone,
				$row->address,
				$row->add2,
				$row->city,
				$row->genderid,
				$row->pstatusid,
				$row->pob,
				$row->dobview,
				$row->umur,
				$row->bloodid,
				$row->kebaktianid,
				$row->persekutuanid,
				$row->rayonid,
				$row->serving,
				$row->fax,
				$row->email,
				$row->website,
				$row->baptismdocno,
				$row->baptis,
				$row->baptismdateview,
				$row->remark,
				$row->relation,
				$row->oldgrp,
				$row->kebaktian,
				//$row->tglbesukview,
				$jlhbesuk,
				$besukdate,
				$pembesukdari,
				$remark,
				$row->modifiedby,
				$row->modifiedonview
				);
			$i++;
		}
		echo json_encode($responce);
	}

	function form($form,$recno,$formname){
		$data['grp_pi'] = $this->uri->segment(6);
		$data['sqlgender'] = $this->mgender->get_jemaat();
		$data['sqlpstatus'] = $this->mpstatus->get_jemaat();
		$data['sqlblood'] = $this->mblood->get_jemaat();
		$data['sqlkebaktian'] = $this->mkebaktian->get_jemaat();
		$data['sqlpersekutuan'] = $this->mpersekutuan->get_jemaat();
		$data['sqlrayon'] = $this->mrayon->get_jemaat();
		$data['sqlserving'] = $this->mserving->get_jemaat();
		$data['sqlstatusid'] = $this->mparameter->get_jemaat();
		$data['formname'] = $formname;
		if($recno!=null || $recno!=""){
			$sql= $this->mtb->getwhere($recno);
			$count = $sql->num_rows();
			$data["recno"] = $recno;
		}
		$this->load->view('tb/'.$form,$data);
	}

	function crud(){
		@$oper=@$_POST['oper'];
	    @$recno=@$_POST['recno'];
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

		@$tgl_hadir = $_POST['tgl_hadir'];
	    @$exp1 = explode('-',$tgl_hadir);
		@$tgl_hadir = $exp1[2]."-".$exp1[1]."-".$exp1[0];

	    @$dob = $_POST['dob'];
	    @$exp1 = explode('-',$dob);
		@$dob = $exp1[2]."-".$exp1[1]."-".$exp1[0];

		@$baptismdate = $_POST['baptismdate'];
		@$exp2 = explode('-',$baptismdate);
		@$baptismdate = $exp2[2]."-".$exp2[1]."-".$exp2[0];

		@$tglbesuk = $_POST['tglbesuk'];
		@$exp3 = explode('-',$tglbesuk);
		@$tglbesuk = $exp3[2]."-".$exp3[1]."-".$exp3[0];
		@$data = array(
			'grp_pi' => isset($_POST['grp_pi']) ? $_POST['grp_pi'] : 0,
			'relationno' => @$_POST['relationno'],
			'tgl_hadir' => @$tgl_hadir,
			'memberno' => @$_POST['memberno'],
			'membername' => @$_POST['membername'],
			'chinesename' => @$_POST['chinesename'],
			'phoneticname' => @$_POST['phoneticname'],
			'aliasname' => @$_POST['aliasname'],
			'tel_h' => @$_POST['tel_h'],
			'tel_o' => @$_POST['tel_o'],
			'handphone' => @$_POST['handphone'],
			'address' => @$_POST['address'],
			'add2' => @$_POST['add2'],
			'city' => @$_POST['city'],
			'genderid' => @$_POST['genderid'],
			'pstatusid' => @$_POST['pstatusid'],
			'pob' => @$_POST['pob'],
			'dob' => @$dob,
			'bloodid' => @$_POST['bloodid'],
			'kebaktianid' => @$_POST['kebaktianid'],
			'persekutuanid' => @$_POST['persekutuanid'],
			'rayonid' => @$_POST['rayonid'],
			'statusid' => 'TB',
			'serving' => $servingid,
			'fax' => @$_POST['fax'],
			'email' => @$_POST['email'],
			'website' => @$_POST['website'],
			'baptismdocno' => @$_POST['baptismdocno'],
			'baptis' => isset($_POST['baptis']) ? $_POST['baptis'] : 0,
			'baptismdate' => @$baptismdate,
			'remark' => @$_POST['remark'],
			'relation' => @$_POST['relation'],
			'oldgrp' => @$_POST['oldgrp'],
			'kebaktian' => @$_POST['kebaktian'],
			'tglbesuk' => @$tglbesuk,
			'teambesuk' => @$_POST['teambesuk'],
			'description' => @$_POST['description'],
			'photofile' => @$photofile,
			'modifiedby' => $_SESSION['username'],
			'modifiedon' => date("Y-m-d H:i:s")
			);
	    switch ($oper) {
	        case 'add':
				$this->mtb->add("tblmember",$data);
				$hasil = array(
			        'status' => 'sukses',
			        'photofile' => $photofile
			    );
			    echo json_encode($hasil);
	            break;
	        case 'edit':
				$this->mtb->edit("tblmember",$data,$recno);
				$hasil = array(
			        'status' => 'sukses',
			        'photofile' => $photofile
			    );
			    echo json_encode($hasil);
	            break;
	         case 'del':
         		if (file_exists("uploads/medium_".$editphotofile)) {
					unlink("uploads/medium_".$editphotofile);
				}
				if (file_exists("uploads/small_".$editphotofile)) {
					unlink("uploads/small_".$editphotofile);
				}
				$this->mtb->del("tblmember",$recno);
				$hasil = array(
			        'status' => 'sukses',
			        'photofile' => $editphotofile
			    );
			    echo json_encode($hasil);
	            break;
	        default :
	        	$hasil = array(
			        'status' => 'No Operation'
			    );
			    echo json_encode($hasil);
	           break;
		}
	}

	function operation($filters){
        $filters = str_replace('\"','"' ,$filters);
        $filters = str_replace('"[','[' ,$filters);
        $filters = str_replace(']"',']' ,$filters);
		$filters = json_decode($filters);
		$where = " ";
		$whereArray = array();
		$rules = $filters->rules;
		$groupOperation = $filters->groupOp;
		foreach($rules as $rule) {
		    $fieldName = $rule->field;
		    $fieldData = escapeString($rule->data);
			   	switch ($rule->op) {
					case "eq": $fieldOperation = " = '".$fieldData."'"; break;
					case "ne": $fieldOperation = " != '".$fieldData."'"; break;
					case "lt": $fieldOperation = " < '".$fieldData."'"; break;
					case "gt": $fieldOperation = " > '".$fieldData."'"; break;
					case "le": $fieldOperation = " <= '".$fieldData."'"; break;
					case "ge": $fieldOperation = " >= '".$fieldData."'"; break;
					case "nu": $fieldOperation = " = ''"; break;
					case "nn": $fieldOperation = " != ''"; break;
					case "in": $fieldOperation = " IN (".$fieldData.")"; break;
					case "ni": $fieldOperation = " NOT IN '".$fieldData."'"; break;
					case "bw": $fieldOperation = " LIKE '".$fieldData."%'"; break;
					case "bn": $fieldOperation = " NOT LIKE '".$fieldData."%'"; break;
					case "ew": $fieldOperation = " LIKE '%".$fieldData."'"; break;
					case "en": $fieldOperation = " NOT LIKE '%".$fieldData."'"; break;
					case "cn": $fieldOperation = " LIKE '%".$fieldData."%'"; break;
					case "nc": $fieldOperation = " NOT LIKE '%".$fieldData."%'"; break;
					default: $fieldOperation = ""; break;
		        }
		    if($fieldOperation != "") {
		    	if($fieldName=="tgl_lahir"){
                	$whereArray[] = "DATE_FORMAT(tgl_lahir,'%d-%m-%Y')".$fieldOperation;
                }
		    	else if($fieldName=="dob"){
                	$whereArray[] = "DATE_FORMAT(dob,'%d-%m-%Y')".$fieldOperation;
                }
                else if($fieldName=="baptismdate"){
                	$whereArray[] = "DATE_FORMAT(baptismdate,'%d-%m-%Y')".$fieldOperation;
                }
                else if($fieldName=="tglbesuk"){
                	$whereArray[] = "DATE_FORMAT(tglbesuk,'%d-%m-%Y')".$fieldOperation;
                }
                else if($fieldName=="modifiedon"){
                	$whereArray[] = "DATE_FORMAT(modifiedon,'%d-%m-%Y %T')".$fieldOperation;
                }
                else if($fieldName=="umur"){
                	$whereArray[] = "DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(dob, '%Y')".$fieldOperation;
                }
                else if($fieldName=="tglbesukterakhir"){
                	$whereArray[] = "DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(dob, '%Y')".$fieldOperation;
                }
                else if($fieldName=="photofile"){
                	if($fieldData=="ada"){
                		$whereArray[] = "photofile !='' ";
                	}
                	else{
                		$whereArray[] = "photofile ='' ";
                	}
                }
                else{
                	$whereArray[] = $fieldName.$fieldOperation;
                }
		    }
		}

		if (count($whereArray)>0) {
		    $where .= join(" ".$groupOperation." ", $whereArray);
		} else {
			$where = " ";
		}
		return $where;
	}

	function upload($namephotofile){
	    $filename = $_FILES['photofile']['name'];
	    if($filename){
	    	$temp = $_FILES['photofile']['tmp_name'];
		    $type = $_FILES['photofile']['type'];
		    $size = $_FILES['photofile']['size'];
		    $newfilename = $namephotofile;
			@$vdir_upload = "uploads/";
			@$directory 	= "uploads/$newfilename";
		    if (MOVE_UPLOADED_FILE($temp,$directory)){
		    	$im_src = imagecreatefromjpeg($directory);
				$src_width = imagesx($im_src);
				$src_height = imagesy($im_src);
				$dst_width = 30;
				$dst_height = ($dst_width/$src_width)*$src_height;
				$im = imagecreatetruecolor($dst_width,$dst_height);
				imagecopyresampled($im, $im_src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
				imagejpeg($im,$vdir_upload."small_".$newfilename);
				imagedestroy($im_src);
				imagedestroy($im);

				$im_src2 = imagecreatefromjpeg($directory);
				$src_width2 = imagesx($im_src2);
				$src_height2 = imagesy($im_src2);
				$dst_width2 = 500;
				$dst_height2 = ($dst_width2/$src_width2)*$src_height2;
				$im2 = imagecreatetruecolor($dst_width2,$dst_height2);
				imagecopyresampled($im2, $im_src2, 0, 0, 0, 0, $dst_width2, $dst_height2, $src_width2, $src_height2);
				imagejpeg($im2,$vdir_upload."medium_".$newfilename);
				imagedestroy($im_src2);
				imagedestroy($im2);
				unlink("uploads/$newfilename");
		        $status = 1;
		    	$msg ="Upload Success";
		    }
		    else{
		        $status = 2;
		    	$msg ="Upload Error";
		 	}
		}
		else{
			$status = 2;
		    $msg ="Upload Null";
		}

	 	$hasil = array(
	        'status' => $status,
	        'msg' => $msg
	    );
	    echo json_encode($hasil);
	}

	function export($file){
		$excel = $_SESSION['exceljemaat'];
		$splitexcel = explode("|",$excel);
		$sord = $splitexcel[0];
		$sidx= $splitexcel[1];
		$where = $splitexcel[2];
		$data['sql']=$this->db->query("SELECT *,
		DATE_FORMAT(dob,'%d-%m-%Y') dob,
		DATE_FORMAT(tglbesuk,'%d-%m-%Y') tglbesuk,
		DATE_FORMAT(baptismdate,'%d-%m-%Y') baptismdate,
		DATE_FORMAT(modifiedon,'%d-%m-%Y') modifiedon,
		DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(dob, '%Y') as umur
		FROM tblmember " . $where . " ORDER BY $sidx $sord");
		$this->load->view('tb/'.$file,$data);
	}

	function report(){
		$excel = $_SESSION['exceljemaat'];
		$splitexcel = explode("|",$excel);
		$sord = $splitexcel[0];
		$sidx= $splitexcel[1];
		$where = $splitexcel[2];
		$data['sql']=$this->db->query("SELECT *,
		DATE_FORMAT(dob,'%d-%m-%Y') dob,
		DATE_FORMAT(tglbesuk,'%d-%m-%Y') tglbesuk,
		DATE_FORMAT(baptismdate,'%d-%m-%Y') baptismdate,
		DATE_FORMAT(modifiedon,'%d-%m-%Y') modifiedon,
		DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(dob, '%Y') as umur
		FROM tblmember " . $where . " ORDER BY $sidx $sord");
		$this->load->view('tb/report',$data);

	}
}



