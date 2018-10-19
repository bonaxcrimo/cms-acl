<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Relasi extends CI_Controller {

	public function __construct(){
		parent::__construct();
		session_start();
		$this->load->model([
			'mjemaat'
		]);
	}
	/**
     * Fungsi awal relasi jemaat
     * @AclName awal relasi
     */
	public function index(){
		if(empty($_GET['relationno'])){
			echo" Empty";
		}
		else{
			$data = array_merge($this->_parameter(),$this->_combo());
			$data['relationno'] = $_GET['relationno'];
			$this->load->view('jemaat/gridrelasi',$data);
		}
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
     * Fungsi grid relasi jemaat
     * @AclName grid relasi
     */
	public function grid($relationno){
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
				//$dta = "checked";
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

	/**
     * Fungsi excel relasi jemaat
     * @AclName excel relasi
     */
	public function excel(){
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