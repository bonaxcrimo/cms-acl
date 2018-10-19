<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class profile extends MY_Controller {
    public function __construct(){
        parent::__construct();
        session_start();
        $this->load->model([
            'mprofile',
            'mbesuk'
        ]);
    }
    function index(){
        $link = base_url()."profile/gridprofile";
        $data['link']=$link;
        $data['activity'] = getComboParameter('ACTIVITY');
        $this->render('profile/gridprofile',$data);
    }
    function jemaat(){
        if(empty($_SESSION['member_key'])){
            echo" Empty";
        }
        else{
            $data['member_key'] = $_SESSION['member_key'];
            $data['activity'] = getComboParameter('ACTIVITY');
            $this->load->view('jemaat/gridprofile',$data);
        }
    }
    function form($form,$profile_key,$member_key,$tabs=1){
        $data["profile_key"] = $profile_key;
        $data["member_key"] = $member_key;
        $data['sqlactivity'] = getParameter('ACTIVITY');
        $data['sql'] = $this->mprofile->getwhere($member_key);
        $view = $tabs==0?'profile/':'jemaat/profile/';
        $this->load->view($view.$form,$data);
    }
    function crud(){
        @$oper=@$_POST['oper'];
        $_POST = array_map("strtoupper", $_POST);
        @$activityid=@$_POST['activityid'];
        @$profile_key = @$_POST['profile_key'];
        @$activitydate = $_POST['activitydate'];
        @$exp1 = explode('/',$activitydate);
        @$activitydate = $exp1[2]."-".$exp1[0]."-".$exp1[1]." ".date("H:i:s");
        @$data = array(
            'member_key' => @$_POST['member_key'],
            'activityid' => @$activityid,
            'activitydate' => @$activitydate,
            'remark' => @$_POST['remark'],
            'modifiedby' => $_SESSION['username'],
            'modifiedon' => date("Y-m-d H:i:s")
            );
        switch ($oper) {
            case 'add':
                $this->mprofile->add("tblprofile",$data);
                $hasil = array(
                    'status' => 'sukses'
                );
                echo json_encode($hasil);
                break;
            case 'edit':
                $this->mprofile->edit("tblprofile",$data,$profile_key);
                $hasil = array(
                    'status' => 'sukses'
                );
                echo json_encode($hasil);
                break;
             case 'del':
                $this->mprofile->del("tblprofile",$profile_key);
                $hasil = array(
                    'status' => 'sukses'.$profile_key.$oper
                );
                echo json_encode($hasil);
                break;
        }
    }
    function grid($member_key){
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $sort = isset($_GET['sort']) ? strval($_GET['sort']) : 'profile_key';
        $order = isset($_GET['order']) ? strval($_GET['order']) : 'asc';

        $filterRules = isset($_GET['filterRules']) ? ($_GET['filterRules']) : '';
        $cond = '';
        if (!empty($filterRules)){
            $cond = ' where tblprofile.member_key = "'.$member_key.'" and  1=1 ';
            $filterRules = json_decode($filterRules);
            foreach($filterRules as $rule){
                $rule = get_object_vars($rule);
                $field = $rule['field'];
                $op = $rule['op'];
                $value = $rule['value'];
                if (!empty($value)){
                    if($field=="activityid"){
                        $field='parametertext';
                        $op="contains";
                    }
                    if ($op == 'contains'){
                        $cond .= " and ($field like '%$value%')";
                    } else if ($op == 'greater'){
                        $cond .= " and $field>$value";
                    }
                }
            }
        }else{
            $cond = ' where tblprofile.member_key = "'.$member_key.'" ';
        }
        $where='';
        $offset = ($page - 1) * $rows;
        $data = $this->mprofile->getM($cond,$sort,$order,$rows,$offset);
        $total = $data->num_rows();
        $data=$data->result();
        foreach($data as $row){
            $view='';
            $edit='';
            $del='';
                $view = '<button id='.$row->member_key.' class="icon-view_detail" onclick="viewProfile(\'view\',\''.$row->profile_key.'\',\''.$row->member_key.'\')" style="width:16px;height:16px;border:0"></button> ';

                $edit = '<button id='.$row->member_key.' class="icon-edit" onclick="saveProfile(\'edit\',\''.$row->profile_key.'\',\''.$row->member_key.'\');" style="width:16px;height:16px;border:0"></button> ';

                $del = '<button id='.$row->member_key.' class="icon-remove" onclick="delProfile(\'del\','.$row->profile_key.',\''.$row->member_key.'\');" style="width:16px;height:16px;border:0"></button>';

            $row->aksi =$view.$edit.$del;
            $row->activityid =  $row->activityid==0?'-':getParameterKey($row->activityid)->parametertext;
        }
        $response = new stdClass;
        $response->total=$total;
        $response->rows = $data;
        $_SESSION['excel']= "asc|profile_key|";
        echo json_encode($response);
    }
    function grid2(){
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $sort = isset($_GET['sort']) ? strval($_GET['sort']) : 'profile_key';
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
                    if($field=="activityid"){
                        $field='parametertext';
                        $op="contains";
                    }
                    if ($op == 'contains'){
                        $cond .= " and ($field like '%$value%')";
                    } else if ($op == 'greater'){
                        $cond .= " and $field>$value";
                    }
                }
            }
        }
        $where='';
        $offset = ($page - 1) * $rows;
        $data = $this->mprofile->getM($cond,$sort,$order,$rows,$offset);
        $total = $data->num_rows();
        $data=$data->result();
        foreach($data as $row){
            $view='';
            $edit='';
            $del='';

                $view = '<button id='.$row->member_key.' class="icon-view_detail" onclick="viewProfile(\'view\',\''.$row->profile_key.'\',\''.$row->member_key.'\')" style="width:16px;height:16px;border:0"></button> ';

                $edit = '<button id='.$row->member_key.' class="icon-edit" onclick="saveProfile(\'edit\',\''.$row->profile_key.'\',\''.$row->member_key.'\');" style="width:16px;height:16px;border:0"></button> ';

                $del = '<button id='.$row->member_key.' class="icon-remove" onclick="delProfile(\'del\','.$row->profile_key.',\''.$row->member_key.'\');" style="width:16px;height:16px;border:0"></button>';

            $row->aksi =$view.$edit.$del;
            $row->activityid =  $row->activityid==0?'-':getParameterKey($row->activityid)->parameterid;
        }
        $response = new stdClass;
        $response->total=$total;
        $response->rows = $data;
        $_SESSION['excel']= "asc|profile_key|";
        echo json_encode($response);
    }
}
?>