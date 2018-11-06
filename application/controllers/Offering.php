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
        $data['link'] = base_url()."offering/grid";
        $data['offering'] = getComboParameter('OFFERING');
        $this->render('offering/gridoffering',$data);
    }
    /**
     * print offering
     * @AclName Print Offering
     */
    public function prints($no=null){
        $no = $no;
        $this->load->view('offering/print',['no'=>$no]);
    }
    /**
     * tab offering jemaat
     * @AclName Tab Offering di Jemaat
     */
    public function jemaat(){
        if(empty($_SESSION['member_key'])){
            echo" Empty";
        }
        else{
            $data['member_key'] = $_SESSION['member_key'];
            $data['sql'] = $this->moffering->getwhere($_SESSION['member_key']);
            $data['offering'] = getComboParameter('OFFERING');
            $this->load->view('jemaat/gridoffering',$data);
        }
    }
    /**
     * Fungsi add offering
     * @AclName Tambah offering
     */
    public function add($member_key=null){
        $data=[];
        $sqloffering = getParameter('OFFERING');
        if($this->input->server('REQUEST_METHOD') == 'POST' ){
            $data = $this->input->post();
            $cek = $this->_save($data);
            $status = $cek?"sukses":"gagal";
            $hasil = array(
                'status' => $status
            );
            echo json_encode($hasil);
            // print_r($this->input->post());
        }else{
            $data = $this->input->post();
            $check=$member_key==null?0:$member_key;
            $this->load->view('offering/add',['row'=>$data,'sqloffering'=>$sqloffering,'check'=>$check,'member_key'=>$member_key]);
        }

    }
    /**
     * Fungsi view offering
     * @AclName View offering
     */
    public function view($offering_key=0,$member_key=null){
        $data = $this->moffering->getById('tbloffering','offering_key',$offering_key);
        if(empty($data)){
            redirect('offering');
        }
        $data_detail = $this->moffering->getListAll('tbldetailoffering',['offeringno'=>$data->offeringno]);
        $check=$member_key==null?0:$member_key;
        $this->load->view('offering/view',['row'=>$data,'row_detail'=>$data_detail,'check'=>$check,'member_key'=>$member_key]);
    }
    /**
     * Fungsi edit offering
     * @AclName Edit offering
     */
    public function edit($id,$member_key=null){
        $data = $this->moffering->getById('tbloffering','offering_key',$id);
        $sqloffering = getParameter('OFFERING');
        if(empty($data)){
            redirect('offering');
        }
        $data_detail = $this->moffering->getListAll('tbldetailoffering',['offeringno'=>$data->offeringno]);
        if($this->input->server('REQUEST_METHOD') == 'POST' ){
            $data = $this->input->post();
            $data['offering_key'] = $this->input->post('offering_key');
            $cek = $this->_save($data);
            $status = $cek?"sukses":"gagal";
            $hasil = array(
                'status' => $status
            );
            echo json_encode($hasil);
        }else{
            $check=$member_key==null?0:$member_key;
            $this->load->view('offering/edit',['row'=>$data,'row_detail'=>$data_detail,'sqloffering'=>$sqloffering,'check'=>$check,'member_key'=>$member_key]);
        }

    }
    /**
     * Fungsi delete offering
     * @AclName Delete offering
     */
    public function delete($id,$member_key=null,$status1='D'){
        $data = $this->moffering->getById('tbloffering','offering_key',$id);
        if(empty($data)){
            redirect('offering');
        }
        $data_detail = $this->moffering->getListAll('tbldetailoffering',['offeringno'=>$data->offeringno]);
        if($this->input->server('REQUEST_METHOD') == 'POST'){
            $cek = $this->moffering->delete($this->input->post('offering_key'),$status1);
            $status = $cek?"sukses":"gagal";
            $hasil = array(
                'status' => $status
            );
            echo json_encode($hasil);
        }else{
            $check=$member_key==null?0:$member_key;
            $this->load->view('offering/delete',['row'=>$data,'row_detail'=>$data_detail,'check'=>$check,'member_key'=>$member_key]);
        }

    }
    private function _save($data){
        $temp=$data;
        unset($data['offeringid']);
        unset($data['offeringvalue']);
        unset($data['offeringdetail_key']);
        $data = array_map("strtoupper", $data);
        $data['offeringid'] = $temp['offeringid'];
        $data['offeringvalue'] = $temp['offeringvalue'];
        $data['offeringdetail_key'] = $temp['offeringdetail_key'];
        return $this->moffering->save($data);
    }

    /**
     * restore offering
     * @AclName restore offering yang dihapus
     */
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
    /**
     * grid  Offering di Jemaat
     * @AclName grid jemaat
     */
    public function gridJemaat($member_key){
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

            $view = hasPermission('offering','view')?'<button class="icon-view_detail" onclick="viewOffer(\''.$row->offering_key.'\')" style="width:16px;height:16px;border:0"></button> ':'';
            $edit = hasPermission('offering','edit')?'<button class="icon-edit" onclick="editOffer(\''.$row->offering_key.'\');" style="width:16px;height:16px;border:0"></button> ':'';
            $del = hasPermission('offering','delete')?'<button  class="icon-remove" onclick="deleteOffer('.$row->offering_key.');" style="width:16px;height:16px;border:0"></button>':'';
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
    public function grid($status=""){
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
                    } else if ($op == 'equal'){
                        $cond .= " and $field = '$value'";
                    }else if($op == 'notequal'){
                        $cond .= " and $field != '' ";
                    }else if($op =="containsend"){
                        $cond .= " and ($field like '$value%')";
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
            $btl='';
            $jumlah=0;
            if($row->row_status==""){
                $view =hasPermission('offering','view')?'<button  class="icon-view_detail" onclick="viewOffer(\''.$row->offering_key.'\')" style="width:16px;height:16px;border:0"></button> ':'';
                $edit = hasPermission('offering','edit')?'<button  class="icon-edit" onclick="editOffer(\''.$row->offering_key.'\');" style="width:16px;height:16px;border:0"></button> ':'';
                $del = hasPermission('offering','delete')?'<button  class="icon-remove" onclick="deleteOffer('.$row->offering_key.',\'D\');" style="width:16px;height:16px;border:0"></button>':'';
                $btl = hasPermission('offering','delete')?' <button  class="icon-undo" onclick="deleteOffer('.$row->offering_key.',\'B\');" style="width:16px;height:16px;border:0"></button>':'';
            }else if($row->row_status=="B"){
                $view =hasPermission('offering','view')?'<button  class="icon-view_detail" onclick="viewOffer(\''.$row->offering_key.'\')" style="width:16px;height:16px;border:0"></button> ':'';
            }
            $print = hasPermission('offering','print')?' <button id='.$row->member_key.' class="icon-print" onclick="reportOffering(\''.$row->offering_key.'\',\''.$row->offeringno.'\')" style="width:16px;height:16px;border:0"></button> ':'';
            $print2 ='<button id='.$row->member_key.' class="icon-print" onclick="report(\''.$row->offering_key.'\',\''.$row->offeringno.'\')" style="width:16px;height:16px;border:0"></button> ';
            $print2='';
            $row->aksi =$print.$print2.$view.$edit.$del.$btl;
            // $row->offeringid =  $row->offeringid==0?'-':getParameterKey($row->offeringid)->parameterid;
            $row->remark2 = nl2br($row->remark);
            $row->row_status=$row->row_status=='B'?'Dibatalkan':'-';
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

}
?>