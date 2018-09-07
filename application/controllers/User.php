<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User extends MY_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model([
			'muser',
			'musermenu',
            'mroles'
		]);
	}
    /**
     * tampilan awal dari user
     * @AclName List User
     */
	public function index(){
		$link = base_url()."user/grid";
		$this->render('user/griduser',['link'=>$link]);
	}
    /**
     *  grid user
     * @AclName Grid User
     */
	public function grid(){
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $sort = isset($_GET['sort']) ? strval($_GET['sort']) : 'userpk';
        $order = isset($_GET['order']) ? strval($_GET['order']) : 'asc';

        $filterRules = isset($_GET['filterRules']) ? ($_GET['filterRules']) : '';
        $cond = '';
        if (!empty($filterRules)){
            $cond = ' where   1=1 ';
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
        $sql = $this->muser->count($cond);
        $total = $sql->num_rows();
        $offset = ($page - 1) * $rows;
        $data = $this->muser->get($cond,$sort,$order,$rows,$offset)->result();
        foreach($data as $row){
            $rolename =  $this->muser->getByIdUser($row->userpk)->roles_name;
            $edit = '<button class="icon-edit" onclick="editData(\''.$row->userpk.'\')" style="width:16px;height:16px;border:0"></button> ';
            $view = '<button class="icon-view_detail" onclick="viewData(\''.$row->userpk.'\')" style="width:16px;height:16px;border:0"></button> ';
            $delete = '<button class="icon-remove" onclick="deleteData(\''.$row->userpk.'\')" style="width:16px;height:16px;border:0"></button> ';
            $row->aksi = $view.$edit.$delete;
            $row->rolename= $rolename;
        }
        $response = new stdClass;
        $response->total=$total;
        $response->rows = $data;
        $_SESSION['exceluser']= "asc|offering_key|";
        echo json_encode($response);

	}
    /**
     *  view user
     * @AclName View User
     */
    public function view($id){
        $data =  $this->muser->getByIdUser($id);

        if(empty($data)){
            redirect('user');
        }
        $data->user_roles = strpos($data->roles,',')===false?$data->roles:explode(', ',$data->roles);
        $this->load->view('user/view',['data'=>$data]);
    }
    /**
     *  tambah user
     * @AclName Tambah User
     */
    public function add(){
        $data=[];
        $roles = $this->mroles->getList();
        $tmp = [];
        foreach($roles as $role){
            $tmp[$role->roleid] = $role->rolename;
        }
        $roles = $tmp;
        unset($tmp);
        if($this->input->server('REQUEST_METHOD')=='POST'){
            $data = $this->input->post();
            $this->_save($data);
        }
        $this->load->view('user/form',['data'=>$data,'roles'=>$roles]);
    }
    /**
     *  edit user
     * @AclName Edit User
     */
    public function edit($id){
        $data=[];
        $roles = $this->mroles->getList();
        $tmp = [];
        foreach($roles as $role){
            $tmp[$role->roleid] = $role->rolename;
        }
        $roles = $tmp;
        unset($tmp);
        $data =  $this->muser->getByIdUser($id);

        if(empty($data)){
            redirect('user');
        }
        $data->user_roles = strpos($data->roles,',')===false?$data->roles:explode(', ',$data->roles);
        if($this->input->server('REQUEST_METHOD')=='POST'){
            $data = $this->input->post();
            $data['userpk'] = $id;
            $this->_save($data);
            // redirect('user');
        }
        $this->load->view('user/form',['data'=>$data,'roles'=>$roles]);
    }
    /**
     * Fungsi delete user
     * @AclName Delete User
     */
    public function delete($id){
       $data = $this->muser->getByIdUser($id);
        if(empty($data)){
            redirect('user');
        }
        if($this->input->server('REQUEST_METHOD') == 'POST'){
            $cek = $this->muser->delete($this->input->post('userpk'));
            $status = $cek?"sukses":"gagal";
            $hasil = array(
                'status' => $status
            );
            echo json_encode($hasil);
        }
        $this->load->view('user/delete',['data'=>$data]);
    }
    private function _save($data){
        $this->muser->save($data);
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