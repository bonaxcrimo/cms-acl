<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Relasi extends CI_Controller {

	public function __construct(){
		parent::__construct();
		// date_default_timezone_set("Asia/Jakarta");
		// ini_set('memory_limit', '-1');
		$this->load->model([
			'mjemaat',
			'mgender',
			'mserving',
			'mparameter',
			'mpstatus',
			'mblood',
			'mkebaktian',
			'mpersekutuan',
			'mrayon'
		]);
	}

	function index(){
		if(empty($_GET['relationno'])){
			echo" Empty";
		}
		else{

			$data['relationno'] = $_GET['relationno'];
			$this->load->view('jemaat/gridrelasi',$data);
		}
	}
	function grid2($relationno){
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : 'member_key';
		$order = isset($_GET['order']) ? strval($_GET['order']) : 'asc';

		$filterRules = isset($_GET['filterRules']) ? ($_GET['filterRules']) : '';
		$cond = '';
		if (!empty($filterRules)){
			$cond = ' where relationno="'.$relationno.'" and 1=1 ';
			$filterRules = json_decode($filterRules);
			foreach($filterRules as $rule){
				$rule = get_object_vars($rule);
				$field = $rule['field'];
				$op = $rule['op'];
				$value = $rule['value'];
				if (!empty($value)){
					if ($op == 'contains'){
						$cond .= " and ($field like '%$value%')";
					} else if ($op == 'equal'){
						$cond .= " and $field = '$value'";
					}else if($op == 'notequal'){
						$cond .= " and $field != '' ";
					}

				}
			}
		}else{
			$cond .= ' where relationno="'.$relationno.'" ';
		}
		$where='';
		$sql = $this->mjemaat->count($cond);
		$total = $sql->num_rows();
		$offset = ($page - 1) * $rows;
		$data = $this->mjemaat->getM($cond,$sort,$order,$rows,$offset)->result();
		$_SESSION['excelrelasi']= $order."|".$sort."|".$cond;
		foreach($data as $row){
			$relation='<a href="#" id="'.$row->relationno.'" title="View Relation" class="relation"><span class="ui-icon ui-icon-note"></span></a>';
			if($row->photofile!=""){
				$photofile="<img style='margin:0 17px;' src='".base_url()."uploads/small_".$row->photofile."' class='btnzoom' onclick='zoom(\"medium_".$row->photofile."\")'>";
			}
			else{
				$data_photo="medium_nofoto.jpg";
				$photofile="<img style='margin:0 17px;' src='".base_url()."uploads/small_nofoto.jpg' class='btnzoom' onclick='zoom(\"".$data_photo."\")'>";
			}
			$row->photofile = $photofile;
			$view='';
			$edit='';
			$del='';
			$view = '<button id='.$row->member_key.' class="icon-view_detail" onclick="viewJemaat(\'view\',\''.$row->member_key.'\',\'formjemaat\')" style="width:16px;height:16px;border:0"></button> ';

			$edit = '<button id='.$row->member_key.' class="icon-edit" onclick="save(\'edit\',\''.$row->member_key.'\',\'formjemaat\',null);" style="width:16px;height:16px;border:0"></button> ';

			$del = '<button id='.$row->member_key.' class="icon-remove" onclick="del(\'del\','.$row->member_key.',\'formjemaat\');" style="width:16px;height:16px;border:0"></button>';

			$rel="";
		    $db1 = get_instance()->db->conn_id;

			if(mysqli_num_rows(mysqli_query($db1,"SHOW TABLES LIKE 'tbltemp".$_SESSION['userpk']."'"))==1){
				$tabel = "tbltemp".$_SESSION['userpk'];
				$q = mysqli_query($db1,"SELECT member_key FROM $tabel WHERE member_key='$row->member_key'");
				if($cek = mysqli_fetch_array($q)){
					$rel = "checked";
				}
			}
			else{
			    $rel = "disabled";
			}

			$member_key = $row->member_key;
			$pembesukdari="";
			$remark="";
			$besukdate="";
			$q = mysqli_query($db1,"SELECT * FROM tblbesuk WHERE member_key='$member_key' ORDER BY besukdate DESC");
			if($dta = mysqli_fetch_array($q,MYSQLI_ASSOC)){
				//$dta = "checked";
				$pembesukdari=$dta['pembesukdari'];
				$remark=$dta['remark'];
				$besukdate=$dta['besukdate'];
				$d=strtotime($besukdate);
				$besukdate = date("Y-m-d", $d);
			}

			$jlhbesuk = $this->mjemaat->jlhbesuk($row->member_key);
			$tglbesukterakhir = $this->mjemaat->tglbesukterakhir($row->member_key);
			$select="<spans style='float:left;margin-top:3px;margin-left:4px;'><input style='width:11px' $rel type='checkbox' name='selectboxid[]' id='selectboxid' value='".$row->member_key."'></span>";
			$row->jlhbesuk = $jlhbesuk;
			$row->tglbesukterakhir = $besukdate;
			$row->pembesukdari = $pembesukdari;
			$row->remark = $remark;
			$row->dob=$row->dob!="00-00-0000"?$row->dob:'-';
			$row->baptismdate=$row->baptismdate!="00-00-0000"?$row->baptismdate:'-';
			$row->umur = $row->umur==Date("Y")?'-':$row->umur;

			$row->aksi =$view.$edit.$del;
		}
		// $total = count($data);
		$response = new stdClass;
		$response->total=$total;
		$response->rows = $data;
		$_SESSION['excel']= "asc|member_key|";
		echo json_encode($response);
	}
	function grid($relationno){
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
			$where = " where relationno='".$relationno."'";
       		if (isset($filters)) {
       			$w = " where relationno='".$relationno."' AND ";
				$where = $w.$this->operation($filters);
		    }
		$sql = $this->mjemaat->count($where);
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
		$data = $this->mjemaat->get($where, $sidx, $sord, $limit, $start);
		$_SESSION['excelrelasi']= $sord."|".$sidx."|".$where;
		@$responce->page = $page;
		@$responce->total = $total_pages;
		@$responce->records = $count;
		$i=0;
		foreach($data->result() as $row){
			$relation='<a href="#" id="'.$row->relationno.'" title="View Relation" class="relation"><span class="ui-icon ui-icon-note"></span></a>';
			if($row->photofile!=""){
				$photofile="<img style='margin:0 17px;' src='".base_url()."uploads/small_".$row->photofile."' id='btnzoomrelasi' fimage='".$row->photofile."' class='dg-picture-zoom'>";
			}
			else{
				$photofile="<img style='margin:0 17px;' src='".base_url()."uploads/small_nofoto.jpg' id='btnzoomrelasi' fimage='nofoto.jpg' class='dg-picture-zoom'>";
			}
			if(substr($acl,0,1)==1){
				$view='<a href="#" relationno='.$row->relationno.' recno='.$row->recno.' title="view" class="btnviewrelasi" style="float:left"><span class="ui-icon ui-icon-document"></span></a>';
			}
			else{
				$view='<span style="float:left" class="ui-state-disabled ui-icon ui-icon-document"></span>';
			}
			if(substr($acl,2,1)==1){
				$edit='<a href="#" relationno='.$row->relationno.' recno='.$row->recno.' title="Edit" class="btneditrelasi" style="float:left"><span class="ui-icon ui-icon-pencil"></span></a>';
			}
			else{
				$edit='<span style="float:left" class="ui-state-disabled ui-icon ui-icon-pencil"></span>';
			}
			if(substr($acl,3,1)==1){
				$del='<a href="#" relationno='.$row->relationno.' recno='.$row->recno.' title="Del" class="btndelrelasi" style="float:left"><span class="ui-icon ui-icon-trash"></span></a>';
			}
			else{
				$del='<span class="ui-state-disabled ui-icon ui-icon-trash"></span>';
			}
			$responce->rows[$i]['id']   = $row->recno;
			$responce->rows[$i]['cell'] = array(
				$row->recno,
				$view.$edit.$del,
				$photofile,
				$row->statusid,
				$row->grp_pi,
				$row->relationno,
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
				$row->tglbesukview,
				$row->teambesuk,
				$row->description,
				$row->modifiedby,
				$row->modifiedonview
				);
			$i++;
		}
		echo json_encode($responce);
	}

	function form($form,$recno,$relationno,$formname){
		$data['sqlgender'] = $this->mgender->get_jemaat();
		$data['sqlpstatus'] = $this->mpstatus->get_jemaat();
		$data['sqlblood'] = $this->mblood->get_jemaat();
		$data['sqlkebaktian'] = $this->mkebaktian->get_jemaat();
		$data['sqlpersekutuan'] = $this->mpersekutuan->get_jemaat();
		$data['sqlrayon'] = $this->mrayon->get_jemaat();
		$data['grp_pi'] = $this->uri->segment(6);
		$data['sqlserving'] = $this->mserving->get_jemaat();
		$data['sqlstatusid'] = $this->mparameter->get_jemaat();
		$data['formname'] = $formname;


		$data['formname'] = $formname;
		if($recno!=null || $recno!=""){
			$sql= $this->mjemaat->getwhere($recno);
			$count = $sql->num_rows();
			$data["recno"] = $recno;
			$data["relationno"] = $relationno;
		}
		$this->load->view('jemaat/'.$form,$data);
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
	    		@$photofile = $editphotofile;
	    	}
	    	else{
	    		@$photofile = "";
	    	}
	    }

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
			'relationno' => @$_POST['relationno'],
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
			'statusid' => @$_POST['statusid'],
			'serving' => @$_POST['serving'],
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
				$this->mjemaat->add("tblmember",$data);
				$hasil = array(
			        'status' => 'sukses',
			        'photofile' => $photofile
			    );
			    echo json_encode($hasil);
	            break;
	        case 'edit':
				$this->mjemaat->edit("tblmember",$data,$recno);
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
				$this->mjemaat->del("tblmember",$recno);
				$hasil = array(
			        'status' => 'sukses',
			        'photofile' => $editphotofile
			    );
			    echo json_encode($hasil);
	            break;
	        default :
	        	$hasil = array(
			        'status' => 'Not Operation'
			    );
			    echo json_encode($hasil);
	           break;
		}
	}

	function operation($filters){
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
		    	if($fieldName=="dob"){
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
                else{
                	$whereArray[] = $fieldName.$fieldOperation;
                }
		    }
		}

		if (count($whereArray)>0) {
		    $where .= join(" ".$groupOperation." ", $whereArray);
		} else {
		    $where = "";
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
				//set ukuran s 60 pixel gambar hasil perubahan
				$dst_width = 30;
				$dst_height = ($dst_width/$src_width)*$src_height;
				//proses perubahan ukuran
				$im = imagecreatetruecolor($dst_width,$dst_height);
				imagecopyresampled($im, $im_src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
				imagejpeg($im,$vdir_upload."small_".$newfilename);
				imagedestroy($im_src);
				imagedestroy($im);

				$im_src2 = imagecreatefromjpeg($directory);
				$src_width2 = imagesx($im_src2);
				$src_height2 = imagesy($im_src2);
				//set ukuran s 60 pixel gambar hasil perubahan
				$dst_width2 = 500;
				$dst_height2 = ($dst_width2/$src_width2)*$src_height2;
				//proses perubahan ukuran
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

	function excel(){
		$excel = $_SESSION['excelrelasi'];
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
		FROM tblmember " . $where . " ORDER BY dob $sord");
		$this->load->view('jemaat/excel',$data);
	}


}