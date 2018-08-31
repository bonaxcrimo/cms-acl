<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends CI_Controller {

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
		$this->load->model('mmenu');
		$this->load->model('mmenutop');
        $this->load->helper('my_helper');

	}

	function index(){
		$data['acl'] = $this->hakakses('menu');
		$data['sqlmenu'] = $this->mmenutop->get_data();
		$this->load->view('header');
		$this->load->view('navbar',$data);
		$this->load->view('menu/gridmenu');
		$this->load->view('footer');
	}
	
	function grid(){
		$acl = $this->hakakses('menu');
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
			$where = "";
       		if(($search==true) &&($filters != "")) {
				$where= $this->operation($filters);
		    }
		$sql = $this->mmenu->count($where);
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
		$data = $this->mmenu->get($where, $sidx, $sord, $limit, $start);
		$_SESSION['excel']= $sord."|".$sidx."|".$where;
		@$responce->page = $page;
		@$responce->total = $total_pages;
		@$responce->records = $count;
		$i=0;
		foreach($data->result() as $row){
			$view='<a href="#" menuid="'.$row->menuid.'" title="View" class="btnview" style="float:left"><span class="ui-icon ui-icon-document"></span></a>';
			$edit='<a href="#" menuid="'.$row->menuid.'" title="Edit" class="btnedit" style="float:left"><span class="ui-icon ui-icon-pencil"></span></a>';
			$del='<a href="#" menuid="'.$row->menuid.'" title="Del" class="btndel" style="float:left"><span class="ui-icon ui-icon-trash"></span></a>';
			$responce->rows[$i]['id']   = '|'.$row->menuid;
			$responce->rows[$i]['cell'] = array(
				$view.$edit.$del,
				$row->menuid,
				$row->menuname,
				$row->menuseq,
				$row->menuparent,
				$row->menuicon,
				$row->menuexe,
				$row->modifiedby,
				$row->modifiedonview
				);
			$i++;
		}
		echo json_encode($responce);
	}

	function form($form,$menuid){
		$data["menuid"] = $menuid;
		$this->load->view('menu/'.$form,$data);
	}

	function crud(){
		@$oper=@$_POST['oper'];
	    @$menuid=@$_POST['menuid'];
		@$data = array(
			'menuname' => @$_POST['menuname'],
			'menuseq' => @$_POST['menuseq'],
			'menuparent' => @$_POST['menuparent'],
			'menuicon' => @$_POST['menuicon'],
			'menuexe' => @$_POST['menuexe'],
			'modifiedby' => $_SESSION['username'],
			'modifiedon' => date("Y-m-d H:i:s")
			);
	    switch ($oper) {
	        case 'add':
				$this->mmenu->add("tblmenu",$data);
				$hasil = array(
			        'status' => 'sukses'
			    );
			    echo json_encode($hasil);
	            break;
	        case 'edit':
				$this->mmenu->edit("tblmenu",$data,$menuid);
				$hasil = array(
			        'status' => 'sukses'
			    );
			    echo json_encode($hasil);
	            break;
	         case 'del':
				$this->mmenu->del("tblmenu",$menuid);
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
        $filters = str_replace('\"','"' ,$filters);
        $filters = str_replace('"[','[' ,$filters);
        $filters = str_replace(']"',']' ,$filters);
		$filters = json_decode($filters);
		$where = " where ";
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
		    	if($fieldName=="modifiedon"){
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

	function excel(){
		$excel = $_SESSION['excel'];
		$splitexcel = explode("|",$excel);
		$sord = $splitexcel[0];
		$sidx= $splitexcel[1];
		$where = $splitexcel[2];
		$data['sql']=$this->db->query("SELECT *,
		DATE_FORMAT(modifiedon,'%d-%m-%Y') modifiedon
		FROM tblmenu " . $where . " ORDER BY $sidx $sord");
		$this->load->view('menu/excel',$data);
	}

	function reseq(){
		$this->mmenu->reseq();
		echo "sukses";
	}

	function hakakses($x){
		$x = $this->mmenutop->get_menuid($x);
		return $x;
	}
}