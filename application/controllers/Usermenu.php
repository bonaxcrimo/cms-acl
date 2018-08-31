<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class usermenu extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library('session'); // session_start()
		$this->load->model('mlogin');
		$cek = $this->mlogin->cek();
		if($cek==""){
			redirect("");
			session_destroy();
		}
		date_default_timezone_set("Asia/Jakarta");
		ini_set('memory_limit', '-1');
		$this->load->model('mmenutop');
        $this->load->helper('my_helper');

		$this->load->model('musermenu');
		$this->load->model('mgender');
		$this->load->model('mpstatus');
		$this->load->model('mblood');
		$this->load->model('mkebaktian');
		$this->load->model('mpersekutuan');
		$this->load->model('mrayon');
	}

	function index(){
		$data['acl'] = $this->hakakses("user");
		if(empty($_GET['userpk'])){
			echo" Empty";
		}
		else{
			$data['userpk'] = $_GET['userpk'];
			$this->load->view('user/gridusermenu',$data);
		}
	}
	
	function grid($userpk){
		$acl = $this->hakakses('user');
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
			$where = " where userpk='".$userpk."'";
       		if (isset($filters)) {
       			$w = " where userpk='".$userpk."' AND ";
				$where = $w.$this->operation($filters);
		    }
		$sql = $this->musermenu->count($where);
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
		$data = $this->musermenu->get($where, $sidx, $sord, $limit, $start);
		$_SESSION['excelusermenu']= $sord."|".$sidx."|".$where;
		@$responce->page = $page;
		@$responce->total = $total_pages;
		@$responce->records = $count;
		$i=0;
		foreach($data->result() as $row){
			if(substr($acl,0,1)==1){
				$view='<a href="#" usermenuid='.$row->usermenuid.' userpk='.$row->userpk.' title="view" class="btnviewusermenu" style="float:left"><span class="ui-icon ui-icon-document"></span></a>';
			}
			else{
				$view='<span style="float:left" class="ui-state-disabled ui-icon ui-icon-document"></span>';
			}
			if(substr($acl,2,1)==1){
				$edit='<a href="#" usermenuid='.$row->usermenuid.' userpk='.$row->userpk.' title="Edit" class="btneditusermenu" style="float:left"><span class="ui-icon ui-icon-pencil"></span></a>';
			}
			else{
				$edit='<span style="float:left" class="ui-state-disabled ui-icon ui-icon-pencil"></span>';
			}
			if(substr($acl,3,1)==1){
				$del='<a href="#" usermenuid='.$row->usermenuid.' userpk='.$row->userpk.' title="Del" class="btndelusermenu" style="float:left"><span class="ui-icon ui-icon-trash"></span></a>';
			}
			else{
				$del='<span class="ui-state-disabled ui-icon ui-icon-trash"></span>';
			}

			$menuid = $row->menuid;
			$menuname="";
			$sql = $this->db->query("SELECT * FROM tblmenu WHERE menuid ='$menuid' LIMIT 0,1");
			foreach ($sql->result() as $key) {
				$menuname.=$key->menuname;
			}
			$responce->rows[$i]['id']   = $row->usermenuid;
			$responce->rows[$i]['cell'] = array(
				$view.$edit.$del,
				$menuname,
				$row->acl,
				$row->modifiedby,
				$row->modifiedonview
				);
			$i++;
		}
		echo json_encode($responce);
	}

	function form($form,$usermenuid,$userpk,$formname){
		$data['formname'] = $formname;
		$data['usermenuid'] = $usermenuid;
		$data['sqlusermenu'] = $this->musermenu->get_form();
		$data['usermenu'] = $this->musermenu->get_combo();
		
		if($userpk!=null || $userpk!=""){
			$sql= $this->musermenu->getwhere($userpk);
			$count = $sql->num_rows();
			$data["userpk"] = $userpk;
		}
		
		$this->load->view('usermenu/'.$form,$data);
	}

	function crud(){
		@$oper=@$_POST['oper'];
	    @$userpk=@$_POST['userpk'];
	    @$usermenuid=@$_POST['usermenuid'];
		
		@$data = array(
			'userpk' => @$_POST['userpk'],
			'menuid' => @$_POST['menuid'],
			'acl' => @$_POST['acl'],
			'modifiedby' => $_SESSION['username'],
			'modifiedon' => date("Y-m-d H:i:s")
			);
	    switch ($oper) {
	        case 'add':
				$this->musermenu->add("tblusermenu",$data);
				$hasil = array(
			        'status' => 'sukses'
			    );
			    echo json_encode($hasil);
	            break;
	        case 'edit':
				$this->musermenu->edit("tblusermenu",$data,$usermenuid);
				$hasil = array(
			        'status' => 'sukses'
			    );
			    echo json_encode($hasil);
	            break;
	         case 'del':
				$this->musermenu->del("tblusermenu",$usermenuid);
				$hasil = array(
			        'status' => 'sukses'
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
				$dst_width2 = 200;
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
		$excel = $_SESSION['excelusermenu'];
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
		$this->load->view('user/excel',$data);
	}
	function hakakses($x){
		$x = $this->mmenutop->get_menuid($x);
		return $x;
	}
}