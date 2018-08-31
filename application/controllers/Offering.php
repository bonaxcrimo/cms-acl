<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class offering extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library('session');
        $this->load->model([
            'mlogin',
            'mmenutop',
            'moffering'
        ]);
        $this->load->helper('my_helper');
        $cek = $this->mlogin->cek();
        if($cek==""){
            redirect("");
            session_destroy();
        }
        date_default_timezone_set("Asia/Jakarta");
        ini_set('memory_limit', '-1');
    }
    function index(){
        $data['acl'] = $this->hakakses('offering');
        $data['sqlmenu'] = $this->mmenutop->get_data();
        $this->load->view('partials/header');
        $this->load->view('navbar',$data);
        $this->load->view('offering/gridoffering');
        $this->load->view('partials/footer');
    }
    function jemaat(){
        if(empty($_SESSION['member_key'])){
            echo" Empty";
        }
        else{
            $data['member_key'] = $_SESSION['member_key'];
            $data['sql'] = $this->moffering->getwhere($_SESSION['member_key']);

            $data['statusid'] = $this->uri->segment(2);
            $data['acl'] = $this->hakakses("jemaat");

            $data['sqlmenu'] = $this->mmenutop->get_data();

            $data['sqlgender'] = getParameter('GENDER');
            $data['sqlpstatus'] =getParameter('PSTATUS');

            $data['sqlstatusidv'] = getParameter('STATUS');
            $data['sqlblood'] =getParameter('BLOOD');
            $data['sqlkebaktian'] =getParameter('KEBAKTIAN');
            $data['sqlpersekutuan'] =getParameter('PERSEKUTUAN');
            $data['sqlrayon'] =getParameter('RAYON');
            $data['sqloffering'] = getParameter('OFFERING');
            $data['listTable'] = $this->db->list_fields('tblmember');

            $data['statusidv'] = getComboParameter('STATUS');
            $data['blood'] = getComboParameter('BLOOD');
            $data['gender'] = getComboParameter('GENDER');
            $data['pstatus'] = getComboParameter('PSTATUS');
            $data['kebaktian'] = getComboParameter('KEBAKTIAN');
            $data['persekutuan'] =getComboParameter('PERSEKUTUAN');
            $data['rayon'] = getComboParameter('RAYON');
            $data['activity'] = getComboParameter('ACTIVITY');
            $this->load->view('jemaat/gridoffering',$data);
        }
    }
    function form($form,$offering_key,$member_key){
        $data["offering_key"] = $offering_key;
        $data["member_key"] = $member_key;
        $data['sqloffering'] = getParameter('OFFERING');
        $data['sql'] = @$this->moffering->getwhere($member_key)->result()[0];
        $data['form'] = $form;
        $this->load->view('offering/'.$form,$data);
    }
    function crud(){
        @$oper=@$_POST['oper'];
        $_POST = array_map("strtoupper", $_POST);
        @$offeringid=@$_POST['offeringid'];
        @$offering_key = @$_POST['offering_key'];
        @$transdate = $_POST['transdate'];
        @$inputdate = $_POST['inputdate'];
        @$offeringvalue = str_replace(".","",$_POST['offeringvalue']);
        @$exp1 = explode('/',$transdate);
        @$transdate = $exp1[2]."-".$exp1[0]."-".$exp1[1]." ".date("H:i:s");
        @$exp2 = explode('/',$inputdate);
        @$inputdate = $exp2[2]."-".$exp2[0]."-".$exp2[1]." ".date("H:i:s");
        if($oper=="add"){
            $noOffering = getTableWhere('tblparameter',array('parametergrpid'=>'FORMAT_NO','parameterid'=>'OFFERING'))[0];
            $period = getTableWhere('tblparameter',array('parameter_key'=>$noOffering->parametermemo))[0];
            $offerData = getDataPeriodly($period->parameterid,'tbloffering','inputdate','offeringno','desc');
            if(count($offerData)==0){
                $offeringno = bacaFormat($noOffering->parametertext,1);
            }else{
                $offerData = $offerData[0]->offeringno;
                $pecah = explode("/",$offerData)[0];
                $offeringno = bacaFormat($noOffering->parametertext,$pecah+1);
            }
        }else{
            $this->db->where('offering_key',$offering_key);
            $offerData = $this->db->get("tbloffering")->result()[0];
            $offeringno = $offerData->offeringno;
        }
        @$data = array(
            'member_key' => @$_POST['member_key'],
            'offeringid' => @$offeringid,
            'offeringno' => @$offeringno,
            'transdate' => @$transdate,
            'inputdate' =>@$inputdate,
            'remark' => @$_POST['remark'],
            'offeringvalue' =>$offeringvalue,
            'row_status'=>'',
            'modifiedby' => $_SESSION['username'],
            'modifiedon' => date("Y-m-d H:i:s")
            );
        switch ($oper) {
            case 'add':
                $this->moffering->add("tbloffering",$data);
                $hasil = array(
                    'status' => 'sukses'
                );
                echo json_encode($hasil);
                break;
            case 'edit':
                $this->moffering->edit("tbloffering",$data,$offering_key);
                $hasil = array(
                    'status' => 'sukses'
                );
                echo json_encode($hasil);
                break;
             case 'del':
                $this->moffering->del("tbloffering",$offering_key);
                $hasil = array(
                    'status' => 'sukses'
                );
                echo json_encode($hasil);
                break;
        }
    }
    function grid($member_key){
        $acl = $this->hakakses('jemaat');
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $sort = isset($_GET['sort']) ? strval($_GET['sort']) : 'offering_key';
        $order = isset($_GET['order']) ? strval($_GET['order']) : 'asc';

        $filterRules = isset($_GET['filterRules']) ? ($_GET['filterRules']) : '';
        $cond = '';
        if (!empty($filterRules)){
            $cond = ' where member_key = "'.$member_key.'" and  1=1 ';
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
            $cond = ' where member_key = "'.$member_key.'" ';
        }
        $where='';
        $sql = $this->moffering->count($cond);
        $total = $sql->num_rows();
        $offset = ($page - 1) * $rows;
        $data = $this->moffering->getM($cond,$sort,$order,$rows,$offset)->result();
        foreach($data as $row){
            $view='';
            $edit='';
            $del='';
            if(substr($acl,0,1)==1){
                $view = '<button id='.$row->member_key.' class="icon-view_detail" onclick="viewOffering(\'view\',\''.$row->offering_key.'\',\''.$row->member_key.'\')" style="width:16px;height:16px;border:0"></button> ';
            }
            if(substr($acl,2,1)==1){
                $edit = '<button id='.$row->member_key.' class="icon-edit" onclick="saveOffering(\'edit\',\''.$row->offering_key.'\',\''.$row->member_key.'\');" style="width:16px;height:16px;border:0"></button> ';
            }
            if(substr($acl,3,1)==1){
                $del = '<button id='.$row->member_key.' class="icon-remove" onclick="delOffering(\'del\','.$row->offering_key.',\''.$row->member_key.'\');" style="width:16px;height:16px;border:0"></button>';
            }
            $print = '<button id='.$row->member_key.' class="icon-print" onclick="reportOffering(\''.$row->offering_key.'\')" style="width:16px;height:16px;border:0"></button> ';
            $row->aksi =$print.$view.$edit.$del;
            $row->offeringid =  $row->offeringid==0?'-':getParameterKey($row->offeringid)->parameterid;
        }
        $response = new stdClass;
        $response->total=$total;
        $response->rows = $data;
        $_SESSION['excel']= "asc|offering_key|";
        echo json_encode($response);
    }
    function grid2(){
        $acl = $this->hakakses('offering');
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $sort = isset($_GET['sort']) ? strval($_GET['sort']) : 'offering_key';
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
        $sql = $this->moffering->count($cond);
        $total = $sql->num_rows();
        $offset = ($page - 1) * $rows;
        $data = $this->moffering->getM($cond,$sort,$order,$rows,$offset)->result();
        foreach($data as $row){
            $view='';
            $edit='';
            $del='';
            if(substr($acl,0,1)==1){
                $view = '<button id='.$row->member_key.' class="icon-view_detail" onclick="viewOffering(\'view\',\''.$row->offering_key.'\',\''.$row->member_key.'\')" style="width:16px;height:16px;border:0"></button> ';
            }
            if(substr($acl,2,1)==1){
                $edit = '<button id='.$row->member_key.' class="icon-edit" onclick="saveOffering(\'edit\',\''.$row->offering_key.'\',\''.$row->member_key.'\');" style="width:16px;height:16px;border:0"></button> ';
            }
            if(substr($acl,3,1)==1){
                $del = '<button id='.$row->member_key.' class="icon-remove" onclick="delOffering(\'del\','.$row->offering_key.',\''.$row->member_key.'\');" style="width:16px;height:16px;border:0"></button>';
            }
            $print = '<button id='.$row->member_key.' class="icon-print" onclick="reportOffering(\''.$row->offering_key.'\')" style="width:16px;height:16px;border:0"></button> ';
            $row->aksi =$print.$view.$edit.$del;
            $row->offeringid =  $row->offeringid==0?'-':getParameterKey($row->offeringid)->parameterid;
            $row->remark2 = nl2br($row->remark);
        }
        $response = new stdClass;
        $response->total=$total;
        $response->rows = $data;
        $_SESSION['excel']= "asc|offering_key|";
        echo json_encode($response);
    }
    function report($offering_key){
        $this->load->library('Pdf');
        $data['key'] = $offering_key;
        $offering = getOne('offering_key',$offering_key,'tbloffering')[0];
        $offering->offeringid =  getParameterKey($offering->offeringid)->parameterid;
        $offering->membername = $this->moffering->getwhere($offering->member_key)->result()[0]->membername;
        $offering->chinesename = $this->moffering->getwhere($offering->member_key)->result()[0]->chinesename;
        $marginLeft = $this->db->query("select * from tblparameter where parametergrpid='PRINTER_MARGIN' and parameterid='LEFT'")->result()[0]->parametertext;
        $marginTop = $this->db->query("select * from tblparameter where parametergrpid='PRINTER_MARGIN' and parameterid='TOP'")->result()[0]->parametertext;
        $noOffering = getTableWhere('tblparameter',array('parametergrpid'=>'FORMAT_NO','parameterid'=>'OFFERING'))[0];
        $offering->noOffering = bacaFormat($noOffering->parametertext,$offering->offering_key);
        $data['offering'] = $offering;
        $data['marginLeft'] = $marginLeft;
        $data['marginTop'] = $marginTop;
        $this->load->view('offering/report',$data);
    }
    function hakakses($x){
        $x = $this->mmenutop->get_menuid($x);
        return $x;
    }
}
?>