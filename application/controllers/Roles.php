<?php
class Roles extends MY_Controller{
    public function __construct(){
        parent::__construct();
        session_start();
        $this->load->model([
            'Mroles',
            'Macos'
        ]);
    }
    /**
     * tampilan awal dari roles
     * @AclName List Roles
     */
    public function index(){
        $link = base_url().'roles/grid';
        $this->render('roles/gridroles',['link'=>$link]);
    }
    /**
     * Fungsi view Roles
     * @AclName View Roles
     */
    public function view($id){
        $acos = $this->Macos->getList();
        $data = $this->Mroles->getByIdRoles($id);
        if(empty($data)){
            redirect('roles');
        }
        $data->role_permission = strpos($data->acos,',')===false?[$data->acos]:explode(', ',$data->acos);
        $this->load->view('roles/view',['data'=>$data,'acos'=>$acos]);
    }
    /**
     * Merupakan Grid dari Roles
     * @AclName Grid Roles
     */
    public function grid(){
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $sort = isset($_GET['sort']) ? strval($_GET['sort']) : 'roleid';
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
        $sql = $this->Mroles->count($cond);
        $total = $sql->num_rows();
        $offset = ($page - 1) * $rows;
        $data = $this->Mroles->get($cond,$sort,$order,$rows,$offset)->result();
        foreach($data as $row){
            $view = hasPermission('roles','view')?'<button class="icon-view_detail" onclick="viewData(\''.$row->roleid.'\')" style="width:16px;height:16px;border:0"></button> ':'';
            $edit = hasPermission('roles','edit')?'<button class="icon-edit" onclick="editData(\''.$row->roleid.'\')" style="width:16px;height:16px;border:0"></button> ':'';
            $del = hasPermission('roles','delete')?'<button class="icon-remove" onclick="deleteData(\''.$row->roleid.'\')" style="width:16px;height:16px;border:0"></button>':'';
            $row->aksi = $view.$edit.$del;
        }
        $response = new stdClass;
        $response->total=$total;
        $response->rows = $data;
        $_SESSION['excel']= "asc|parameter_key|".$cond;
        echo json_encode($response);
    }
    /**
     * Fungsi tambah roles
     * @AclName Tambah Roles
     */
    public function add(){
        $data = [];
        $acos = $this->Macos->getList();
        if($this->input->server('REQUEST_METHOD') == "POST"){
            if($this->_validateForm()){
                $data = $this->input->post();
                $this->_save($data);
                $error = 0;
                // redirect('roles');
            }else{
                $data = $this->input->post();
                $error = 1;
            }
            echo json_encode(['error'=>$error,'message'=>$this->form_validation->error_array()]);
        }else{
            $this->load->view('roles/form',['data'=>$data,'acos'=>$acos]);
        }

    }
    /**
     * Fungsi edit roles
     * @AclName Edit Roles
     */
    public function edit($id){
        $acos = $this->Macos->getList();
        $data = $this->Mroles->getByIdRoles($id);
        if(empty($data)){
            redirect('roles');
        }
        $temp = $data;
        $data->role_permission = strpos($data->acos,',')===false?[$data->acos]:explode(', ',$data->acos);
        $error = 0;
        if($this->input->server('REQUEST_METHOD') == "POST"){
            if($this->_validateForm()){
                $data = $this->input->post();
                $data['roleid']=$id;
                $this->_save($data);
            }else{
                $data = $this->input->post();
                $error =1;
            }
            echo json_encode(['error'=>$error,'message'=>$this->form_validation->error_array()]);
        }else{
            $this->load->view('roles/form',['data'=>$data,'acos'=>$acos]);
        }
    }

    /**
     * Fungsi delete roles
     * @AclName Delete Roles
     */
    public function delete($id){
        $acos = $this->Macos->getList();
        $data = $this->Mroles->getByIdRoles($id);
        if(empty($data)){
            redirect('roles');
        }
        $data->role_permission = strpos($data->acos,',')===false?[$data->acos]:explode(', ',$data->acos);
        $error = 0;
        if($this->input->server('REQUEST_METHOD') == 'POST'){
            $cek = $this->Mroles->delete($id);
            $error = $cek?0:1;
            $hasil = array(
                'error' => $error
            );
            echo json_encode($hasil);
        }else{
            $this->load->view('roles/delete',['data'=>$data,'acos'=>$acos]);
        }

    }
    private function _save($data){
        $this->Mroles->save($data);
    }
    private function _validateForm(){
        $rules = [
            [
                'field' => 'rolename',
                'label' => 'rolename',
                'rules' => 'trim|required|max_length[50]|callback_validateName'
            ]
        ];
        $this->form_validation->set_rules($rules);
        return $this->form_validation->run();
    }
    public function validateName($name){
        //ambil id dari url
        $id = $this->uri->segment('3');
        $exist = $this->Mroles->isNameExists($name, $id);
        if($exist === false){
            //nama tidak ada di table
            return true;
        }
        //nama ada kembalikan pesan error
        $this->form_validation->set_message(__FUNCTION__, "{field} '$name' is already exists.");
        return false;
    }
}