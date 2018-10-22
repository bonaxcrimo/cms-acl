<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tb extends MY_Controller {

	public function __construct(){
		parent::__construct();
		session_start();
		$this->load->model([
			'mtb',
			'mmenu',
			'mjemaat'
		]);
	}

	/**
     * Fungsi index tb
     * @AclName index tb
     */
	public function index(){
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
     * Fungsi view tb
     * @AclName View tb
     */
	public function view($member_key=0){
		$data['data'] = $this->mtb->getById('tblmember','member_key',$member_key);
		$data['member_key'] = $member_key;
		$this->load->view('tb/view',$data);
	}
	/**
     * Fungsi add tb
     * @AclName Tambah tb
     */
	public function add(){
		$data=[];
		if($this->input->server('REQUEST_METHOD') == 'POST' ){
			$data = $this->input->post();

			$cek = $this->_save($data);

		    echo json_encode($cek);
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

		    echo json_encode($cek);
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
     * Fungsi delete tb
     * @AclName Delete tb
     */
	public function delete($id){
		$data = $this->mtb->getById('tblmember','member_key',$id);
        if(empty($data)){
            redirect('tb');
        }
        $data=[];
		if($this->input->server('REQUEST_METHOD') == 'POST'){
			$cek = $this->mtb->delete($this->input->post('member_key'),$this->input->post('editphotofile'));
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
	/**
     * Fungsi grid tb
     * @AclName grid tb
     */
	public function grid(){
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
			}else{
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
	/**
     * Fungsi export tb
     * @AclName export tb
     */
	public function export($file){
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
	/**
     * Fungsi report tb
     * @AclName report tb
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
}



