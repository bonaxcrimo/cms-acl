<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class offering extends MY_Controller {
    public function __construct(){
        parent::__construct();
        session_start();
        $this->load->model([
            'moffering'
        ]);
    }
    /**
     * tampilan awal dari offering
     * @AclName List Offering
     */
    function index(){
        $link = base_url()."offering/grid";
        $this->render('offering/gridoffering',['link'=>$link]);
    }
    /**
     * print offering
     * @AclName Print Offering
     */
    public function prints($no=null){
        $no = $no;
        $this->load->view('offering/print',['no'=>$no]);
    }
    function jemaat(){
        if(empty($_SESSION['member_key'])){
            echo" Empty";
        }
        else{
            $data['member_key'] = $_SESSION['member_key'];
            $data['sql'] = $this->moffering->getwhere($_SESSION['member_key']);


            $data['sqlmenu'] = $this->mmenutop->get_data();

            $data['sqlgender'] = getParameter('GENDER');
            $data['sqlpstatus'] =getParameter('PSTATUS');

            $data['sqlstatusidv'] = getParameter('STATUS');
            $data['sqlblood'] =getParameter('BLOOD');
            $data['sqlkebaktian'] =getParameter('KEBAKTIAN');
            $data['sqlpersekutuan'] =getParameter('PERSEKUTUAN');
            $data['sqlrayon'] =getParameter('RAYON');
            $data['sqloffering'] = getParameter('OFFERING');

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
     function form($form,$offering_key,$member_key,$tabs=1){
        $data["offering_key"] = $offering_key;
        $data["member_key"] = $member_key;
        $data['sqloffering'] = getParameter('OFFERING');
        $data['sql'] = @$this->moffering->getwhere($member_key)->result()[0];
        $data['form'] = $form;
        $view = $tabs==0?'offering/':'jemaat/offering/';
        $this->load->view($view.$form,$data);
    }
    function restoreChecked(){
        $json = $_POST['dataOffering'];
        $status = $_POST['status'];
        $data = json_decode($json);
        foreach($data as $d){
            $sql="update tbloffering set row_status = '' where offering_key= ".$d->offering_key;
            $check = $this->db->query($sql);
            if(!$check){
                $gagal=1;
            }
        }
        $hasil = array(
            'status' => $gagal==0?"Sukses":"Gagal"
        );
        return json_encode($hasil);
    }
    function crud(){
        @$oper=@$_POST['oper'];
        $_POST = array_map("strtoupper", $_POST);
        @$offeringid=@$_POST['offeringid'];
        @$row_status = @$_POST['row_status'];
        @$offering_key = @$_POST['offering_key'];
        @$transdate = $_POST['transdate'];
        @$inputdate = $_POST['inputdate'];
        @$aliasname2 = $_POST['aliasname2'];
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
            'membername'=>@$_POST['member_name'],
            'chinesename'=>@$_POST['chinese_name'],
            'address'=>@$_POST['address'],
            'handphone'=>@$_POST['handphone'],
            'offeringno' => @$offeringno,
            'transdate' => @$transdate,
            'inputdate' =>@$inputdate,
            'aliasname2'=>@$aliasname2,
            'remark' => @$_POST['remark'],
            'offeringvalue' =>$offeringvalue,
            'row_status'=>@$row_status,
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
    /**
     * grid offering
     * @AclName grid Offering di Jemaat
     */
    function grid($member_key){
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
        $sql = $this->moffering->count($cond,'');
        $total = $sql->num_rows();
        $offset = ($page - 1) * $rows;
        $data = $this->moffering->get($cond,$sort,$order,$rows,$offset,'')->result();

        foreach($data as $row){
            $view='';
            $edit='';
            $del='';

            $view = hasPermission('offering','view')?'<button id='.$row->member_key.' class="icon-view_detail" onclick="viewOffering(\'view\',\''.$row->offering_key.'\',\''.$row->member_key.'\')" style="width:16px;height:16px;border:0"></button> ':'';
            $edit = hasPermission('offering','edit')?'<button id='.$row->member_key.' class="icon-edit" onclick="saveOffering(\'edit\',\''.$row->offering_key.'\',\''.$row->member_key.'\');" style="width:16px;height:16px;border:0"></button> ':'';
            $del = hasPermission('offering','delete')?'<button id='.$row->member_key.' class="icon-remove" onclick="delOffering(\'del\','.$row->offering_key.',\''.$row->member_key.'\');" style="width:16px;height:16px;border:0"></button>':'';
            $print =hasPermission('offering','print')?'<button id='.$row->member_key.' class="icon-print" onclick="reportOffering(\''.$row->offering_key.'\',\''.$row->offeringno.'\')" style="width:16px;height:16px;border:0"></button> ':'';

            $row->aksi =$print.$view.$edit.$del;
            $row->offeringid =  $row->offeringid==0?'-':getParameterKey($row->offeringid)->parameterid;
        }
        $response = new stdClass;
        $response->total=$total;
        $response->rows = $data;
        $_SESSION['excel']= "asc|offering_key|".$cond;
        echo json_encode($response);
    }
    /**
     * grid offering
     * @AclName grid Offering
     */
    function grid2($status=""){
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $rows = isset($_GET['rows']) ? intval($_GET['rows']) : 10;
        $sort = isset($_GET['sort']) ? strval($_GET['sort']) : 'offering_key';
        $order = isset($_GET['order']) ? strval($_GET['order']) : 'asc';

        $filterRules = isset($_GET['filterRules']) ? ($_GET['filterRules']) : '';
        $cond = '';
        if (!empty($filterRules)){
            $cond = ' where 1 = 1 ';
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
        $sql = $this->moffering->count($cond,$status);
        $total = $sql->num_rows();
        $offset = ($page - 1) * $rows;
        $data = $this->moffering->get($cond,$sort,$order,$rows,$offset,$status)->result();
        foreach($data as $row){
            $view='';
            $edit='';
            $del='';
            $jumlah=0;
            $view =hasPermission('offering','view')?'<button id='.$row->member_key.' class="icon-view_detail" onclick="viewOffering(\'view\',\''.$row->offering_key.'\',\''.$row->member_key.'\')" style="width:16px;height:16px;border:0"></button> ':'';
            $edit = hasPermission('offering','edit')?'<button id='.$row->member_key.' class="icon-edit" onclick="saveOffering(\'edit\',\''.$row->offering_key.'\',\''.$row->member_key.'\');" style="width:16px;height:16px;border:0"></button> ':'';
            if($status=="")
                $del = hasPermission('offering','delete')?'<button id='.$row->member_key.' class="icon-remove" onclick="delOffering(\'del\','.$row->offering_key.',\''.$row->member_key.'\');" style="width:16px;height:16px;border:0"></button>':'';
            $print = hasPermission('offering','print')?'<button id='.$row->member_key.' class="icon-print" onclick="reportOffering(\''.$row->offering_key.'\',\''.$row->offeringno.'\')" style="width:16px;height:16px;border:0"></button> ':'';
            $print2 ='<button id='.$row->member_key.' class="icon-print" onclick="report(\''.$row->offering_key.'\',\''.$row->offeringno.'\')" style="width:16px;height:16px;border:0"></button> ';
            $print2='';
            $row->aksi =$print.$print2.$view.$edit.$del;
            $row->offeringid =  $row->offeringid==0?'-':getParameterKey($row->offeringid)->parameterid;
            $row->remark2 = nl2br($row->remark);
        }
        $response = new stdClass;
        $response->total=$total;
        $response->rows = $data;
        $_SESSION['excel']= "asc|offering_key|".$cond;
        echo json_encode($response);
    }

    public function report_old($offering_key){
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
    /**
     * report offering
     * @AclName Report Offering
     */
    public function report(){
        if($this->input->server('REQUEST_METHOD') == 'POST' ){
            $filter = $this->input->post('filter');
            $mulai = $this->input->post('mulai');
            $selesai = $this->input->post('selesai');
            $link = base_url()."rptjs_new/rptoffering.php?filter=".$filter."&mulai=".$mulai."&akhir=".$selesai;
            echo $link;
        }else{
            $this->render('offering/report');
        }

    }
    public function printupdate(){
        $no = $_POST['noOffering'];
        $sql="update tbloffering set printedon = '".date("Y-m-d H:i:s")."',printedby='".$_SESSION['username']."' where offering_key= ".$no;
        $check = $this->db->query($sql);
        $gagal=0;
        if(!$check){
            $gagal=1;
        }
        $hasil = array(
            'status' => $gagal==0?"Sukses":"Gagal"
        );
        echo json_encode($hasil);
    }
}
?>