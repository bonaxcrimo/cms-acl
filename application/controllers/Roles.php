<?php
class Roles extends MY_Controller{
    public function __construct(){
        parent::__construct();
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
        $response = new stdClass;
        $response->total=$total;
        $response->rows = $data;
        $_SESSION['excelroles']= "asc|parameter_key|".$cond;
        echo json_encode($response);
    }
    /**
     * Fungsi tambah roles
     * @AclName Tambah Roles
     */
    public function add(){
        $data = [];
        $acos = $this->Macos->getList();
        $groups = $this->Macos->getGroup();
        if($this->input->server('REQUEST_METHOD') == "POST"){
            if($this->_validateForm()){
                $data = $this->input->post();
                $this->_save($data);
                redirect('roles');
            }else{
                $data = $this->input->post();
            }
        }
        $this->render('roles/add',['data'=>$data,'acos'=>$acos,'groups'=>$groups]);
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
        $data->role_permission = strpos($data->acos,',')===false?[$data->acos]:explode(', ',$data->acos);
        if($this->input->server('REQUEST_METHOD') == "POST"){
            if($this->_validateForm()){
                $data = $this->input->post();
                $data['roleid']=$id;
                $this->_save($data);
                redirect('roles');
            }else{
                $data = $this->input->post();
            }
        }
        $this->render('roles/edit',['data'=>$data,'acos'=>$acos]);
    }
    /**
     * Fungsi delete roles
     * @AclName Delete Roles
     */
    public function delete($id){
        $role = $this->Mroles->getByIdRoles($id);
        if(empty($role)){
            redirect('roles');
        }
        $this->Mroles->delete($id);
        redirect('roles');
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
            ],
            [
                'field' => 'role_permission[]|numeric',
                'label' => 'Roles',
                'rules' => 'required'
            ]
        ];
        $this->form_validation->set_rules($rules);
        return $this->form_validation->run();
    }
     public function validateName($name){
        //get id from the url
        $id = $this->uri->segment('3');
        $exist = $this->Mroles->isNameExists($name, $id);

        if($exist === false){
            //name does not exists in table
            return true;
        }
        //name exists and throw error
        $this->form_validation->set_message(__FUNCTION__, "{field} '$name' is already exists.");
        return false;
    }
}