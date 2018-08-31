<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Besuk extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('my_helper');
		$this->load->model([
			'mlogin',
			'mbesuk',
			'mgender',
			'mpstatus',
			'mblood',
			'mkebaktian',
			'mpersekutuan',
			'mrayon',
			'mmenutop',
			'mjemaat',
			'mparameter',
			'mserving',
			'mmenu'
		]);
		$cek = $this->mlogin->cek();
		if($cek==""){
			redirect("");
			session_destroy();
		}
		date_default_timezone_set("Asia/Jakarta");
		ini_set('memory_limit', '-1');
		$this->load->helper('my_helper');
	}
	function set(){
		$_SESSION['member_key'] = $_GET['member_key'];
	}
	function index(){
		$data['acl'] = hakakses('besuk');
		$data['sqlmenu'] = $this->mmenutop->get_data();
		$this->load->view('partials/header');
		$this->load->view('navbar',$data);
		$this->load->view('besuk/gridbesuk');
		$this->load->view('partials/footer');
	}
	function jemaat(){
		if(empty($_SESSION['member_key'])){
			echo" Empty";
		}
		else{
			$data['member_key'] = $_SESSION['member_key'];
			$data['sql'] = $this->mbesuk->getwhere($_SESSION['member_key']);

			$data['statusid'] = $this->uri->segment(2);
			$data['acl'] = hakakses($_GET['op']);

			$data['sqlmenu'] = $this->mmenutop->get_data();

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

			$this->load->view('jemaat/gridbesuk2',$data);
		}
	}
	function grid2($member_key){
		$acl = hakakses('jemaat');
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
			if(substr($acl,0,1)==1){
				$view = '<button id='.$row->member_key.' class="icon-view_detail" onclick="viewBesuk(\'view\',\''.$row->besukid.'\',\''.$row->member_key.'\')" style="width:16px;height:16px;border:0"></button> ';
			}
			if(substr($acl,2,1)==1){
				$edit = '<button id='.$row->member_key.' class="icon-edit" onclick="saveBesuk(\'edit\',\''.$row->besukid.'\',\''.$row->member_key.'\');" style="width:16px;height:16px;border:0"></button> ';
			}
			if(substr($acl,3,1)==1){
				$del = '<button id='.$row->member_key.' class="icon-remove" onclick="delBesuk(\'del\','.$row->besukid.',\''.$row->member_key.'\');" style="width:16px;height:16px;border:0"></button>';
			}
			$row->aksi =$view.$edit.$del;
			$row->besukdate=$row->besukdate=="00-00-0000"?"-":$row->besukdate;
		}
		$response = new stdClass;
		$response->total=$total;
		$response->rows = $data;
		$_SESSION['excelbesuk']= "asc|member_key|".$cond;
		echo json_encode($response);
	}
	function grid3(){
		$acl = hakakses('jemaat');
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
			if(substr($acl,0,1)==1){
				$view = '<button id='.$row->member_key.' class="icon-view_detail" onclick="viewBesuk(\'view\',\''.$row->besukid.'\',\''.$row->member_key.'\')" style="width:16px;height:16px;border:0"></button> ';
			}
			if(substr($acl,2,1)==1){
				$edit = '<button id='.$row->member_key.' class="icon-edit" onclick="saveBesuk(\'edit\',\''.$row->besukid.'\',\''.$row->member_key.'\');" style="width:16px;height:16px;border:0"></button> ';
			}
			if(substr($acl,3,1)==1){
				$del = '<button id='.$row->member_key.' class="icon-remove" onclick="delBesuk(\'del\','.$row->besukid.',\''.$row->member_key.'\');" style="width:16px;height:16px;border:0"></button>';
			}
			$row->aksi =$view.$edit.$del;
			$row->besukdate=$row->besukdate=="00-00-0000"?"-":$row->besukdate;
		}
		$response = new stdClass;
		$response->total=$total;
		$response->rows = $data;
		$_SESSION['excelbesuk']= "asc|member_key|".$cond;
		echo json_encode($response);
	}
	function coba(){
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

	function excel(){
		$excel = $_SESSION['excelbesuk'];
		$splitexcel = explode("|",$excel);
		$sord = $splitexcel[0];
		$sidx= $splitexcel[1];
		$where = $splitexcel[2];
		$data['sql']=$this->db->query("SELECT *,
		DATE_FORMAT(modifiedon,'%d-%m-%Y') modifiedon
		FROM tblbesuk " . $where . " ORDER BY $sidx $sord");
		$this->load->view('besuk/excel',$data);
	}

}