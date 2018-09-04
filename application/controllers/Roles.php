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
    function grid(){
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
        $_SESSION['excelblood']= "asc|parameter_key|".$cond;
        echo json_encode($response);
    }
}