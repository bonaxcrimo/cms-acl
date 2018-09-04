<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model([
			'muser',
			'musermenu'
		]);
	}

	public function index(){
		$link = base_url()."user/grid";
		$this->render('user/griduser',['link'=>$link]);
	}
	public function grid(){

	}

	function grid2(){
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
		@$search = $_POST['_search'];
			$where = "";
       		if(($search==true) &&($filters != "")) {
				$where= $this->operation($filters);
		    }
		$sql = $this->muser->count($where);
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
		$data = $this->muser->get($where, $sidx, $sord, $limit, $start);
		$_SESSION['exceluser']= $sord."|".$sidx."|".$where;
		@$responce->page = $page;
		@$responce->total = $total_pages;
		@$responce->records = $count;
		$i=0;
		foreach($data->result() as $row){
			if(substr($acl,0,1)==1){
				$view='<a href="#" id='.$row->userpk.' title="view" class="btnview" style="float:left"><span class="ui-icon ui-icon-document"></span></a>';
			}
			else{
				$view='<span style="float:left" class="ui-state-disabled ui-icon ui-icon-document"></span>';
			}
			if(substr($acl,2,1)==1){
				$edit='<a href="#" id='.$row->userpk.' title="Edit" class="btnedit" style="float:left"><span class="ui-icon ui-icon-pencil"></span></a>';
			}
			else{
				$edit='<span style="float:left" class="ui-state-disabled ui-icon ui-icon-pencil"></span>';
			}
			if(substr($acl,3,1)==1){
				$del='<a href="#" id='.$row->userpk.' title="Del" class="btndel" style="float:left"><span class="ui-icon ui-icon-trash"></span></a>';
			}
			else{
				$del='<span class="ui-state-disabled ui-icon ui-icon-trash"></span>';
			}
			$responce->rows[$i]['id']   = $row->userpk;
			$responce->rows[$i]['cell'] = array(
				$view.$edit.$del,
				$row->userid,
				$row->username,
				$row->userlevel,
				$row->password,
				$row->password1,
				$row->authorityid,
				$row->dashboard,
				$row->modifiedby,
				$row->modifiedonview
				);
			$i++;
		}
		echo json_encode($responce);
	}

	function form($form,$userpk,$formname){
		$data['formname'] = $formname;
		if($userpk!=null || $userpk!=""){
			$sql= $this->muser->getwhere($userpk);
			$count = $sql->num_rows();
			$data["userpk"] = $userpk;
		}
		$this->load->view('user/'.$form,$data);
	}

	function crud(){
		@$oper=@$_POST['oper'];
	    @$userpk=@$_POST['userpk'];
	    if($_POST['password']!=""){
			@$data = array(
				'userid' => @$_POST['userid'],
				'username' => @$_POST['username'],
				'userlevel' => @$_POST['userlevel'],
				'password' => md5(@$_POST['password']),
				'password1' => md5(@$_POST['password1']),
				'authorityid' => @$_POST['authorityid'],
				'dashboard' => @$_POST['dashboard'],
				'modifiedby' => $_SESSION['userid'],
				'modifiedon' => date("Y-m-d H:i:s")
			);
		}
		else{
			@$data = array(
				'userid' => @$_POST['userid'],
				'username' => @$_POST['username'],
				'userlevel' => @$_POST['userlevel'],
				'authorityid' => @$_POST['authorityid'],
				'dashboard' => @$_POST['dashboard'],
				'modifiedby' => $_SESSION['userid'],
				'modifiedon' => date("Y-m-d H:i:s")
			);
		}
	    switch ($oper) {
	        case 'add':
				$userpk = $this->muser->add("tbluser",$data);
				$this->musermenu->addusermenu($userpk);
				$hasil = array(
			        'status' => 'sukses'
			    );
			    echo json_encode($hasil);
	            break;
	        case 'edit':
				$this->muser->edit("tbluser",$data,$userpk);
				$hasil = array(
			        'status' => 'sukses'
			    );
			    echo json_encode($hasil);
	            break;
	         case 'del':
				$this->muser->del("tbluser",$userpk);
				$this->musermenu->delusermenu($userpk);
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

	function generate($userpk){
		$this->musermenu->delusermenu($userpk);
		$this->musermenu->addusermenu($userpk);
		echo "sukses";
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

}