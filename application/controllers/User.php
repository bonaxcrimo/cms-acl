<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User extends MY_Controller {
	public function __construct(){
		parent::__construct();
        session_start();
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

    public function getRoles(){
        $role = $this->db->get('tblroles')->result();
        echo json_encode($role);
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


}