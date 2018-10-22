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
    /**
     * Fungsi awal activity
     * @AclName awal activity
     */
    public function index(){
        $link = base_url()."profile/gridprofile";
        $data['link']=$link;
        $data['activity'] = getComboParameter('ACTIVITY');
        $this->render('profile/gridprofile',$data);
    }
    /**
     * Fungsi load activity jemaat
     * @AclName load jemaat
     */
    public function jemaat(){
        if(empty($_SESSION['member_key'])){
            echo" Empty";
        }
        else{
            $data['member_key'] = $_SESSION['member_key'];
            $data['activity'] = getComboParameter('ACTIVITY');
            $this->load->view('jemaat/gridprofile',$data);
        }
    }
    /**
     * Fungsi add activity
     * @AclName Tambah activity
     */
    public function add($member_key=null){
        $data=[];
        $sqlactivity = getParameter('ACTIVITY');
        if($this->input->server('REQUEST_METHOD') == 'POST' ){
            $data = $this->input->post();
            $cek = $this->_save($data);
            $status = $cek?"sukses":"gagal";
            $hasil = array(
                'status' => $status
            );
            echo json_encode($hasil);
        }else{
            $data = $this->input->post();
            $check=$member_key==null?0:$member_key;
            $this->load->view('profile/add',['data'=>$data,'check'=>$check,'sqlactivity'=>$sqlactivity,'member_key'=>$member_key]);
        }

    }
    /**
     * Fungsi edit activity
     * @AclName Edit activity
     */
    public function edit($id,$member_key=null){
        $data = $this->mprofile->getById('tblprofile','profile_key',$id);
        $sqlactivity = getParameter('ACTIVITY');
        if(empty($data)){
            redirect('profile');
        }
        if($this->input->server('REQUEST_METHOD') == 'POST' ){
            $data = $this->input->post();
            $data['profile_key'] = $this->input->post('profile_key');
            $cek = $this->_save($data);
            $status = $cek?"sukses":"gagal";
            $hasil = array(
                'status' => $status
            );
            echo json_encode($hasil);
        }else{
            $check=$member_key==null?0:$member_key;
            $this->load->view('profile/edit',['row'=>$data,'check'=>$check,'sqlactivity'=>$sqlactivity,'member_key'=>$member_key]);
        }

    }
    /**
     * Fungsi delete activity
     * @AclName Delete activity
     */
    public function delete($id,$member_key=null){
        $data = $this->mprofile->getById('tblprofile','profile_key',$id);
        if(empty($data)){
            redirect('profile');
        }
        if($this->input->server('REQUEST_METHOD') == 'POST'){
            $cek = $this->mprofile->delete($id);
            $status = $cek?"sukses":"gagal";
            $hasil = array(
                'status' => $status
            );
            echo json_encode($hasil);
        }else{
            $check=$member_key==null?0:$member_key;
            $this->load->view('profile/delete',['row'=>$data,'check'=>$check,'member_key'=>$member_key]);
        }
    }
    private function _save($data){
        $data = array_map("strtoupper", $data);
        return $this->mprofile->save($data);
    }
    /**
     * Fungsi view activity
     * @AclName View activity
     */
    public function view($id,$member_key=null){
        $data = $this->mprofile->getById('tblprofile','profile_key',$id);
        if(empty($data)){
            redirect('profile');
        }
        $check=$member_key==null?0:$member_key;
        $this->load->view('profile/view',['row'=>$data,'check'=>$check,'member_key'=>$member_key]);
    }

    /**
     * Fungsi export excel
     * @AclName Export excel
     */
    public function excel(){
        excel('excelprofile','tblprofile','profile/excel');
    }
    /**
     * Fungsi grid profil di jemaat
     * @AclName grid jemaat
     */
    public function gridJemaat($member_key){
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
                $view = '<button id='.$row->member_key.' class="icon-view_detail" onclick="viewProfile(\''.$row->profile_key.'\')" style="width:16px;height:16px;border:0"></button> ';

                $edit = '<button id='.$row->member_key.' class="icon-edit" onclick="editProfile(\''.$row->profile_key.'\');" style="width:16px;height:16px;border:0"></button> ';

                $del = '<button id='.$row->member_key.' class="icon-remove" onclick="deleteProfile('.$row->profile_key.');" style="width:16px;height:16px;border:0"></button>';

            $row->aksi =$view.$edit.$del;
            $row->activityid =  $row->activityid==0?'-':getParameterKey($row->activityid)->parametertext;
        }
        $response = new stdClass;
        $response->total=$total;
        $response->rows = $data;
        $_SESSION['excel']= "asc|profile_key|";
        echo json_encode($response);
    }
    /**
     * Fungsi grid profil
     * @AclName grid
     */
    public function grid(){
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
                    }if($field=="remark"){
                        $field= "tblprofile.remark";
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
            $view = hasPermission('profile','view')?'<button  class="icon-view_detail" onclick="viewProfile(\''.$row->profile_key.'\')" style="width:16px;height:16px;border:0"></button>':'';
            $edit = hasPermission('profile','edit')?'<button class="icon-edit" onclick="editProfile(\''.$row->profile_key.'\');" style="width:16px;height:16px;border:0"></button> ':'';
            $del = hasPermission('profile','delete')?'<button  class="icon-remove" onclick="deleteProfile('.$row->profile_key.');" style="width:16px;height:16px;border:0"></button>':'';
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