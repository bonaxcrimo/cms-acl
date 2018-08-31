<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Create_relasi extends CI_Controller {

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
		
		$this->load->model('mjemaat');
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

	function index(){
		echo "ccccccc";
		$this->load->view("jemaat/gridcreaterelasi");
	}

	function view(){
		$data['sqlgender'] = $this->mgender->get_jemaat();
		$data['sqlpstatus'] = $this->mpstatus->get_jemaat();
		$data['sqlstatusidv'] = $this->mparameter->get_jemaat();
		$data['sqlblood'] = $this->mblood->get_jemaat();
		$data['sqlkebaktian'] = $this->mkebaktian->get_jemaat();
		$data['sqlpersekutuan'] = $this->mpersekutuan->get_jemaat();
		$data['sqlrayon'] = $this->mrayon->get_jemaat();
		
		$data['gender'] = $this->mgender->get_combo();
		$data['pstatus'] = $this->mpstatus->get_combo();
		$data['statusidv'] = $this->mparameter->get_combo();
		$data['blood'] = $this->mblood->get_combo();
		$data['kebaktian'] = $this->mkebaktian->get_combo();
		$data['persekutuan'] = $this->mpersekutuan->get_combo();
		$data['rayon'] = $this->mrayon->get_combo();
		
		$data['bgfilter'] = $this->mparameter->get_bgfilter();
		$data['bgsortira'] = $this->mparameter->get_bgsortira();
		$data['bgsortird'] = $this->mparameter->get_bgsortird();
		$this->load->view('jemaat/gridcreaterelasi',$data);
	}
	function grid2(){
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : 'recno';
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
		$sql = $this->mjemaat->count_relasi($cond);
		$total = $sql->num_rows();
		$offset = ($page - 1) * $rows;
		$data = $this->mjemaat->get_relasiM($cond,$sort,$order,$rows,$offset);
		$listField = $data->list_fields();
		$_SESSION['listField']= $listField;
		$data= $data->result();
		// $total = count($data);

		$response = new stdClass;
		$response->total=$total;
		$response->rows = $data;
		$_SESSION['excel']= "asc|recno|";
		echo json_encode($response);
	}
	function grid(){
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
			$where="";
       		if($filters!= "") {
				$where = $this->operation($filters);
		    }
		$sql = $this->mjemaat->count_relasi($where);
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
		$data = $this->mjemaat->get_relasi($where, $sidx, $sord, $limit, $start);
		@$responce->page = $page;
		@$responce->total = $total_pages;
		@$responce->records = $count;
		$i=0;
		foreach($data->result() as $row){
			$del='<a href="#" id='.$row->recno.' title="Del" class="btndelcreatrelasi" style="float:left"><span class="ui-icon ui-icon-trash"></span></a>';
			$responce->rows[$i]['id']   = $row->recno;
			$responce->rows[$i]['cell'] = array(
				$del,
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

	function creat(){
		$sql = $this->mjemaat->creat();
		echo $sql;
	}

	function delete($recno){
		echo $this->mjemaat->deletecreat($recno);
	}

	function deleteall(){
		$this->mjemaat->deletecreatall();
		echo 1;
	}

	function deletetabel(){
		echo $this->mjemaat->deletetabel();
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
			$where = " ";
		}
		return $where;
	}

}