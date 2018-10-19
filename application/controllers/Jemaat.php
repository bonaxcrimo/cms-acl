<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jemaat extends MY_Controller {

	public function __construct(){
		parent::__construct();
		session_start();
		$this->load->model([
			'mjemaat',
			'mparameter',
			'mmenu'
		]);
        $this->load->library('pinyin');

	}

	/**
     * Fungsi awal jemaat
     * @AclName Awal jemaat
     */
	function index(){
		$data = array_merge($this->_parameter(),$this->_combo());
		$this->render('jemaat/gridjemaat',$data);
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
	/**
     * Fungsi view jemaat
     * @AclName View jemaat
     */
	public function view($member_key=0){
		$data['data'] = $this->mjemaat->getById('tblmember','member_key',$member_key);
		$data['member_key'] = $member_key;
		$this->load->view('jemaat/view',$data);
	}
	/**
     * Fungsi add jemaat
     * @AclName Tambah jemaat
     */
	public function add(){
		$data=[];
		if($this->input->server('REQUEST_METHOD') == 'POST' ){
			$data = $this->input->post();

			$cek = $this->_save($data);

		    echo json_encode($cek);
		}else{
			$data = $this->_parameter();
			$this->load->view('jemaat/add',$data);
		}

	}
	/**
     * Fungsi edit jemaat
     * @AclName Edit jemaat
     */
	public function edit($id){
		$data = $this->mjemaat->getById('tblmember','member_key',$id);
        if(empty($data)){
            redirect('jemaat');
        }
        $data=[];
		if($this->input->server('REQUEST_METHOD') == 'POST' ){

			$data = $this->input->post();
			$data['member_key'] = $id;
			$cek = $this->_save($data);
		    echo json_encode($cek);
		}else{
			$data = $this->_parameter();
			$data['member_key'] = $id;
			$this->load->view('jemaat/edit',$data);
		}
	}
	private function _save($data){
		$data = array_map("strtoupper",$data);
		return $this->mjemaat->save($data);
	}
	/**
     * Fungsi delete jemaat
     * @AclName Delete jemaat
     */
	public function delete($id){
		$data = $this->mjemaat->getById('tblmember','member_key',$id);
        if(empty($data)){
            redirect('jemaat');
        }
        $data=[];
		if($this->input->server('REQUEST_METHOD') == 'POST'){
			$cek = $this->mjemaat->delete($this->input->post('member_key'),$this->input->post('editphotofile'));
			$status = $cek?"sukses":"gagal";
			$hasil = array(
		        'status' => $status
		    );
		    echo json_encode($hasil);
		}else{
			$data = $this->_parameter();
			$data['member_key'] = $id;
			$this->load->view('jemaat/delete',$data);
		}

	}
	/**
     * Fungsi grid  jemaat
     * @AclName grid  jemaat
     */
	public function grid($status=''){
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : 'member_key';
		$order = isset($_GET['order']) ? strval($_GET['order']) : 'asc';

		$filterRules = isset($_GET['filterRules']) ? ($_GET['filterRules']) : '';
		$cond = '';
		$where="";
		if($status=="m"){
			$where .= " where status_key='9' ";
		}else if($status=="pi"){
			$where .= " where grp_pi=1 ";
		}else{
			$where .= " where status_key != '9' and status_key != '18' ";
		}
		if (!empty($filterRules)){
			$cond = ' and  1=1 ';
			$filterRules = json_decode($filterRules);


			foreach($filterRules as $rule){
				$rule = get_object_vars($rule);
				$field = $rule['field'];
				$op = $rule['op'];
				$value = $rule['value'];

				if (!empty($value)){
					if($field=="umur"){
						$field = " DATE_FORMAT(NOW(),'%Y') - DATE_FORMAT(dob,'%Y') ";
						$op="equal";
					}else if($field=="dob"){
						$field=" DATE_FORMAT(dob,'%d-%m-%Y')  ";
						$op = "containsend";
					}else if($field=="baptismdate"){
						$field=" DATE_FORMAT(baptismdate,'%d-%m-%Y')  ";
						$op = "containsend";
					}else if($field=="tglbesuk"){
						$field=" DATE_FORMAT(tglbesuk,'%d-%m-%Y')  ";
						$op = "containsend";
					}else if($field=="modifiedon"){
						$field=" DATE_FORMAT(modifiedon,'%d-%m-%Y')  ";
						$op = "containsend";
					}
					if ($op == 'contains'){
						$cond .= " and ($field like '%$value%')";
					} else if ($op == 'equal'){
						$cond .= " and $field = '$value'";
					}else if($op == 'notequal'){
						$cond .= " and $field != '' ";
					}else if($op =="containsend"){
						$cond .= " and ($field like '$value%')";
					}

				}
			}
		}
		$cond = $where.$cond;
		$sql = $this->mjemaat->count($cond);
		$total = $sql->num_rows();
		$offset = ($page - 1) * $rows;
		$data = $this->mjemaat->getM($cond,$sort,$order,$rows,$offset)->result();
		$_SESSION['exceljemaat']= $order."|".$sort."|".$cond;
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


			$jlhbesuk = $this->mjemaat->jlhbesuk($row->member_key);
			$tglbesukterakhir = $this->mjemaat->tglbesukterakhir($row->member_key);

			$row->dob=$row->dob!="00-00-0000"?$row->dob:'-';
			$row->baptismdate=$row->baptismdate!="00-00-0000"?$row->baptismdate:'-';
			$row->umur = $row->umur==Date("Y")?'-':$row->umur;
			$row->relationno = $row->relationno==0?"-":$row->relationno;
			$row->jlhbesuk = $jlhbesuk;
			$row->tglbesukterakhir = $besukdate;
			$row->pembesukdari = $pembesukdari;
			$row->remark = $status;

			$row->aksi =$view.$edit.$del;
		}
		$response = new stdClass;
		$response->total=$total;
		$response->rows = $data;
		$_SESSION['excel']= "asc|member_key|";
		echo json_encode($response);
	}
	/**
     * Fungsi grid lookup jemaat
     * @AclName grid lookup besuk
     */
	function lookup_jemaat(){
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : 'member_key';
		$order = isset($_GET['order']) ? strval($_GET['order']) : 'asc';

		$filterRules = isset($_GET['filterRules']) ? ($_GET['filterRules']) : '';
		$cond = '';
		$where="";
		$where .= " where status_key !='9' ";
		if (!empty($filterRules)){
			$cond = ' and 1=1 ';
			$filterRules = json_decode($filterRules);

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
		}
		$cond = $where.$cond;
		$sql = $this->mjemaat->count($cond);
		$total = $sql->num_rows();
		$offset = ($page - 1) * $rows;
		$data = $this->mjemaat->getM($cond,$sort,$order,$rows,$offset)->result();
		$_SESSION['exceljemaat']= $order."|".$sort."|".$cond;
		foreach($data as $row){
						if($row->photofile!=""){
				$photofile="<img style='margin:0 17px;width:20px;' src='".base_url()."uploads/small_".$row->photofile."' class='btnzoom' onclick='zoom(\"medium_".$row->photofile."\")'>";
			}
			else{
				$data_photo="medium_nofoto.jpg";
				$photofile="<img style='margin:0 17px;width:20px;' src='".base_url()."uploads/small_nofoto.jpg' class='btnzoom' onclick='zoom(\"".$data_photo."\")'>";
			}
			$row->photofile = $photofile;

			$rel="";
		    $db1 = get_instance()->db->conn_id;



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

			$row->blood_key = $row->blood_key=='' || $row->blood_key=="-" ?'-':getParameterKey($row->blood_key)->parametertext;
			$row->gender_key = $row->gender_key=='' || $row->gender_key=="-" ?'-':getParameterKey($row->gender_key)->parametertext;
			$row->status_key = $row->status_key=='' || $row->status_key=="-" ?'-':getParameterKey($row->status_key)->parametertext;
			$row->kebaktian_key = $row->kebaktian_key==''  || $row->kebaktian_key=="-"  ?'-':getParameterKey($row->kebaktian_key)->parametertext;
			$row->persekutuan_key  = $row->persekutuan_key=='' || $row->persekutuan_key=="-"?'-':getParameterKey($row->persekutuan_key)->parametertext;
			$row->rayon_key = $row->rayon_key=='' || $row->rayon_key=="-"  ?'-':getParameterKey($row->rayon_key)->parametertext;
			$row->pstatus_key =  $row->pstatus_key=='' || $row->pstatus_key=="-" ?'-':getParameterKey($row->pstatus_key)->parametertext;


			$jlhbesuk = $this->mjemaat->jlhbesuk($row->member_key);
			$tglbesukterakhir = $this->mjemaat->tglbesukterakhir($row->member_key);

			$row->dob=$row->dob!="00-00-0000"?$row->dob:'-';
			$row->baptismdate=$row->baptismdate!="00-00-0000"?$row->baptismdate:'-';
			$row->umur = $row->umur==Date("Y")?'-':$row->umur;
			$row->relationno = $row->relationno==0?"-":$row->relationno;


			$row->jlhbesuk = $jlhbesuk;
			$row->tglbesukterakhir = $besukdate;
			$row->pembesukdari = $pembesukdari;
			$row->remark = $remark;
		}
		// $total = count($data);
		$response = new stdClass;
		$response->total=$total;
		$response->rows = $data;
		$_SESSION['excel']= "asc|member_key|";
		echo json_encode($response);
	}
	/**
     * Fungsi buat relasi
     * @AclName membuat relasi jemaat
     */
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
	/**
     * Fungsi export jemaat
     * @AclName export  jemaat
     */
	public function export($file){
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
		$this->load->view('jemaat/'.$file,$data);
	}
	/**
     * Fungsi report jemaat
     * @AclName report  jemaat
     */
	public function report(){
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
	public function konversiRelation(){
		$data = $this->db->query("select relationno from tblmember2 where relationno!='' group by relationno");
		$no=1;
		foreach($data->result() as $d){

			$members = $this->db->query("select * from tblmember2 where relationno='".$d->relationno."'")->result();
			foreach($members as $member){
				$id = $member->member_key;
				$sql="update tblmember2 set relationno='".$no."' where member_key = ".$id;
				$check = $this->db->query($sql);
				if($check){
					echo "berhasil";
				}else{
					echo "gagal";
				}
			}
			echo "<br>";
			echo $no."=".$d->relationno."=".count($members)."<br>";
			$no++;
		}
	}
	public function konversi(){
		$data = $this->db->query("SELECT member_key,TRIM(serving) AS serving FROM tblmember WHERE serving!=''")->result();
		// $total=0;
		// $totalInsert = 0;
		// $totalParam=0;
		foreach($data as $row){
			$pecah = explode("/",$row->serving);
			array_pop($pecah);
			// $total+=count($pecah);
			foreach($pecah as $p){
				$check = $this->mblood->getListAll('tblparameter',['parametergrpid'=>'SERVING','parameterid'=>$p]);
				if(count($check)==0){
					$insert= $this->db->query("INSERT INTO tblparameter values (NULL,'SERVING','".$p."','".$p."','','".$_SESSION['username']."',NOW())");
					$parkey=$this->db->insert_id();
					// if($insert){
					// 	$totalParam+=1;
					// }
					$sql="insert into tblprofile values (NULL,'".$row->member_key."','".$parkey."',NOW(),'',NOW(),'".$_SESSION['username']."')";
					$insert = $this->db->query($sql);
					// if($insert){
					// 	$totalInsert+=1;
					// }
				}else{
					$parkey = !empty($check)?$check[0]->parameter_key:0;
					$sql="insert into tblprofile values (NULL,'".$row->member_key."','".$parkey."',NOW(),'',NOW(),'".$_SESSION['username']."')";
					$insert = $this->db->query($sql);
					// if($insert){
					// 	$totalInsert+=1;
					// }
				}
			}
		}
		// echo "Total Data Serving = ".$total."<br>Total Data Insert Activity = ".$totalInsert."<br>Total Data Parameter = ".$totalParam;
	}
	public function konversiParameter($param,$field_awal){
		$data = $this->db->query("SELECT member_key,TRIM($field_awal) AS field FROM tblmemberlama WHERE $field_awal!='' and $field_awal!='-'")->result();
		foreach($data as $row){
			$check = $this->mblood->getListAll('tblparameter',['parametergrpid'=>$param,'parameterid'=>$row->field]);
			if(count($check)==0){
				$insert= $this->db->query("INSERT INTO tblparameter values (NULL,'".$param."','".$row->field."','".$row->field."','','".$_SESSION['username']."',NOW())");
				$parkey=$this->db->insert_id();
				$sql="update tblmemberlama set $field_awal='$parkey' where member_key = ".$row->member_key;
				// $insert = $this->db->query($sql);
				echo $sql."=1<br>";
			}else{
				$parkey = !empty($check)?$check[0]->parameter_key:0;
				$sql="update tblmemberlama set $field_awal='$parkey' where member_key = ".$row->member_key;
				// $insert = $this->db->query($sql);
				echo $sql."=2<br>";
			}
		}
	}
}






