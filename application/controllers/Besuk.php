<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Besuk extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model([
			'mbesuk',
			'mgender',
			'mpstatus',
			'mblood',
			'mkebaktian',
			'mpersekutuan',
			'mrayon',
			'mjemaat',
			'mparameter',
			'mserving',
			'mmenu'
		]);
	}
	public function set(){
		$_SESSION['member_key'] = $_GET['member_key'];
	}
	public function index(){
		$link = base_url()."besuk/gridbesuk";
		$this->render('besuk/gridbesuk',['link'=>$link]);
	}
	public function jemaat(){
		if(empty($_SESSION['member_key'])){
			echo" Empty";
		}
		else{
			$data['member_key'] = $_SESSION['member_key'];
			$data['sql'] = $this->mbesuk->getwhere($_SESSION['member_key']);


			$data['sqlgender'] = getParameter('GENDER');
			$data['sqlpstatus'] =getParameter('PSTATUS');

			$data['sqlstatusidv'] = getParameter('STATUS');
			$data['sqlblood'] =getParameter('BLOOD');
			$data['sqlkebaktian'] =getParameter('KEBAKTIAN');
			$data['sqlpersekutuan'] =getParameter('PERSEKUTUAN');
			$data['sqlrayon'] =getParameter('RAYON');

			$data['statusidv'] = getComboParameter('STATUS');
			$data['blood'] = getComboParameter('BLOOD');
			$data['gender'] = getComboParameter('GENDER');
			$data['pstatus'] = getComboParameter('PSTATUS');
			$data['kebaktian'] = getComboParameter('KEBAKTIAN');
			$data['persekutuan'] =getComboParameter('PERSEKUTUAN');
			$data['rayon'] = getComboParameter('RAYON');
			$this->load->view('jemaat/gridbesuk2',$data);
		}
	}
	public function gridBesukJemaat($member_key){
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : 'member_key';
		$order = isset($_GET['order']) ? strval($_GET['order']) : 'asc';

		$filterRules = isset($_GET['filterRules']) ? ($_GET['filterRules']) : '';
		$cond = '';
		if (!empty($filterRules)){
			$cond = ' where member_key = "'.$member_key.'" and  1=1 ';
			$filterRules = json_decode($filterRules);

			foreach($filterRules as $rule){
				$rule = get_object_vars($rule);
				$field = $rule['field'];
				$op = $rule['op'];
				$value = $rule['value'];
				if (!empty($value)){
					if ($op == 'contains'){
						$cond .= " and ($field like '%$value%')";
					} else if ($op == 'greater'){
						$cond .= " and $field>$value";
					}
				}
			}
		}else{
			$cond = ' where member_key = "'.$member_key.'" ';
		}
		$where='';
		$sql = $this->mbesuk->count($cond);
		$total = $sql->num_rows();
		$offset = ($page - 1) * $rows;
		$data = $this->mbesuk->getM($cond,$sort,$order,$rows,$offset)->result();
		foreach($data as $row){
			$view='';
			$edit='';
			$del='';
				$view = '<button id='.$row->member_key.' class="icon-view_detail" onclick="viewBesuk(\'view\',\''.$row->besukid.'\',\''.$row->member_key.'\')" style="width:16px;height:16px;border:0"></button> ';

				$edit = '<button id='.$row->member_key.' class="icon-edit" onclick="saveBesuk(\'edit\',\''.$row->besukid.'\',\''.$row->member_key.'\');" style="width:16px;height:16px;border:0"></button> ';

				$del = '<button id='.$row->member_key.' class="icon-remove" onclick="delBesuk(\'del\','.$row->besukid.',\''.$row->member_key.'\');" style="width:16px;height:16px;border:0"></button>';

			$row->aksi =$view.$edit.$del;
			$row->besukdate=$row->besukdate=="00-00-0000"?"-":$row->besukdate;
		}
		$response = new stdClass;
		$response->total=$total;
		$response->rows = $data;
		$_SESSION['excelbesuk']= "asc|member_key|".$cond;
		echo json_encode($response);
	}
	public function grid(){
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : 'member_key';
		$order = isset($_GET['order']) ? strval($_GET['order']) : 'asc';

		$filterRules = isset($_GET['filterRules']) ? ($_GET['filterRules']) : '';
		$cond = '';
		if (!empty($filterRules)){
			$cond = ' where  1=1 ';
			$filterRules = json_decode($filterRules);

			foreach($filterRules as $rule){
				$rule = get_object_vars($rule);
				$field = $rule['field'];
				$op = $rule['op'];
				$value = $rule['value'];
				if (!empty($value)){
					if ($op == 'contains'){
						$cond .= " and ($field like '%$value%')";
					} else if ($op == 'greater'){
						$cond .= " and $field>$value";
					}
				}
			}
		}
		$where='';
		$sql = $this->mbesuk->count($cond);
		$total = $sql->num_rows();
		$offset = ($page - 1) * $rows;
		$data = $this->mbesuk->getM($cond,$sort,$order,$rows,$offset)->result();
		foreach($data as $row){
			$view='';
			$edit='';
			$del='';
				$view = '<button id='.$row->member_key.' class="icon-view_detail" onclick="viewBesuk(\'view\',\''.$row->besukid.'\',\''.$row->member_key.'\')" style="width:16px;height:16px;border:0"></button> ';

				$edit = '<button id='.$row->member_key.' class="icon-edit" onclick="saveBesuk(\'edit\',\''.$row->besukid.'\',\''.$row->member_key.'\');" style="width:16px;height:16px;border:0"></button> ';

				$del = '<button id='.$row->member_key.' class="icon-remove" onclick="delBesuk(\'del\','.$row->besukid.',\''.$row->member_key.'\');" style="width:16px;height:16px;border:0"></button>';

			$row->aksi =$view.$edit.$del;
			$row->besukdate=$row->besukdate=="00-00-0000"?"-":$row->besukdate;
		}
		$response = new stdClass;
		$response->total=$total;
		$response->rows = $data;
		$_SESSION['excelbesuk']= "asc|member_key|".$cond;
		echo json_encode($response);
	}
	public function excel(){
		excel('excelbesuk','tblbesuk','besuk/excel');
	}
	function form($form,$besukid,$member_key){
		$data["besukid"] = $besukid;
		$data["member_key"] = $member_key;
		$data['sql'] = $this->mbesuk->getwhere($member_key);
		$this->load->view('besuk/'.$form,$data);
	}

	function crud(){
		@$oper=@$_POST['oper'];
		$_POST = array_map("strtoupper", $_POST);
	    @$besukid=@$_POST['besukid'];
	    @$besukdate = $_POST['besukdate'];
	    @$exp1 = explode('/',$besukdate);
		@$besukdate = $exp1[2]."-".$exp1[0]."-".$exp1[1]." ".date("H:i:s");
		@$data = array(
			'member_key' => @$_POST['member_key'],
			'besukdate' => @$besukdate,
			'pembesuk' => @$_POST['pembesuk'],
			'pembesukdari' => @$_POST['pembesukdari'],
			'remark' => @$_POST['remark'],
			'besuklanjutan' => @$_POST['besuklanjutan'],
			'modifiedby' => $_SESSION['username'],
			'modifiedon' => date("Y-m-d H:i:s")
			);
	    switch ($oper) {
	        case 'add':
				$this->mbesuk->add("tblbesuk",$data);
				$hasil = array(
			        'status' => 'sukses'
			    );
			    echo json_encode($hasil);
	            break;
	        case 'edit':
				$this->mbesuk->edit("tblbesuk",$data,$besukid);
				$hasil = array(
			        'status' => 'sukses'
			    );
			    echo json_encode($hasil);
	            break;
	         case 'del':
				$this->mbesuk->del("tblbesuk",$besukid);
				$hasil = array(
			        'status' => 'sukses'.$besukid.$oper
			    );
			    echo json_encode($hasil);
	            break;
		}
	}

}