<?php
class UserAcl extends MY_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model([
            'Museracl',
            'Macos'
        ]);
    }
    public function user(){
        if(empty($_SESSION['userpk'])){
            echo "Empty";
        }
        else{
            $data['userpk'] = $_SESSION['userpk'];
            $this->load->view('user/griduseracl',$data);
        }
    }
    public function griduser($userpk){
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