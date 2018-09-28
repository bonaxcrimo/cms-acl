<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tb extends MY_Controller {

	public function __construct(){
		parent::__construct();

		session_start();
		$this->load->model([
			'mtb',
			'mpstatus',
			'mparameter',
			'mblood',
			'mkebaktian',
			'mpersekutuan',
			'mrayon',
			'mserving',
			'mmenu',
			'mjemaat'
		]);

	}

	function download($filename){
		$this->load->helper('download');
		$data = file_get_contents('uploads/'.$filename);
		force_download($filename,$data);
	}

	function index(){
		$data = array_merge( $this->_parameter(),$this->_combo());
		$this->render('tb/gridjemaat',$data);
	}
	private function _combo(){
		$data['statusidv'] = getComboParameter('STATUS');
		$data['blood'] = getComboParameter('BLOOD');
		$data['gender'] = getComboParameter('GENDER');
		$data['pstatus'] = getComboParameter('PSTATUS');
		$data['kebaktian'] = getComboParameter('KEBAKTIAN');
		$data['persekutuan'] =getComboParameter('PERSEKUTUAN');
		$data['rayon'] = getComboParameter('RAYON');
		return $data;
	}

	/**
     * Fungsi view rayon
     * @AclName View rayon
     */
	public function view($member_key=0){
		$data['data'] = $this->mtb->getById('tblmember','member_key',$member_key);
		$data['member_key'] = $member_key;
		$this->load->view('tb/view',$data);
	}
	/**
     * Fungsi add besuk
     * @AclName Tambah besuk
     */
	public function add(){
		$data=[];
		if($this->input->server('REQUEST_METHOD') == 'POST' ){
			$data = $this->input->post();
			$cek = $this->_save($data);
			$status = $cek?"sukses":"gagal";
			$hasil = array(
		        'status' => $status
		    );
		    echo json_encode($hasil);
		}else{
			$data = $this->_parameter();
			$this->load->view('tb/add',$data);
		}

	}
	/**
     * Fungsi edit besuk
     * @AclName Edit besuk
     */
	public function edit($id){
		$data = $this->mtb->getById('tblmember','member_key',$id);
        if(empty($data)){
            redirect('tb');
        }
        $data=[];
		if($this->input->server('REQUEST_METHOD') == 'POST' ){

			$data = $this->input->post();
			$data['member_key'] = $id;
			$cek = $this->_save($data);
			$status = $cek?"sukses":"gagal";
			$hasil = array(
		        'status' => $status
		    );
		    echo json_encode($hasil);
		}else{
			$data = $this->_parameter();
			$data['member_key'] = $id;
			$this->load->view('tb/edit',$data);
		}

	}
	private function _parameter(){
		$data['sqlgender'] = getParameter('GENDER');
		$data['sqlpstatus'] = getParameter('PSTATUS');
		$data['sqlblood'] =getParameter('BLOOD');
		$data['sqlkebaktian'] = getParameter('KEBAKTIAN');
		$data['sqlpersekutuan'] = getParameter('PERSEKUTUAN');
		$data['sqlrayon'] =getParameter('RAYON');
		$data['sqlserving'] =getParameter('SERVING');
		$data['sqlstatusid'] =getParameter('STATUS');
		return $data;
	}
	private function _save($data){
		$data = array_map("strtoupper",$data);
		return $this->mtb->save($data);
	}
	/**
     * Fungsi delete besuk
     * @AclName Delete besuk
     */
	public function delete($id){
		$data = $this->mtb->getById('tblmember','member_key',$id);
        if(empty($data)){
            redirect('tb');
        }
        $data=[];
		if($this->input->server('REQUEST_METHOD') == 'POST'){
			$cek = $this->mtb->delete($this->input->post('member_key'));
			$status = $cek?"sukses":"gagal";
			$hasil = array(
		        'status' => $status
		    );
		    echo json_encode($hasil);
		}else{
			$data = $this->_parameter();
			$data['member_key'] = $id;
			$this->load->view('tb/delete',$data);
		}

	}
	function creatrelation(){
		$this->mtb->creat_relation();
		echo 1;
	}

	function simpan_relation($recno){
		$this->mtb->simpan_relation($recno);
		echo $recno;
	}
	function grid(){
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : 'member_key';
		$order = isset($_GET['order']) ? strval($_GET['order']) : 'asc';

		$filterRules = isset($_GET['filterRules']) ? ($_GET['filterRules']) : '';
		$cond = '';
		$status = $this->uri->segment(3);
		if (!empty($filterRules)){
			$cond = ' where 1=1 ';
			$filterRules = json_decode($filterRules);
			$where = "";
			// if($status=="m"){
			// 	$where = " and status_key = 9 ";
			// }else if($status=="pi"){
			// 	$where = " and grp_pi=1 ";
			// }else{
			// 	$where = " and status_key = 18 ";
			// }
			foreach($filterRules as $rule){
				$rule = get_object_vars($rule);
				$field = $rule['field'];
				$op = $rule['op'];
				$value = $rule['value'];

				if (!empty($value)){
					if($field=="umur"){
						$field = " DATE_FORMAT(NOW(),'%Y') - DATE_FORMAT(dob,'%Y') ";
						$op="equal";
					}
					if ($op == 'contains'){
						$cond .= " and ($field like '%$value%')";
					} else if ($op == 'equal'){
						$cond .= " and $field = '$value'";
					}else if($op == 'notequal'){
						$cond .= " and $field != '' ";
					}

				}
			}

		$cond .= $where;
		}
		$sql = $this->mtb->count($cond);
		$total = $sql->num_rows();
		$offset = ($page - 1) * $rows;
		$data = $this->mtb->getM($cond,$sort,$order,$rows,$offset)->result();
		$_SESSION['exceltb']= $order."|".$sort."|".$cond;
		foreach($data as $row){
			$relation='<a href="#" id="'.$row->relationno.'" title="View Relation" class="relation"><span class="ui-icon ui-icon-note"></span></a>';
			if($row->photofile!=""){
				$photofile="<img style='width:20px;height:16px;' src='".base_url()."uploads/small_".$row->photofile."' class='btnzoom' onclick='zoom(\"medium_".$row->photofile."\")'>";
			}
			else{
				$data_photo="medium_nofoto.jpg";
				$photofile="<img style='width:20px;' src='".base_url()."uploads/small_nofoto.jpg' class='btnzoom' onclick='zoom(\"".$data_photo."\")'>";
			}
			$row->photofile = $photofile;
			$view='';
			$edit='';
			$del='';
				$view = '<button id='.$row->member_key.' class="icon-view_detail" onclick="viewData(\''.$row->member_key.'\')" style="width:16px;height:16px;border:0"></button> ';

				$edit = '<button id='.$row->member_key.' class="icon-edit" onclick="editData(\''.$row->member_key.'\');" style="width:16px;height:16px;border:0"></button> ';

				$del = '<button id='.$row->member_key.' class="icon-remove" onclick="deleteData('.$row->member_key.');" style="width:16px;height:16px;border:0"></button>';

			$rel="";
		    $db1 = get_instance()->db->conn_id;
			$member_key = $row->member_key;
			$pembesukdari="";
			$remark="";
			$besukdate="";
			$q = mysqli_query($db1,"SELECT * FROM tblbesuk WHERE member_key='$member_key' ORDER BY besukdate DESC");
			if($dta = mysqli_fetch_array($q,MYSQLI_ASSOC)){
				$pembesukdari=$dta['pembesukdari'];
				$remark=$dta['remark'];
				$besukdate=$dta['besukdate'];
				$d=strtotime($besukdate);
				$besukdate = date("Y-m-d", $d);
			}

			$row->blood_key = $row->blood_key=='' || $row->blood_key=="-" ?'-':getParameterKey($row->blood_key)->parameterid;
			$row->gender_key = $row->gender_key=='' || $row->gender_key=="-" ?'-':getParameterKey($row->gender_key)->parametertext;
			$row->status_key = $row->status_key=='' || $row->status_key=="-" ?'-':getParameterKey($row->status_key)->parametertext;
			$row->kebaktian_key = $row->kebaktian_key==''  || $row->kebaktian_key=="-"  ?'-':getParameterKey($row->kebaktian_key)->parametertext;
			$row->persekutuan_key  = $row->persekutuan_key=='' || $row->persekutuan_key=="-"?'-':getParameterKey($row->persekutuan_key)->parametertext;
			$row->rayon_key = $row->rayon_key=='' || $row->rayon_key=="-"  ?'-':getParameterKey($row->rayon_key)->parametertext;
			$row->pstatus_key =  $row->pstatus_key=='' || $row->pstatus_key=="-" ?'-':getParameterKey($row->pstatus_key)->parametertext;

			$jlhbesuk = $this->mtb->jlhbesuk($row->member_key);
			$tglbesukterakhir = $this->mtb->tglbesukterakhir($row->member_key);

			$row->dob=$row->dob!="00-00-0000"?$row->dob:'-';
			$row->baptismdate=$row->baptismdate!="00-00-0000"?$row->baptismdate:'-';
			$row->umur = $row->umur==Date("Y")?'-':$row->umur;
			$row->relationno = $row->relationno==0?"-":$row->relationno;
			$row->jlhbesuk = $jlhbesuk;
			$row->tglbesukterakhir = $besukdate;
			$row->pembesukdari = $pembesukdari;
			$row->remark = $remark;

			$row->aksi =$view.$edit.$del;
		}
		$response = new stdClass;
		$response->total=$total;
		$response->rows = $data;
		$_SESSION['exceltb']= "asc|member_key|".$cond;
		echo json_encode($response);
	}


	function image($image){
		$data["image"] = $image;
		$this->load->view('jemaat/image',$data);
	}
	function form($form,$member_key,$formname){
		$data['grp_pi'] = $this->uri->segment(6);
		$data['sqlgender'] = getParameter('GENDER');
		$data['sqlpstatus'] = getParameter('PSTATUS');
		$data['sqlblood'] =getParameter('BLOOD');
		$data['sqlkebaktian'] = getParameter('KEBAKTIAN');
		$data['sqlpersekutuan'] = getParameter('PERSEKUTUAN');
		$data['sqlrayon'] =getParameter('RAYON');
		$data['sqlserving'] =getParameter('SERVING');
		$data['sqlstatusid'] =getParameter('STATUS');
		$data['formname'] = $formname;
		if($member_key!=null || $member_key!=""){
			$sql= $this->mjemaat->getwhere($member_key);
			$count = $sql->num_rows();
			$data["member_key"] = $member_key;
		}
		$this->load->view('tb/'.$form,$data);
	}
	function makeRelation(){
		$json = $_POST['dataMember'];
		$rel = $_POST['dataRel'];
		$data = json_decode($json);
		$checkRel = $this->db->query("select * from tblmember where relationno=".$rel)->result();
		$lastNumber = $rel;
		if(count($checkRel)==0){
			$lastNum = $this->db->query("select relationno from tblmember order by relationno desc")->result();
			$lastNumber= count($lastNum)>0?$lastNum[0]->relationno+1:1;
		}
		$gagal=0;
		foreach($data as $d){
			$sql="update tblmember set relationno = ".$lastNumber." where member_key= ".$d->member_key;
			$check = $this->db->query($sql);
			if(!$check){
				$gagal=1;
			}
		}
		$hasil = array(
			'status' => $gagal==0?"Sukses":"Gagal"
		);
		return json_encode($hasil);
	}
	function crud(){
		@$oper=@$_POST['oper'];
	    @$member_key=@$_POST['member_key'];
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
		$_POST = array_map("strtoupper", $_POST);
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
			'gender_key' => @$_POST['genderid'],
			'pstatus_key' => @$_POST['pstatusid'],
			'pob' => @$_POST['pob'],
			'dob' => @$dob,
			'blood_key' => @$_POST['bloodid'],
			'kebaktian_key' => @$_POST['kebaktianid'],
			'persekutuan_key' => @$_POST['persekutuanid'],
			'rayon_key' => @$_POST['rayonid'],
			'status_key' => '18',
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
				$this->mtb->edit("tblmember",$data,$member_key);
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
				$this->mtb->del("tblmember",$member_key);
				$hasil = array(
			        'status' => 'sukses',
			        'photofile' => $editphotofile
			    );
			    echo json_encode($hasil);
	            break;
	        default :
	        	$hasil = array(
			        'status' => 'No Operation'.implode("", $_POST)
			    );
			    echo json_encode($hasil);
	           break;
		}
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
		    	$msg ="Upload Success".$dst_height2;
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
	function uploadWA($namephotofile){
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
				// start
				$actualHeight  =  imagesy($im_src2);
				$actualWidth   = imagesx($im_src2);
				$maxHeight = 1280;
				$maxWidth = 1280;
				$imgRatio = $actualWidth / $actualHeight;
				$maxRatio = $maxWidth/$maxHeight;
				if($actualHeight>$maxHeight || $actualWidth > $maxWidth){
					if($imgRatio < $maxRatio){
						//menyesuaikan lebar menurut maxHeight
						$imgRatio = $maxHeight / $actualHeight;
						$actualWidth = $imgRatio * $actualWidth;
						$actualHeight = $maxHeight;
					}else if($imgRatio > $maxRatio){
						//menyesuaikan tinggi menurut maxWidth
						$imgRatio = $maxWidth / $actualWidth;
						$actualHeight = $imgRatio * $actualHeight;
						$actualWidth = $maxWidth;
					}else{
						$actualHeight = $maxHeight;
						$actualWidth = $maxWidth;
					}
				}
				//end
				$im2 = imagecreatetruecolor($actualWidth,$actualHeight);
				imagecopyresampled($im2, $im_src2, 0, 0, 0, 0, $actualWidth, $actualHeight, $src_width2, $src_height2);
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
		$excel = $_SESSION['exceltb'];
		$splitexcel = explode("|",$excel);
		$sord = $splitexcel[0];
		$sidx= $splitexcel[1];
		$where = $splitexcel[2];
		if($where!="")
			$where2=" and status_key='18' ";
		else
			$where2=" where status_key = '18' ";
		$data['sql']=$this->db->query("SELECT *,
		DATE_FORMAT(dob,'%d-%m-%Y') dob,
		DATE_FORMAT(tglbesuk,'%d-%m-%Y') tglbesuk,
		DATE_FORMAT(baptismdate,'%d-%m-%Y') baptismdate,
		DATE_FORMAT(modifiedon,'%d-%m-%Y') modifiedon,
		DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(dob, '%Y') as umur
		FROM tblmember " . $where.$where2 . " ORDER BY $sidx $sord");
		$this->load->view('jemaat/'.$file,$data);
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
		$this->load->view('jemaat/report',$data);

	}
}



