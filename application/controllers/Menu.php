<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('mmenu');
	}
	/**
     * Fungsi list menu
     * @AclName List Menu
     */
	function index(){
		$link = base_url().'menu/grid';
		$this->render('menu/gridmenu',['link'=>$link]);
	}
	/**
     * Fungsi grid menu
     * @AclName Grid Menu
     */
	function grid(){
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : 'menuid';
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
					}
				}
			}
		}

		$sql = $this->mmenu->count($cond);
		$total = $sql->num_rows();
		$offset = ($page - 1) * $rows;
		$data = $this->mmenu->get($cond,$sort,$order,$rows,$offset)->result();

		foreach($data as $row){
			$view = hasPermission('menu','view')?'<button class="icon-view_detail" onclick="viewData(\''.$row->menuid.'\')" style="width:16px;height:16px;border:0"></button> ':'';
			$edit = hasPermission('menu','edit')?'<button class="icon-edit" onclick="editData(\''.$row->menuid.'\')" style="width:16px;height:16px;border:0"></button> ':'';
			$del = hasPermission('menu','delete')?'<button class="icon-remove" onclick="deleteData(\''.$row->menuid.'\')" style="width:16px;height:16px;border:0"></button>':'';
			$row->aksi = $view.$edit.$del;
		}
		$response = new stdClass;
		$response->total=$total;
		$response->rows = $data;
		$_SESSION['excel']= "asc|menuid|".$cond;
		echo json_encode($response);
	}
	/**
     * Fungsi view Menu
     * @AclName View Menu
     */
	public function view($id){
		$data = $this->mmenu->getById('tblmenu','menuid',$id);
        if(empty($data)){
            redirect('menu');
        }
		$this->load->view('menu/view',['data'=>$data]);
	}
	/**
     * Fungsi add Menu
     * @AclName Tambah Menu
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
			$data = $this->input->post();
		}
		$this->load->view('menu/form',['data'=>$data]);
	}
	/**
     * Fungsi edit menu
     * @AclName Edit Menu
     */
	public function edit($id){
		$data = $this->mmenu->getById('tblmenu','menuid',$id);
        if(empty($data)){
            redirect('menu');
        }
		if($this->input->server('REQUEST_METHOD') == 'POST' ){
			$data = $this->input->post();
			$data['menuid'] = $this->input->post('menuid');
			$cek = $this->_save($data);
			$status = $cek?"sukses":"gagal";
			$hasil = array(
		        'status' => $status
		    );
		    echo json_encode($hasil);
		}
		$this->load->view('menu/form',['data'=>$data]);
	}
	/**
     * Fungsi delete Menu
     * @AclName Delete Menu
     */
	public function delete($id){
		$data = $this->mmenu->getById('tblmenu','menuid',$id);
		if(empty($data)){
			redirect('menu');
		}
		if($this->input->server('REQUEST_METHOD') == 'POST'){
			$cek = $this->mmenu->delete($this->input->post('menuid'));
			$status = $cek?"sukses":"gagal";
			$hasil = array(
		        'status' => $status
		    );
		    echo json_encode($hasil);
		}
		$this->load->view('menu/delete',['data'=>$data]);
	}
	private function _save($data){
        $this->mmenu->save($data);
    }
	function grid2(){
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