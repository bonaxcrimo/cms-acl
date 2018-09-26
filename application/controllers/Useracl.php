<?php
class UserAcl extends MY_Controller{
    public function __construct(){
        parent::__construct();
        session_start();
        $this->load->model([
            'Museracl',
            'Macos'
        ]);
    }
    /**
     * Fungsi list awal
     * @AclName List Awal
     */
    public function index(){
        if(empty($_SESSION['userpk'])){
            echo "Empty";
        }
        else{
            $data['userpk'] = $_SESSION['userpk'];
            $this->load->view('user/griduseracl',$data);
        }
    }
    /**
     * Fungsi edit user acl
     * @AclName Edit User Acl
     */
    public function edit($userpk=null){
        if(empty($userpk)){
            redirect('user');
        }
        $acos = $this->Macos->getList();
        $data = $this->Museracl->getByIdUser($userpk);
        $data->role_permission = strpos($data->acos,',')===false?[$data->acos]:explode(', ',$data->acos);
        $error = 0;
        if($this->input->server('REQUEST_METHOD') == "POST"){
            if($this->_validateForm()){
                $data = $this->input->post();
                $data['userpk']=$userpk;
                $this->_save($data);
            }else{
                $data = $this->input->post();
                $error =1;
            }
            echo json_encode(['error'=>$error,'message'=>$this->form_validation->error_array()]);
        }else{
            $this->load->view('user/useracl/form',['data'=>$data,'acos'=>$acos,'userpk'=>$userpk]);
        }
    }
    private function _save($data){
        $this->Museracl->save($data);
    }
    private function _validateForm(){
        $rules = [
            [
                'field' => 'userpk',
                'label' => 'userpk',
                'rules' => 'required|numeric'
            ],
            [
                'field' => 'role_permission[]',
                'label' => 'Roles',
                'rules' => 'required|numeric'
            ]
        ];
        $this->form_validation->set_rules($rules);
        return $this->form_validation->run();
    }
    public function grid($userpk){
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $sort = isset($_GET['sort']) ? strval($_GET['sort']) : 'useraclid';
        $order = isset($_GET['order']) ? strval($_GET['order']) : 'asc';
        $filterRules = isset($_GET['filterRules']) ? ($_GET['filterRules']) : '';
        $cond = '';
        if (!empty($filterRules)){
            $cond = ' where userpk = "'.$userpk.'" and  1=1 ';
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
            $cond = ' where userpk = "'.$userpk.'" ';
        }
        $where='';
        $sql = $this->Museracl->count($cond,'');
        $total = $sql->num_rows();
        $offset = ($page - 1) * $rows;
        $data = $this->Museracl->get($cond,$sort,$order,$rows,$offset,'')->result();
        foreach($data as $row){
            $acos =getTableWhere('tblacos',['acosid'=>$row->acoid]);
            $row->acoid = !empty($acos)?$acos[0]->class."/".$acos[0]->method:'-';
        }
        $response = new stdClass;
        $response->total=$total;
        $response->rows = $data;
        $_SESSION['excel']= "asc|useraclid|".$cond;
        echo json_encode($response);
    }
}