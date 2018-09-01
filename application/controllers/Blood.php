<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class blood extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model([
			'mblood'
		]);
	}
	function index(){
		$link = base_url()."blood/grid";
		$this->render('blood/gridblood',['link'=>$link]);
	}
	function grid(){
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : 'parameterid';
		$order = isset($_GET['order']) ? strval($_GET['order']) : 'asc';
		$filterRules = isset($_GET['filterRules']) ? ($_GET['filterRules']) : '';
		$cond = '';
		if (!empty($filterRules)){
			$cond = ' where 1=1 ';
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
		$sql = $this->mblood->count($cond);
		$total = $sql->num_rows();
		$offset = ($page - 1) * $rows;
		$data = $this->mblood->getJ($cond,$sort,$order,$rows,$offset)->result();
		// $total = count($data);
		foreach($data as $row){
				$view = '<button class="icon-view_detail" onclick="viewBlood(\''.$row->parameter_key.'\')" style="width:16px;height:16px;border:0"></button> ';
				$edit = '<button class="icon-edit" onclick="editBlood(\''.$row->parameter_key.'\')" style="width:16px;height:16px;border:0"></button> ';
				$del = '<button class="icon-remove" onclick="deleteBlood(\''.$row->parameter_key.'\')" style="width:16px;height:16px;border:0"></button>';
			$row->aksi = $view.$edit.$del;
		}
		$response = new stdClass;
		$response->total=$total;
		$response->rows = $data;
		$_SESSION['excel']= "asc|parameter_key|".$cond;
		echo json_encode($response);
	}

	function form($form,$bloodid){
		$data["parameter_key"] = $parameter_key;
		$this->load->view('blood/'.$form,$data);
	}
	function crud($id=0){
		$_POST = array_map("strtoupper", $_POST);
		@$paramkey = @($_REQUEST['parameter_key']);
		@$paramtext = @($_REQUEST['parametertext']);
		@$oper= @$_REQUEST['oper'];
		@$data = array(
			'parametergrpid' =>'BLOOD',
			'parameterid' =>  strtoupper(@$paramtext),
			'parametertext' => strtoupper(@$paramtext),
			'modifiedby' => $_SESSION['username'],
			'modifiedon' => date("Y-m-d H:i:s")
		);
		switch ($oper) {
	        case 'add':
				$cek = $this->mblood->add("tblparameter",$data);
				$status = $cek?"sukses":"gagal";
				$hasil = array(
			        'status' => $status
			    );
			    echo json_encode($hasil);
	            break;
            case 'del':
				$cek = $this->mblood->del("tblparameter",$paramkey);
				$status = $cek?"sukses":"gagal";
				$hasil = array(
			        'status' => $status
			    );
			    echo json_encode($hasil);
	            break;
	        case 'edit':
				$cek = $this->mblood->edit("tblparameter",$data,$paramkey);
				$status = $cek?"sukses":"gagal";
				$hasil = array(
			        'status' => $status
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

}